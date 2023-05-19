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

    public function index()
    {
        $currentYear = Carbon::now()->year;
        $lastYear = Carbon::now()->subYear()->year;
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $result = [];
        // Separator
        $transTypes = ['Jual', 'Beli', 'Price'];

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
            }
        }

//        $test = $result['Dec 2023']['Jual']->sum('Price');
//        $test = $test = isset($result['Apr 2023']['Jual']) ? $result['Apr 2023']['Jual']->sum('Price') : 0;
//        dd($test);

        $dataj = [];
        $datab = [];
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

        $merge = array_merge($dataj, $datab);
        $monthIncome = $merge['datj5122'];
        $monthExpense = $merge['datb5122'];
        $monthProfit = $monthIncome - $monthExpense;

//---------------------------------- Format
        $locale = 'en_US';
        $formattedSales = number_format($monthIncome, 2, '.', ',');
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        $currencySymbol = $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
        $monthIncome = $currencySymbol . $formattedSales;

        $formattedSales = number_format($monthExpense, 2, '.', ',');
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        $currencySymbol = $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
        $monthExpense = $currencySymbol . $formattedSales;

        $formattedSales = number_format($monthProfit, 2, '.', ',');
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        $currencySymbol = $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
        $monthProfit = $currencySymbol . $formattedSales;
//---------------------------------- Format
//        dd($localizedSales);

        $userCount = User::count();
        $merge2 = [
            'monthIncome' => $monthIncome,
            'monthExpense' => $monthExpense,
            'monthProfit' => $monthProfit,
            'totalUser' => $userCount,
        ];
//        dd($merge2);

        return view('pages.dashboard-chart')->with($merge)->with($merge2);
//        return view('pages.dashboard-chart')->with($dataj,$datab);
//        return view('pages.dashboard-chart', compact('dataj', 'datab'));



        dd($dataj,$datab);
//return redirect('transaksi-edit')->with($data);
//        return view('pages.dashboard-chart')->with($dataj,$datab);
//        return redirect('pages.dashboard-chart')->with($data);
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
