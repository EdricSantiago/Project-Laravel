<?php

namespace App\Http\Controllers;

use App\Models\Saham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SahamController extends Controller
{
    private function isAdmin(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public function index()
    {
        $sahams = Saham::orderBy('kode_saham')->paginate(12);

        return view('saham.index', compact('sahams'));
    }

    public function create()
    {
        abort_unless($this->isAdmin(), 403, 'Hanya admin yang bisa menambah saham.');

        return view('saham.create');
    }

    public function store(Request $request)
    {
        abort_unless($this->isAdmin(), 403, 'Hanya admin yang bisa menambah saham.');

        $validated = $request->validate([
            'kode_saham' => 'required|string|max:10|unique:sahams,kode_saham',
            'nama_perusahaan' => 'required|string|max:255',
            'sektor' => 'nullable|string|max:255',
            'harga_saat_ini' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        Saham::create($validated);

        return redirect()
            ->route('saham.index')
            ->with('success', 'Saham baru berhasil ditambahkan ke daftar.');
    }

    public function show(Saham $saham)
    {
        return view('saham.show', compact('saham'));
    }

    public function edit(Saham $saham)
    {
        abort_unless($this->isAdmin(), 403, 'Hanya admin yang bisa mengubah data saham.');

        return view('saham.edit', compact('saham'));
    }

    public function update(Request $request, Saham $saham)
    {
        abort_unless($this->isAdmin(), 403, 'Hanya admin yang bisa mengubah data saham.');

        $validated = $request->validate([
            'kode_saham' => 'required|string|max:10|unique:sahams,kode_saham,' . $saham->id,
            'nama_perusahaan' => 'required|string|max:255',
            'sektor' => 'nullable|string|max:255',
            'harga_saat_ini' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $saham->update($validated);

        return redirect()
            ->route('saham.show', $saham)
            ->with('success', 'Data saham berhasil diperbarui.');
    }

    public function destroy(Saham $saham)
    {
        abort_unless($this->isAdmin(), 403, 'Hanya admin yang bisa menghapus saham.');

        $saham->delete();

        return redirect()
            ->route('saham.index')
            ->with('success', 'Saham berhasil dihapus dari daftar.');
    }
}
