<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use App\Http\Traits\DebugToConsole;

class RegisterController extends Controller
{
//    use DebugToConsole;
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {

        $curid = DB::table('Users')->count();
        $curid +=1;

        Log::debug("currid: ".$curid);

        $attributes = request()->validate([
            'username' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
//            'password' => 'required|min:5|max:255|confirmed',
//            Note: Using confirmed doesn't show alert message at confirmation input field
//            workaround: create "confirm-password' field
            'password' => 'required|min:5|max:255',
            'confirm-password' => 'same:password',
            'terms' => 'required',
//
            'role' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',

            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'postal' => 'required|numeric',
            'about' => 'max:255',
        ]);
        Log::info(print_r($attributes, true));

        $user = User::create($attributes);
//
        if ($request->role === "Super Admin"){
            $role = 0;
        }
        else{
            $role = 1;
        }

        $update = DB::table('users')
            ->where('id', $curid)
            ->update([
                'role' =>  $role,
                'pp_path' =>  '0-Default.jpg',
            ]);

//        Log::debug("curruse: ".$user);
//        Log::debug("currid: ".$update);

        return back()
            ->with('succes','User Created');
//            ->with('image',$imageName);
    }
}
