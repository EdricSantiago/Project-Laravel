<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceProduct extends Model
{
    protected $fillable = [
        'category',
        'provider',
        'name',
        'nominal',
        'price',
        'status',
    ];

    public function orders()
    {
        return $this->hasMany(EcommerceOrder::class);
    }
}