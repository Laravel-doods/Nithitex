<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\VerificationCode;
use App\Traits\Utils;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    use Utils;
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username'   => 'required',
            'password' => 'required'
        ]);
        $device_id = $request->device_id;

        $useremail = User::where('email', $request->username)->orwhere('phone', $request->username)->pluck('email')->first();
        $userphone = User::where('email', $request->username)->orwhere('phone', $request->username)->pluck('phone')->first();

        $code = User::where('email', $useremail)->pluck('verification_code')->first();
        if ($useremail) {
            $verifyRole = $this->getRoleId($useremail);
            if ($verifyRole) {

                if (Auth::attempt(['email' => $useremail, 'password' => $request->password, 'phone' => $userphone], $request->get('remember'))) {

                    if ($request->fcm_token) {
                        $this->updateFCMToken($request->fcm_token);
                    }

                    if ($verifyRole == 1) {
                        $verification = User::where('email', $request->username)->orwhere('phone', $request->username)->pluck('verification_code')->first();
                        if (!$verification) {
                            $notification = array(
                                'message' => 'Please Verify OTP',
                                'alert-type' => 'error'
                            );
                            $verification = $this->SendOTP($userphone);
                            return redirect()->route('otp.verification', ['user_id' => $verification->user_id])->with($notification);
                        } else {
                            $this->updateGuestToUser($device_id);

                            $notification = array(
                                'message' => 'Logged In Successfully',
                                'alert-type' => 'success'
                            );
                        }
                        return redirect()->route('user.dashboard')->with($notification);
                    } else if ($verifyRole == 2) {
                        $this->updateGuestToUser($device_id);

                        $notification = array(
                            'message' => 'Reseller Logged In Successfully',
                            'alert-type' => 'success'
                        );
                        return redirect()->route('seller.dashboard')->with($notification);
                    }
                } else {
                    $notification = array(
                        'message' => 'Invalid Credentials',
                        'alert-type' => 'error'
                    );
                    return back()->with($notification);
                }
            } else {
                $notification = array(
                    'message' => 'Your Approval Is Pending',
                    'alert-type' => 'error'
                );
                return redirect()->route('user.login')->with($notification);
            }
        } else {
            $notification = array(
                'message' => 'Invalid Credentials',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    private function getRoleId($email)
    {
        return User::where('email', $email)->pluck('userrole_id')->first();
    }

    public function regiterStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|digits_between:10,15|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);
        user::create([
            'name' => $request->name,
            'email' => $request->email,
            'userrole_id' => 1,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'referral_code' => $this->generateReferralCode()
        ]);
        $notification = array(
            'message' => 'Please Verify OTP',
            'alert-type' => 'success'
        );
        $verificationCode = $this->SendOTP($request->phone);
        return redirect()->route('otp.verification', [
            'user_id' => $verificationCode->user_id,
            'fcm_token' => $request->fcm_token
        ])->with($notification);
    }

    private function SendOTP($phone)
    {
        $verificationCode = $this->generateOtp($phone);

        Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your OTP for login to Nithitex is ' . $verificationCode->otp . '. Valid for 5 minutes. Please do not share this OTP.&number=' . $phone . '');

        return $verificationCode;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        $request->session()->invalidate();
        $notification = array(
            'message' => 'User Logged Out Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('user.login')->with($notification);
    }

    public function generateOtp($phone)
    {
        $user = User::where('phone', $phone)->first();

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('user_id', $user->id)->first();

        $now = Carbon::now();

        if ($verificationCode && $now->isBefore($verificationCode->expire_at)) {
            return $verificationCode;
        }

        // Create a New OTP
        return VerificationCode::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => Carbon::now()->addMinutes(5)
        ]);
    }

    public function verification($user_id, $fcm_token = null)
    {
        return view('auth.otp-verify')->with([
            'user_id' => $user_id,
            'fcm_token' => $fcm_token
        ]);
    }

    public function loginWithOtp(Request $request)
    {
        #Validation
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required'
        ]);

        #Validation Logic
        $verificationCode   = VerificationCode::where('user_id', $request->user_id)->where('otp', $request->otp)->first();

        $now = Carbon::now();
        if (!$verificationCode) {
            return redirect()->back()->with('error', 'Your OTP is not correct');
        } elseif ($verificationCode && $now->isAfter($verificationCode->expire_at)) {
            return redirect()->back()->with('error', 'Your OTP has been expired');
        }

        $user = User::whereId($request->user_id)->first();

        if ($user) {
            // Expire The OTP
            $verificationCode->update([
                'expire_at' => Carbon::now()
            ]);

            $user->update([
                'verification_code' => $request->otp
            ]);

            Auth::login($user);

            if ($request->fcm_token) {
                $this->updateFCMToken($request->fcm_token);
            }
            $device_id = $request->device_id;
            $this->updateGuestToUser($device_id);

            $notification = array(
                'message' => 'User Registered Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('user.dashboard')->with($notification);
        }

        return redirect()->back()->with('error', 'You Have Entered Incorrect OTP!');
    }
}
