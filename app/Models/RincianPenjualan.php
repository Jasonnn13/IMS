<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianPenjualan extends Model
{
    use HasFactory;

    protected $table = 'rincianpenjualan';

    protected $fillable = ['stocks_id', 'penjualan_id', 'quantity', 'price', 'total'];

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stocks_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
