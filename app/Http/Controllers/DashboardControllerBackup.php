<?php

namespace App\Http\Controllers;

use App\Models\BTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardControllerBackup extends Controller
{

    public function index()
    {
//        $currYear = \Carbon\Carbon::now()->year;
//
//        $basedata = DB::table('b_detail_transaksis')
//            ->rightJoin('b_transaksis', 'b_detail_transaksis.Transaksi_id', '=', 'b_transaksis.No_Trans')
//            ->rightJoin('a_nomor_seris', 'b_detail_transaksis.Product_id', '=', 'a_nomor_seris.Product_id')
//            ->rightJoin('a_barangs', 'a_nomor_seris.Product_id', '=', 'a_barangs.Model_No')
//            ->select(
//                'b_detail_transaksis.Transaksi_id',
//                'b_detail_transaksis.Product_id',
//                'a_barangs.Product_Name',
//                'a_barangs.Brand',
//                'a_nomor_seris.Price',
//                'b_detail_transaksis.Discount',
//                'b_transaksis.Tanggal',
//                'b_transaksis.Trans_Type',
//                'a_nomor_seris.Serial_no',
//                'a_nomor_seris.Used'
//            )
//            ->get();
//
//        dd($basedata);


        $currentYear = Carbon::now()->year;
        $lastYear = Carbon::now()->subYear()->year;
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $result = [];
        for ($month = $startMonth; $month <= $endMonth; $month->addMonth()) {
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
                ->get();

            $result[$month->format('M Y')] = $monthData;
            $result2[$month->format('M Y')] = $monthData;
        }

        $basedata = BTransaksi::query();
        $basedata2 = $basedata
            ->select('No_Trans','Tanggal')
            ->rightJoin('b_detail_transaksis','Transaksi_id','=','No_Trans')
            ->get();

//
//        $y2022 = DB::table('b_transaksis')
//            ->whereYear('Tanggal', ($currYear) - 1)
//            ->get();
//
//        $y2023 = DB::table('b_transaksis')
//            ->whereYear('Tanggal', $currYear)
//            ->get();
//
//        $y1m1 = $y2022
//            ->select('*')
//            ->rightJoin('b_detail_transaksis','Transaksi_id','=','No_Trans')
//            ->get();

//        dd($y2022,$y2023,$y1m1);

//        $y1m1 = $basedata
//            ->select('*')
//            ->where()
//            ->count();

//        dd($y1m1);



//        accessor
//        $result['Jan 2023']
//        $result['Feb 2023']
//        $result[$month->format('M Y')][$transType]

//        $test=isset($result['Jan 2023']['Jual']);
//        dd($test->count());


//        $month_12_year_22 =  $result['Jan '.$currentYear]['Jual']->count() + $result['Feb '.$currentYear]['Jual']->count() ;
//        $month_12_year_22 =  $result['Jan '.$lastYear]['Jual']->count() + $result['Feb '.$lastYear]['Jual']->count() ;
//        $month_34_year_22 =  $result['Mar '.$lastYear]['Jual']->count() + $result['Apr '.$lastYear]['Jual']->count() ;
//        $month_56_year_22 =  $result['May '.$lastYear]['Jual']->count() + $result['Jun '.$lastYear]['Jual']->count() ;
//        $month_78_year_22 =  $result['Jul '.$lastYear]['Jual']->count() + $result['Aug '.$lastYear]['Jual']->count() ;
//        $month_910_year_22 =  $result['Sep '.$lastYear]['Jual']->count() + $result['Oct '.$lastYear]['Jual']->count() ;
//        $month_1112_year_22 =  $result['Nov '.$lastYear]['Jual']->count() + $result['Dec '.$lastYear]['Jual']->count() ;
//----------------------------------------------------------------------------------------------------------------------
        // get 2 month per year data
        // 2022
//----------------------------------------------------------------------------------------------------------------------
        $month_12_year_22 = (isset($result['Jan '.$lastYear]['Jual'])) ? $result['Jan '.$lastYear]['Jual']->count() : 0;
        $month_12_year_22 += (isset($result['Feb '.$lastYear]['Jual'])) ? $result['Feb '.$lastYear]['Jual']->count() : 0;

        $month_34_year_22 = (isset($result['Mar '.$lastYear]['Jual'])) ? $result['Mar '.$lastYear]['Jual']->count() : 0;
        $month_34_year_22 += (isset($result['Apr '.$lastYear]['Jual'])) ? $result['Apr '.$lastYear]['Jual']->count() : 0;

        $month_56_year_22 = (isset($result['May '.$lastYear]['Jual'])) ? $result['May '.$lastYear]['Jual']->count() : 0;
        $month_56_year_22 += (isset($result['Jun '.$lastYear]['Jual'])) ? $result['Jun '.$lastYear]['Jual']->count() : 0;

        $month_78_year_22 = (isset($result['Jul '.$lastYear]['Jual'])) ? $result['Jul '.$lastYear]['Jual']->count() : 0;
        $month_78_year_22 += (isset($result['Aug '.$lastYear]['Jual'])) ? $result['Aug '.$lastYear]['Jual']->count() : 0;

        $month_910_year_22 = (isset($result['Sep '.$lastYear]['Jual'])) ? $result['Sep '.$lastYear]['Jual']->count() : 0;
        $month_910_year_22 += (isset($result['Oct '.$lastYear]['Jual'])) ? $result['Oct '.$lastYear]['Jual']->count() : 0;

        $month_1112_year_22 = (isset($result['Nov '.$lastYear]['Jual'])) ? $result['Nov '.$lastYear]['Jual']->count() : 0;
        $month_1112_year_22 += (isset($result['Dec '.$lastYear]['Jual'])) ? $result['Dec '.$lastYear]['Jual']->count() : 0;
//----------------------------------------------------------------------------------------------------------------------
        // get 2 month per year data
        // 2023
//----------------------------------------------------------------------------------------------------------------------


//        dd($month_12_year_22,$month_34_year_22,$month_56_year_22,$month_78_year_22,$month_910_year_22,$month_1112_year_22);
//        dd($result['Jan 2023']);











//        return view('pages.dashboard-chart')->with($data);
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







//backup, try to add Beli

//$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
//
//$month_12_year_22 = 0;
//$month_34_year_22 = 0;
//$month_56_year_22 = 0;
//$month_78_year_22 = 0;
//$month_910_year_22 = 0;
//$month_1112_year_22 = 0;
//
//$month_12_year_23 = 0;
//$month_34_year_23 = 0;
//$month_56_year_23 = 0;
//$month_78_year_23 = 0;
//$month_910_year_23 = 0;
//$month_1112_year_23 = 0;
//
//foreach ($months as $index => $month) {
//    $keyYear22 = $month . ' ' . $lastYear;
//    $keyYear23 = $month . ' ' . ($lastYear + 1);
//
//    $transJualYear22 = (isset($result[$keyYear22]['Jual'])) ? $result[$keyYear22]['Jual']->count() : 0;
//    $transJualYear23 = (isset($result[$keyYear23]['Jual'])) ? $result[$keyYear23]['Jual']->count() : 0;
//
//    if ($index < 2) {
//        $month_12_year_22 += $transJualYear22;
//        $month_12_year_23 += $transJualYear23;
//    } elseif ($index < 4) {
//        $month_34_year_22 += $transJualYear22;
//        $month_34_year_23 += $transJualYear23;
//    } elseif ($index < 6) {
//        $month_56_year_22 += $transJualYear22;
//        $month_56_year_23 += $transJualYear23;
//    } elseif ($index < 8) {
//        $month_78_year_22 += $transJualYear22;
//        $month_78_year_23 += $transJualYear23;
//    } elseif ($index < 10) {
//        $month_910_year_22 += $transJualYear22;
//        $month_910_year_23 += $transJualYear23;
//    } else {
//        $month_1112_year_22 += $transJualYear22;
//        $month_1112_year_23 += $transJualYear23;
//    }
//}

//???? loop lebih hemat performa dari multiple insert

//$data = [
//    'dat1222_jual' => $month_12_year_22_jual,
//    'dat3422_jual' => $month_34_year_22_jual,
//    'dat5622_jual' => $month_56_year_22_jual,
//    'dat7822_jual' => $month_78_year_22_jual,
//    'dat91022_jual' => $month_910_year_22_jual,
//    'dat111222_jual' => $month_1112_year_22_jual,
//    'dat1223_jual' => $month_12_year_23_jual,
//    'dat3423_jual' => $month_34_year_23_jual,
//    'dat5623_jual' => $month_56_year_23_jual,
//    'dat7823_jual' => $month_78_year_23_jual,
//    'dat91023_jual' => $month_910_year_23_jual,
//    'dat111223_jual' => $month_1112_year_23_jual,
//    'dat1222_beli' => $month_12_year_22_beli,
//    'dat3422_beli' => $month_34_year_22_beli,
//    'dat5622_beli' => $month_56_year_22_beli,
//    'dat7822_beli' => $month_78_year_22_beli,
//    'dat91022_beli' => $month_910_year_22_beli,
//    'dat111222_beli' => $month_1112_year_22_beli,
//    'dat1223_beli' => $month_12_year_23_beli,
//    'dat3423_beli' => $month_34_year_23_beli,
//    'dat5623_beli' => $month_56_year_23_beli,
//    'dat7823_beli' => $month_78_year_23_beli,
//    'dat91023_beli' => $month_910_year_23_beli,
//    'dat111223_beli' => $month_1112_year_23_beli,
//];
