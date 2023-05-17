<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ABarang extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
//    protected $table = 'Barang';

    protected $fillable = [
        'Product_Name',
        'Brand',
        'Price',
        'Model_No',
    ];

    protected string $model = ABarang::class;
}
