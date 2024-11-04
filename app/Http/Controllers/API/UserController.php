<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserReferralHistory;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseAPI;
use App\Traits\Utils;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    use ResponseAPI;
    use Utils;

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'phone' => 'required|numeric|digits_between:10,15|unique:users'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all())], 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'userrole_id' => 1,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'referral_code' => $this->generateReferralCode()
            ]);

            if ($request->has('referral_code')) {
                $referral_code = User::where('referral_code', $request->referral_code)->first();
                if ($referral_code) {
                    //Add ReferralHistory
                    $this->addReferralPoints($user->id);
                    $this->addReferralHistory($user->id, $referral_code->id);
                } else {
                    return response()->json(
                        [
                            'status' => false,
                            'message' => 'Invalid Referral Code'
                        ],
                        400
                    );
                }
            }
            DB::commit();
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Registered Successfully',
                    'user_id' => $user->id,
                    'phone' => $request->phone,
                    'referral_code' => $user->referral_code
                ],
                200
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function sellerregister(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:sellers',
            'password' => 'required|min:8',
            'phone' => 'required|numeric|digits_between:10,15|unique:sellers',
            'shop_name' => 'required',
            'bank_name' => 'required',
            'account_holder_name' => 'required',
            'ifsc_code' => 'required',
            'account_number' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all())], 400);
        }

        Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'userrole_id' => 2,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'shop_name' => $request->shop_name,
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->account_holder_name,
            'bank_ifsc' => $request->ifsc_code,
            'bank_account_number' => $request->account_number,
            'referral_code' => $this->generateReferralCode()
        ]);

        return response()->json(['status' => true, 'message' => 'Registered Successfully'], 200);
    }

    public function login(Request $request)
    {
        $validator = validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all()), 'isApproved' => true], 400);
        }

        $useremail = User::where('email', $request->username)->orwhere('phone', $request->username)->pluck('email')->first();
        $userphone = User::where('email', $request->username)->orwhere('phone', $request->username)->pluck('phone')->first();

        $selleremail = Seller::where('email', $request->username)->orwhere('phone', $request->username)->pluck('email')->first();

        if ($useremail) {
            $data = [
                'email' => $useremail,
                'password' => $request->password,
                'phone' => $userphone,
            ];
            $verifyRole = $this->getRoleIdByEmail($useremail);
            if (Auth::attempt($data)) {
                $token = Auth::user()->createToken('Auth Token')->accessToken;
                $user_id = Auth::user()->id;
                $user_name = Auth::user()->name;
                $userrole_id = Auth::user()->userrole_id;

                $decodedData = json_decode($request->input('data'), true); 
                if ($decodedData) {
                    $this->updateAppGuestToUser((object)$decodedData); 
                }

                $responseData['status'] = true;
                $responseData['message'] = 'Logged in Successfully';
                $userCollection = array(
                    "id" => $user_id,
                    "name" => $user_name,
                    "token" => $token,
                    "userrole_id" => $userrole_id,
                    "phone" => $userphone,
                    "email" => $useremail,
                    "IsOTPVerified" => $this->CheckIsOTPVerified($useremail, $userphone, $verifyRole)
                );
                $responseData['data'] = $userCollection;
                return response()->json($responseData, 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Invalid Credentials', 'isApproved' => true], 401);
            }
        } else if ($selleremail) {
            return response()->json(['status' => false, 'message' => 'Your Approval Is Pending', 'isApproved' => false], 401);
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid Credentials', 'isApproved' => true], 401);
        }
    }

    private function CheckIsOTPVerified($useremail, $userphone, $role_id)
    {
        if ($role_id == 2) {
            return true;
        } else {
            $code = User::where('email', $useremail)->orwhere('phone', $userphone)->pluck('verification_code')->first();
            $verification = User::where('verification_code', $code)->where('email', $useremail)->orwhere('phone', $userphone)->pluck('verification_code')->first();
            if ($verification)
                return true;
            else
                return false;
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            auth()->user()->token()->revoke();
            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out'
            ], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'User does not exist please sign up'], 400);
        }
    }

    public function deleteAccount()
    {
        try {
            $orders = Order::where('user_id', Auth::user()->id)->get();
            if ($orders->count() > 0) {
                $response = array(
                    'message' => "You can't able to delete your account. Please contact administrator for further clarification.",
                    'status' => false,
                );
            } else {
                $user = User::find(Auth::user()->id);
                //Delete user account
                $user->delete();
                $response = array(
                    'message' => 'Account deleted succesfully',
                    'status' => true,
                );
            }
            return response($response, 200);
        } catch (\Exception $e) {
            $response = array(
                'status' => false,
                'message' => $this->errorMessage,
            );
            return response($response, 400);
        }
    }

    public function sendOTP(Request $request)
    {
        $verificationCode = $this->generateOtp($request->phone);
        try {
            Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your OTP for login to Nithitex is ' . $verificationCode->otp . '. Valid for 5 minutes. Please do not share this OTP.&number=' . $request->phone . '');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return response()->json(['status' => true, 'message' => 'OTP Sent Successfully'], 200);
    }

    private function generateOtp($phone)
    {
        $user = User::where('phone', $phone)->first();
        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('user_id', $user->id)->latest()->first();
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

    public function verifyOTP(Request $request)
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
            return response()->json(['status' => false, 'message' => 'Your OTP is not correct'], 400);
        } elseif ($verificationCode && $now->isAfter($verificationCode->expire_at)) {
            return response()->json(['status' => false, 'message' => 'Your OTP has been expired'], 400);
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

            $message = "OTP Verified Successfully";
            return response()->json(['status' => true, 'message' => $message], 200);
        }

        return response()->json(['status' => false, 'message' => 'You Have Entered Incorrect OTP!'], 400);
    }

    public function checkRoleChanged(Request $request)
    {
        try {
            $existing_role_id =  $request->role_id;
            $user_id =  $request->user_id;
            $current_role_id = User::where('id', $user_id)->pluck('userrole_id')->first();
            if ($existing_role_id == $current_role_id) {
                return response()->json(['status' => true], 200);
            } else {
                return response()->json(['status' => false], 200);
            }
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
