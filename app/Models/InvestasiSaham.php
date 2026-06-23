<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestasiSaham extends Model
{
    use HasFactory;

    protected $table = 'investasi_sahams';

    protected $fillable = [
        'user_id',
        'saham_id',
        'jumlah_lembar',
        'harga_beli',
        'tanggal_beli',
        'status',
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
        'harga_beli' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function saham(): BelongsTo
    {
        return $this->belongsTo(Saham::class);
    }

    public function getTotalInvestasiAttribute(): float
    {
        return $this->jumlah_lembar * (float) $this->harga_beli;
    }

    public function getNilaiSekarangAttribute(): float
    {
        return $this->jumlah_lembar * (float) $this->saham->harga_saat_ini;
    }

    public function getKeuntunganAttribute(): float
    {
        return $this->nilai_sekarang - $this->total_investasi;
    }
}
