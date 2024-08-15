<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_information',
        'address',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'customers_id');
    }

    public function penjualan()
    {
        return $this->hasMany(Stock::class, 'customers_id');
    }
}
