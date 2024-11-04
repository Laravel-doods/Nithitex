<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Traits\Utils;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CashController extends Controller
{
	use Utils;
	public function CashOrder(Request $request)
	{

		DB::beginTransaction();
		// dd($request->all());
		try {

			DB::table('products')->lockForUpdate()->get();

			$cart_true = $request->cart_true;
			$shippingCharge = $request->shipping_charge;

			$buy_product_name = $request->buy_now_product_name;
			$buy_product_qty = $request->buy_now_product_qty;
			$buy_product_id = $request->buy_now_product_id;
			$buy_variant_id = $request->buy_now_variant_id;

			$buy_price = $request->buy_now_price;
			$buy_total = $request->buy_now_total;

			$cart_subtotal = $request->cart_subtotal;
			$cart_total = $request->cart_total;

			if ($request->cart_true == 1) {
				$total_amount = $cart_total;
				$sub_total = $cart_subtotal;
				$totQty = Cart::where('user_id', Auth::user()->id)->sum('qty');
			} else {
				$total_amount = $buy_total;
				$sub_total = $buy_price;
				$totQty = $buy_product_qty;
			}
			$coupon_amount = $request->hddiscount * $sub_total / 100;
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
				'payment_type' => 'Cash On Delivery',
				'payment_status' => 'Unpaid',
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
				'coupon_discount' => $coupon_amount,
				'coupon_id' => $request->hdcoupon_id

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
					$product->Update();
				}
			} else {

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
				$product->Update();
			}

			$order = Order::with('user')->where('id', $order_id)->where('user_id', Auth::id())->first();

			Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your order is pending and will be Confirmed shortly. Check your status here: https://nithitex.com/track-your-order , Nithitex&number=' . $order->phone . '');


			//  End Send Email 
			if ($request->cart_true == 1) {
				$rowId = Cart::where('user_id', Auth::user()->id)->delete();
				// Cart::destroy($rowId);
			}

			$notification = array(
				'message' => 'Your Order Placed Successfully',
				'alert-type' => 'success'
			);

			DB::commit();
			return redirect()->route('user.dashboard')->with($notification);
		} // end method 
		catch (Exception $e) {
			DB::rollBack();
			$notification = array(
				'message' => 'Order not placed due to out of stock. So please try again.',
				'alert-type' => 'error'
			);
			return redirect()->route('user.dashboard')->with($notification);
		}
	}
}
