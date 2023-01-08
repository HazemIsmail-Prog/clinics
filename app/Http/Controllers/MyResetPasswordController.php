<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MyResetPasswordController extends Controller
{
    public function index()
    {
        return view('pages.reset_password.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'old_password'          => 'required',
            'new_password' => 'required|same:password_confirmation|different:old_password',
        ];

        $this->validate($request, $rules);

        $user = User::loggedClinic()->findOrFail(auth()->id());


        if (Hash::check($request->old_password, $user->password)) {
            $user->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            $request->session()->flash('success', 'Password changed');
            return redirect()->back();

        } else {
            $request->session()->flash('failed', 'Password does not match');
            return redirect()->back();
        }
    }


}
