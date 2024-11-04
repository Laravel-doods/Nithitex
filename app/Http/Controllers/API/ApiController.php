<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPassword;
use App\Models\About;
use App\Models\Colors;
use App\Models\Coupon;
use App\Models\ShopInformation;
use App\Models\Slider;
use App\Models\Order;
use App\Models\Policy;
use App\Models\Seller;
use App\Models\State;
use App\Models\User;
use App\Models\UserReferralPayments;
use Illuminate\Http\Request;
use App\Traits\ResponseAPI;
use App\Traits\Utils;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mail;

class ApiController extends Controller
{
    use ResponseAPI;
    use Utils;

    public function slider(Request $request)
    {
        try {
            $responseData = [];

            $responseData['slider'] = [];
            if ($request->userrole_id == 2) {
                $slider = Slider::where('userrole_id', '!=', 1)->get();
            } else {
                $slider = Slider::where('userrole_id', '!=', 2)->get();
            }
            foreach ($slider as $item) {
                $sliderDetails['id'] = $item->id;
                $sliderDetails['slider_image'] = url($item->slider_image);
                $sliderDetails['slider_url'] = $item->app_redirect_url;
                array_push($responseData['slider'], $sliderDetails);
            }
            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function contact()
    {
        try {
            $responseData = [];

            $responseData['contact'] = [];
            $contact = ShopInformation::get();
            foreach ($contact as $item) {
                $contactDetails['address_line_1'] = $item->address_line_1;
                $contactDetails['address_line_2'] = $item->address_line_2;
                $contactDetails['pincode'] = $item->pincode;
                $contactDetails['mobile'] = $item->mobile_number;
                $contactDetails['email'] = $item->email;
                $contactDetails['opening_hours'] = "09:00am - 06:00pm";
                $contactDetails['opening_days'] = "Monday - Saturday";
                $contactDetails['announcement'] = $item->announcement;
                $contactDetails['facebook'] = $item->facebook;
                $contactDetails['twitter'] = $item->twitter;
                $contactDetails['instagram'] = $item->instagram;
                $contactDetails['youtube'] = $item->youtube;
                $contactDetails['chatboturl'] = "https://app.queuebot.in/appbot/?uniqueid=15a9da34-823b-4a70-9e44-e7649cd76561";

                array_push($responseData['contact'], $contactDetails);
            }
            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function UserPasswordUpdate(Request $request)
    {

        $validator = validator::make($request->all(), [
            'oldpassword' => 'required',
            'password' => 'required||min:8|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all())], 400);
        }
        $hashedPassword = Auth::user()->password;
        if (Hash::check($request->oldpassword, $hashedPassword)) {
            $user = User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();

            $message = "Password Changed Successfully";
            return response()->json(['status' => true, 'message' => $message], 200);
        } else {
            $message = "Password Does Not Matched";
            return response()->json(['status' => false, 'message' => $message], 400);
        }
    }

    public function UserProfileStore(Request $request)
    {
        $data = User::find(Auth::user()->id);
        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->door_no = $request->door_no;
        $data->street_address = $request->street_address;
        $data->city_name = $request->city_name;
        $data->state_name = $request->state_name;
        $data->pin_code = $request->pin_code;

        if ($request->file('profile_photo_path')) {
            $file = $request->file('profile_photo_path');
            @unlink(public_path('upload/user_images/' . $data->profile_photo_path));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/user_images'), $filename);
            $data['profile_photo_path'] = $filename;
        }

        Seller::where('email', Auth::user()->email)->update([
            'shop_name' => $request->shop_name,
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->bank_account_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_ifsc' => $request->bank_ifsc
        ]);

        $data->save();
        $message = "Profile Updated Successfully";
        return response()->json(['status' => true, 'message' => $message], 200);
    }

    public function colorList()
    {
        try {
            $responseData = [];
            $responseData['color_list'] = [];
            $color_details = Colors::get();
            foreach ($color_details as $item) {

                $colorDetails['color_id'] = $item->id;
                $colorDetails['color_code'] = $item->color_code;
                $colorDetails['color_name'] = $item->color_name;

                array_push($responseData['color_list'], $colorDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function userProfileGet()
    {
        try {
            $responseData = [];
            $responseData['user_profile'] = [];
            $user_details = User::where('id', Auth::user()->id)->get();
            foreach ($user_details as $item) {

                $seller = Seller::where('email', Auth::user()->email)->first();
                $state = State::where('state_name', $item->state_name)->first();
                $coupon = Coupon::where('id', $item->coupon_id)->first();
                $total_points_received = UserReferralPayments::select(DB::raw('SUM(amount_paid) as total_points_received'))->where('user_id', $item->id)->value('total_points_received');

                $userDetails['user_id'] = $item->id;
                $userDetails['user_name'] = $item->name;
                $userDetails['email'] = $item->email;
                $userDetails['phone'] = $item->phone;
                $userDetails['door_no'] = $item->door_no;
                $userDetails['street_name'] = $item->street_address;
                $userDetails['city'] = $item->city_name;
                $userDetails['state'] = $item->state_name;
                $userDetails['state_id'] =  ($state != null ? $state->id : 0);
                $userDetails['short_name'] = ($state != null ? $state->iso2 : "");
                $userDetails['shipping_charge'] = ($state != null ? $state->shipping_charge : 0);
                $userDetails['cod_charge'] = ($state != null ? $state->cod_charge : 0);
                $userDetails['pincode'] = $item->pin_code;
                $userDetails['referral_code'] = $item->referral_code;
                $userDetails['total_points_earned'] = $item->referral_points;
                $userDetails['total_points_received'] = $total_points_received;
                $userDetails['coupon_name'] = ($coupon != null ? $coupon->coupon_name : "");
                $userDetails['coupon_code'] = ($coupon != null ? $coupon->coupon_code : "");
                $userDetails['color_code'] = ($coupon != null ? $coupon->color_code : "");

                if ($seller) {
                    $userDetails['shop_name'] = $seller->shop_name;
                    $userDetails['bank_name'] = $seller->bank_name;
                    $userDetails['bank_account_name'] = $seller->bank_account_name;
                    $userDetails['bank_account_number'] = $seller->bank_account_number;
                    $userDetails['bank_ifsc'] = $seller->bank_ifsc;
                }

                if ($item->profile_photo_path) {
                    $userDetails['user_image'] =
                        url('upload/user_images/' . $item->profile_photo_path);
                } else {
                    $userDetails['user_image'] = null;
                }

                array_push($responseData['user_profile'], $userDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function purchaseHistory()
    {
        try {
            $responseData = [];
            $responseData['purchase_history'] = [];
            $order_details = Order::join('order_items', 'order_items.order_id', 'orders.id')
                ->join('products', 'products.id', 'order_items.product_id')
                ->where('orders.status', '=', 'delivered')
                ->where('user_id', Auth::user()->id)
                ->select('orders.*', 'order_items.product_id', 'products.product_image', 'products.product_name')
                ->orderby('id', 'DESC')
                ->get();

            foreach ($order_details as $item) {

                $orderDetails['order_id'] = $item->id;
                $orderDetails['product_id'] = $item->product_id;
                $orderDetails['product_name'] = $item->product_name;
                $orderDetails['inovice_no'] = $item->invoice_no;
                $orderDetails['date'] = $item->order_date;
                $orderDetails['amount'] = $item->amount;
                $orderDetails['status'] = $item->status;
                $orderDetails['payment'] = $item->payment_type;
                $orderDetails['product_image'] = url($item->product_image);

                array_push($responseData['purchase_history'], $orderDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function invoiceDownload($order_id)
    {
        try {
            $responseData = [];
            $responseData['order_invoice'] = [];
            $invoicedetails = Order::where('id', $order_id)->first();

            $orderDetails['invoice'] = url($invoicedetails->invoice);

            array_push($responseData['order_invoice'], $orderDetails);

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function state()
    {
        try {
            $responseData = [];

            $responseData['state'] = [];
            $state = State::orderBy('state_name', 'ASC')->get();
            foreach ($state as $item) {
                $stateDetails['state_id'] = $item->id;
                $stateDetails['state_name'] = $item->state_name;
                $stateDetails['short_name'] = $item->iso2;
                $stateDetails['shipping_charge'] = $item->shipping_charge;
                $stateDetails['cod_charge'] = $item->cod_charge;
                array_push($responseData['state'], $stateDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function forgetPasswordLinkGenerate(Request $request)
    {
        try {
            $validator = validator::make($request->all(), [
                'email' => 'required|email|exists:users'
            ], [
                'email.exists' => 'No user was found with this e-mail address'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all())], 400);
            }

            $token = Str::random(64);

            DB::table('user_password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::to($request->email)->send(new ForgetPassword($token), ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });
            $message = "We have e-mailed your password reset link!";
            return response()->json(['status' => true, 'message' => $message, 'token' => $token], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getDefaultContent()
    {
        try {
            $online = "For online payment the shipping cost is";
            $cod = "We are giving correct market price but if the payment is in Cash on Delivery means cost will be high if you feel is high Cash on Delivery means please reduce the number of COD options. Kindly choose a reliable online payment reduced very low cost of shipping platform.";
            $razorpayId = "rzp_test_bUcyzVDkdztvea"; //"rzp_test_bUcyzVDkdztvea"; //rzp_live_BPqOpSftyPAkey
            $razorpaykey = "s1BI0kDv3nEaTehLWoLPQQGJ"; //"s1BI0kDv3nEaTehLWoLPQQGJ"; //NZrNloxbjs4zifCkpf0kgUOj
            $orderplaced = "You will be receiving a email/sms confirmation with order details";
            $paymentsuccess = "You will be receiving a email/sms confirmation with order details";
            $paymentfailed = "Your order is not placed due to payment failure";
            $offerbannerurl = "https://www.nithitex.com/frontend/assets/images/banner/bg_offers.png";
            $razorpaylogourl = "https://www.nithitex.com/public/frontend/assets/images/app_logo.png";

            return response()->json([
                'online' => $online,
                'cod' => $cod,
                'razorpayId' => $razorpayId,
                'razorpaykey' => $razorpaykey,
                'orderplaced' => $orderplaced,
                'paymentsuccess' => $paymentsuccess,
                'paymentfailed' => $paymentfailed,
                'offerbannerurl' => $offerbannerurl,
                'razorpaylogourl' => $razorpaylogourl
            ], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getPoliciesContent(Request $request)
    {
        try {
            $policy = Policy::where('id', 1)->first();
            switch ($request->type) {
                case 1:
                    $content = ($policy != null ? $policy->terms_condition : "");
                    break;
                case 2:
                    $content = ($policy != null ? $policy->privacy_policy : "");
                    break;
                case 3:
                    $content = ($policy != null ? $policy->return_policy : "");
                    break;
                case 4:
                    $content = ($policy != null ? $policy->support_policy : "");
                    break;
                default:
                    $content = "";
                    break;
            }
            return response()->json(['content' => $content], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getAboutContent(Request $request)
    {
        try {
            $about = About::where('id', 1)->first();
            $about_img = ($about != null ? url($about->about_image) : "");
            $content = ($about != null ? $about->about_description : "");
            return response()->json(['content' => $content, 'about_img' => $about_img], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function marginSummary()
    {
        try {
            $responseData = [];

            $responseData['margin_summary'] = [];
            $margin_earned = Order::where('user_id', Auth::user()->id)
                ->where('return_order', '!=', 2)
                ->where('cancel_request', '!=', 2)
                ->where('status', '!=', 'cancelled')
                ->sum('margin_amount');
            $received = Order::where('user_id', Auth::user()->id)->where('payment_status', 'paid')
                ->where('return_order', '!=', 2)
                ->where('cancel_request', '!=', 2)
                ->where('status', '!=', 'cancelled')
                ->sum('margin_amount');
            $pending = Order::where('user_id', Auth::user()->id)->where('payment_status', 'unpaid')
                ->where('return_order', '!=', 2)
                ->where('cancel_request', '!=', 2)
                ->where('status', '!=', 'cancelled')
                ->sum('margin_amount');

            $marginDetails['margin_earned'] = $margin_earned;
            $marginDetails['received'] = $received;
            $marginDetails['pending'] = $pending;

            array_push($responseData['margin_summary'], $marginDetails);

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }


    function verifyCoupon(Request $request)
    {
        $validator = validator::make($request->all(), [
            'coupon_code' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all())], 400);
        }

        $coupon_id = Coupon::where('coupon_code', $request->coupon_code)->pluck('id')->first();
        if ($this->getRoleId(Auth::user()->id) == 2) {
            if (Auth::user()->coupon_id == $coupon_id) {
                $coupon_details = User::join('coupons', 'coupons.id', 'users.coupon_id')->where('coupon_id', $coupon_id)->first();
            } else {
                $coupon_details = Coupon::where('id', $coupon_id)->where('is_common', 1)->where('is_active', 1)->first();
            }
        } else {
            $coupon_details = Coupon::where('id', $coupon_id)->where('is_common', 1)->where('is_active', 1)->first();
        }

        if ($coupon_details) {
            $current_date = Carbon::today();
            if ($coupon_details->start_date <= $current_date && $coupon_details->end_date >= $current_date) {
                $coupon = Coupon::where('coupon_code', $request->coupon_code)
                    ->pluck('discount_percentage')->first();
            } else {
                $message = "Coupon Expired";
                return response()->json(['status' => false, 'message' => $message], 200);
            }
        } else {
            $message = "Invalid Coupon";
            return response()->json(['status' => false, 'message' => $message], 200);
        }

        $message = "Coupon Applied Successfully";
        return response()->json(['status' => true, 'message' => $message, 'discount_percentage' => $coupon], 200);
    }
}
