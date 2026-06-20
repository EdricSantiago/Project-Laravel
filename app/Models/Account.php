<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    // SEMUA PROPERTI DIGABUNG DI SINI (TIDAK ADA DUPLIKAT)
    protected $fillable = [
        'user_id',
        'account_number', 
        'balance',
        'status',         
        'asuransi_premium',
        'asuransi_aktif',
        'asuransi_last_paid', // <-- Kodingan asuransimu aman digabung ke sini
    ];

    protected $casts = [
        'balance' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sentTransactions()
    {
        return $this->hasMany(Transaction::class, 'sender_id');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'receiver_id');
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }
}