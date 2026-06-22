<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\InvestasiSaham;
use App\Models\Saham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvestasiController extends Controller
{
    public function store(Request $request, Saham $saham)
    {
        $validated = $request->validate([
            'jumlah_lembar' => 'required|integer|min:1',
        ]);

        $totalHarga = $validated['jumlah_lembar'] * (float) $saham->harga_saat_ini;

        try {
            DB::transaction(function () use ($validated, $saham, $totalHarga) {
                $account = Account::where('user_id', Auth::id())
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($account->status !== 'active') {
                    throw new \RuntimeException('akun_tidak_aktif');
                }

                if ((float) $account->balance < $totalHarga) {
                    throw new \RuntimeException('saldo_kurang');
                }

                $account->decrement('balance', $totalHarga);

                InvestasiSaham::create([
                    'user_id' => Auth::id(),
                    'saham_id' => $saham->id,
                    'jumlah_lembar' => $validated['jumlah_lembar'],
                    'harga_beli' => $saham->harga_saat_ini,
                    'tanggal_beli' => now(),
                    'status' => 'aktif',
                ]);
            });
        } catch (\RuntimeException $e) {
            $pesan = match ($e->getMessage()) {
                'saldo_kurang' => 'Saldo tidak mencukupi untuk melakukan investasi ini.',
                'akun_tidak_aktif' => 'Rekening Anda sedang tidak aktif, hubungi customer service.',
                default => 'Terjadi kesalahan, silakan coba lagi.',
            };

            return back()->with('error', $pesan);
        }

        return redirect()
            ->route('investasi.index')
            ->with('success', "Berhasil invest {$validated['jumlah_lembar']} lembar {$saham->kode_saham}.");
    }

    public function index()
    {
        $investasi = InvestasiSaham::with('saham')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('investasi.index', compact('investasi'));
    }

    public function show(InvestasiSaham $investasi)
    {
        abort_unless($investasi->user_id === Auth::id(), 403);

        $investasi->load('saham');

        return view('investasi.show', compact('investasi'));
    }
}
