<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ANomorSeri extends Model
{
    use HasFactory;

//    public function category(){
//        return $this->belongTo(Kategori::class,'id_kategori','id')
//    }

    protected $fillable = [
        'Product_id',
        'Serial_no',
        'Price',
        'Prod_date',
        'Warranty_Start',
        'Warranty_Duration',
        'Used',
    ];

    protected $dates = ['created_at', 'updated_at', 'Prod_date', 'Warranty_Start', 'Warranty_Duration'];

//    protected string $model = ANomorSeri::class;
}
