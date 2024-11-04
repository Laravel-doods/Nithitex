<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\User;
use Mail;
use Hash;
use Illuminate\Support\Str;

class ForgetPasswordController extends Controller
{
  public function showForgetPasswordForm()
  {
    return view('auth.forgetPassword');
  }

  public function submitForgetPasswordForm(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users',
    ], [
      'email.exists' => 'No user was found with this e-mail address'
    ]);

    $token = Str::random(64);

    DB::table('password_resets')->insert([
      'email' => $request->email,
      'token' => $token,
      'created_at' => Carbon::now()
    ]);

    Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($request) {
      $message->to($request->email);
      $message->subject('Reset Password');
    });

    $notification = array(
      'message' => 'We have e-mailed your password reset link!',
      'alert-type' => 'success'
    );
    return redirect('login')->with($notification);
  }

  public function showResetPasswordForm($token)
  {
    return view('auth.forgetPasswordLink', ['token' => $token]);
  }

  public function submitResetPasswordForm(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users',
      'password' => 'required|string|min:8|confirmed',
      'password_confirmation' => 'required'
    ], [
      'email.exists' => 'No user was found with this e-mail address'
    ]);

    $updatePassword = DB::table('user_password_resets')
      ->where([
        'email' => $request->email,
        'token' => $request->token
      ])
      ->first();

    if (!$updatePassword) {
      return back()->withInput()->with('error', 'Invalid token!');
    }

    $user = User::where('email', $request->email)
      ->update(['password' => Hash::make($request->password)]);

    DB::table('user_password_resets')->where(['email' => $request->email])->delete();

    $notification = array(
      'message' => 'Your password has been changed!',
      'alert-type' => 'success'
    );

    return redirect()->route('user.login')->with($notification);
  }
}
