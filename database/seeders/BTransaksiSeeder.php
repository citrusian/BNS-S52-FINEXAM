<?php

namespace Database\Seeders;

use App\Models\BTransaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private static $counterBarang = 1;
    private static $counterNoTrans = 1;

    public function run()
    {
        $initGetBarang = DB::table('a_nomor_seris')->count();

//        $barang =
        for ($i = 0; $i < $initGetBarang; $i++){
            $tanggal = DB::table('a_nomor_seris')->where('id', (self::$counterBarang))->value('Prod_date');

            $getUsed = DB::table('a_nomor_seris')->where('id', (self::$counterBarang))->value('Used');
            $transCV = "Beli";
            $TransJB = "Vendor";

            DB::table('b_transaksis')->insert([
                'Tanggal' => $tanggal,
                'No_Trans' => (self::$counterNoTrans++) + (1000),
                'Customer / Vendor' => $transCV,
                'Trans_Type' => $TransJB,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),
            ]);

            // only add additional transaction when Used === 1
            if ($getUsed === 1){
                $transCV = "Jual";
                $TransJB = "Customer";
                $tanggal = DB::table('a_nomor_seris')->where('id', (self::$counterBarang))->value('Warranty_Start');

                DB::table('b_transaksis')->insert([
                    'Tanggal' => $tanggal,
                    'No_Trans' => (self::$counterNoTrans++) + (1000),
                    'Customer / Vendor' => $transCV,
                    'Trans_Type' => $TransJB,
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
