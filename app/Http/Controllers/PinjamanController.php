<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $pinjaman = Pinjaman::where('account_id', $request->user()->account->id)
        ->latest()
        ->paginate(10);

        return view('pinjaman.index', compact('pinjaman'));
    }

    public function create()
    {
        return view('pinjaman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:100000'],
            'tenor_months' => ['required', 'integer', 'in:3,6,12,24,36'],
            'purpose' => ['required', 'string', 'max:255'],
        ]);

        $interestRate = 1.5;
        $totalRepayment = $validated['amount'] * (1 + ($interestRate / 100) * $validated['tenor_months']);
        $monthlyInstallment = $totalRepayment / $validated['tenor_months'];

        Pinjaman::create([
            'account_id'          => $request->user()->account->id,
            'amount'              => $validated['amount'],
            'tenor_months'        => $validated['tenor_months'],
            'interest_rate'       => $interestRate,
            'monthly_installment' => $monthlyInstallment,
            'total_repayment'     => $totalRepayment,
            'purpose'             => $validated['purpose'],
            'status'              => 'pending',
        ]);

        return redirect()->route('pinjaman.index')->with('success', 'Pengajuan pinjaman berhasil dikirim.');
    }

    public function show(Pinjaman $pinjaman)
    {
        return view('pinjaman.show', compact('pinjaman'));
    }

    public function edit(Pinjaman $pinjaman)
    {
        abort_if($pinjaman->status !== 'pending', 403, 'Pinjaman tidak bisa diedit.');
        return view('pinjaman.edit', compact('pinjaman'));
    }

    public function update(Request $request, Pinjaman $pinjaman)
    {
        abort_if($pinjaman->status !== 'pending', 403, 'Pinjaman tidak bisa diedit.');
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:100000'],
            'tenor_months' => ['required', 'integer', 'in:3,6,12,24,36'],
            'purpose' => ['required', 'string', 'max:255'],
        ]);

        $interestRate = 1.5;
        $totalRepayment = $validated['amount'] * (1 + ($interestRate / 100) * $validated['tenor_months']);
        $monthlyInstallment = $totalRepayment / $validated['tenor_months'];

        $pinjaman->update([
            'amount' => $validated['amount'],
            'tenor_months' => $validated['tenor_months'],
            'interest_rate' => $interestRate,
            'monthly_installment' => $monthlyInstallment,
            'total_repayment' => $totalRepayment,
            'purpose' => $validated['purpose'],
        ]);
        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil diperbarui.');
    }

    public function destroy(Pinjaman $pinjaman)
    {
        abort_if($pinjaman->status !== 'pending', 422, 'Hanya pinjaman pending yang bisa dihapus.');
        $pinjaman->delete();
        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil dihapus.');
    }
}