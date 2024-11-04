<?php

namespace App\Http\Controllers\SELLER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Models\User;
use App\Models\State;
use App\Traits\Utils;
use Illuminate\Support\Facades\Auth;

class BuynowController extends Controller
{
  use Utils;
  public function ProductBuyNow(Request $request, $id)
  {
    $offer = $request->query('offer', 0);

    $productvariant = Product::where('is_product_variant', 1)->where('id', $id)->first();
    $variant_id = 0;
    $variant_size = null;
    if($productvariant){
      $variant = ProductVariants::where('product_id', $id)->where('stock','!=', 0)->first();
      if(!$variant){
        $notification = array(
          'message' => 'All variants of this product are currently out of stock.',
          'alert-type' => 'error'
        );
        return Redirect()->back()->with($notification);
      }
      $variant_id = $variant->id;
    }

    $out_of_stock = $this->validateOutOfStock(0, auth()->user() ? auth()->user()->id : null, $id, 1, $variant_id);
    // $out_of_stock = $this->validateOutOfStock(0, Auth::user()->id, $id, 1);
    // dd($out_of_stock);
    if ($out_of_stock) {
      $notification = array(
        'message' => 'Order not placed!. Out of stock. So please try again.',
        'alert-type' => 'error'
      );
      return Redirect()->route('seller.product.shop')->with($notification);
    }
    $state = State::orderby('state_name', 'ASC')->get();

    if (Auth::check()) {
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

        $product = Product::with('category')->findOrFail($id);
        $cart_true = 0;
        $quantity = 1;

        if($product->is_product_variant == 1 && $variant_id != 0 ){
          $variant = ProductVariants::find($variant_id);
          if($offer == 1){
            $buynow_price = $variant->price - ($variant->price * $product->category->offer / 100);
          }else{
            $buynow_price = $variant->seller_price;
          }
          $variant_size = $variant->size;
        }else{
          if($offer == 1){
            $buynow_price = (int)$product->product_price - ((int)$product->product_price * $product->category->offer / 100);
          }else{
            $buynow_price = $product->seller_discount;
          }
        }
        
        $product_id = $product->id;
        $carts = null;
        return view('seller.checkout.checkout_view', compact('product', 'carts', 'cart_true', 'quantity', 'buynow_price', 'product_id', 'state', 'shipping_charge', 'cod_charge', 'variant_id', 'variant_size','offer'));
      } else {

        $notification = array(
          'message' => 'You Need to Login First',
          'alert-type' => 'error'
        );

        return redirect()->route('seller.login')->with($notification);
      }
    }
  }

  public function ProductDetailsBuyNow(Request $request, $id)
  {
    $offer = $request->hdTodayOffer;
    $out_of_stock = $this->validateOutOfStock(0, auth()->user() ? auth()->user()->id : null, $id, $request->hdbuyqty, $request->hdvariantID);
    // $out_of_stock = $this->validateOutOfStock(0, Auth::user()->id, $id, $request->hdbuyqty);
    // dd($out_of_stock);
    if ($out_of_stock) {
      $notification = array(
        'message' => 'Order not placed!. Out of stock. So please try again.',
        'alert-type' => 'error'
      );
      return Redirect()->route('seller.product.shop')->with($notification);
    }
    $state = State::orderby('state_name', 'ASC')->get();

    if (Auth::check()) {
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

        $product = Product::with('category')->findOrFail($id);
        $cart_true = 0;
        $quantity = $request->hdbuyqty;
        $variant_id = $request->hdvariantID;
        $variant_size = null;

        if($product->is_product_variant == 1 && $variant_id != 0 ){

          $variant = ProductVariants::find($variant_id);
          $buynow_price = $quantity * $variant->seller_price;
          $variant_size = $variant->size;
        }else{
          $buynow_price = $quantity * $product->seller_discount;
        }

        $product_id = $product->id;
        $carts = null;
        return view('seller.checkout.checkout_view', compact('product', 'carts', 'cart_true', 'quantity', 'buynow_price', 'product_id', 'state', 'shipping_charge', 'cod_charge', 'variant_id', 'variant_size', 'offer'));
      } else {

        $notification = array(
          'message' => 'You Need to Login First',
          'alert-type' => 'error'
        );

        return redirect()->route('seller.login')->with($notification);
      }
    }
  }
}
