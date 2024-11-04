<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;
use App\Models\Cart;
use App\Models\Product;
use App\Traits\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Pdf;

class RazorpayController extends Controller
{
    use Utils;
    private $razorpayId = "rzp_test_bUcyzVDkdztvea";
    private $razorpaykey = "s1BI0kDv3nEaTehLWoLPQQGJ";
    // private $razorpayId ="rzp_live_BPqOpSftyPAkey";
    // private $razorpaykey="NZrNloxbjs4zifCkpf0kgUOj";

    public function RazorpayComplete(Request $request)
    {
        $signatureStatus = $this->signatureVerify(
            $request->all()['razorpay_signature'],
            $request->all()['razorpay_payment_id'],
            $request->all()['razorpay_order_id']
        );

        // if ($request->cart_true == 1) {
        //     $totQty = Cart::where('user_id', Auth::user()->id)->sum('qty');
        // } else {
        //     $totQty = $request->buy_now_product_qty;
        // }

        // $order_id = Order::insertGetId([
        //     'user_id' => Auth::id(),
        //     'userrole_id' => 1,
        //     'door_no' => $request->door_no,
        //     'street_address' => $request->street_address,
        //     'city_name' => $request->city_name,
        //     'state_name' => $request->state_name,
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'phone' => $request->phone,
        //     'alternative_number' => $request->alternative_number,
        //     'pin_code' => $request->pin_code,
        //     'payment_type' => 'Razorpay',
        //     'payment_status' => 'unpaid',
        //     // 'r_payment_id' => $request->all()['razorpay_payment_id'],
        //     // 'r_order_id' => $request->all()['razorpay_order_id'],
        //     'currency' =>  'INR',
        //     'amount' => $request->amount / 100,
        //     'sub_total' => $request->sub_total,
        //     'shipping_charge' => $request->shipping_charge,
        //     // 'invoice_no' => 'NITHTX' . mt_rand(10000000, 99999999),
        //     'order_number' => 'NTXOR' . mt_rand(10000000, 99999999),
        //     'order_date' => Carbon::now()->format('d F Y'),
        //     'order_month' => Carbon::now()->format('F'),
        //     'order_year' => Carbon::now()->format('Y'),
        //     'status' => 'pending',
        //     'created_at' => Carbon::now(),
        //     'tot_Qty' => $totQty,
        //     'coupon_id' => $request->coupon_id,
        //     'coupon_discount' => $request->coupon_discount
        // ]);

        // if ($request->cart_true == 1) {
        //     $carts = Cart::where('user_id', Auth::user()->id)->get();
        //     foreach ($carts as $cart) {
        //         OrderItem::insert([
        //             'order_id' => $order_id,
        //             'product_id' => $cart->product_id,
        //             'qty' => $cart->qty,
        //             'price' => $cart->total,
        //             'created_at' => Carbon::now(),
        //         ]);

        //         $product = Product::where('id', $cart->product_id)->first();
        //         $product->current_stock = $product->current_stock - $cart->qty;
        //         $product->update();
        //     }
        // } else {

        //     $buy_product_name = $request->buy_now_product_name;
        //     $buy_product_qty = $request->buy_now_product_qty;
        //     $buy_price = $request->buy_now_price;
        //     $buy_product_id = $request->buy_now_product_id;

        //     OrderItem::insert([
        //         'order_id' => $order_id,
        //         'product_id' => $buy_product_id,
        //         'qty' => $buy_product_qty,
        //         'price' => $buy_price,
        //         'created_at' => Carbon::now(),
        //     ]);

        //     $product = Product::where('id', $buy_product_id)->first();
        //     $product->current_stock = $product->current_stock - $buy_product_qty;
        //     $product->update();
        // }

        // // Start Send Email 
        // $order = Order::with('user')->where('id', $order_id)->where('user_id', Auth::id())->first();
        // $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

        // $pdf = PDF::loadView('frontend.user.order.order_invoice', compact('order', 'orderItem'));
        // Mail::to($request->email)->send(new OrderMail($pdf, $order->invoice_no, 'online'));

        // $content = $pdf->download()->getOriginalContent();

        // Storage::disk('local')->put($order->invoice_no . '.pdf', $content);

        // $save_url = 'invoice/' . $order->invoice_no . '.pdf';

        // Order::where('id', $order->id)->update([
        //     'invoice' => $save_url
        // ]);

        // $order = Order::with('user')->where('id', $order_id)->where('user_id', Auth::id())->first();


        // Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your order is pending and will be Confirmed shortly. Check your status here: https://nithitex.com/track-your-order , Nithitex&number=' . $order->phone . '');


        // if ($request->cart_true == 1) {
        //     $rowId = Cart::where('user_id', Auth::user()->id)->get();
        //     Cart::destroy($rowId);
        // }

        $order_id = Order::where('user_id', Auth::user()->id)->where('id', $request->order_id)->pluck('id')->first();

        if ($signatureStatus == true) {
            Order::findorfail($order_id)->update([
                'invoice_no' => 'NITHTX' . mt_rand(10000000, 99999999),
                'r_payment_id' => $request->all()['razorpay_payment_id'],
                'r_order_id' => $request->all()['razorpay_order_id'],
                'payment_status' => 'paid',
            ]);

            // Start Send Email 
            $order = Order::with('user')->where('id', $order_id)->where('user_id', Auth::id())->first();
            $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

            $pdf = PDF::loadView('frontend.user.order.order_invoice', compact('order', 'orderItem'));
            Mail::to($request->email)->send(new OrderMail($pdf, $order->invoice_no, 'online'));

            $content = $pdf->download()->getOriginalContent();

            $make_name = 'invoice/' . $order->invoice_no . '.pdf';

            $save_url = $this->fileUploadS3Bucket($make_name, $content, 1);

            Order::where('id', $order->id)->update([
                'invoice' => $save_url
            ]);
            $notification = array(
                'message' => 'Your Order Placed Successfully',
                'alert-type' => 'success'
            );
        }
        return redirect()->route('user.dashboard')->with($notification);
        //    }
        //    else{
        //     $notification = array(
        //         'message' => 'Your order could not be placed. Please try again!',
        //         'alert-type' => 'error'
        //     );

        //     return redirect()->route('user.dashboard')->with($notification);

        //    }
    }
    private function signatureVerify($_signature, $_paymentId, $_orderId)
    {
        try {
            $api = new Api($this->razorpayId, $this->razorpaykey);
            $attributes  = array('razorpay_signature'  => $_signature,  'razorpay_payment_id'  => $_paymentId,  'razorpay_order_id' => $_orderId);
            $order  = $api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
