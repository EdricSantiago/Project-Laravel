<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $account = Account::where('user_id', $user->id)->first();

        $transactions = Transaction::where('sender_id', $account?->id)
            ->orWhere('receiver_id', $account?->id)
            ->latest()
            ->get();

        return view('transaction', compact('user', 'account', 'transactions'));
    }

    public function deposit(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
        ]);

        $user    = Auth::user();
        if ($user->status === 'suspended') {
            return back()->withErrors(['error' => 'Akun Anda dibekukan. Transaksi tidak dapat dilakukan.']);
        }

        $account = Account::where('user_id', $user->id)->firstOrFail();

        $account->balance += $request->amount;
        $account->save();

        Transaction::create([
            'type'        => 'deposit',
            'amount'      => $request->amount,
            'sender_id'   => null,
            'receiver_id' => $account->id,
            'status'      => 'success',
        ]);

        return redirect()->route('transaction.index')
            ->with('success', 'Deposit berhasil! Saldo bertambah Rp ' . number_format($request->amount, 0, ',', '.'));
    }

    public function withdraw(Request $request): RedirectResponse
    {
        if (!$request->session()->has('pin_verified')) {
            return back()->withErrors(['error' => 'Anda wajib memverifikasi PIN terlebih dahulu.']);
        }
        $request->session()->forget('pin_verified');

        $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
        ]);

        $user    = Auth::user();
        if ($user->status === 'suspended') {
            return back()->withErrors(['error' => 'Akun Anda dibekukan. Transaksi tidak dapat dilakukan.']);
        }

        $account = Account::where('user_id', $user->id)->firstOrFail();
        $todayTotal = Transaction::where('sender_id', $account->id)            
            ->whereIn('type', ['withdraw', 'transfer'])
            ->whereDate('created_at', now()->toDateString())
            ->sum('amount');

        if (($todayTotal + $request->amount) > 50000000) {
            return back()->withErrors(['error' => 'Transaksi gagal! Anda melebihi limit Rp 50.000.000 untuk transaksi hari ini.']);
        }
        $sisaSaldo = $account->balance - $request->amount;
        if ($sisaSaldo < 50000) {
            return back()->withErrors([
                'amount' => 'Saldo tidak mencukupi. Minimal sisa saldo setelah penarikan adalah Rp 50.000.'
            ]);
        }

        $account->balance -= $request->amount;
        $account->save();

        Transaction::create([
            'type'        => 'withdraw',
            'amount'      => $request->amount,
            'sender_id'   => $account->id,
            'receiver_id' => null,
            'status'      => 'success',
        ]);

        return redirect()->route('transaction.index')
            ->with('success', 'Penarikan berhasil! Saldo berkurang Rp ' . number_format($request->amount, 0, ',', '.'));
    }

    public function transfer(Request $request): RedirectResponse
    {
        if (!$request->session()->has('pin_verified')) {
            return back()->withErrors(['error' => 'Anda wajib memverifikasi PIN terlebih dahulu.']);
        }
        $request->session()->forget('pin_verified');

        $request->validate([
            'amount'           => ['required', 'numeric', 'min:10000'],
            'receiver_account' => ['required', 'string'],
        ]);

        $user = Auth::user();
        if ($user->status === 'suspended') {
            return back()->withErrors(['error' => 'Akun Anda dibekukan. Transaksi tidak dapat dilakukan.']);
        }
        
        $senderAcc = Account::where('user_id', $user->id)->firstOrFail();

        $receiverUser = \App\Models\User::where('account_number', $request->receiver_account)->first();
        if (!$receiverUser) {
            return back()->withErrors(['receiver_account' => 'Nomor rekening tujuan tidak ditemukan.']);
        }

        $receiverAcc = Account::where('user_id', $receiverUser->id)->first();

        if ($receiverAcc->id === $senderAcc->id) {
            return back()->withErrors(['receiver_account' => 'Tidak dapat transfer ke rekening sendiri.']);
        }
            $todayTotal = Transaction::where('sender_id', $senderAcc->id)            
            ->whereIn('type', ['withdraw', 'transfer'])
            ->whereDate('created_at', now()->toDateString())
            ->sum('amount');

        if (($todayTotal + $request->amount) > 50000000) {
            return back()->withErrors(['error' => 'Transaksi gagal! Anda melebihi limit Rp 50.000.000 untuk transaksi hari ini.']);
        }
        $sisaSaldo = $senderAcc->balance - $request->amount;
        if ($sisaSaldo < 50000) {
            return back()->withErrors([
                'amount' => 'Saldo tidak mencukupi. Minimal sisa saldo setelah transfer adalah Rp 50.000.'
            ]);
        }

        $senderAcc->balance   -= $request->amount;
        $receiverAcc->balance += $request->amount;
        $senderAcc->save();
        $receiverAcc->save();

        Transaction::create([
            'type'        => 'transfer',
            'amount'      => $request->amount,
            'sender_id'   => $senderAcc->id,
            'receiver_id' => $receiverAcc->id,
            'status'      => 'success',
        ]);

        return redirect()->route('transaction.index')
            ->with('success', 'Transfer berhasil! Rp ' . number_format($request->amount, 0, ',', '.') . ' dikirim.');
    }

    public function history(Request $request)
    {
        $user    = Auth::user();
        $account = Account::where('user_id', $user->id)->first();

        $transactions = Transaction::where(function($q) use ($account) {
            $q->where('sender_id', $account?->id)
            ->orWhere('receiver_id', $account?->id);
        });

        if ($request->filled('type')) {
            $transactions->where('type', $request->type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $transactions->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $transactions = $transactions->latest()->get();

        return view('transaction', compact('user', 'account', 'transactions'));
    }

    public function payInsurance(Request $request): RedirectResponse
    {
        if (!$request->session()->has('pin_verified')) {
            return back()->withErrors(['error' => 'Anda wajib memverifikasi PIN terlebih dahulu.']);
        }
        $request->session()->forget('pin_verified');
        return DB::transaction(function () {
            $user    = Auth::user();
            $account = Account::where('user_id', $user->id)->lockForUpdate()->first();
            $amount  = $account->asuransi_premium ?? 100000;

            if ($account->balance < $amount) {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk membayar Asuransi!');
            }

            $account->decrement('balance', $amount);
            $account->asuransi_last_paid = now()->toDateString();
            $account->save();

            Transaction::create([
                'sender_id'   => $account->id,
                'receiver_id' => null,
                'amount'      => $amount,
                'type'        => 'withdraw',
                'status'      => 'success',
            ]);

            return redirect()->back()
                ->with('success', 'Berhasil membayar Asuransi sebesar Rp ' . number_format($amount, 0, ',', '.'));
        });
    }

    // ─── EXPORT PDF ─────────────────────────────────────────────────────────────
    /**
     * Export riwayat transaksi user ke PDF.
     * Route: GET /transaction/export-pdf
     */
    public function exportPdf(Request $request): Response
    {
        $user    = Auth::user();
        $account = Account::where('user_id', $user->id)->firstOrFail();

        // Ambil semua transaksi user, load relasi sender/receiver
        $transactions = Transaction::with(['sender.user', 'receiver.user'])
            ->where('sender_id', $account->id)
            ->orWhere('receiver_id', $account->id)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('pdf.transaction-history', [
            'user'         => $user,
            'account'      => $account,
            'transactions' => $transactions,
            'generated_at' => now()->format('d M Y, H:i'),
        ])->setPaper('a4', 'portrait');

        $filename = 'mutasi-rekening-' . $account->account_number . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
