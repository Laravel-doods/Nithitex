<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\User;
use Mail;
use Hash;
use Illuminate\Support\Str;

class UserForgotPasswordController extends Controller
{
  /**
   * Write code on Method
   *
   * @return response()
   */
  public function showForgetPasswordForm()
  {
    return view('auth.userforgetPassword');
  }

  /**
   * Write code on Method
   *
   * @return response()
   */
  public function submitForgetPasswordForm(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users',
    ], [
      'email.exists' => 'No user was found with this e-mail address'
    ]);

    $token = Str::random(64);

    DB::table('user_password_resets')->insert([
      'email' => $request->email,
      'token' => $token,
      'created_at' => Carbon::now()
    ]);

    Mail::send('mail.userforgetPassword', ['token' => $token], function ($message) use ($request) {
      $message->to($request->email);
      $message->subject('Reset Password');
    });

    $notification = array(
      'message' => 'We have e-mailed your password reset link!',
      'alert-type' => 'success'
    );

    return redirect('/login')->with($notification);
  }
  /**
   * Write code on Method
   *
   * @return response()
   */
  public function showResetPasswordForm($token)
  {
    return view('auth.userforgetPasswordLink', ['token' => $token]);
  }

  /**
   * Write code on Method
   *
   * @return response()
   */
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
      $notification = array(
        'message' => 'Invalid token!',
        'alert-type' => 'error'
      );
      return redirect()->back()->with($notification);
    }

    User::where('email', $request->email)
      ->update(['password' => Hash::make($request->password)]);

    DB::table('user_password_resets')->where(['email' => $request->email])->delete();

    $notification = array(
      'message' => 'Your password has been changed!',
      'alert-type' => 'success'
    );

    return redirect('/login')->with($notification);
  }
}
