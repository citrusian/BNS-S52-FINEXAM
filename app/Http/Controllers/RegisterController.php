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

        $attributes = request()->validate([
            'username' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
//            'password' => 'required|min:5|max:255|confirmed',
//            Note: Using confirmed doesn't show alert message at confirmation input field
//            workaround: create "confirm-password' field
            'password' => 'required|min:5|max:255',
            'confirm-password' => 'same:password',
            'terms' => 'required',

            'role' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',

            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'postal' => 'required|numeric',
            'about' => 'max:255',
        ]);

        // use array merge rather than using DB::update,
        // calling currentid+1 sometimes give random increment
        $attributes = array_merge($attributes, ['pp_path' => "0-Default.jpg"]);

        $user = User::create($attributes);

        return back()
            ->with('succes','User Created');
    }
}
