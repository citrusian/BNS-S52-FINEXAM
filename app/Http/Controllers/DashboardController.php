<?php

namespace App\Http\Controllers;

use App\Models\BTransaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use NumberFormatter;

class DashboardController extends Controller
{

//----------------------------------------------
    // Get Data per month
//----------------------------------------------
    public function index()
    {
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

        return view('pages.dashboard-chart')->with($merge)->with($merge2);
//        return view('pages.dashboard-chart')->with($dataj,$datab);
//        return view('pages.dashboard-chart', compact('dataj', 'datab'));
    }
}
//$data = [
//    'postkey' => $postkey,
//    'Transaksi_id' => $postkey,
//    'Product_id' => $detailTransaksi->Product_id,
//    'Serial_no' => $product->Serial_no,
//    'Product_Name' => $product->Product_Name,
//    'Brand' => $product->Brand,
//    'Customer_Vendor' => $transaksi->Customer_Vendor,
//    'Trans_Type' => $transaksi->Trans_Type,
//    'Price' => $detailTransaksi->Price,
//    'Discount' => $detailTransaksi->Discount,
//];
//
//return redirect('transaksi-edit')->with($data);
