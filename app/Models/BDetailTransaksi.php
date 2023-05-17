<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BDetailTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'Transaksi_id',
        'Product_id',
        'Serial_no',
        'Price',
        'Discount',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
