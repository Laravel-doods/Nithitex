<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\OrderMail;
use App\Models\Coupon;
use App\Models\ProductVariants;
use App\Models\State;
use App\Models\User;
use App\Traits\ResponseAPI;
use App\Traits\Utils;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Razorpay\Api\Api;

class OrderController extends Controller
{
    use Utils;
    use ResponseAPI;
    private $razorpayId = "rzp_test_bUcyzVDkdztvea";
    private $razorpaykey = "s1BI0kDv3nEaTehLWoLPQQGJ";
    // private $razorpayId = "rzp_live_BPqOpSftyPAkey";
    // private $razorpaykey = "NZrNloxbjs4zifCkpf0kgUOj";

    public function placeOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('products')->lockForUpdate()->get();
            $cart_true = $request->cart_true;
            $shippingCharge = $request->shipping_charge;

            $buy_product_qty = $request->buy_now_product_qty;
            $buy_product_id = $request->buy_now_product_id;

            $buy_price = $request->buy_now_price;
            $buy_total = $request->buy_now_total;

            $cart_subtotal = $request->cart_subtotal;
            $cart_total = $request->cart_total;

            if ($cart_true == 1) {
                $total_amount = $cart_total;
                $sub_total = $cart_subtotal;
                $totQty = Cart::where('user_id', Auth::user()->id)->sum('qty');
            } else {
                $total_amount = $buy_total;
                $sub_total = $buy_price;
                $totQty = $buy_product_qty;
            }
            $coupon_id = Coupon::where('coupon_code', $request->coupon_code)->pluck('id')->first();
            $order_id = Order::insertGetId([
                'user_id' => Auth::user()->id,
                'userrole_id' => Auth::user()->userrole_id,
                'door_no' => $request->door_no,
                'street_address' => $request->street_address,
                'city_name' => $request->city_name,
                'state_name' => $request->state_name,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'alternative_number' => $request->alternative_number,
                'pin_code' => $request->pin_code,
                'payment_type' => ($request->payment_type == 'COD' ? 'Cash On Delivery' : 'Razorpay'),
                'payment_status' => ($request->payment_type == 'COD' ? 'Unpaid' : 'paid'),
                'r_order_id' => $request->r_order_id,
                'r_payment_id' => $request->r_payment_id,
                'currency' =>  'INR',
                'amount' => $total_amount,
                'sub_total' => $sub_total,
                'shipping_charge' => $shippingCharge,
                'order_number' => 'NTXOR' . mt_rand(10000000, 99999999),
                'order_date' => Carbon::now()->format('d F Y'),
                'order_month' => Carbon::now()->format('F'),
                'order_year' => Carbon::now()->format('Y'),
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'tot_Qty' => $totQty,
                'collectcash_amount' => $request->collectcash_amount,
                'margin_amount' => $request->margin_amount,
                'coupon_discount' => $request->coupon_discount,
                'coupon_id' => $coupon_id
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
                        'qty' => $cart->qty,
                        'price' => $cart->total,
                        'created_at' => Carbon::now(),
                    ]);

                    $product = Product::where('id', $cart->product_id)->first();
                    $product->current_stock = $product->current_stock - $cart->qty;
                    $product->update();
                }
            } else {

                $out_of_stock = $this->validateOutOfStock(0, Auth::user()->id, $buy_product_id, $buy_product_qty);
                // dd($out_of_stock);
                if ($out_of_stock) {
                    throw new Exception("something happened");
                }

                OrderItem::insert([
                    'order_id' => $order_id,
                    'product_id' => $buy_product_id,
                    'qty' => $buy_product_qty,
                    'price' => $buy_price,
                    'created_at' => Carbon::now(),
                ]);

                $product = Product::where('id', $buy_product_id)->first();
                $product->current_stock = $product->current_stock - $buy_product_qty;
                $product->update();
            }

            if ($request->payment_type == "Razorpay") {

                Order::where('id', $order_id)->update([
                    'invoice_no' => 'NITHTX' . mt_rand(10000000, 99999999)
                ]);

                $order = Order::with('seller')->where('id', $order_id)->where('user_id', Auth::user()->id)->first();
                $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

                $pdf = PDF::loadView('seller.dashboard.order_invoice', compact('order', 'orderItem'));
                Mail::to($request->email)->send(new OrderMail($pdf, $order->invoice_no, 'online'));

                $content = $pdf->download()->getOriginalContent();

                $make_name = 'invoice/' . $order->invoice_no . '.pdf';

                $save_url = $this->fileUploadS3Bucket($make_name, $content, 1);

                Order::where('id', $order->id)->update([
                    'invoice' => $save_url
                ]);
            }

            if (Auth::user()->userrole_id == 2) {
                $mobile = Auth::user()->phone;
            } else {
                $mobile = $request->phone;
            }

            Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your order is pending and will be Confirmed shortly. Check your status here: https://nithitex.com/track-your-order , Nithitex&number=' . $mobile . '');

            if ($request->cart_true == 1) {
                $rowId = Cart::where('user_id', Auth::user()->id)->get();
                Cart::destroy($rowId);
            }
            $message = "Order Placed Successfully";
            DB::commit();
            return response()->json(['status' => true, 'message' => $message], 200);
        } catch (Exception $e) {
            DB::rollBack();
            $message = "Order not placed due to out of stock. So please try again.";
            return response()->json(['status' => false, 'message' => $message, 'error' => $e], 200);
        }
    }
    public function placeOrderv1(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('products')->lockForUpdate()->get();
            $cart_true = $request->cart_true;
            $shippingCharge = $request->shipping_charge;

            $buy_product_qty = $request->buy_now_product_qty;
            $buy_product_id = $request->buy_now_product_id;
            $buy_variant_id = $request->variant_id;

            $buy_price = $request->buy_now_price;
            $buy_total = $request->buy_now_total;

            $cart_subtotal = $request->cart_subtotal;
            $cart_total = $request->cart_total;

            if ($cart_true == 1) {
                $total_amount = $cart_total;
                $sub_total = $cart_subtotal;
                $totQty = Cart::where('user_id', Auth::user()->id)->sum('qty');
            } else {
                $total_amount = $buy_total;
                $sub_total = $buy_price;
                $totQty = $buy_product_qty;
            }
            $coupon_id = Coupon::where('coupon_code', $request->coupon_code)->pluck('id')->first();
            $order_id = Order::insertGetId([
                'user_id' => Auth::user()->id,
                'userrole_id' => Auth::user()->userrole_id,
                'door_no' => $request->door_no,
                'street_address' => $request->street_address,
                'city_name' => $request->city_name,
                'state_name' => $request->state_name,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'alternative_number' => $request->alternative_number,
                'pin_code' => $request->pin_code,
                'payment_type' => ($request->payment_type == 'COD' ? 'Cash On Delivery' : 'Razorpay'),
                'payment_status' => 'Unpaid',
                // 'r_order_id' => $request->r_order_id,
                // 'r_payment_id' => $request->r_payment_id,
                'currency' =>  'INR',
                'amount' => $total_amount,
                'sub_total' => $sub_total,
                'shipping_charge' => $shippingCharge,
                'order_number' => 'NTXOR' . mt_rand(10000000, 99999999),
                'order_date' => Carbon::now()->format('d F Y'),
                'order_month' => Carbon::now()->format('F'),
                'order_year' => Carbon::now()->format('Y'),
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'tot_Qty' => $totQty,
                'collectcash_amount' => $request->collectcash_amount,
                'margin_amount' => $request->margin_amount,
                'coupon_discount' => $request->coupon_discount,
                'referral_discount' => $request->referral_discount,
                'coupon_id' => $coupon_id
            ]);

            if ($request->cart_true == 1) {

                $out_of_stock = $this->validateOutOfStock(1, Auth::user()->id, 0, 0);
                // dd($out_of_stock);
                if ($out_of_stock) {
                    throw new Exception("something happened");
                }

                $carts = Cart::where('user_id', Auth::user()->id)->get();
                foreach ($carts as $cart) {
                    $variant = ProductVariants::where('id', $cart->variant_id)->first();
                    OrderItem::insert([
                        'order_id' => $order_id,
                        'product_id' => $cart->product_id,
                        'qty' => $cart->qty,
                        'price' => $cart->total,
                        'variant_size' => $variant->size ?? null,
                        'created_at' => Carbon::now(),
                    ]);

                    $product = Product::where('id', $cart->product_id)->first();
                    $product->current_stock = $product->current_stock - $cart->qty;
                    $product->update();

                    //variant stockUpdate
                    if ($variant) {
                        $variant->stock = $variant->stock - $cart->qty;
                        $variant->update();
                    }
                }
            } else {

                $out_of_stock = $this->validateOutOfStock(0, Auth::user()->id, $buy_product_id, $buy_product_qty, $buy_variant_id);
                // dd($out_of_stock);
                if ($out_of_stock) {
                    throw new Exception("something happened");
                }

                $variant = ProductVariants::where('id', $buy_variant_id)->first();
                OrderItem::insert([
                    'order_id' => $order_id,
                    'product_id' => $buy_product_id,
                    'qty' => $buy_product_qty,
                    'price' => $buy_price,
                    'variant_size' => $variant->size ?? null,
                    'created_at' => Carbon::now(),
                ]);

                $product = Product::where('id', $buy_product_id)->first();
                $product->current_stock = $product->current_stock - $buy_product_qty;
                $product->update();

                //variant stockUpdate
                if ($variant) {
                    $variant->stock = $variant->stock - $buy_product_qty;
                    $variant->update();
                }
            }

            if (Auth::user()->userrole_id == 2) {
                $mobile = Auth::user()->phone;
            } else {
                $mobile = $request->phone;
            }

            Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your order is pending and will be Confirmed shortly. Check your status here: https://nithitex.com/track-your-order , Nithitex&number=' . $mobile . '');

            if ($request->cart_true == 1) {
                $rowId = Cart::where('user_id', Auth::user()->id)->get();
                Cart::destroy($rowId);
            }

            if ($request->payment_type == 'COD') {
                User::where('id', Auth::user()->id)->update([
                    'referral_points' => DB::raw('referral_points - ' . $request->referral_discount)
                ]);
            }
            $message = "Order Placed Successfully";
            DB::commit();
            return response()->json(['status' => true, 'order_id' => $order_id, 'message' => $message], 200);
        } catch (Exception $e) {
            DB::rollBack();
            $message = "Order not placed due to out of stock. So please try again.";
            return response()->json(['status' => false, 'message' => $message, 'error' => $e], 200);
        }
    }

    public function updatePaymetStatus(Request $request)
    {
        try {
            $order_id = $request->order_id;

            Order::where('id', $order_id)->update([
                'invoice_no' => 'NITHTX' . mt_rand(10000000, 99999999),
                'r_order_id' => $request->r_order_id,
                'r_payment_id' => $request->r_payment_id,
                'payment_status' => 'paid'
            ]);

            $order = Order::with('seller', 'user')->where('id', $order_id)->where('user_id', Auth::user()->id)->first();
            $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

            $pdf = PDF::loadView(Auth::user()->id == 2 ? 'seller.dashboard.order_invoice' : 'frontend.user.order.order_invoice', compact('order', 'orderItem'));
            Mail::to($request->email)->send(new OrderMail($pdf, $order->invoice_no, 'online'));

            $content = $pdf->download()->getOriginalContent();

            $make_name = 'invoice/' . $order->invoice_no . '.pdf';

            $save_url = $this->fileUploadS3Bucket($make_name, $content, 1);

            Order::where('id', $order->id)->update([
                'invoice' => $save_url
            ]);

            User::where('id', $order->user_id)->update([
                'referral_points' => DB::raw('referral_points - ' . $order->referral_discount)
            ]);
            $message = "Order Updated Successfully";
            return response()->json(['status' => true, 'message' => $message], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function orderList()
    {
        try {
            $responseData = [];

            $responseData['orders'] = [];
            $order = Order::where('user_id', Auth::user()->id)->orderby('id', 'DESC')->get();

            foreach ($order as $item) {
                $orderDetails['order_id'] = $item->id;
                $orderDetails['order_number'] = $item->order_number;
                $orderDetails['qty'] = $item->tot_Qty;
                $orderDetails['order_date'] = ($item->created_at)->format('d/m/y');
                $orderDetails['cancel_date'] = ($item->cancel_date != null ? $item->cancel_date : "");
                $orderDetails['return_date'] = ($item->return_date != null ? $item->return_date : "");
                $orderDetails['sub_total'] = $item->sub_total;
                $orderDetails['shipping_charge'] = $item->shipping_charge;
                $orderDetails['payment_type'] = $item->payment_type;
                $orderDetails['payment_status'] = $item->payment_status;
                $orderDetails['status'] = $item->status;
                $orderDetails['total'] = $item->amount;
                $orderDetails['status'] = $item->status;
                $orderDetails['is_returned'] = $item->return_order;
                $orderDetails['is_cancelled'] = $item->cancel_request;
                $orderDetails['margin_amount'] = ($item->margin_amount ? $item->margin_amount : 0);

                array_push($responseData['orders'], $orderDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function orderDetail($order_id)
    {
        try {
            $responseData['orders'] = [];

            $orders = Order::where('id', $order_id)->first();
            $state_name = State::where('state_name', $orders->state_name)->pluck('id')->first();

            $invoicedetails['order_date'] = ($orders->created_at)->format('d/m/y');
            $invoicedetails['cancel_date'] = ($orders->cancel_date != null ? $orders->cancel_date : "");
            $invoicedetails['return_date'] = ($orders->return_date != null ? $orders->return_date : "");
            $invoicedetails['order_id'] = $order_id;
            $invoicedetails['order_number'] = $orders->order_number;
            $invoicedetails['invoice_number'] = ($orders->invoice_no ? $orders->invoice_no : "");
            $invoicedetails['sub_total'] = $orders->sub_total;
            $invoicedetails['shipping_charge'] = $orders->shipping_charge;
            $invoicedetails['margin_amount'] = ($orders->margin_amount ? $orders->margin_amount : 0);
            $invoicedetails['coupon_discount'] = $orders->coupon_discount;
            $invoicedetails['referral_discount'] = $orders->referral_discount;
            $invoicedetails['amount'] = $orders->amount;
            $invoicedetails['payment_type'] = $orders->payment_type;
            $invoicedetails['payment_status'] = $orders->payment_status;
            $invoicedetails['status'] = $orders->status;
            $invoicedetails['name'] = $orders->name;
            $invoicedetails['phone'] = $orders->phone;
            $invoicedetails['door_no'] = $orders->door_no;
            $invoicedetails['street_address'] = $orders->street_address;
            $invoicedetails['street_address'] = $orders->street_address;
            $invoicedetails['city_name'] = $orders->city_name;
            $invoicedetails['state_name'] = $orders->state_name;
            $invoicedetails['state_id'] = $state_name;
            $invoicedetails['pin_code'] = $orders->pin_code;
            $invoicedetails['is_returned'] = $orders->return_order;
            $invoicedetails['return_reason'] = $orders->return_reason;
            $invoicedetails['is_cancelled'] = $orders->cancel_request;
            $invoicedetails['transaction_id'] = ($orders->r_payment_id ? $orders->r_payment_id : "");
            $invoicedetails['tot_Qty'] = $orders->tot_Qty;
            $invoicedetails['alternative_number'] = ($orders->alternative_number ? $orders->alternative_number : "");


            $responseData['orders_detail'] = [];
            $order = OrderItem::where('order_id', $order_id)->get();
            foreach ($order as $item) {
                $orderdetails['product_id'] = $item->product_id;
                $orderdetails['qty'] = $item->qty;
                $orderdetails['product_size'] = $item->variant_size;

                $products = Product::where('id', $item->product_id)->get();
                foreach ($products as $product) {
                    $orderdetails['product_name'] = $product->product_name;
                    // if ($this->getRoleId(Auth::user()->id) == 2) {
                    //     $orderdetails['product_price'] = round($product->seller_discount);
                    // } else {
                    $orderdetails['product_price'] = round($item->price / $item->qty);
                    // }
                    $orderdetails['product_image'] = url($product->product_image);
                }
                array_push($responseData['orders_detail'], $orderdetails);
            }
            array_push($responseData['orders'], $invoicedetails);

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function returnOrder(Request $request, $id)
    {
        if (Auth::user()->userrole_id == 2) {
            $username = Auth::user()->name;
            $mobile = Auth::user()->phone;
        } else {
            $username = Order::where('id', $id)->pluck('name')->first();
            $mobile = Order::where('id', $id)->pluck('phone')->first();
        }
        Order::find($id)->update([
            'return_date' => Carbon::now(),
            'return_reason' => $request->return_reason,
            'return_order' => 1,
        ]);

        Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Dear ' . $username . ', Your return request has been submitted successfully. Our executive will contact you soon. Regards - NITHTX&number=' . $mobile . '');

        $message = "Your return request has been submitted successfully";
        return response()->json(['message' => $message], 200);
    }

    public function returnOrderList()
    {
        try {
            $responseData = [];

            $responseData['orders'] = [];
            $orders = Order::where('user_id', Auth::user()->id)
                ->where('status', 'returned')
                ->orderBy('id', 'DESC')
                ->get();
            foreach ($orders as $item) {

                $returnOrderDetails['order_id'] = $item->id;
                $returnOrderDetails['order_number'] = $item->order_number;
                $returnOrderDetails['qty'] = $item->tot_Qty;
                $returnOrderDetails['order_date'] = ($item->created_at)->format('d/m/y');
                $returnOrderDetails['cancel_date'] = ($item->cancel_date != null ? $item->cancel_date : "");
                $returnOrderDetails['return_date'] = ($item->return_date != null ? $item->return_date : "");
                $returnOrderDetails['sub_total'] = $item->sub_total;
                $returnOrderDetails['shipping_charge'] = $item->shipping_charge;
                $returnOrderDetails['payment_type'] = $item->payment_type;
                $returnOrderDetails['payment_status'] = $item->payment_status;
                $returnOrderDetails['status'] = $item->status;
                $returnOrderDetails['total'] = $item->amount;
                $returnOrderDetails['status'] = $item->status;
                $returnOrderDetails['is_returned'] = $item->return_order;
                $returnOrderDetails['is_cancelled'] = $item->cancel_request;
                $returnOrderDetails['margin_amount'] = ($item->margin_amount ? $item->margin_amount : 0);

                array_push($responseData['orders'], $returnOrderDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function cancelRequest($order_id)
    {
        Order::findOrFail($order_id)->update([
            'cancel_date' => Carbon::now(),
            'cancel_request' => 1,
        ]);

        $message = "Your cancel request has been submitted successfully";
        return response()->json(['message' => $message], 200);
    }

    public function cancelOrders()
    {
        try {
            $responseData = [];

            $responseData['orders'] = [];
            $orders = Order::where('user_id', Auth::user()->id)
                ->where('status', 'cancelled')
                ->orderBy('id', 'DESC')
                ->get();

            foreach ($orders as $item) {

                $cancelOrderDetails['order_id'] = $item->id;
                $cancelOrderDetails['order_number'] = $item->order_number;
                $cancelOrderDetails['qty'] = $item->tot_Qty;
                $cancelOrderDetails['order_date'] = ($item->created_at)->format('d/m/y');
                $cancelOrderDetails['cancel_date'] = ($item->cancel_date != null ? $item->cancel_date : "");
                $cancelOrderDetails['return_date'] = ($item->return_date != null ? $item->return_date : "");
                $cancelOrderDetails['sub_total'] = $item->sub_total;
                $cancelOrderDetails['shipping_charge'] = $item->shipping_charge;
                $cancelOrderDetails['payment_type'] = $item->payment_type;
                $cancelOrderDetails['payment_status'] = $item->payment_status;
                $cancelOrderDetails['status'] = $item->status;
                $cancelOrderDetails['total'] = $item->amount;
                $cancelOrderDetails['status'] = $item->status;
                $cancelOrderDetails['is_returned'] = $item->return_order;
                $cancelOrderDetails['is_cancelled'] = $item->cancel_request;
                $cancelOrderDetails['margin_amount'] = ($item->margin_amount ? $item->margin_amount : 0);

                array_push($responseData['orders'], $cancelOrderDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function orderSummary()
    {
        try {
            $responseData = [];
            $responseData['order_summary'] = [];
            $order_details = Cart::select('products.id', 'carts.variant_id', 'carts.qty', 'carts.total', 'products.product_name', 'carts.price', 'product_variants.size')
                ->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->where('user_id', '=', Auth::user()->id)
                ->get();

            foreach ($order_details as $item) {

                $orderDetails['product_id'] = $item->id;
                $orderDetails['qty'] = $item->qty;
                $orderDetails['product_name'] = $item->product_name;
                $orderDetails['product_size'] = $item->size;
                $orderDetails['price'] = $item->price;

                array_push($responseData['order_summary'], $orderDetails);
            }
            $responseData['orders_total'] = [];
            $total = Cart::where('user_id', Auth::user()->id)->sum('total');
            $qty = Cart::where('user_id', Auth::user()->id)->sum('qty');
            $totalDetails['subtotal'] = $total;
            $totalDetails['total'] = $total;
            $totalDetails['qty'] = $qty;

            array_push($responseData['orders_total'], $totalDetails);

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function trackOrder(Request $request)
    {
        try {

            $responseData = [];

            $responseData['tracking_details'] = [];

            if ($request->user_id) {
                $track = Order::where('track_number', $request->tracking_number)->where('user_id', $request->user_id)->first();

                if ($track) {

                    $trackingDetails['invoice_no'] = $track->invoice_no;
                    $trackingDetails['order_number'] = $track->order_number;
                    $trackingDetails['order_date'] = $track->order_date;
                    $trackingDetails['name'] = $track->name;
                    $trackingDetails['city_name'] = $track->city_name;
                    $trackingDetails['state_name'] = $track->state_name;
                    $trackingDetails['phone'] = $track->phone;
                    $trackingDetails['payment_type'] = $track->payment_type;
                    $trackingDetails['payment_status'] = $track->payment_status;
                    $trackingDetails['amount'] = $track->amount;
                    $trackingDetails['status'] = $track->status;
                    array_push($responseData['tracking_details'], $trackingDetails);

                    return response()->json(['data' => $responseData], 200);
                } else {
                    return response()->json(['data' => $responseData], 200);
                }
            } else {
                return response()->json(['data' => $responseData], 200);
            }
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    public function generateRazorpayOrder(Request $request)
    {
        try {
            $api = new Api($this->razorpayId, $this->razorpaykey);
            $order = $api->order->create([
                'receipt' => $request->receipt_id,
                'amount' => $request->total_amount * 100,
                'currency' => 'INR',
            ]);

            return response()->json(['order_id' => $order['id']], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
