<?php

namespace App\Http\Controllers;

use App\Models\ABarang;
use App\Models\ANomorSeri;
use App\Models\BDetailTransaksi;
use App\Models\BTransaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\DebugToConsole;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    use DebugToConsole;


    public function get()
    {
        $query = DB::table('b_transaksis')
            ->select('*')
            ->rightJoin('b_detail_transaksis','Transaksi_id','=','No_Trans')
            ->get();
//        dd($query);
        return view("pages.transaksi-view",['q1'=>$query]);
    }

    public function index()
    {
        return view('pages.transaksi-register');
    }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
    public function delete(Request $request)
    {
        $postkey = $request->get('postkey');
//        dd($postkey);

        $getModel = DB::table('b_detail_transaksis')
            ->select('*')->where('Transaksi_id', ($postkey))
            ->get()->first()->Product_id;

        $getSerial = DB::table('b_detail_transaksis')
            ->select('*')->where('Transaksi_id', ($postkey))
            ->get()->first()->Serial_no;

        $getType = DB::table('b_transaksis')
            ->select('*')->where('No_Trans', ($postkey))
            ->get()->first()->Trans_Type;

//        dd($postkey,$getType,$getModel,$getSerial);
//        -------------------------------------------------------------------------------
//        Jual - Update ANomorSeri DB
//        -------------------------------------------------------------------------------
        if ($getType === "Jual"){
            $delT = DB::table('b_transaksis')->where('No_Trans', $postkey)->delete();
            $delTD = DB::table('b_detail_transaksis')->where('Transaksi_id', $postkey)->delete();


            ANomorSeri::where('Serial_no', $getSerial)
                ->update([
                    // price not updated, because it was buying price
                    'Warranty_Start' => null,
                    'Warranty_Duration' => null,
                    'Used' => '0',
                ]);
            return back()
                ->with('warn','Transaction Deleted!');
        }
//        -------------------------------------------------------------------------------
//        Beli - Delete All Related to the items
//        -------------------------------------------------------------------------------
        elseif ($getType === "Beli"){
            $delT = DB::table('b_transaksis')->where('No_Trans', $postkey)->delete();
            $delTD = DB::table('b_detail_transaksis')->where('Transaksi_id', $postkey)->delete();
            $delTD = DB::table('a_barangs')->where('Model_No', $getModel)->delete();
            $delTD = DB::table('a_nomor_seris')->where('Product_id', $getModel)->delete();
            return back()
                ->with('warn','Transaction Deleted!');
        }
        else{
            return back()
                ->with('error','Error! Missing Request Data!');
        }
    }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
    public function edit(Request $request)
    {
        $postkey = $request->get('postkey');
//        dd($postkey);

        $getModel = DB::table('b_detail_transaksis')
            ->select('*')->where('Transaksi_id', ($postkey))
            ->get()->first()->Product_id;
//        dd($getModel);

        $getDetailData = DB::table('b_detail_transaksis')
            ->select('*')->where('Transaksi_id', ($postkey))
            ->get()->first();

        $getTransData = DB::table('b_transaksis')
            ->select('*')->where('No_Trans', ($postkey))
            ->get()->first();

        $getSNData = DB::table('a_nomor_seris')
            ->select('*')->where('Product_id', ($getModel))
            ->get()->first();

        $getBarangData = DB::table('a_barangs')
            ->select('*')->where('Model_No', ($getModel))
            ->get()->first();
//        dd($getBarangData);

//        dd($getModel,$getDetailData,$getTransData,$getSNData,$getBarangData);
        $Transaksi_id = $postkey;
        $Product_id = $getModel;
        $Serial_no = $getDetailData->Serial_no;
        $Product_Name = $getBarangData->Product_Name;
        $Brand = $getBarangData->Brand;
        $Customer_Vendor = $getTransData->Customer_Vendor;
        $Trans_Type = $getTransData->Trans_Type;
        $Price = $getDetailData->Price;
        $Discount = $getDetailData->Discount;


//        dd($Transaksi_id,$Product_id,$Serial_no,$Price,$Discount,$Trans_Type,$Customer_Vendor,$Product_Name,$Brand);
//        $collection = [$Transaksi_id,$Product_id,$Serial_no,$Price,$Discount,$Trans_Type,$Customer_Vendor,$Product_Name,$Brand];
//        dd($collection);
//        $JSONcoll = json_encode($collection);
//        dd($myJSON);

//        value="{{session('q1')[2]}}" >


//        return view('transaksi-edit')->with('postkey',$postkey)->with('q1',$collection);
//        return redirect('transaksi-edit')->with('collection', $JSONcoll);

        return redirect('transaksi-edit')->with([
            'postkey' =>  $postkey,
            'Transaksi_id' =>  $Transaksi_id,
            'Product_id' =>  $Product_id,
            'Serial_no' =>  $Serial_no,
            'Product_Name' =>  $Product_Name,
            'Brand' =>  $Brand,
            'Customer_Vendor' =>  $Customer_Vendor,
            'Trans_Type' =>  $Trans_Type,
            'Price' =>  $Price,
            'Discount' =>  $Discount,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
    public function update(Request $request)
    {
//        dd($request);
        $old_Product_id = $request->old_Product_id;
        $old_Serial_no = $request->old_Serial_no;
        $new_Product_id = $request->Product_id;
        $new_Serial_no = $request->Serial_no;


        $postkey =  $request->postkey; $TID =  $request->Transaksi_id; $PID =  $request->old_Product_id;
        $SN =  $request->old_Serial_no; $PN =  $request->Product_Name; $BR =  $request->Brand;
        $CV =  $request->Customer_Vendor; $TY =  $request->Trans_Type; $PR =  $request->Price;
        $DC =  $request->Discount; $SNN = $new_Serial_no;  $PIDN = $new_Product_id;

//        Manual validator, unique->ignore cant validate request .......................
        if ($old_Product_id != $new_Product_id){
            $attributes = Validator::make($request->all(),[
                'Transaksi_id' => ['required'],
                'Product_id' => ['required','unique:a_nomor_seris'],
                'Serial_no' => ['required','numeric'],
                'Product_Name' => 'required|max:255',   // Editable
                'Brand' => 'required|max:255',          // Editable
                'Customer_Vendor' => 'required',        // Editable
                'Trans_Type' => 'required',
                'Price' => 'required',                  // Editable
                'Discount' => 'required',               // Editable
            ]);
//            $message = $attributes->messages()->all();
//            dd ($message);
            if ($attributes->fails()) {
                $message = $attributes->messages()->all()[0];
                return redirect('transaksi-edit')
                    ->with(['error' => $message])
                    // not in collection, because i already using this at view-edit
                    ->with([
                    'postkey' =>  $postkey,'Transaksi_id' =>  $TID,'Product_id' =>  $PID,'Serial_no' => $SN,
                    'Product_Name' =>  $PN,'Brand' =>  $BR,'Customer_Vendor' =>  $CV,'Trans_Type' =>  $TY,
                    'Price' =>  $PR,'Discount' =>  $DC,
                ]);
            }
        }
        if ($old_Serial_no !=$new_Serial_no){
            $attributes = Validator::make($request->all(),[
                'Transaksi_id' => ['required'],
                'Product_id' => ['required'],
                'Serial_no' => ['required','numeric','unique:a_nomor_seris'],
                'Product_Name' => 'required|max:255',   // Editable
                'Brand' => 'required|max:255',          // Editable
                'Customer_Vendor' => 'required',        // Editable
                'Trans_Type' => 'required',
                'Price' => 'required',                  // Editable
                'Discount' => 'required',               // Editable
            ]);
//            $message = $attributes->messages()->all()[0];
            if ($attributes->fails()) {
                return redirect('transaksi-edit')
                    ->with(['error' => $message])
                    ->with([
                        'postkey' =>  $postkey,'Transaksi_id' =>  $TID,'Product_id' =>  $PID,'Serial_no' => $SN,
                        'Product_Name' =>  $PN,'Brand' =>  $BR,'Customer_Vendor' =>  $CV,'Trans_Type' =>  $TY,
                        'Price' =>  $PR,'Discount' =>  $DC,
                    ]);
            }
        }
        elseif ($old_Product_id === $new_Product_id || $old_Serial_no === $new_Serial_no){
            $attributes = request()->validate([
                'Transaksi_id' => ['required'],
                'Product_id' => ['required'],           // Editable
                'Serial_no' => ['required','numeric'],  // Editable

                'Product_Name' => 'required|max:255',   // Editable
                'Brand' => 'required|max:255',          // Editable
                'Customer_Vendor' => 'required',        // Editable

                'Trans_Type' => 'required',

                'Price' => 'required',                  // Editable
                'Discount' => 'required',               // Editable
            ]);
        }
//        dd($test);

        ANomorSeri::where('Serial_no', $SN)
            ->update([
                // price not updated, because it was buying price
                'Product_id' => $PIDN,
                'Serial_no' => $SNN,
                'Price' => $PR,
            ]);
//        dd($test);
        ABarang::where('Model_No', $PID)
            ->update([
                // price not updated, because it was buying price
                'Model_No' => $PIDN,
                'Product_Name' => $PN,
                'Brand' => $BR,
                'Price' => $PR,
            ]);
//        dd($test);
        BDetailTransaksi::where('Serial_no', $SN)
            ->update([
                // price not updated, because it was buying price
                'Product_id' => $PIDN,
                'Serial_no' => $SNN,
                'Price' => $PR,
            ]);
        BTransaksi::where('No_Trans', $TID)
            ->update([
                // price not updated, because it was buying price
                'Customer_Vendor' => $CV,
            ]);

        return redirect('transaksi-view')
            ->with('succes','Data Updated!');
//        return back()
//            ->with('succes','Data Updated!');
    }
    public function invoke()
    {
        return back()
            ->with('error','Error! Invoke!');
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

        $request->request->add(['Transaksi_id' => $Transaksi_id, 'No_Trans' => $Transaksi_id, 'Tanggal' => $currdate]);
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
