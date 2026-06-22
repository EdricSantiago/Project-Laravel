<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Saham extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_saham',
        'nama_perusahaan',
        'sektor',
        'harga_saat_ini',
        'deskripsi',
    ];

    protected $casts = [
        'harga_saat_ini' => 'decimal:2',
    ];

    public function investasi(): HasMany
    {
        return $this->hasMany(InvestasiSaham::class);
    }
}
