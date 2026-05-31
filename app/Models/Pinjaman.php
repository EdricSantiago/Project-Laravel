<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $fillable = [
        'account_id',
        'amount',
        'tenor_months',
        'interest_rate',
        'monthly_installment',
        'total_repayment',
        'purpose',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'total_repayment' => 'decimal:2',
    ];
    
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedMonthlyInstallmentAttribute(): string
    {
        return 'Rp ' . number_format($this->monthly_installment, 0, ',', '.');
    }

    public function getFormattedTotalRepaymentAttribute(): string
    {
        return 'Rp ' . number_format($this->total_repayment, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Lunas',
            default => $this->status,
        };
    }
}