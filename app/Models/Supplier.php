<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_information',
        'address',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'suppliers_id');
    }

    public function pembelian()
    {
        return $this->hasMany(Stock::class, 'suppliers_id');
    }
}
