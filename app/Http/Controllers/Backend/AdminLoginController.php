<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminLoginController extends Controller
{
    use Utils;
    public function loginForm()
    {
        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'   => 'required',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $notification = array(
                'message' => 'Admin Logged In Successfully',
                'alert-type' => 'success'
            );

            //Generate referral code for users
            $users = User::whereNUll('referral_code')->get();

            foreach ($users as $users) {
                $users->update([
                    'referral_code' => $this->generateReferralCode()
                ]);
            }
            return redirect()->route('admin.dashboard')->with($notification);
        } else {
            $notification = array(
                'message' => 'Invalid Credentials',
                'alert-type' => 'error'
            );
            return redirect()->route('admin.form')->with($notification);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Session::flush();
        $request->session()->invalidate();
        $notification = array(
            'message' => 'Admin Logged Out Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.form')->with($notification);
    }
}
