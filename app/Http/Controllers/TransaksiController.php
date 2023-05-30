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
use App\Http\Traits\DebugToConsole;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use NumberFormatter;


class TransaksiController extends Controller
{

    public function get(Request $request)
    {
        // ---------------------------------------------
        // Transaction list query using filter
        // ---------------------------------------------
        $filter = $request->input('filter');
        // Filter | 1 - Sell | 2 - Buy
        // remove repetition
        // use join by default, then if filter selected use where
        $query = DB::table('b_transaksis')
            ->join('b_detail_transaksis', 'Transaksi_id', '=', 'No_Trans')
            ->orderByDesc('b_transaksis.tanggal');
        if ($filter == 2) {
            $query = $query->where('Trans_Type', '=', 'Beli');
        }
        elseif ($filter == 1) {
            $query = $query->where('Trans_Type', '=', 'Jual');
        }
        $filterResult = $query->get();
//        $queryresult = $query->orderByDesc('b_transaksis.tanggal')->get();



        // ---------------------------------------------
        // Used as main query to get data per month
        // ---------------------------------------------
        $role = Auth::user()->role;
        $lastYear = Carbon::now()->subYear()->year;
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $result = [];
        $transTypes = ['Jual', 'Beli'];
//        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // change looping queries every month into 2 loop (Jual/Beli)
        // then grouped by month
        foreach ($transTypes as $transType) {
            $monthData = DB::table('b_detail_transaksis')
                ->Join('b_transaksis', 'b_detail_transaksis.Transaksi_id', '=', 'b_transaksis.No_Trans')
                ->Join('a_nomor_seris', 'b_detail_transaksis.Product_id', '=', 'a_nomor_seris.Product_id')
                ->Join('a_barangs', 'a_nomor_seris.Product_id', '=', 'a_barangs.Model_No')
                ->select(
                    'b_detail_transaksis.Transaksi_id',
                    'b_detail_transaksis.Product_id',
                    'a_barangs.Product_Name',
                    'a_barangs.Brand',
                    // wrong use of table, get price should be from b_detail_transaksis not a_nomor_seris
                    'b_detail_transaksis.Price',
                    'b_detail_transaksis.Discount',
                    'b_transaksis.Tanggal',
                    'b_transaksis.Trans_Type',
                    'a_nomor_seris.Serial_no',
                    'a_nomor_seris.Used'
                )
                ->whereBetween('b_transaksis.Tanggal', [$startMonth, $endMonth])
                ->where('b_transaksis.Trans_Type', $transType)
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->Tanggal)->format('M Y');
                });

            // loop get data without new query
            foreach ($monthData as $month => $data) {
                $result[$month][$transType] = $data;

                // Calculate the Price based on Jual/Beli transactions
//                $price = $data->sum('Price');
                $price = $data->pluck('Price')->sum();
                $result[$month]['Price'] = $price;

                // Count available stock by brand
                $getStockbyBrand = $data->where('Used', '!=', true)
                    ->groupBy('Brand')
                    ->pluck('Brand', DB::raw('COUNT(*) as count'));

                $result[$month]['getStockbyBrand'] = $getStockbyBrand;
            }
        }

//        dd($result);

        // ---------------------------------------------
        // Main Driver for Top Statistic Bar "Sales Overview"
        // ---------------------------------------------
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

                $dataj['datj' . ($index + 1) . "_" . $year] = $jualPrice;
                $datab['datb' . ($index + 1) . "_" . $year] = $beliPrice;
            }
        }
//        dd($result);
//        dd([$dataj], [$datab]);

        // ---------------------------------------------
        // Used by Google Chart to get last 6 month data
        // ---------------------------------------------
        $currY = Carbon::now()->year;
        $currM = Carbon::now()->month;

        $moneyFlow = array_merge($dataj, $datab);

        $monthIncomeData = 'datj' . $currM . "_" . $currY;
        $monthExpenseData = 'datb' . $currM . "_" . $currY;

        $chartYear = [];
        $chartMonth = [];
        for ($i = 0; $i < 6; $i++) {
            $chartYear[] = Carbon::now()->subMonths($i)->format('Y');
            $chartMonth[] = Carbon::now()->subMonths($i)->format('n');
        }

        for ($i = 1; $i <= 6; $i++) {
            ${"gchartJ$i"} = $moneyFlow['datj' . Carbon::now()->subMonths($i - 1)->format('n_Y')];
            ${"gchartB$i"} = $moneyFlow['datb' . Carbon::now()->subMonths($i - 1)->format('n_Y')];
        }

        $chartBeli = array_merge([$gchartB1],[$gchartB2],[$gchartB3],[$gchartB4],[$gchartB5],[$gchartB6]);
        $chartJual = array_merge([$gchartJ1],[$gchartJ2],[$gchartJ3],[$gchartJ4],[$gchartJ5],[$gchartJ6]);

//        dd($chartYear, $chartMonth, $chartBeli, $chartJual);
//        dd($moneyFlow, $chartBeli, $chartJual);

        $monthIncome = $moneyFlow[$monthIncomeData];
        $monthExpense = $moneyFlow[$monthExpenseData];


        $monthProfit = $monthIncome - $monthExpense;
//        dd($moneyFlow, $chartBeli, $chartJual);
        if ($monthProfit > 1){
            $lossStatus = 0;
        }
        else {
            $lossStatus = 1;
        }

        // ---------------------------------------------
        // Main Driver for Graph "Available Stock by Brand"
        // ---------------------------------------------
        // dont use function, just use query
        // function === calling query every brand insertion like $Acer
        // in this case equal to 6 loop
        $brandsQuery = DB::table('b_detail_transaksis')
            ->join('b_transaksis', 'b_detail_transaksis.Transaksi_id', '=', 'b_transaksis.No_Trans')
            ->join('a_nomor_seris', 'b_detail_transaksis.Product_id', '=', 'a_nomor_seris.Product_id')
            ->join('a_barangs', 'a_nomor_seris.Product_id', '=', 'a_barangs.Model_No')
            ->select(
                'a_barangs.Brand',
                DB::raw('COUNT(*) AS count')
            )
            ->where('a_nomor_seris.Used', 0)
            ->groupBy('a_barangs.Brand');
//            ->get();

        // store pluck data as array
        $brandsCount = $brandsQuery->pluck('count', 'Brand')->toArray();

        // use isset to check null/0 value
        $Acer = isset($brandsCount['Acer']) ? $brandsCount['Acer'] : 0;
        $Apple = isset($brandsCount['Apple']) ? $brandsCount['Apple'] : 0;
        $Asus = isset($brandsCount['Asus']) ? $brandsCount['Asus'] : 0;
        $Dell = isset($brandsCount['Dell']) ? $brandsCount['Dell'] : 0;
        $HP = isset($brandsCount['HP']) ? $brandsCount['HP'] : 0;
        $Lenovo = isset($brandsCount['Lenovo']) ? $brandsCount['Lenovo'] : 0;

        // set exclusion for Custom/Other brand query
        $exclusion = ['Acer', 'Apple', 'Asus', 'Dell', 'HP', 'Lenovo'];

        // get Custom/Other brand count
        $Other = $brandsQuery
            ->whereNotIn('a_barangs.Brand', $exclusion)
            ->count();


        // ---------------------------------------------
        // Locale formatter,
        // using Rp xxx,xxx,xxx rather than pure number
        // ---------------------------------------------
        $locale = 'id_ID';
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $currencySymbol = $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

        function formatCurrency($value, NumberFormatter $formatter) {
            return $formatter->format($value);
        }
        $monthIncomeFormatted = formatCurrency($monthIncome, $formatter, $currencySymbol);
        $monthExpenseFormatted = formatCurrency($monthExpense, $formatter, $currencySymbol);
        $monthProfitFormatted = formatCurrency($monthProfit, $formatter, $currencySymbol);

        // ---------------------------------------------
        // Merge all used data to output
        // ---------------------------------------------
        $userCount = User::count();
        $stockData = [
            'monthIncome' => $monthIncomeFormatted,
            'monthExpense' => $monthExpenseFormatted,
            'monthProfit' => $monthProfitFormatted,
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

        return view("pages.transaksi.transaksi-view",)
//            ->with($chartBeli, $chartJual)
            ->with([
                'chartYear' => $chartYear,
                'chartMonth' => $chartMonth,
                'chartBeli' => $chartBeli,
                'chartJual' => $chartJual,
                'q1' => $filterResult,
            ])
            ->with('role',$role)
            ->with($moneyFlow)
            ->with($stockData);
    }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
    public function delete(Request $request)
    {
        $postkey = $request->get('postkey');

        $detailTransaksi = DB::table('b_detail_transaksis')
            ->select('*')->where('Transaksi_id', $postkey)
            ->first();

        $getModel = $detailTransaksi->Product_id;
        $getSerial = $detailTransaksi->Serial_no;

        $getType = DB::table('b_transaksis')
            ->select('*')->where('No_Trans', $postkey)
            ->first()->Trans_Type;

        if ($getType === "Jual") {
            DB::table('b_transaksis')->where('No_Trans', $postkey)->delete();
            DB::table('b_detail_transaksis')->where('Transaksi_id', $postkey)->delete();

            ANomorSeri::where('Serial_no', $getSerial)
                ->update([
                    // price not updated, because it was buying price
                    'Warranty_Start' => null,
                    'Warranty_Duration' => null,
                    'Used' => '0',
                ]);
            return back()
                ->with('sweetConfirm', 'Transaction ' . $postkey . ' Deleted!');
        }
        elseif ($getType === "Beli") {
            DB::table('b_transaksis')->where('No_Trans', $postkey)->delete();
            DB::table('b_detail_transaksis')->where('Transaksi_id', $postkey)->delete();
            DB::table('a_barangs')->where('Model_No', $getModel)->delete();
            DB::table('a_nomor_seris')->where('Product_id', $getModel)->delete();
            return back()
                ->with('sweetConfirm', 'Transaction ' . $postkey . ' Deleted!');
        }
        else {
            return back()
                ->with('error', 'Error! Missing Request Data!');
        }
    }

//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
//Placeholder if somehow form button use invoke !! submit
    public function invoke()
    {
        return back()
            ->with('error','Error! Invoke! How did you find this!');
    }
//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
}
