<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'Tanggal',
        'No_Trans',
        'Customer / Vendor',
        'Trans_Type',
    ];

    protected $dates = ['created_at', 'updated_at', 'Tanggal'];

//    protected string $model = BTransaksi::class;
}
