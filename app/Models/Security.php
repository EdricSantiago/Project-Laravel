<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Security extends Model
{
    use HasFactory;

    protected $table = 'security';

    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}