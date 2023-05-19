<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'No_Trans',
        'Tanggal',
        'Customer_Vendor',
        'Trans_Type',
    ];

    protected $dates = ['created_at', 'updated_at', 'Tanggal'];

//    protected string $model = BTransaksi::class;
}
