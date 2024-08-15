<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = ['suppliers_id', 'total', "users_id"];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'suppliers_id');
    }

    // In Pembelian.php
    public function rincianPembelians()
    {
        return $this->hasMany(RincianPembelian::class, 'pembelian_id');
    }


    public function user()
{
    return $this->belongsTo(User::class, 'users_id');
}

}
