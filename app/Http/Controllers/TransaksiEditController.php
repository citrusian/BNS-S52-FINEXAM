<?php

namespace App\Http\Controllers;

use App\Models\ABarang;
use App\Models\ANomorSeri;
use App\Models\BDetailTransaksi;
use App\Models\BTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiEditController extends Controller
{
    public function index(Request $request)
    {

        $role = Auth::user()->role;
        if ($role === 1){
            // if user doesn't have privilege use abort to explicit 404 error
            abort(404);
        }

        $postkey = $request->get('postkey');

        $detailTransaksi = DB::table('b_detail_transaksis')
            ->where('Transaksi_id', $postkey)
            ->first();

        $transaksi = DB::table('b_transaksis')
            ->where('No_Trans', $postkey)
            ->first();

        $product = DB::table('a_nomor_seris')
            ->join('a_barangs', 'a_nomor_seris.Product_id', '=', 'a_barangs.Model_No')
            ->select('a_nomor_seris.Serial_no', 'a_barangs.Product_Name', 'a_barangs.Brand')
            ->where('a_nomor_seris.Product_id', $detailTransaksi->Product_id)
            ->first();

        if (!$detailTransaksi || !$transaksi || !$product) {
            // if user have privilege but data error use return with reason
//            return back(404);
            return back(404)
                ->with('sweetConfirm','Transaksi '. $postkey . ' Berhasil Ditambahkan!');
        }

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

        return redirect('transaksi.transaksi-edit')->with($data);
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

                'Price' => 'numeric|required',                  // Editable
                'Discount' => 'numeric|required',               // Editable
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
//            ->with('succes','Data Transaksi '. $TID .' Updated!');
            ->with('sweetConfirm','Data Transaksi '. $TID . ' Updated!');
    }
}
