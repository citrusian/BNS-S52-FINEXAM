<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BDetailTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//-------------------------------------------------------
//  Chunk Test
//-------------------------------------------------------
    public function run()
    {
        $chunkSize = 500; // Adjust the chunk size as needed

        DB::table('a_nomor_seris')
            ->orderBy('id')
            ->chunk($chunkSize, function ($barangs) {
                $transaksis = [];
                // init array
                $Transaksi_id = 1000;
                // doesnt need static var
                foreach ($barangs as $barang) {
                    $getUsed = $barang->Used;
                    $Product_id = $barang->Product_id;
                    $Serial_no = $barang->Serial_no;
                    $PriceBuy = $barang->Price;

//                  store data in array first before insert, more performance than calling query on loop
                    $transaksis[] = [
                        'Transaksi_id' => $Transaksi_id++,
                        'Product_id' => $Product_id,
                        'Serial_no' => $Serial_no,
                        'Price' => $PriceBuy,
                        'Discount' => '0',
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
                    ];

                    // only add additional transaction when Used === 1
                    if ($getUsed === 1) {
                        $Discount = Arr::random([0.05, 0.08, 0.1, 0.15]);
                        $DiscountPrice = $PriceBuy * $Discount;

                        $randgen = Arr::random([0, 1]);

                        if ($randgen === 0) {
                            // Untung
                            $FinalPrice = $PriceBuy + $DiscountPrice;
                            $FinalDiscount = $DiscountPrice;
                        } else {
                            // Rugi / Discount
                            $FinalPrice = $PriceBuy - $DiscountPrice;
                            $FinalDiscount = $DiscountPrice * -1;
                        }

                        $transaksis[] = [
                            'Transaksi_id' => $Transaksi_id++,
                            'Product_id' => $Product_id,
                            'Serial_no' => $Serial_no,
                            'Price' => $FinalPrice,
                            'Discount' => $FinalDiscount,
                            'created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now(),
                        ];
                    }
                }
                DB::table('b_detail_transaksis')->insert($transaksis);
            });
    }

}
