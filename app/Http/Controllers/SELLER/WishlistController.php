<?php

namespace App\Http\Controllers\SELLER;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function ViewWishlist(){
        if (Auth::check()) {
        return view('seller.wishlist.wishlist');
        }else{
            return view('auth.seller_login');
        }
    }

    public function GetWishlistProduct()
    {
        if (Auth::check()) {

        $wishlist = Wishlist::with('product')->join('products','products.id','wishlists.product_id')->where('user_id',Auth::user()->id)->where('products.current_stock','>',0)->select('wishlists.id','products.product_name','products.product_image','wishlists.user_id','wishlists.product_id','products.seller_discount')->get();
		$wishlistQty= Wishlist::join('products','products.id','wishlists.product_id')->where('user_id',Auth::user()->id)->where('products.current_stock','>',0)->count('wishlists.id');

        return response()->json(array(
			'wishlist' => $wishlist,
			'wishlistQty' => $wishlistQty
	
		));
    }else{
        return view('auth.seller_login');
    }

    }

    public function RemoveWishlistProduct($id)
    {
        Wishlist::where('user_id',Auth::user()->id)->where('id',$id)->delete();
        return response()->json(['success' => 'Product successfully removed from wishlist']);
    }

    public function AddToWishlist($product_id)
    {
		if (Auth::check()) {
	
			$exists = Wishlist::where('user_id',Auth::user()->id)->where('product_id',$product_id)->first();
			if (!$exists) {
			Wishlist::insert([
				'user_id' => Auth::user()->id, 
				'product_id' => $product_id, 
                'is_favourite' =>1, 
				'created_at' => Carbon::now(), 
			]);

			$wishlistQty= Wishlist::where('user_id',Auth::user()->id)->count('id');

			
		   return response()->json(['wishlistQty' => $wishlistQty,'success' => 'Product successfully added on your wishlist']);
		   
		}else{

			return response()->json(['error' => 'This product was already on your wishlist']);
		}    
		}else{

			return response()->json(['error' => 'Please login to your account before adding to wishlist']);
		}
	
	}
}
