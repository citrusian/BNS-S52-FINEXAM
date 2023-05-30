<?php

namespace App\Http\Controllers;

use App\Models\ABarang;
use App\Models\ANomorSeri;
use App\Models\BDetailTransaksi;
use App\Models\BTransaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiRegisterController extends Controller
{
    public function index()
    {
        return view('pages.transaksi.transaksi-register');
    }


    public function register(Request $request)
    {
        $userRole = Auth::user()->role;

        $latestTransaction = DB::table('b_transaksis')->latest('id')->first();
        $curid = $latestTransaction->id + 1;
        $currdate = now()->toDateString();
        $Transaksi_id = $curid + 1000;

        $request->request->add(['Transaksi_id' => $Transaksi_id, 'No_Trans' => $Transaksi_id, 'Tanggal' => $currdate]);
        $merge = [];
        $merge = array_merge($merge, Arr::except($request->all(), ['_token']));
        $merge = array_merge($merge,[
            'Transaksi_id' => $Transaksi_id,
            'No_Trans' => $Transaksi_id,
            'Tanggal' => $currdate
        ]);
//        dd($merge);

        if ($request->Trans_Type === "Jual") {
            $getDuplicate = DB::table('b_detail_transaksis')->where('Product_id', $request->Product_id)->count();
            $getDuplicate2 = DB::table('b_detail_transaksis')->where('Serial_no', $request->Serial_no)->count();

            if ($getDuplicate > 1 || $getDuplicate2 > 1) {
                return back()->with('error', 'Error! Stok Barang Sudah Terjual!')
                    ->with(['merge' =>  $merge,]);
            }

            if ($getDuplicate === 0 || $getDuplicate2 === 0){
                return back()
                    ->withErrors(['Product_id' => 'Error! Barang Tidak Terdaftar!'])
                    ->with(['merge' =>  $merge,])
                    ->with('error', 'Error! Barang Tidak Terdaftar!');
            }

            $checkSerial = DB::table('b_detail_transaksis')->where('Product_id', $request->Product_id)->value('Serial_no');
            $checkModel = DB::table('b_detail_transaksis')->where('Serial_no', $request->Serial_no)->value('Product_id');

            if ($request->Serial_no !== $checkSerial || $request->Product_id !== $checkModel) {
                return back()->with('error', 'Error! Model dan Serial Number Tidak Cocok!')
                    ->with(['merge' =>  $merge,]);
            }

            $request->merge([
                'Model_No' => $request->Product_id,
                'Used' => '1',
                'Warranty_Start' => $currdate,
                'Warranty_Duration' => Carbon::parse($currdate)->addYears(2)->toDateString()
            ]);

            $validatedData = $request->validate([
                'Transaksi_id' => 'required',
                'No_Trans' => 'required',
                'Model_No' => 'required|min:3',
                'Product_id' => 'required|min:3',
                'Serial_no' => 'required|numeric|min:3',
                'Tanggal' => 'required|date',
                'Warranty_Start' => 'required|date',
                'Warranty_Duration' => 'required|date',
                'Price' => 'numeric|required',
                'Discount' => 'numeric',
                'Customer_Vendor' => 'required',
                'Trans_Type' => 'required',
                'Used' => 'required'
            ]);

            $validatedData['Discount'] = isset($request->Discount) ? $request->Discount : 0;


            BTransaksi::create($validatedData);
            BDetailTransaksi::create($validatedData);

            ANomorSeri::where('Serial_no', $checkSerial)->update([
                'Warranty_Start' => $currdate,
                'Warranty_Duration' => Carbon::parse($currdate)->addYears(2)->toDateString(),
                'Used' => '1'
            ]);

            if ($userRole == 1){
                return redirect('profile');
            }
            else{
                return back()->with('succes', 'Success! Transaction Data Added!');
            }
        }
        elseif ($request->Trans_Type === "Beli") {
            $request->merge([
                'Model_No' => $request->Product_id,
                'Used' => '0',
                'Prod_date' => $currdate
            ]);

            $getDuplicate = DB::table('b_detail_transaksis')->where('Product_id', $request->Product_id)->count();
            $getDuplicate2 = DB::table('b_detail_transaksis')->where('Serial_no', $request->Serial_no)->count();
            $brand = $request->Brand;

            if ($getDuplicate > 0 || $getDuplicate2 > 0) {
                return back()->with('error', 'Error! Barang Sudah Terdaftar!');
            }

            if (!$request->Discount) {
                $request->merge(['Discount' => '0']);
            }

            if (!$request->Product_Name) {
                $request->merge(['Product_Name' => 'Custom']);
            }

            if (!$brand) {
                $request->merge(['Brand' => 'Custom']);
            }

            $request->merge(['Prod_date' => $currdate]);
//            dd($request);

            $validatedData = $request->validate([
                'Transaksi_id' => 'required',
                'No_Trans' => 'required',
                'Product_Name' => 'required|min:3',
                'Brand' => 'required|min:3',
                'Model_No' => 'required|min:3',
                'Product_id' => 'required|min:3',
                'Serial_no' => 'required|numeric|min:3',
                'Tanggal' => 'required|date',
                'Prod_date' => 'required|date',
                'Price' => 'numeric|required',
                'Discount' => 'numeric',
                'Customer_Vendor' => 'required',
                'Trans_Type' => 'required',
                'Used' => 'required'
            ]);
            $transaksiid = $request->Transaksi_id;
//            dd($transaksiid);

            BTransaksi::create($validatedData);
            BDetailTransaksi::create($validatedData);
            ANomorSeri::create($validatedData);
            ABarang::create($validatedData);

            if ($userRole == 1){
                return redirect('profile')
                    ->with('sweetConfirm','Transaksi '. $transaksiid . ' Berhasil Ditambahkan!');
            }
            else{
                return back()->with('succes', 'Success! Transaction Data Added!');
            }
        }
        else {
            return back()
                ->with('error','Error! Missing Request Data!');
        }
    }
}
