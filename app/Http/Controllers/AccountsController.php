<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountsController extends Controller
{
    public function index(): View
    {
        $user    = Auth::user();
        $account = Account::where('user_id', $user->id)->first();

        return view('homepage', compact('user', 'account'));
    }

    public function accounts(): View
    {
        $user    = Auth::user();
        $account = Account::where('user_id', $user->id)->first();

        // Fetch recent transactions where this account is sender or receiver
        $transactions = collect();
        if ($account) {
            $transactions = Transaction::with(['sender.user', 'receiver.user'])
                ->where('sender_id', $account->id)
                ->orWhere('receiver_id', $account->id)
                ->orderByDesc('created_at')
                ->take(10)
                ->get();
        }

        return view('accounts', compact('user', 'account', 'transactions'));
    }

    public function security(): View
    {
        $user    = Auth::user();
        $account = Account::where('user_id', $user->id)->first();
        $logs = \App\Models\Security::where('user_id', Auth::id())->latest()->paginate(10);

        return view('security', compact('user', 'account', 'logs'));
    }

    public function show(): View
    {
        $user    = Auth::user();
        $account = Account::where('user_id', $user->id)->firstOrFail();

        return view('account.show', compact('user', 'account'));
    }

    public function topup(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
        ]);

        $account = Account::where('user_id', Auth::id())->firstOrFail();
        $account->balance += $request->amount;
        $account->save();

        return back()->with('success', 'Top up berhasil! Saldo bertambah Rp ' . number_format($request->amount, 0, ',', '.'));
    }

    public function withdraw(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
        ]);

        $account   = Account::where('user_id', Auth::id())->firstOrFail();
        $sisaSaldo = $account->balance - $request->amount;

        if ($sisaSaldo < 50000) {
            return back()->withErrors([
                'amount' => 'Saldo tidak mencukupi. Minimal sisa saldo Rp 50.000.',
            ]);
        }

        $account->balance -= $request->amount;
        $account->save();

        return back()->with('success', 'Penarikan berhasil! Saldo berkurang Rp ' . number_format($request->amount, 0, ',', '.'));
    }

}