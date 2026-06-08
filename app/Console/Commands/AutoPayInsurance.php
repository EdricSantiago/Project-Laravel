<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoPayInsurance extends Command
{
    /**
     * Nama command yang dipanggil via artisan.
     * Contoh: php artisan insurance:autopay
     */
    protected $signature = 'insurance:autopay';

    protected $description = 'Otomatis memotong premi asuransi dari semua akun yang aktif setiap bulan';

    public function handle(): int
    {
        $bulanIni = Carbon::now()->format('Y-m');

        // Ambil semua akun yang: asuransi aktif, dan belum bayar bulan ini
        $accounts = Account::where('asuransi_aktif', true)
            ->where(function ($q) use ($bulanIni) {
                $q->whereNull('asuransi_last_paid')
                  ->orWhereRaw("DATE_FORMAT(asuransi_last_paid, '%Y-%m') != ?", [$bulanIni]);
            })
            ->with('user')
            ->get();

        if ($accounts->isEmpty()) {
            $this->info('Semua akun sudah membayar premi bulan ini. Tidak ada yang diproses.');
            return Command::SUCCESS;
        }

        $this->info("Memproses {$accounts->count()} akun...");
        $berhasil = 0;
        $gagal    = 0;

        foreach ($accounts as $account) {
            DB::transaction(function () use ($account, &$berhasil, &$gagal) {
                // Lock row supaya aman dari race condition
                $account = Account::lockForUpdate()->find($account->id);
                $premi   = $account->asuransi_premium ?? 100000;

                if ($account->balance < $premi) {
                    // Saldo tidak cukup: tandai gagal tapi catat transaksi
                    Transaction::create([
                        'sender_id'   => $account->id,
                        'receiver_id' => null,
                        'amount'      => $premi,
                        'type'        => 'withdraw',
                        'status'      => 'failed',
                    ]);

                    Log::warning("AutoPayInsurance: Saldo tidak cukup untuk akun #{$account->id} ({$account->account_number})");
                    $gagal++;
                    return;
                }

                // Potong saldo
                $account->balance         -= $premi;
                $account->asuransi_last_paid = Carbon::now()->toDateString();
                $account->save();

                Transaction::create([
                    'sender_id'   => $account->id,
                    'receiver_id' => null,
                    'amount'      => $premi,
                    'type'        => 'withdraw',
                    'status'      => 'success',
                ]);

                $berhasil++;
            });
        }

        $this->info("✅ Berhasil: {$berhasil} akun | ❌ Gagal (saldo kurang): {$gagal} akun");
        Log::info("AutoPayInsurance selesai — Berhasil: {$berhasil}, Gagal: {$gagal}");

        return Command::SUCCESS;
    }
}
