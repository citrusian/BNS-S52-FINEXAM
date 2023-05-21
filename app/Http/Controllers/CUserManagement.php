<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $test = " Test";
        $test2 = ' Test';

        return view("pages.user-management",['q1'=>$query]);
    }
}
