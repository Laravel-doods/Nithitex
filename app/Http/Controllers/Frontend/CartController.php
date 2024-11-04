<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\GuestCart;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Models\State;
use App\Models\User;
use App\Models\Wishlist;
use App\Traits\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
	use Utils;
	public function AddToCart(Request $request, $id)
	{
		$product = Product::findOrFail($id);
		$variant = null;
		$quantity = $request->quantity;

		if ($request->variant_id) {
			$variant = ProductVariants::where('id', $request->variant_id)->first();
		} else if ($product->is_product_variant) {
			$variant = ProductVariants::where('product_id', $product->id)->where('stock', '!=', 0)->first();
			if (!$variant) {
				return response()->json(['error' => 'All variants of this product are currently out of stock.']);
			}
		}

		$cartQuery = Auth::check()
			? Cart::where('user_id', Auth::user()->id)
			: Cart::where('device_id', $request->device_id);

		$exists = $cartQuery->where('product_id', $id)->where('variant_id', $variant ? $variant->id : null)->first();
		if ($exists) {
			return response()->json(['error' => 'This product is already in your cart.']);
		}

		$price = $this->calculatePrice($product, $variant, $request->offer);
		$current_stock = $variant ? $variant->stock : $product->current_stock;

		if ($current_stock < $quantity) {
			return response()->json(['error' => 'You cannot buy more than the current stock (' . $current_stock . ')']);
		}

		Cart::create([
			'product_id' => $id,
			'variant_id' => $variant ? $variant->id : null,
			'user_id' => Auth::check() ? Auth::user()->id : null,
			'device_id' => Auth::check() ? null : $request->device_id,
			'is_offer_product' => $request->offer == 1 ? 1 : 0,
			'name' => $product->product_name,
			'qty' => $quantity,
			'price' => $price,
			'total' => $price * $quantity,
			'image' => $product->product_image
		]);

		return response()->json(['success' => 'Product successfully added to your cart']);
	}



	public function simpleAddToCart(Request $request, $id)
	{
		$device_id = $request->device_id;

		if (Auth::check()) {

			$variant_id = $request->variant_id;
			if ($variant_id == null) {
				$product = Product::where('is_product_variant', 1)->where('id', $id)->first();
				if ($product) {
					$variant = ProductVariants::where('product_id',  $product->id)->where('stock', '!=', 0)->first();
					if (!$variant) {
						return response()->json(['error' => 'All variants of this product are currently out of stock.']);
					}
					$variant_id = $variant->id;
				}
			} else {
				$variant = ProductVariants::where('id', $variant_id)->first();
			}
			$product = Product::findOrFail($id);
			$exists = Cart::where('user_id', Auth::user()->id)->where('product_id', $id)->where('variant_id', $variant_id)->first();
			if (!$exists) {
				$quantity = 1;

				if ($request->today_offer == 1) {
					//today offer product
					if ($variant_id) {
						$price = (int)$variant->price - ((int)$variant->price * $product->category->offer / 100);
					} else {
						$price = (int)$product->product_price - ((int)$product->product_price * $product->category->offer / 100);
					}
				} else {
					if ($variant_id) {
						$price = (Auth::user()->userrole_id == 1 ? $variant->customer_price : $variant->seller_price);
					} else {
						$price = (Auth::user()->userrole_id == 1 ? $product->product_discount : $product->seller_discount);
					}
				}

				$cart_total = $price * $quantity;
				Cart::create([
					'product_id' => $id,
					'variant_id' => $variant_id,
					'user_id' => Auth::user()->id,
					'is_offer_product' => $request->today_offer == 1 ? $request->today_offer : 0,
					'name' => $product->product_name,
					'qty' => $quantity,
					'price' => $price,
					'total' => $cart_total,
					'image' => $product->product_image
				]);

				return response()->json(['success' => 'Successfully Added on Your Cart']);
			} else {

				return response()->json(['error' => 'This product was already added on your cart']);
			}
		} else if ($device_id) {

			$variant_id = $request->variant_id;
			if ($variant_id == null) {
				$product = Product::where('is_product_variant', 1)->where('id', $id)->first();
				if ($product) {
					$variant = ProductVariants::where('product_id',  $product->id)->where('stock', '!=', 0)->first();
					if (!$variant) {
						return response()->json(['error' => 'All variants of this product are currently out of stock.']);
					}
					$variant_id = $variant->id;
				}
			} else {
				$variant = ProductVariants::where('id', $variant_id)->first();
			}
			$product = Product::findOrFail($id);
			$exists = Cart::where('device_id', $device_id)->where('product_id', $id)->where('variant_id', $variant_id)->first();
			if (!$exists) {
				$quantity = 1;

				if ($request->today_offer == 1) {
					//today offer product
					if ($variant_id) {
						$price = (int)$variant->price - ((int)$variant->price * $product->category->offer / 100);
					} else {
						$price = (int)$product->product_price - ((int)$product->product_price * $product->category->offer / 100);
					}
				} else {
					if ($variant_id) {
						$price = $variant->customer_price;
					} else {
						$price = $product->product_discount;
					}
				}

				$cart_total = $price * $quantity;
				Cart::create([
					'product_id' => $id,
					'variant_id' => $variant_id,
					'device_id' => $device_id,
					'is_offer_product' => $request->today_offer == 1 ? $request->today_offer : 0,
					'name' => $product->product_name,
					'qty' => $quantity,
					'price' => $price,
					'total' => $cart_total,
					'image' => $product->product_image
				]);

				return response()->json(['success' => 'Successfully Added on Your Cart']);
			} else {

				return response()->json(['error' => 'This product was already added on your cart']);
			}
		}
	} // end mehtod 


	public function AddMiniCart(Request $request)
	{
		$device_id = $request->device_id;

		if (Auth::check()) {

			$carts = Cart::join('products', 'products.id', 'carts.product_id')
				->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
				->where('user_id', Auth::user()->id)
				->where('current_stock', '>=', 0)
				->where(function ($query) {
					$query->where('product_variants.stock', '>=', 0)
						->orWhereNull('carts.variant_id');
				})
				->select('carts.*', 'products.current_stock', 'products.product_image', 'product_variants.size')
				->get();

			$cartTotal = Cart::join('products', 'products.id', 'carts.product_id')
				->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
				->where('user_id', Auth::user()->id)
				->where('current_stock', '>=', 0)
				->where(function ($query) {
					$query->where('product_variants.stock', '>=', 0)
						->orWhereNull('carts.variant_id');
				})
				->select('carts.*', 'products.current_stock')
				->sum('total');

			$cartQty = Cart::join('products', 'products.id', 'carts.product_id')
				->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
				->where('user_id', Auth::user()->id)
				->where('current_stock', '>=', 0)
				->where(function ($query) {
					$query->where('product_variants.stock', '>=', 0)
						->orWhereNull('carts.variant_id');
				})
				->select('carts.*', 'products.current_stock')
				->count('carts.id');

			return response()->json(array(
				'carts' => $carts,
				'cartQty' => $cartQty,
				'cartTotal' => $cartTotal,

			));
		} else if ($device_id) {
			// Guest user (device_id)
			$carts = Cart::join('products', 'products.id', 'carts.product_id')
				->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
				->where('device_id', $device_id)
				->where('current_stock', '>=', 0)
				->where(function ($query) {
					$query->where('product_variants.stock', '>=', 0)
						->orWhereNull('carts.variant_id');
				})
				->select('carts.*', 'products.current_stock', 'products.product_image', 'product_variants.size')
				->get();

			$cartTotal = Cart::join('products', 'products.id', 'carts.product_id')
				->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
				->where('device_id', $device_id)
				->where('current_stock', '>=', 0)
				->where(function ($query) {
					$query->where('product_variants.stock', '>=', 0)
						->orWhereNull('carts.variant_id');
				})
				->sum('total');

			$cartQty = Cart::join('products', 'products.id', 'carts.product_id')
				->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
				->where('device_id', $device_id)
				->where('current_stock', '>=', 0)
				->count('carts.id');

			return response()->json(array(
				'carts' => $carts,
				'cartQty' => $cartQty,
				'cartTotal' => $cartTotal,
			));
		} else {
			return response()->json(['error' => 'Unable to fetch cart data']);
		}
	} // end method 
	/// remove mini cart 
	public function RemoveMiniCart($rowId)
	{
		$Cart = Cart::findOrFail($rowId);
		$Cart->delete();
		return response()->json(['success' => 'Product successfully removed from your cart']);
	}
	public function AddToWishlist(Request $request, $product_id)
	{
		$device_id = $request->device_id;

		if (Auth::check()) {
			$variant_id =  $request->variant_id;
			if ($variant_id == null) {
				$product = Product::where('is_product_variant', 1)->where('id', $product_id)->first();
				if ($product) {
					$variant = ProductVariants::where('product_id',  $product->id)->first();
					$variant_id = $variant->id;
				}
			}

			$exists = Wishlist::where('user_id', Auth::id())->where('product_id', $product_id)->where('variant_id', $variant_id)->first();
			if (!$exists) {

				Wishlist::insert([
					'user_id' => Auth::id(),
					'product_id' => $product_id,
					'variant_id' => $variant_id,
					'is_favourite' => 1,
					'created_at' => Carbon::now(),
				]);

				$wishlistQty = Wishlist::where('user_id', Auth::user()->id)->count('id');

				return response()->json([
					'wishlistQty' => $wishlistQty,
					'variant_wishlist' => $request->variant_id ?? null,
					'success' => 'Product successfully added on your wishlist'
				]);
			} else {

				return response()->json(['error' => 'This product was already on your wishlist']);
			}
		} else if ($device_id) {

			$variant_id =  $request->variant_id;
			if ($variant_id == null) {
				$product = Product::where('is_product_variant', 1)->where('id', $product_id)->first();
				if ($product) {
					$variant = ProductVariants::where('product_id',  $product->id)->first();
					$variant_id = $variant->id;
				}
			}

			$exists = Wishlist::where('device_id', $device_id)->where('product_id', $product_id)->where('variant_id', $variant_id)->first();
			if (!$exists) {

				Wishlist::insert([
					'device_id' => $device_id,
					'product_id' => $product_id,
					'variant_id' => $variant_id,
					'is_favourite' => 1,
					'created_at' => Carbon::now(),
				]);

				$wishlistQty = Wishlist::where('device_id', $device_id)->count('id');

				return response()->json([
					'wishlistQty' => $wishlistQty,
					'variant_wishlist' => $request->variant_id ?? null,
					'success' => 'Product successfully added on your wishlist'
				]);
			} else {

				return response()->json(['error' => 'This product was already on your wishlist']);
			}
		}
	} // end method
	// Checkout Method 
	public function CheckoutCreate()
	{


		if (Auth::check()) {

			$out_of_stock = $this->validateOutOfStock(1, Auth::user()->id, 0, 0);
			// dd($out_of_stock);
			if ($out_of_stock) {
				$notification = array(
					'message' => 'Order not placed!. One of the product in your cart is out of stock. So please try again.',
					'alert-type' => 'error'
				);
				return Redirect()->route('mycart')->with($notification);
			}
			$state = State::orderBy('state_name', 'ASC')->get();
			if (Auth::user()->state_name) {
				$state_name = User::where('state_name', Auth::user()->state_name)->pluck('state_name')->first();
				$s_name = State::where('state_name', $state_name)->first();
				$shipping_charge = State::where('shipping_charge', $s_name->shipping_charge)->pluck('shipping_charge')->first();
				$cod_charge = State::where('cod_charge', $s_name->cod_charge)->pluck('cod_charge')->first();
			} else {
				$shipping_charge = 0;
				$cod_charge = 0;
			}


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

				return view('frontend.checkout.checkout_view', compact('carts', 'cartQty', 'cartTotal', 'cart_true', 'state', 'shipping_charge', 'cod_charge', 'offer'));
			} else {

				$notification = array(
					'message' => 'Please Add Atleast One Product',
					'alert-type' => 'error'
				);

				return redirect()->to('/')->with($notification);
			}
		} else {

			$notification = array(
				'message' => 'Please login to your account before checkout',
				'alert-type' => 'error'
			);

			return redirect()->route('user.login')->with($notification);
		}
	} // end method 

	public function couponCalculation(Request $request)
	{
		$coupon = $this->couponCalc($request->coupon_code, Auth::user()->userrole_id, Auth::user()->coupon_id);
		if ($coupon) {
			return response()->json(['discount_percentage' => $coupon->discount_percentage, 'coupon_id' => $coupon->id, 'success' => 'Coupon Applied!']);
		} else {
			return response()->json(['error' => 'Expired or invalid coupon!']);
		}
	}
}
