<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function ViewWishlist()
    {
        return view('frontend.wishlist.view_wishlist');
    }

    public function GetWishlistProduct(Request $request)
    {
        $device_id = $request->device_id;
        if (Auth::check()) {

            $wishlist = Wishlist::with('product')
                ->join('products', 'products.id', 'wishlists.product_id')
                ->leftjoin('product_variants', 'product_variants.id', 'wishlists.variant_id')
                ->where('user_id', Auth::user()->id)
                ->where('products.current_stock', '>=', 0)
                ->where(function ($query) {
                    $query->where('product_variants.stock', '>=', 0)
                        ->orWhereNull('wishlists.variant_id');
                })
                ->select(
                    'wishlists.id',
                    'product_variants.size',
                    'product_variants.customer_price',
                    'product_variants.seller_price',
                    'products.product_name',
                    'products.product_image',
                    'wishlists.user_id',
                    'wishlists.variant_id',
                    'wishlists.product_id',
                    'products.seller_discount',
                    'products.product_discount',
                    DB::raw('CASE WHEN wishlists.variant_id IS NULL THEN products.current_stock ELSE product_variants.stock END AS stock')
                )
                ->get();

            $wishlistQty = Wishlist::join('products', 'products.id', 'wishlists.product_id')
                ->where('user_id', Auth::user()->id)
                ->where('products.current_stock', '>=', 0)
                ->count('wishlists.id');

            return response()->json(array(
                'wishlist' => $wishlist,
                'wishlistQty' => $wishlistQty,
                'userrole_id' => Auth::user()->userrole_id
            ));
        } else if ($device_id) {

            $wishlist = Wishlist::with('product')
                ->join('products', 'products.id', 'wishlists.product_id')
                ->leftjoin('product_variants', 'product_variants.id', 'wishlists.variant_id')
                ->where('device_id', $device_id)
                ->where('products.current_stock', '>=', 0)
                ->where(function ($query) {
                    $query->where('product_variants.stock', '>=', 0)
                        ->orWhereNull('wishlists.variant_id');
                })
                ->select(
                    'wishlists.id',
                    'product_variants.size',
                    'product_variants.customer_price',
                    'product_variants.seller_price',
                    'products.product_name',
                    'products.product_image',
                    'wishlists.device_id',
                    'wishlists.variant_id',
                    'wishlists.product_id',
                    'products.seller_discount',
                    'products.product_discount',
                    DB::raw('CASE WHEN wishlists.variant_id IS NULL THEN products.current_stock ELSE product_variants.stock END AS stock')
                )
                ->get();

            $wishlistQty = Wishlist::join('products', 'products.id', 'wishlists.product_id')
                ->where('device_id', $device_id)
                ->where('products.current_stock', '>=', 0)
                ->count('wishlists.id');

            return response()->json(array(
                'wishlist' => $wishlist,
                'wishlistQty' => $wishlistQty,
                'userrole_id' => 1
            ));
        }
    } // end mehtod 
    public function RemoveWishlistProduct($id)
    {

        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();
        return response()->json(['success' => 'Product successfully removed from wishlist']);
    }
}
