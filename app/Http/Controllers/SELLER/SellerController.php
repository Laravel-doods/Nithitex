<?php

namespace App\Http\Controllers\SELLER;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class SellerController extends Controller
{
    // use AuthenticatesUsers;

    /**
     * Where to redirect admins after login.
     *
     * @var string
     */
    protected $redirectTo = '/seller';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest:seller')->except('logout');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    public function registerFormshow()
    {
        return view('auth.seller_register');
    }


    public function regiterStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:sellers',
            'phone' => 'required|numeric|digits_between:10,15',
            'shopname' => 'required|string',
            'holdername' => 'string',
            'accountnumber' => 'numeric|digits_between:9,16',
            'password' => 'required|string|min:6|confirmed'

        ]);
        Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'userrole_id' => 2,
            'shop_name' => $request->shopname,
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->holdername,
            'bank_account_number' => $request->accountnumber,
            'bank_ifsc' => str::upper($request->ifsccode),
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        $notification = array(
            'message' => 'Reseller Created Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('home')->with($notification);
    }

    public function loginFormshow()
    {
        return view('auth.seller_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'   => 'required',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('seller')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->get('remember'))) {
            $verify = Seller::where('is_verified', 1)->where('email', $request->email)->pluck('is_verified')->first();
            if ($verify == 1) {
                $notification = array(
                    'message' => 'Seller Logged In Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('seller.dashboard')->with($notification);
            } else {
                $notification = array(
                    'message' => 'Your Approval Is Pending',
                    'alert-type' => 'error'
                );
                return redirect()->route('seller.login')->with($notification);
            }
        } else {
            $notification = array(
                'message' => 'Invalid Credentials',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $notification = array(
            'message' => 'Seller Logged Out Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('seller.login')->with($notification);
    }
}
