<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
        $account = Account::where('user_id', $user->id)->firstOrFail();

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
            'amount'             => ['required', 'numeric', 'min:10000'],
            'receiver_account'   => ['required', 'string'],
        ]);

        $user        = Auth::user();
        $senderAcc   = Account::where('user_id', $user->id)->firstOrFail();

        $receiverUser = \App\Models\User::where('account_number', $request->receiver_account)->first();
        if (!$receiverUser) {
        return back()->withErrors(['receiver_account' => 'Nomor rekening tujuan tidak ditemukan.']);
}
        $receiverAcc = Account::where('user_id', $receiverUser->id)->first();

        if ($receiverAcc->id === $senderAcc->id) {
            return back()->withErrors(['receiver_account' => 'Tidak dapat transfer ke rekening sendiri.']);
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

    public function history(): View
    {
        $user    = Auth::user();
        $account = Account::where('user_id', $user->id)->first();

        $transactions = Transaction::where('sender_id', $account?->id)
            ->orWhere('receiver_id', $account?->id)
            ->latest()
            ->get();

        return view('transaction.history', compact('user', 'account', 'transactions'));
    }
}