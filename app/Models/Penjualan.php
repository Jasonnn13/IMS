<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = ['customers_id', 'total', 'users_id', 'status', 'tenggat_waktu', 'sales', 'diskon', 'ppn', 'total_netto', 'dpp'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }

    // In penjualan.php
    public function rincianPenjualans()
    {
        return $this->hasMany(RincianPenjualan::class, 'penjualan_id');
    }


    public function user()
{
    return $this->belongsTo(User::class, 'users_id');
}

}
