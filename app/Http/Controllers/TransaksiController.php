<?php

namespace App\Http\Controllers;

use App\Models\ABarang;
use App\Models\ANomorSeri;
use App\Models\BDetailTransaksi;
use App\Models\BTransaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\DebugToConsole;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use NumberFormatter;

class TransaksiController extends Controller
{
    use DebugToConsole;


    public function get(Request $request)
    {
//        dd($request->all());
        $filter = $request->input('filter');
        // Filter
        // 0 - Disable
        // 1 - Sell
        // 2 - Buy

        if ($filter == 2) {
            $query = DB::table('b_transaksis')
                ->select('*')
                ->where('Trans_Type', '=', 'Beli')
                ->rightJoin('b_detail_transaksis', 'Transaksi_id', '=', 'No_Trans')
                ->orderByDesc('b_transaksis.created_at')
                ->get();
        }
        elseif ($filter == 1){
            $query = DB::table('b_transaksis')
                ->select('*')
                ->where('Trans_Type', '=', 'Jual')
                ->rightJoin('b_detail_transaksis', 'Transaksi_id', '=', 'No_Trans')
                ->orderByDesc('b_transaksis.created_at')
                ->get();
        }
        else{
            $query = DB::table('b_transaksis')
                ->select('*')
                ->rightJoin('b_detail_transaksis','Transaksi_id','=','No_Trans')
                ->orderByDesc('b_transaksis.created_at')
                ->get();
        }
//        dd($query);

        $role = Auth::user()->role;


        $currentYear = Carbon::now()->year;
        $lastYear = Carbon::now()->subYear()->year;
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $result = [];
        // Separator
//        $transTypes = ['Jual', 'Beli', 'Price'];

        $transTypes = ['Jual', 'Beli'];

        for ($month = $startMonth; $month <= $endMonth; $month->addMonth()) {
            foreach ($transTypes as $transType) {
                $monthData = DB::table('b_detail_transaksis')
                    ->rightJoin('b_transaksis', 'b_detail_transaksis.Transaksi_id', '=', 'b_transaksis.No_Trans')
                    ->rightJoin('a_nomor_seris', 'b_detail_transaksis.Product_id', '=', 'a_nomor_seris.Product_id')
                    ->rightJoin('a_barangs', 'a_nomor_seris.Product_id', '=', 'a_barangs.Model_No')
                    ->select(
                        'b_detail_transaksis.Transaksi_id',
                        'b_detail_transaksis.Product_id',
                        'a_barangs.Product_Name',
                        'a_barangs.Brand',
                        'a_nomor_seris.Price',
                        'b_detail_transaksis.Discount',
                        'b_transaksis.Tanggal',
                        'b_transaksis.Trans_Type',
                        'a_nomor_seris.Serial_no',
                        'a_nomor_seris.Used'
                    )
                    ->whereMonth('b_transaksis.Tanggal', $month->month)
                    ->where('b_transaksis.Trans_Type', $transType)
                    ->get();

                $result[$month->format('M Y')][$transType] = $monthData;

                // Calculate the Price based on Jual/Beli transactions
                $price = $monthData->sum('Price');
                $result[$month->format('M Y')]['Price'] = $price;

                // Count available stock by brand
                $getStockbyBrand = $monthData->where('a_nomor_seris.Used', '!=', true)
                    ->groupBy('a_barangs.Brand')
                    ->pluck('Brand', DB::raw('COUNT(*) as count'));

                $result[$month->format('M Y')]['getStockbyBrand'] = $getStockbyBrand;
            }
        }

//----------------------------------------------
        // Get Stock By Brand
//----------------------------------------------
        function getStockbyBrand($brandName)
        {
            return DB::table('b_detail_transaksis')
                ->rightJoin('b_transaksis', 'b_detail_transaksis.Transaksi_id', '=', 'b_transaksis.No_Trans')
                ->rightJoin('a_nomor_seris', 'b_detail_transaksis.Product_id', '=', 'a_nomor_seris.Product_id')
                ->rightJoin('a_barangs', 'a_nomor_seris.Product_id', '=', 'a_barangs.Model_No')
                ->select(
                    'a_barangs.Brand',
                    DB::raw('COUNT(*) AS count')
                )
                ->where('a_nomor_seris.Used', 0)
                ->where('a_barangs.Brand', $brandName)
                ->groupBy('a_barangs.Brand')
                ->get();
        }

        $brandName = 'Acer';
        $getObject = getStockbyBrand($brandName);
        $Acer = $getObject[0]->count;

        $brandName = 'Apple';
        $getObject = getStockbyBrand($brandName);
        $Apple = $getObject[0]->count;

        $brandName = 'Asus';
        $getObject = getStockbyBrand($brandName);
        $Asus = $getObject[0]->count;

        $brandName = 'Dell';
        $getObject = getStockbyBrand($brandName);
        $Dell = $getObject[0]->count;

        $brandName = 'HP';
        $getObject = getStockbyBrand($brandName);
        $HP = $getObject[0]->count;

        $brandName = 'Lenovo';
        $getObject = getStockbyBrand($brandName);
        $Lenovo= $getObject[0]->count;

//        dd($Lenovo,$Asus,$Apple,$Apple,$Dell,$HP,$Lenovo);

//----------------------------------------------
        // get other Brand, eg: manual input
//----------------------------------------------

        $exclusion = ['Acer', 'Apple', 'Asus', 'Dell', 'HP', 'Lenovo'];

        $getObject = DB::table('b_detail_transaksis')
            ->rightJoin('b_transaksis', 'b_detail_transaksis.Transaksi_id', '=', 'b_transaksis.No_Trans')
            ->rightJoin('a_nomor_seris', 'b_detail_transaksis.Product_id', '=', 'a_nomor_seris.Product_id')
            ->rightJoin('a_barangs', 'a_nomor_seris.Product_id', '=', 'a_barangs.Model_No')
            ->select(
                'a_barangs.Brand',
                DB::raw('COUNT(*) AS count')
            )
            ->where('a_nomor_seris.Used', 0)
            ->whereNotIn('a_barangs.Brand', $exclusion)
            ->groupBy('a_barangs.Brand')
            ->get();


        if ($getObject->count() > 0) {
            $tempRandom = $getObject->random();
            $brandName = $tempRandom->Brand;
            $count = $tempRandom->count;
        }
        else{
            $count = 0;
            // give 0 if no other brand exist
        }

        $Other= $count;

//        dd($Lenovo,$Asus,$Apple,$Apple,$Dell,$HP,$Lenovo,$Other);
//----------------------------------------------
        // loop to Insert Data per Month
//----------------------------------------------
        $dataj = [];
        $datab = [];
        // init array............
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        for ($year = $lastYear; $year <= $lastYear + 1; $year++) {
            for ($index = 0; $index < count($months); $index++) {
                $month = $months[$index];
                $key = $month . ' ' . $year;

                $jualPrice = isset($result[$key]['Jual']) ? $result[$key]['Jual']->sum('Price') : 0;
                $beliPrice = isset($result[$key]['Beli']) ? $result[$key]['Beli']->sum('Price') : 0;

                $dataj['datj' . ($index + 1) . ($year - $lastYear) . '22'] = $jualPrice;
                $datab['datb' . ($index + 1) . ($year - $lastYear) . '22'] = $beliPrice;
            }
        }


//        $test = compact('dataj', 'datab');
//        $test->merge('dataj', 'datab');
//        use php or js?
        $merge = array_merge($dataj, $datab);
        $monthIncome = $merge['datj5122'];
        $monthExpense = $merge['datb5122'];
        $monthProfit = $monthIncome - $monthExpense;
        if ($monthProfit > 1){
            $lossStatus = 0;
        }
        else{
            $lossStatus = 1;
        }

//---------------------------------- Format
        $locale = 'en_US';
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        function formatCurrency($value, $formatter) {
            $formattedValue = number_format($value, 2, '.', ',');
            $currencySymbol = $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
            return $currencySymbol . $formattedValue;
        }

        $monthIncome = formatCurrency($monthIncome, $formatter);
        $monthExpense = formatCurrency($monthExpense, $formatter);
        $monthProfit = formatCurrency($monthProfit, $formatter);
//---------------------------------- Format
//        dd($localizedSales);
//        dd($Lenovo,$Asus,$Apple,$Acer,$Dell,$HP,$Lenovo,$Other);

        $userCount = User::count();
        $merge2 = [
            'monthIncome' => $monthIncome,
            'monthExpense' => $monthExpense,
            'monthProfit' => $monthProfit,
            'lossStatus' => $lossStatus,
            'totalUser' => $userCount,
            'Acer' => $Acer,
            'Apple' => $Apple,
            'Asus' => $Asus,
            'Dell' => $Dell,
            'HP' => $HP,
            'Lenovo' => $Lenovo,
            'Other' => $Other,
        ];
//        dd($merge2);

//        return view('pages.dashboard-chart')->with($merge)->with($merge2);
        return view("pages.transaksi-view",['q1'=>$query])
            ->with('role',$role)
            ->with($merge)
            ->with($merge2);
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
//        Form insert barang, jika masi ada waktu optimize, ada validate & merge berulang2
//--------------------------------------------------------------------------------------------------------------------------------------

    public function create(Request $request)
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
                'Price' => 'required',
                'Discount' => 'required',
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
