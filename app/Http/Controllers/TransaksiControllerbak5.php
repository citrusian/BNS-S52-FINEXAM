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
use Illuminate\Support\Facades\Log;
use App\Http\Traits\DebugToConsole;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class TransaksiControllerbak5 extends Controller
{
    use DebugToConsole;


    public function get()
    {
        $query = DB::table('b_transaksis')
            ->select('*')
            ->rightJoin('b_detail_transaksis','Transaksi_id','=','No_Trans')
            ->get();
//        dd($query);

        $role = Auth::user()->role;

        return view("pages.transaksi-view",['q1'=>$query])
            ->with('role',$role);
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
//                ->with('warn','Transaction Deleted!')
                ->with('sweetConfirm','Transaction '. $postkey . ' Deleted!');
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
//                ->with('warn','Transaction Deleted!')
                ->with('sweetConfirm','Transaction '. $postkey . ' Deleted!');
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

        $role = Auth::user()->role;
        if ($role === 1){
            return back(404);
        }






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

        $role = Auth::user()->role;
        if ($role === 1){
            return response()->view('errors.401', [], 401);
        }

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

        // combine redundant validator
        if ($old_Product_id != $new_Product_id){
            // only need this validator at this check
            $attributes = Validator::make($request->all(),[
                'Product_id' => ['required','unique:a_nomor_seris'],
            ]);

            if ($request->Trans_Type === "Jual") {
                if ($getDuplicate > 1 || $getDuplicate === 0) {
                    $errorMessage = $getDuplicate > 1 ? 'Sudah ada transaksi lain terhadap Model Code tersebut!' : 'Model Code Tidak Terdaftar. Silahkan Tambahkan Barang Baru!';
                    return back()->with('error', 'Error! <br>' . $errorMessage)
                        ->with([
                            'postkey' =>  $postkey,'Transaksi_id' =>  $TID,'Product_id' =>  $PID,'Serial_no' => $SN,
                            'Product_Name' =>  $PN,'Brand' =>  $BR,'Customer_Vendor' =>  $CV,'Trans_Type' =>  $TY,
                            'Price' =>  $PR,'Discount' =>  $DC,
                        ]);
                }
            }
            if ($attributes->fails()) {
                // only need this validator at this check
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
        if ($old_Serial_no !=$new_Serial_no){
            $attributes = Validator::make($request->all(),[
                'Serial_no' => ['required','numeric','unique:a_nomor_seris'],
            ]);

            if ($request->Trans_Type === "Jual") {
                if ($getDuplicate2 > 1 || $getDuplicate2 === 0) {
                    $errorMessage = $getDuplicate > 1 ? 'Sudah ada transaksi lain terhadap Serial Number tersebut!' : 'Serial Number Tidak Terdaftar <br> Silahkan Tambahkan Barang Baru!';
                    return back()->with('error', 'Error! <br>' . $errorMessage)
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

        // skip price update anywhere except BDetailTransaksi
        // because those DB store buying price
        // and use db raw, to call old value
        ANomorSeri::where('Serial_no', $SN)
            ->update([
                'Product_id' => $PIDN,
                'Serial_no' => $SNN,
                'Price' => $request->Trans_Type === "Jual" ? DB::raw('Price') : $PR,
            ]);

        ABarang::where('Model_No', $PID)
            ->update([
                'Model_No' => $PIDN,
                'Product_Name' => $PN,
                'Brand' => $BR,
                'Price' => $request->Trans_Type === "Jual" ? DB::raw('Price') : $PR,
            ]);

        BDetailTransaksi::where('Transaksi_id', $TID)
            ->update([
                'Product_id' => $PIDN,
                'Serial_no' => $SNN,
                'Price' => $PR,
            ]);

        BTransaksi::where('No_Trans', $TID)
            ->update([
                'Customer_Vendor' => $CV,
            ]);

        return redirect('transaksi-view')
            ->with('succes','Data Transaksi '. $TID .' Updated!');
    }

    public function invoke()
    {
        return back()
            ->with('error','Error! Invoke! Placeholder Command!');
    }
//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------

    public function create(Request $request)
    {

        $user = Auth::user();




        $latestTransaction = DB::table('b_transaksis')->latest('id')->first();
        $curid = $latestTransaction->id + 1;
        $currdate = now()->toDateString();
        $Transaksi_id = $curid + 1000;



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
//                return back()->with('error', 'Error! Barang Tidak Terdaftar!');
//                return back()->with('error', 'Error! Barang Tidak Terdaftar!')->with($merge);
//                return back()->with('error', 'Error! Barang Tidak Terdaftar!')->withErrors('Product_id', 'Error! Barang Tidak Terdaftar!')->with($merge);
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
