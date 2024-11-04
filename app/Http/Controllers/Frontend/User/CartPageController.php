<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
// use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartPageController extends Controller
{
	public function MyCart()
	{
		// if (Auth::check()) {
			return view('frontend.cartlist.view_mycart');
		// }
	}
	public function GetCartProduct(Request $request)
	{
		if (Auth::check()) {

			$carts = Cart::join('products', 'products.id', 'carts.product_id')
				->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
				->where('user_id', Auth::user()->id)
				->where('current_stock', '>=', 0)
				->where(function ($query) {
                    $query->where('product_variants.stock', '>=', 0)
                          ->orWhereNull('carts.variant_id');
                })
				->select('carts.*', 'products.current_stock', 'products.product_image', 'product_variants.size', 'product_variants.stock')
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
		} else if($request->device_id){
			$device_id = $request->device_id;
			$carts = Cart::join('products', 'products.id', 'carts.product_id')
				->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
				->where('device_id', $device_id)
				->where('current_stock', '>=', 0)
				->where(function ($query) {
                    $query->where('product_variants.stock', '>=', 0)
                          ->orWhereNull('carts.variant_id');
                })
				->select('carts.*', 'products.current_stock', 'products.product_image', 'product_variants.size', 'product_variants.stock')
				->get();

			$cartTotal = Cart::join('products', 'products.id', 'carts.product_id')
				->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
				->where('device_id', $device_id)
				->where('current_stock', '>=', 0)
				->where(function ($query) {
                    $query->where('product_variants.stock', '>=', 0)
                          ->orWhereNull('carts.variant_id');
                })
				->select('carts.*', 'products.current_stock')
				->sum('total');

			$cartQty = Cart::join('products', 'products.id', 'carts.product_id')
				->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
				->where('device_id', $device_id)
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
		}
	} //end method 
	
	public function RemoveCartProduct($rowId)
	{
		$Cart = Cart::findOrFail($rowId);
		$Cart->delete();

		return response()->json(['success' => 'Successfully Remove From Cart']);
	}
	// Cart Increment 
	public function CartIncrement($id)
	{
		$row = Cart::find($id);
		$qtys = $row->qty + 1;
		$totalcart = $row->price * $qtys;
		// $total=
		Cart::findOrFail($id)->update([
			'qty' => $qtys,
			'total' => $totalcart
		]);


		return response()->json('increment');
	} // end mehtod 

	// Cart Decrement  
	public function CartDecrement($id)
	{

		$row = Cart::find($id);
		$qtys = $row->qty - 1;
		$totalcart = $row->price * $qtys;
		Cart::findOrFail($id)->update([
			'qty' => $qtys,
			'total' => $totalcart
		]);

		return response()->json('Decrement');
	} // end mehtod 
}
