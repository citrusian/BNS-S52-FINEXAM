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

class TransaksiControllerBak3 extends Controller
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

        $detailTransaksi = DB::table('b_detail_transaksis')
            ->select('*')
            ->where('Transaksi_id', $postkey)
            ->first();

        $transaksi = DB::table('b_transaksis')
            ->select('*')
            ->where('No_Trans', $postkey)
            ->first();

        $product = DB::table('a_nomor_seris')
            ->join('a_barangs', 'a_nomor_seris.Product_id', '=', 'a_barangs.Model_No')
            ->select('a_nomor_seris.Serial_no', 'a_barangs.Product_Name', 'a_barangs.Brand')
            ->where('a_nomor_seris.Product_id', $detailTransaksi->Product_id)
            ->first();

        $data = [
            'postkey' => $postkey,
            'Transaksi_id' => $postkey,
            'Product_id' => $detailTransaksi->Product_id,
            'Serial_no' => $product->Serial_no,
            'Product_Name' => $product->Product_Name,
            'Brand' => $product->Brand,
            'Customer_Vendor' => $transaksi->Customer_Vendor,
            'Trans_Type' => $transaksi->Trans_Type,
            'Price' => $detailTransaksi->Price,
            'Discount' => $detailTransaksi->Discount,
        ];

        return redirect('transaksi-edit')->with($data);
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



        // when editing existing jual transaction, it checks if  Product_id or Serial_no already used in other Transaction
        // and only if Product_id is not exist (it means the Product_id not inserted yet
        // to add Product_id, create new Beli transaction, not update data
//        Manual validator, unique->ignore cant validate request .......................
        $getDuplicate = DB::table('b_detail_transaksis')->where('Product_id', $new_Product_id)->count();
        $getDuplicate2 = DB::table('b_detail_transaksis')->where('Serial_no', $new_Serial_no)->count();
//        dd($getDuplicate);
//        dd($getDuplicate2);

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


            if ($request->Trans_Type === "Jual") {
                if ($getDuplicate > 1) {
                return back()->with('error', 'Error! <br>Sudah ada transaksi lain terhadap Model Code tersebut!')
                    ->with([
                        'postkey' =>  $postkey,'Transaksi_id' =>  $TID,'Product_id' =>  $PID,'Serial_no' => $SN,
                        'Product_Name' =>  $PN,'Brand' =>  $BR,'Customer_Vendor' =>  $CV,'Trans_Type' =>  $TY,
                        'Price' =>  $PR,'Discount' =>  $DC,
                    ]);
                }
                if ($getDuplicate === 0) {
                    return back()->with('error', 'Error! <br>Model Code Tidak Terdaftar <br> Silahkan Tambahkan Barang Baru!')
                        ->with([
                            'postkey' =>  $postkey,'Transaksi_id' =>  $TID,'Product_id' =>  $PID,'Serial_no' => $SN,
                            'Product_Name' =>  $PN,'Brand' =>  $BR,'Customer_Vendor' =>  $CV,'Trans_Type' =>  $TY,
                            'Price' =>  $PR,'Discount' =>  $DC,
                        ]);
                }
            }
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

            if ($request->Trans_Type === "Jual") {
                if ($getDuplicate2 > 1) {
                    return back()->with('error', 'Error! <br>Sudah ada transaksi lain terhadap Serial Number tersebut!')
                        ->with([
                            'postkey' =>  $postkey,'Transaksi_id' =>  $TID,'Product_id' =>  $PID,'Serial_no' => $SN,
                            'Product_Name' =>  $PN,'Brand' =>  $BR,'Customer_Vendor' =>  $CV,'Trans_Type' =>  $TY,
                            'Price' =>  $PR,'Discount' =>  $DC,
                        ]);
                }
                if ($getDuplicate2 === 0) {
                    return back()->with('error', 'Error! <br>Serial Number Tidak Terdaftar <br> Silahkan Tambahkan Barang Baru!')
                        ->with([
                            'postkey' =>  $postkey,'Transaksi_id' =>  $TID,'Product_id' =>  $PID,'Serial_no' => $SN,
                            'Product_Name' =>  $PN,'Brand' =>  $BR,'Customer_Vendor' =>  $CV,'Trans_Type' =>  $TY,
                            'Price' =>  $PR,'Discount' =>  $DC,
                        ]);
                }
            }

            if ($attributes->fails()) {
                $message = $attributes->messages()->all()[0];
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
        $latestTransaction = DB::table('b_transaksis')->latest('id')->first();
        $curid = $latestTransaction->id + 1;
        $currdate = now()->toDateString();
        $Transaksi_id = $curid + 1000;

        $request->merge([
            'Transaksi_id' => $Transaksi_id,
            'No_Trans' => $Transaksi_id,
            'Tanggal' => $currdate
        ]);

        if ($request->Trans_Type === "Jual") {
            $getDuplicate = DB::table('b_detail_transaksis')->where('Product_id', $request->Product_id)->count();
            $getDuplicate2 = DB::table('b_detail_transaksis')->where('Serial_no', $request->Serial_no)->count();

            if ($getDuplicate > 1 || $getDuplicate2 > 1) {
                return back()->with('error', 'Error! Stok Barang Sudah Terjual!');
            }

            if ($getDuplicate === 0 || $getDuplicate2 === 0) {
                return back()->with('error', 'Error! Barang Tidak Terdaftar!');
            }

            $checkSerial = DB::table('b_detail_transaksis')->where('Product_id', $request->Product_id)->first()->Serial_no;
            $checkModel = DB::table('b_detail_transaksis')->where('Serial_no', $request->Serial_no)->first()->Product_id;

            if ($request->Serial_no !== $checkSerial || $request->Product_id !== $checkModel) {
                return back()->with('error', 'Error! Model dan Serial Number Tidak Cocok!');
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
                'Price' => 'required',
                'Discount' => 'required',
                'Customer_Vendor' => 'required',
                'Trans_Type' => 'required',
                'Used' => 'required'
            ]);

            BTransaksi::create($validatedData);
            BDetailTransaksi::create($validatedData);

            ANomorSeri::where('Serial_no', $checkSerial)->update([
                'Warranty_Start' => $currdate,
                'Warranty_Duration' => Carbon::parse($currdate)->addYears(2)->toDateString(),
                'Used' => '1'
            ]);

            return back()->with('succes', 'Success! Transaction Data Added!');
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
                'Price' => 'required',
                'Discount' => 'required',
                'Customer_Vendor' => 'required',
                'Trans_Type' => 'required',
                'Used' => 'required'
            ]);

            BTransaksi::create($validatedData);
            BDetailTransaksi::create($validatedData);
            ANomorSeri::create($validatedData);
            ABarang::create($validatedData);

            return back()->with('succes', 'Success! Transaction Data Added!');
        }
        else {
            return back()
                ->with('error','Error! Missing Request Data!');
        }
    }
}
