<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BDetailTransaksiSeederbak extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private static $counterBarang = 1;
    private static $Transaksi_id = 1;

    public function run()
    {
        $initGetBarang = DB::table('a_nomor_seris')->count();

//        $barang =
        for ($i = 0; $i < $initGetBarang; $i++){

            $getUsed = DB::table('a_nomor_seris')->where('id', (self::$counterBarang))->value('Used');

            $Product_id = DB::table('a_nomor_seris')->where('id', (self::$counterBarang))->value('Product_id');
            $Serial_no = DB::table('a_nomor_seris')->where('id', (self::$counterBarang))->value('Serial_no');

            $PriceBuy = DB::table('a_nomor_seris')->where('id', (self::$counterBarang))->value('Price');



            DB::table('b_detail_transaksis')->insert([
                'Transaksi_id' => (self::$Transaksi_id++) + (1000),
//                'Product_id' => $Product_id . " beli",
                'Product_id' => $Product_id,
                'Serial_no' => $Serial_no,
                'Price' => $PriceBuy,
                'Discount' => '0',
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),
            ]);

            // only add additional transaction when Used === 1
            if ($getUsed === 1){
                $Discount = Arr::random([0.05, 0.08 ,0.1, 0.15]);
                $DiscountPrice = $PriceBuy * $Discount;

                $randgen = Arr::random([0, 1]);
                $randcall = $randgen;

                if ($randgen === 0){
                    // Untung
                    $FinalPrice = $PriceBuy + $DiscountPrice;
                    $FinalDiscount = $DiscountPrice;
                }
                else {
                    // Rugi / Discount
                    $FinalPrice = $PriceBuy - $DiscountPrice;
                    $FinalDiscount = $DiscountPrice * -1;
                }

                DB::table('b_detail_transaksis')->insert([
                    'Transaksi_id' => (self::$Transaksi_id++) + (1000),
//                    'Product_id' => $Product_id . " jual",
                    'Product_id' => $Product_id,
                    'Serial_no' => $Serial_no,
                    'Price' => $FinalPrice,
                    'Discount' => $FinalDiscount,
                    "created_at" =>  \Carbon\Carbon::now(),
                    "updated_at" => \Carbon\Carbon::now(),
                ]);
            }
            self::$counterBarang++;
            // note:
            // $counterNoTrans get increased at call
            // $counterBarang  get increased at loop end

        }
    }
}

//'Transaksi_id',
//'Product_id',
//'Serial_no',
//'Price',
//'Discount',
