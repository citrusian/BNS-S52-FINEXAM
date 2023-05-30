<?php

namespace App\Http\Controllers;

use App\Models\ABarang;
use App\Models\ANomorSeri;
use App\Models\BDetailTransaksi;
use App\Models\BTransaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function get()
    {
        $query = DB::table('a_barangs')
            ->select('*')
            ->rightJoin('a_nomor_seris','Product_id','=','Model_No')
            ->get();
//        dd($query);
        return view("pages.item.item-view",['q1'=>$query]);
    }

    public function index()
    {
        return view('pages.item.item-register');
    }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
    public function delete()
    {
        return back()
            ->with('error','Error! Missing Request Data!');
    }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
    public function edit()
    {
        return back()
            ->with('error','Error! Missing Request Data!');
    }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------

    public function create(Request $request)
    {
//        -------------------------------------------------------------------------------
//        get additional data needed by model
//        -------------------------------------------------------------------------------
        $getlast = DB::table('b_transaksis')
            ->latest('id')
            ->first();
        $curid = $getlast->id;
        $curid +=1;
        $currdate = \Carbon\Carbon::now()->toDateString();
//        dd($currdate);
        $Transaksi_id = $curid + 1000;
//        dd($Transaksi_id);

        $request->request->add(['Transaksi_id' => $Transaksi_id, 'No_Trans' => $curid, 'Tanggal' => $currdate]);
        // adding item to request not safe, but idk the alternative
//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------

        if ($request->Trans_Type == "Jual"){

//        -------------------------------------------------------------------------------
//            check if the items already used 2 times or sold
//            alternative to unique, because validator can only check if value is truly unique
//        -------------------------------------------------------------------------------
            $getDuplicate = DB::table('b_detail_transaksis')->where('Product_id', ($request->Product_id))->count();
            $getDuplicate2 = DB::table('b_detail_transaksis')->where('Serial_no', ($request->Serial_no))->count();

            if ($getDuplicate > 1 || $getDuplicate2 > 1){
                return back()
                    ->with('error','Error! Stok Barang Sudah Terjual!');
            }
            if ($getDuplicate  === 0 || $getDuplicate2 === 0){
                return back()
                    ->with('error','Error! Barang Tidak Terdaftar!');
            }
//        -------------------------------------------------------------------------------
//            check if the items Model and SN is equal with each other
//        -------------------------------------------------------------------------------
            $checkSerial = DB::table('b_detail_transaksis')->where('Product_id', ($request->Product_id))->first()->Serial_no;
            $checkModel = DB::table('b_detail_transaksis')->where('Serial_no', ($request->Serial_no))->first()->Product_id;
//            dd($checkSerial);
            if ( ($request->Serial_no) != $checkSerial || ($request->Product_id) != $checkModel){
                return back()
                    ->with('error','Error! Model dan Serial Number Tidak Cocok!');
            }
//        -------------------------------------------------------------------------------
//            Main Driver
//        -------------------------------------------------------------------------------
            $request->request->add(['Model_No' => request()->Product_id, 'Used' => '1',]);
            $Warranty_Duration = Carbon::parse($currdate)->addYears(2)->toDateString();

            $request->request->add(['Warranty_Start' => $currdate, 'Warranty_Duration' => $Warranty_Duration,]);
            $attributes = request()->validate([
                'Transaksi_id' => 'required',
                'No_Trans' => 'required',
                // auto increment using currid

                'Model_No' => 'required|min:3',
                'Product_id' => 'required|min:3',
                'Serial_no' => 'required|numeric|min:3',
                'Tanggal' => 'required:Y-m-d',
//
                'Warranty_Start' => 'required',
                'Warranty_Duration' => 'required',

                'Price' => 'required',
                'Discount' => 'required',

                'Customer_Vendor' => 'required',
                'Trans_Type' => 'required',
                'Used' => 'required',
            ]);

            $insert = BTransaksi::create($attributes);
            $insert2 = BDetailTransaksi::create($attributes);

            ANomorSeri::where('Serial_no', $checkSerial)
                ->update([
                    // price not updated, because it was buying price
                    'Warranty_Start' => $currdate,
                    'Warranty_Duration' => $Warranty_Duration,
                    'Used' => '1',
                ]);
            // ABarang sama seperti ANomorSeri, menggunakan buying price

            return back()
                ->with('succes','Succes! Transaction Data Added!');
        }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------

        elseif ($request->Trans_Type == "Beli"){
//        -------------------------------------------------------------------------------
//            check if the Product_id and Serial_no were unique
//            'Product_id' digunakan sebagai PK dalam database 'a_barang', sehingga harus unique
//        -------------------------------------------------------------------------------
            $request->request->add(['Model_No' => request()->Product_id, 'Used' => '0',]);
            $getDuplicate = DB::table('b_detail_transaksis')->where('Product_id', ($request->Product_id))->count();
            $getDuplicate2 = DB::table('b_detail_transaksis')->where('Serial_no', ($request->Serial_no))->count();
            $brand = $request->Brand;
//            dd($getDuplicate,$getDuplicate2);
            if ($getDuplicate > 0 || $getDuplicate2 > 0){
                return back()
                    ->with('error','Error! Barang Sudah Terdaftar!');
            }
            if (($request->Discount) === null){
                $request->request->add(['Discount' => '0',]);
            }
            if (($request->Product_Name) === null){
                $request->request->add(['Product_Name' => 'Custom',]);
            }
            if (($brand) === null){
                $request->request->add(['Brand' => 'Custom',]);
            }
            // skip adding multiple if else auto insert statement
            // implement jika waktunya cukup
//            if (($brand) === 'Asus'){
//                $request->request->add(['Brand' => 'Custom',]);
//            }

            $request->request->add(['Prod_date' => $currdate,]);
            $attributes = request()->validate([
                'Transaksi_id' => 'required',
                'No_Trans' => 'required',
                // auto increment using currid
                'Product_Name' => 'required|min:3',
                'Brand' => 'required|min:3',

                'Model_No' => 'required|min:3',
                'Product_id' => 'required|min:3',
                'Serial_no' => 'required|numeric|min:3',
                'Tanggal' => 'required:Y-m-d',
//
                'Prod_date' => 'required',

                'Price' => 'required',
                'Discount' => 'required',
//                'Discount',

                'Customer_Vendor' => 'required',
                'Trans_Type' => 'required',
                'Used' => 'required',
            ]);
//            dd($attributes);

            $insert = BTransaksi::create($attributes);
            $insert2 = BDetailTransaksi::create($attributes);
            $insert3 = ANomorSeri::create($attributes);
            $insert4 = ABarang::create($attributes);

            return back()
                ->with('succes','Succes! Transaction Data Added!');
//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
        }
        else {
            return back()
                ->with('error','Error! Missing Request Data!');
        }
    }
}
