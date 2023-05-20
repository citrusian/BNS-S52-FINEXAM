<?php

namespace Database\Seeders;

use App\Models\ANomorSeri;
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

    public function run()
    {
        // modify into chunk too,
        // BTransaksiSeeder "1,061.17 ms DONE" into "17.23 ms DONE"
        // BDetailTransaksiSeeder "1,215.23 ms DONE" into "20.36 ms DONE"

        $chunkSize = env('CHUNK_SIZE', 500);
        // Init trans number from 1000 before ++
        $counterNoTrans = 1000;

        DB::table('a_nomor_seris')
            ->orderBy('id') // Add the orderBy clause to ensure consistent results
            ->select('Prod_date', 'Used', 'Warranty_Start')
            ->chunk($chunkSize, function ($barangData) use (&$counterNoTrans) {
                $insertData = [];

                foreach ($barangData as $barang) {
                    $tanggal = $barang->Prod_date;
                    $getUsed = $barang->Used;
                    $TransJB = "Beli";
                    $transCV = "Vendor";

                    $insertData[] = [
                        'No_Trans' => $counterNoTrans++,
                        'Tanggal' => $tanggal,
                        'Customer_Vendor' => $transCV,
                        'Trans_Type' => $TransJB,
                        "created_at" => \Carbon\Carbon::now(),
                        "updated_at" => \Carbon\Carbon::now(),
                    ];

                    // Only add additional transaction when Used === 1
                    if ($getUsed === 1) {
                        $TransJB = "Jual";
                        $transCV = "Customer";
                        $tanggal = $barang->Warranty_Start;

                        $insertData[] = [
                            'No_Trans' => $counterNoTrans++,
                            'Tanggal' => $tanggal,
                            'Customer_Vendor' => $transCV,
                            'Trans_Type' => $TransJB,
                            "created_at" => \Carbon\Carbon::now(),
                            "updated_at" => \Carbon\Carbon::now(),
                        ];
                    }
                }
                DB::table('b_transaksis')->insert($insertData);
            }
        );
    }
}
