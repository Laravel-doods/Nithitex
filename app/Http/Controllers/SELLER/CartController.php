<?php

namespace App\Http\Controllers\SELLER;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Models\State;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
	use Utils;
	public function MyCart()
	{
		if (Auth::check()) {
			return view('seller.cartlist.mycart');
		}
	}

	public function CheckoutCreate()
	{
		$out_of_stock = $this->validateOutOfStock(1, Auth::user()->id, 0, 0);
		// dd($out_of_stock);
		if ($out_of_stock) {
			$notification = array(
				'message' => 'Order not placed due to out of stock. So please try again.',
				'alert-type' => 'error'
			);
			return Redirect()->route('seller.mycart')->with($notification);
		}
		$state = State::orderby('state_name', 'ASC')->get();
		if (Auth::user()->state_name) {
			$state_name = User::where('state_name', Auth::user()->state_name)->pluck('state_name')->first();
			$s_name = State::where('state_name', $state_name)->first();
			$shipping_charge = State::where('shipping_charge', $s_name->shipping_charge)->pluck('shipping_charge')->first();
			$cod_charge = State::where('cod_charge', $s_name->cod_charge)->pluck('cod_charge')->first();
		} else {
			$shipping_charge = 0;
			$cod_charge = 0;
		}

		if (Auth::check()) {
			$cartTotal = Cart::where('user_id', Auth::user()->id)->sum('total');
			if ($cartTotal > 0) {
				$carts = Cart::with('productVariant')
				->select('carts.*', 'categories.weight')
				->join('products', 'products.id', 'carts.product_id')
				->join('categories', 'categories.id', 'products.category_id')
				->where('user_id', Auth::user()->id)->get();
				$cartQty = Cart::where('user_id', Auth::user()->id)->get()->count('id');
				$offer = Cart::where('user_id', Auth::user()->id)->where('is_offer_product', 1)->get()->count('id');
				$cart_true = 1;

				return view('seller.checkout.checkout_view', compact('carts', 'cartQty', 'cartTotal', 'cart_true', 'state', 'shipping_charge', 'cod_charge', 'offer'));
			} else {

				$notification = array(
					'message' => 'Shopping At list One Product',
					'alert-type' => 'error'
				);

				return redirect()->to('seller/dashboard')->with($notification);
			}
		} else {

			$notification = array(
				'message' => 'You Need to Login First',
				'alert-type' => 'error'
			);

			return redirect()->route('login')->with($notification);
		}
	}
}
