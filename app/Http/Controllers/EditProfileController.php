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
    public function index(Request $request)
    {
//      move php script from html to here, more secure
        $user = DB::table('users')->where('id', $request->get('postid'))->get();
        return view('pages.account.edit-profile')
            ->with(['user' => $user]);
    }

    public function updateuser(Request $request)
    {
        socket_addrinfo_bind();
        $curid = $request->get('postid');
        $attributes = $request->validate([
            'username' => ['required', 'max:255', 'min:2', Rule::unique('users')->ignore($curid)],
            'firstname' => ['max:100'],
            'lastname' => ['max:100'],
            'address' => ['max:100'],
            'city' => ['max:100'],
            'country' => ['max:100'],
            'postal' => ['max:100'],
            'about' => ['max:255'],
        ]);

        // you can use $attributes data with DB->update
        // rather than using insert one by one
         User::where('id', $curid)
            ->update($attributes);
         return back()->with('success', 'Profile successfully updated!');
}

    public function updateuser_picture(Request $request)
    {
        // Set current id to add/replace
        $curid = $request->get('postid');

        // Check if file is present in the request
        if ($request->hasFile('image')) {
            // validate if image is acceptable
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ]);

            // check if old image exist then delete
            // only check in profile folder
            // so the backup file for seeder not deleted
            $oldImagePath = User::where('id', $curid)->value('pp_path');
            if ($oldImagePath && file_exists(public_path('img/profile/'.$oldImagePath))) {
                unlink(public_path('img/profile/'.$oldImagePath));
            }

            $imageName = $curid.'.'.$request->image->extension();
            $request->image->move(public_path('img/profile'), $imageName);

            // update with new image
            User::where('id', $curid)
                ->update([
                    'pp_path' => $imageName,
                ]);

            return back()
                ->with('success', 'Picture successfully updated')
                ->with('image', $imageName);

        }
        else {
            return back()
                ->with('error', 'No image file found');
        }
    }
}
