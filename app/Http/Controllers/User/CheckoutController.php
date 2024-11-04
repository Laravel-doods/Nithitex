<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\str;
use Razorpay\Api\Api;
use App\Models\State;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariants;
use App\Traits\Utils;
use Exception;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
  use Utils;
  private $razorpayId = "rzp_test_bUcyzVDkdztvea";
  private $razorpaykey = "s1BI0kDv3nEaTehLWoLPQQGJ";
  // private $razorpayId ="rzp_live_BPqOpSftyPAkey";
  // private $razorpaykey="NZrNloxbjs4zifCkpf0kgUOj";

  public function CheckoutStore(Request $request)
  {
    DB::beginTransaction();
    try {
      $shipping = State::where('state_name', $request->state_name)->first();
      if ($request->payment_method == 'razorpay') {
        $cart_true = $request->cart_true;
        $shipping_charge = $request->hdShipping_online;
        if ($request->cart_true == 1) {
          $totQty = Cart::where('user_id', Auth::user()->id)->sum('qty');
          $cartTotal = Cart::where('user_id', Auth::user()->id)->sum('total');
          $shipping_charge = ($totQty * $shipping_charge);
          $sub_total = $cartTotal;
          $total_amount = $cartTotal + $shipping_charge - $request->hdtotcouponamount;
        } else {
          $totQty = $request->buy_now_product_qty;
          $shipping_charge = ($totQty * $shipping_charge);
          $buy_price = $request->buy_now_price;
          $sub_total = $buy_price;
          $total_amount = $buy_price + $shipping_charge - $request->hdtotcouponamount;
        }

        $receiptId = Str::random(20);
        $api = new Api($this->razorpayId, $this->razorpaykey);
        $order = $api->order->create([
          'receipt' => $receiptId,
          'amount' => $total_amount * 100,
          'currency' => 'INR',
        ]);

        $response = [
          "order_id"    =>  $order['id'],
          "razorpayId"  =>  $this->razorpayId,
          "amount"      =>  $total_amount * 100,
          "name"        =>  $request->shipping_name,
          'currency'    =>  'INR',
          "email"       =>  $request->shipping_email,
          "phone"       =>  $request->shipping_phone
          // "address"   => $request->address,


        ];
        //  $total_amount= Cart::where('user_id',Auth::user()->id)->sum('total');
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['cart_true'] = $request->cart_true;
        $data['buy_now_product_name'] = $request->buy_now_product_name;
        $data['buy_now_product_qty'] = $request->buy_now_product_qty;
        $data['buy_now_product_id'] = $request->buy_now_product_id;
        $data['buy_now_variant_id'] = $request->buy_now_variant_id;
        $data['buy_now_price'] = $request->buy_now_price;
        $data['total_amount'] = $total_amount;
        $data['sub_total'] = $sub_total;
        $data['shipping_charge'] = $shipping_charge;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['alternative_number'] = $request->alternative_number;
        $data['door_no']     =    $request->door_no;
        $data['street_address'] = $request->street_address;
        $data['city_name'] = $request->city_name;
        $data['state_name'] = $request->state_name;
        $data['coupon_discount'] = $request->hdtotcouponamount;
        $data['coupon_id'] = $request->hdcoupon_id;
        $data['pin_code'] = $request->pin_code;
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $cartTotal = Cart::where('user_id', Auth::user()->id)->sum('total');
        $cartTotal = $cartTotal + $shipping_charge;
        $coupon_discount = $request->hdtotcouponamount;
        $coupon_amount = $request->hdtotcouponamount;
        $coupon_id = $request->hdcoupon_id;
        // dd($coupon_id);

        $order_id = $this->placeOrder($request, $total_amount, $sub_total, $shipping_charge);
        $data['order_id'] = $order_id;

        DB::commit();
        return view('frontend.payment.razorpay_button', compact('response', 'data', 'carts', 'cartTotal', 'coupon_discount', 'coupon_id', 'coupon_amount'));
      } else {

        $cart_true = $request->cart_true;
        $data = array();
        $shipping_charge = $request->hdShipping_cod;

        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['alternative_number'] = $request->alternative_number;
        $data['door_no'] = $request->door_no;
        $data['street_address'] = $request->street_address;
        $data['city_name'] = $request->city_name;
        $data['state_name'] = $request->state_name;
        $data['pin_code'] = $request->pin_code;
        $data['coupon_discount'] = $request->hddiscount;
        $data['coupon_id'] = $request->hdcoupon_id;
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $cartTotal = Cart::where('user_id', Auth::user()->id)->sum('total');
        if ($request->cart_true == 1) {
          $totQty = Cart::where('user_id', Auth::user()->id)->sum('qty');
        } else {
          $totQty = $request->buy_now_product_qty;
        }
        $shipping_charge = ($totQty * $shipping_charge);
        $cart_total = $cartTotal + $shipping_charge;
        $buy_product_name = $request->buy_now_product_name;
        $buy_product_qty = $request->buy_now_product_qty;
        $buy_price = $request->buy_now_price;
        $buy_product_id = $request->buy_now_product_id;
        $buy_variant_id = $request->buy_now_variant_id;
        $buy_total = $buy_price + $shipping_charge;
        $coupon_discount = $request->hddiscount;
        $coupon_id = $request->hdcoupon_id;

        DB::commit();
        return view('frontend.payment.cash', compact(
          'data',
          'cartTotal',
          'carts',
          'cart_true',
          'buy_product_name',
          'buy_product_qty',
          'buy_price',
          'buy_product_id',
          'buy_variant_id',
          'shipping_charge',
          'buy_total',
          'cart_total',
          'totQty',
          'coupon_discount',
          'coupon_id'
        ));
      }
    } catch (Exception $e) {
      DB::rollBack();
      $notification = array(
        'message' => 'Order not placed due to out of stock. So please try again.',
        'alert-type' => 'error'
      );
      return redirect()->route('user.dashboard')->with($notification);
    }
  }

  public function paymentstatus()
  {
    return view('frontend.payment.payment_status');
  }

  private function placeOrder(Request $request, $amount, $sub_total, $shipping_charge)
  {
    if ($request->cart_true == 1) {
      $totQty = Cart::where('user_id', Auth::user()->id)->sum('qty');
    } else {
      $totQty = $request->buy_now_product_qty;
    }
    $order_id = Order::insertGetId([
      'user_id' => Auth::id(),
      'userrole_id' => 1,
      'door_no' => $request->door_no,
      'street_address' => $request->street_address,
      'city_name' => $request->city_name,
      'state_name' => $request->state_name,
      'name' => $request->shipping_name,
      'email' => $request->shipping_email,
      'phone' => $request->shipping_phone,
      'alternative_number' => $request->alternative_number,
      'pin_code' => $request->pin_code,
      'payment_type' => 'Razorpay',
      'payment_status' => 'unpaid',
      // 'r_payment_id' => $request->all()['razorpay_payment_id'],
      // 'r_order_id' => $request->all()['razorpay_order_id'],
      'currency' =>  'INR',
      'amount' => $amount,
      'sub_total' => $sub_total,
      'shipping_charge' => $shipping_charge,
      // 'invoice_no' => 'NITHTX' . mt_rand(10000000, 99999999),
      'order_number' => 'NTXOR' . mt_rand(10000000, 99999999),
      'order_date' => Carbon::now()->format('d F Y'),
      'order_month' => Carbon::now()->format('F'),
      'order_year' => Carbon::now()->format('Y'),
      'status' => 'pending',
      'created_at' => Carbon::now(),
      'tot_Qty' => $totQty,
      'coupon_id' => $request->hdcoupon_id,
      'coupon_discount' => $request->hdtotcouponamount
    ]);

    if ($request->cart_true == 1) {

      $out_of_stock = $this->validateOutOfStock(1, Auth::user()->id, 0, 0);
      // dd($out_of_stock);
      if ($out_of_stock) {
        throw new Exception("something happened");
      }

      $carts = Cart::where('user_id', Auth::user()->id)->get();
      foreach ($carts as $cart) {
        OrderItem::insert([
          'order_id' => $order_id,
          'product_id' => $cart->product_id,
          'variant_size' => $cart->variant_id != null ? $cart->productVariant->size  : null,
          'qty' => $cart->qty,
          'price' => $cart->total,
          'created_at' => Carbon::now(),
        ]);

        $product = Product::where('id', $cart->product_id)->first();
        $variant = ProductVariants::where('id', $cart->variant_id)->first();

        if($variant){
          $variant->stock = $variant->stock - $cart->qty;
          $variant->update(); 
        }
        $product->current_stock = $product->current_stock - $cart->qty;
        $product->update();
        
      }
    } else {
      $buy_product_name = $request->buy_now_product_name;
      $buy_product_qty = $request->buy_now_product_qty;
      $buy_price = $request->buy_now_price;
      $buy_product_id = $request->buy_now_product_id;
      $buy_variant_id = $request->buy_now_variant_id;

      $out_of_stock = $this->validateOutOfStock(0, Auth::user()->id, $buy_product_id, $buy_product_qty, $buy_variant_id);
      // dd($out_of_stock);
      if ($out_of_stock) {
        throw new Exception("something happened");
      }
      $variant = null;
      if($buy_variant_id != 0){
        $variant = ProductVariants::find($buy_variant_id);
        
      }

      OrderItem::insert([
        'order_id' => $order_id,
        'product_id' => $buy_product_id,
        'variant_size' => $variant ? $variant->size : null,
        'qty' => $buy_product_qty,
        'price' => $buy_price,
        'created_at' => Carbon::now(),
      ]);

      $product = Product::where('id', $buy_product_id)->first();
      if($product->is_product_variant == 1 && $buy_variant_id != 0){
        $variant = ProductVariants::find($buy_variant_id);
        $variant->stock = $variant->stock - $buy_product_qty;
        $variant->update();  
      }
      $product->current_stock = $product->current_stock - $buy_product_qty;
      $product->update();
    }

    $order = Order::with('user')->where('id', $order_id)->where('user_id', Auth::id())->first();


    Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your order is pending and will be Confirmed shortly. Check your status here: https://nithitex.com/track-your-order , Nithitex&number=' . $order->phone . '');


    if ($request->cart_true == 1) {
      $rowId = Cart::where('user_id', Auth::user()->id)->get();
      Cart::destroy($rowId);
    }
    DB::commit();
    return $order_id;
  }
}
