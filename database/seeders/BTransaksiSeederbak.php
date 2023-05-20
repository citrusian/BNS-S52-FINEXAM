<?php

namespace Database\Seeders;

use App\Models\BTransaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BTransaksiSeederbak extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private static int $counterBarang = 1;
    private static int $counterNoTrans = 1;

    public function run()
    {
        $initGetBarang = DB::table('a_nomor_seris')->count();

//        $barang =
        for ($i = 0; $i < $initGetBarang; $i++){
            $tanggal = DB::table('a_nomor_seris')->where('id', (self::$counterBarang))->value('Prod_date');

            $getUsed = DB::table('a_nomor_seris')->where('id', (self::$counterBarang))->value('Used');
            $TransJB = "Beli";
            $transCV = "Vendor";

            DB::table('b_transaksis')->insert([
                'No_Trans' => (self::$counterNoTrans++) + (1000),
                'Tanggal' => $tanggal,
                'Customer_Vendor' => $transCV,
                'Trans_Type' => $TransJB,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),
            ]);

            // only add additional transaction when Used === 1
            if ($getUsed === 1){
                $TransJB = "Jual";
                $transCV = "Customer";
                $tanggal = DB::table('a_nomor_seris')->where('id', (self::$counterBarang))->value('Warranty_Start');

                DB::table('b_transaksis')->insert([
                    'No_Trans' => (self::$counterNoTrans++) + (1000),
                    'Tanggal' => $tanggal,
                    'Customer_Vendor' => $transCV,
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
