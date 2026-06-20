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
        'user_ip',
        'device_type',
        'old_value',
        'new_value', 
        'status', 
        'notes',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}