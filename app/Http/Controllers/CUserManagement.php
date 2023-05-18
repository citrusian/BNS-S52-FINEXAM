<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CUserManagement extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//        return view('ages.user-management');


//        Get All Query
        $query = DB::table('users')
            ->select('*')
            ->get();

        $test = "session Test";

//        return view("pages.user-management",['q1'=>$query]);
//        return view("pages.user-management",['asd'=>$test]);
////        return view("pages.user-management",['asd'=>$test])->with('asd',$test);
//        return view("pages.user-management")->with('asd',$test);
    }


//    public function user_management()
//    {
////        Get All Query
//        $query = DB::table('users')
//            ->select('*')
//            ->get();
//
//        $test = "session Test";
//
////        return view("pages.user-management",['q1'=>$query]);
////        return view("pages.user-management",['asd'=>$test]);
//////        return view("pages.user-management",['asd'=>$test])->with('asd',$test);
//        return view("pages.user-management")->with('asd',$test);
//    }


}
