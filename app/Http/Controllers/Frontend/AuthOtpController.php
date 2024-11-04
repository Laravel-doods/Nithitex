<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class AuthOtpController extends Controller
{
    // Return View of OTP Login Page
    public function login()
    {
        return view('auth.otp');
    }

    // Generate OTP
    public function generate(Request $request)
    {
        // dd($request->all());
        # Validate Data
        // $request->validate([
        //     'phone' => 'required|exists:users,phone'
        // ]);

        # Generate An OTP
        $verificationCode = $this->generateOtp($request->mobile_no);

        $message = "OTP Sent To Your Mobile Number";

        Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your OTP for login to Nithitex is '.$verificationCode->otp.'. Valid for 5 minutes. Please do not share this OTP.&number='.$request->mobile_no.'');
        # Return With OTP 

        return redirect()->route('otp.verification', ['user_id' => $verificationCode->user_id])->with('success',  $message); 
    }

    public function generateOtp($phone)
    {
        $user = User::where('phone', $phone)->first();

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('user_id', $user->id)->latest()->first();

        $now = Carbon::now();

        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return $verificationCode;
        }

        // Create a New OTP
        return VerificationCode::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => Carbon::now()->addMinutes(5)
        ]);

    }

    public function verification($user_id)
    {
        return view('auth.otp-verify')->with([
            'user_id' => $user_id
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
        }elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
            return redirect()->route('otp.login')->with('error', 'Your OTP has been expired');
        }

        $user = User::whereId($request->user_id)->first();

        if($user){
            // Expire The OTP
            $verificationCode->update([
                'expire_at' => Carbon::now()
            ]);

            $user->update([
                'verification_code' => $request->otp
            ]);

            Auth::login($user);
            $notification = array(
                'message' => 'User Registered Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('user.dashboard')->with($notification);
        }

        return redirect()->route('otp.login')->with('error', 'You Have Entered Incorrect OTP!');
    }
}
