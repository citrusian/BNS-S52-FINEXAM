<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index(string $page)
    {
        error_log('Some message here.');
        if (view()->exists("pages.{$page}")) {
            return view("pages.{$page}");
        }

        return abort(404);
    }

    public function credits()
    {
        return view("pages.credits");
    }
//    public function transaksi()
//    {
//        return view("pages.transaksiview");
//    }
//    public function itemmanagement()
//    {
//        return view("pages.item");
//    }
//
//    public function signin()
//    {
//        return view("pages.sign-in-static");
//    }
//
//    public function signup()
//    {
//        return view("pages.sign-up-static");
//    }

    public function itemData()
    {
//        Get All Query
        $query = DB::table('data_barangs')
            ->select('*')
            ->get();

        return view("pages.itemData",['q1'=>$query]);
    }

    public function transactionData()
    {
//        Get All Query
        $query = DB::table('data_barangs')
            ->select('*')
            ->get();

        return view("pages.itemData",['q1'=>$query]);
    }

    public function user_management()
    {
//        Get All Query
        $query = DB::table('users')
            ->select('*')
            ->get();
        Log::debug($query);
//        Log::info(print_r($query, true));
//        return view("pages.user-management",['q1'=>$query]);
//        return view("pages.user-management")->json([
//            'data' => $query,
//            'message' => 'Success get all Students',
//            ]);
        error_log('Some message here.');
        return response()
            ->json([
                'data' => $query,
                'message' => 'Success get all Students',
            ]);










    }

    public function new_user()
    {
        return view("pages.new_user");
    }
















}
