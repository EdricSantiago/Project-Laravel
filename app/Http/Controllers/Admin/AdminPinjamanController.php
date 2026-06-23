<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use Illuminate\Http\Request;

class AdminPinjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pinjaman::with('account.user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pinjaman = $query->paginate(15)->withQueryString();

        return view('admin.pinjaman.index', compact('pinjaman'));
    }

    public function show(Pinjaman $pinjaman)
    {
        $pinjaman->load('account.user');

        return view('admin.pinjaman.show', compact('pinjaman'));
    }

    public function approve(Pinjaman $pinjaman)
    {
        abort_if($pinjaman->status !== 'pending', 422, 'Hanya pinjaman pending yang bisa disetujui.');

        $pinjaman->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('admin.pinjaman.show', $pinjaman)
            ->with('success', "Pinjaman #{$pinjaman->id} berhasil disetujui.");
    }

    public function reject(Request $request, Pinjaman $pinjaman)
    {
        abort_if($pinjaman->status !== 'pending', 422, 'Hanya pinjaman pending yang bisa ditolak.');

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $pinjaman->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at'      => now(),
        ]);

        return redirect()
            ->route('admin.pinjaman.show', $pinjaman)
            ->with('success', "Pinjaman #{$pinjaman->id} berhasil ditolak.");
    }
}