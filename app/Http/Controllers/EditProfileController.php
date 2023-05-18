<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class EditProfileController extends Controller
{
    public function show(Request $request)
    {
        $userid = $request->get('postid');
        Log::debug("testreq: ".$userid);
        Session::put('user', $userid);
//        dd($userid);
        return redirect('edit-profile');
    }

    public function updateuser(Request $request)
    {
        Log::debug("postid: ".$request->get('postid'));
        Log::debug("username: ".$request->get('username'));
        Log::debug("firstname: ".$request->get('firstname'));
        Log::debug("lastname: ".$request->get('lastname'));
        Log::debug("email: ".$request->get('email'));
        Log::debug("address: ".$request->get('address'));
        Log::debug("city: ".$request->get('city'));
        Log::debug("country: ".$request->get('country'));
        Log::debug("postal: ".$request->get('postal'));
        Log::debug("TTL: ".$request->get('TTL'));
        Log::debug("gender: ".$request->get('gender'));
        Log::debug("idtype: ".$request->get('idtype'));
        $curid = $request->get('postid');
        $user = DB::table('users')->where('id',$curid)->get();
        $call = $user;
//        dd($user);

        $attributes = $request->validate([
//            'username' => ['required','max:255', 'min:2'],
            'username' => ['required','max:255', 'min:2'],
            'firstname' => ['max:100'],
            'lastname' => ['max:100'],
//            'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore(auth()->user()->id),],
//            'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore(auth()->user()->id),],
            'address' => ['max:100'],
            'city' => ['max:100'],
            'country' => ['max:100'],
            'postal' => ['max:100'],
            'about' => ['max:255'],
//            'role' => ['required'],
        ]);
//        $call = $attributes;
//        dd($attributes);

        User::where('id',$curid)
            ->update([
                'username' => $request->get('username'),
                'firstname' => $request->get('firstname'),
                'lastname' => $request->get('lastname'),
//                'email' => $request->get('email') ,
                'address' => $request->get('address'),
                'city' => $request->get('city'),
                'country' => $request->get('country'),
                'postal' => $request->get('postal'),
                'about' => $request->get('about'),
                'role' => $request->get('role'),
            ]);
        Session::put('user', $curid);
        return back()->with('succes', 'Profile succesfully updated');
    }


    public function updateppicture(Request $request)
    {
        // limit input
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $curid = $request->get('postid');
//        dd($curid);

        $imageName = $curid.'.'.$request->image->extension();
        $request->image->move(public_path('img/profile'), $imageName);

//        set current id to add / replace
//        $curid = $request->get('postid');
//        dd($curid);
        User::where('id',$curid)
            ->update([
                'pp_path' => $imageName,
            ]);

        Session::put('user', $curid);
        return back()
            ->with('succes', 'Picture succesfully updated')
            ->with('image',$imageName);
    }

}
