<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductMultipleImage;
use App\Models\ProductVariants;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Traits\ResponseAPI;
use App\Traits\Utils;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  use ResponseAPI;
  use Utils;
  private $recordsperpage = 10;

  public function productsByMainCategory(Request $request, $id)
  {
    try {
      $user_id = ($request->user_id > 0 ? array($request->user_id) : 0);
      $product_id = ($request->product_id > 0 ? $request->product_id : 0);

      $query = $this->getProducts($user_id)
        ->where('main_category_id', $id)
        ->inRandomOrder()
        ->orderBy('id', 'DESC');

      if ($product_id > 0) {
        $query = $query->where('products.id', '!=', $product_id);
      }

      $products = $query->paginate($this->recordsperpage);

      $responseData = $this->getProductLists($products, $user_id);

      return response()->json(['data' => $responseData, 'count' => $products->count(), 'total' => $products->total(), 'currentPage' => $products->currentPage(), 'lastPage' => $products->lastPage()], 200);
    } catch (Exception $e) {
      return $this->error($e->getMessage(), $e->getCode());
    }
  }

  public function productsByCategory(Request $request, $id)
  {
    try {
      $user_id = ($request->user_id > 0 ? array($request->user_id) : 0);
      $product_id = ($request->product_id > 0 ? $request->product_id : 0);

      $query = $this->getProducts($user_id)
        ->where('category_id', $id)
        ->inRandomOrder()
        ->orderBy('id', 'DESC');

      if ($product_id > 0) {
        $query = $query->where('products.id', '!=', $product_id);
      }

      $products = $query->paginate($this->recordsperpage);

      $responseData = $this->getProductLists($products, $user_id);

      return response()->json(['data' => $responseData, 'count' => $products->count(), 'total' => $products->total(), 'currentPage' => $products->currentPage(), 'lastPage' => $products->lastPage()], 200);
    } catch (Exception $e) {
      return $this->error($e->getMessage(), $e->getCode());
    }
  }
  
  public function productsByTodayOffer(Request $request)
  {
    try {
      $cat_ids = Category::where('is_today_offer', 1)->pluck('id');
      $offer = 1;

      $user_id = ($request->user_id > 0 ? array($request->user_id) : 0);
      $product_id = ($request->product_id > 0 ? $request->product_id : 0);

      $query = $this->getProducts($user_id)
        ->whereIn('category_id', $cat_ids)
        ->inRandomOrder()
        ->orderBy('id', 'DESC');

      if ($product_id > 0) {
        $query = $query->where('products.id', '!=', $product_id);
      }

      $products = $query->paginate($this->recordsperpage);

      $responseData = $this->getProductLists($products, $user_id, $offer);

      return response()->json(['data' => $responseData, 'count' => $products->count(), 'total' => $products->total(), 'currentPage' => $products->currentPage(), 'lastPage' => $products->lastPage()], 200);
    } catch (Exception $e) {
      return $this->error($e->getMessage(), $e->getCode());
    }
  }

  public function paging(Request $request)
  {
    $user_id = ($request->user_id > 0 ? array($request->user_id) : 0);

    $query = $this->getProducts($user_id)
      ->inRandomOrder()
      ->orderBy('id', 'DESC');

    if ($request->product_type == 0) {
      $products = $query->paginate($this->recordsperpage);
    } else if ($request->product_type == 1) {
      $products = $this->getOfferProducts($user_id)
        ->inRandomOrder()
        ->orderBy('id', 'DESC')
        ->paginate($this->recordsperpage);
    } else if ($request->product_type == 2) {
      $products = $query->where('is_featured', '=', 1)->paginate($this->recordsperpage);
    } else if ($request->product_type == 3) {
      $products = $query->where('is_bestSelling', '=', 1)->paginate($this->recordsperpage);
    } else if ($request->product_type == 4) {
      $products = $query->where('is_newArrival', '=', 1)->paginate($this->recordsperpage);
    }

    $responseData = $this->getProductLists($products, $user_id);

    return response()->json(['data' => $responseData, 'count' => $products->count(), 'total' => $products->total(), 'currentPage' => $products->currentPage(), 'lastPage' => $products->lastPage()], 200);
  }

  public function allProducts(Request $request)
  {
    try {

      $user_id = ($request->user_id > 0 ? array($request->user_id) : 0);

      $query = $this->getProducts($user_id)
        ->inRandomOrder()
        ->orderBy('id', 'DESC');

      if ($request->product_type == 0) {
        $offer = null;
        $products = $query->paginate($this->recordsperpage);
      } else if ($request->product_type == 1) {
        $offer = null;
        $products = $this->getOfferProducts($user_id)
          ->inRandomOrder()
          ->orderBy('id', 'DESC')
          ->paginate($this->recordsperpage);
      } else if ($request->product_type == 2) {
        $offer = null;
        $products = $query->where('is_featured', '=', 1)->paginate($this->recordsperpage);
      } else if ($request->product_type == 3) {
        $offer = null;
        $products = $query->where('is_bestSelling', '=', 1)->paginate($this->recordsperpage);
      } else if ($request->product_type == 4) {
        $offer = null;
        $products = $query->where('is_newArrival', '=', 1)->paginate($this->recordsperpage);
      } else if ($request->product_type == 6) { //Today Offer by Category
        $cat_ids = Category::where('is_today_offer', 1)->pluck('id');
        $offer = 1;
        $products = $this->getProducts($user_id)
        ->whereIn('category_id', $cat_ids)
        ->inRandomOrder()
        ->orderBy('id', 'DESC')
        ->paginate($this->recordsperpage);
      }

      $responseData = $this->getProductLists($products, $user_id, $offer);

      return response()->json(['data' => $responseData, 'count' => $products->count(), 'total' => $products->total(), 'currentPage' => $products->currentPage(), 'lastPage' => $products->lastPage()], 200);
    } catch (\Exception $e) {
      return $this->error($e->getMessage(), $e->getCode());
    }
  }

  public function productdetail(Request $request, $id)
  {
    try {
      $responseData = [];

      $responseData['product'] = [];
      $user_id = ($request->user_id > 0 ? array($request->user_id) : 0);
      if ($user_id) {
        $query = Product::with('color')->select('wishlists.is_favourite', 'wishlists.user_id', 'products.*');
        $query->leftJoin('wishlists', function ($join) {
          $join->on('wishlists.product_id', '=', 'products.id')
            ->where("wishlists.user_id", '=', DB::raw('?'));
        })->setBindings(array_merge($query->getBindings(), $user_id));
        $product = $query->where('products.id', '=', $id)
          ->first();
      } else {
        $product = Product::with('color')->where('products.id', '=', $id)->first();
      }
      $productDetails['id'] = $product->id;

      $cart = Cart::where('product_id', $product->id)->where('user_id', $user_id)->first();

      $category = Category::where('id', $product->category_id)->first();

      $productDetails['category_id'] = $product->category_id;
      $productDetails['category_name'] = $category->category_name;
      $productDetails['product_name'] = $product->product_name;
      $productDetails['product_price'] = $product->product_price;

      $offer = $request->offer ?? 0;
      if($offer == 1){
        $price = $product->product_price - ($product->product_price * $category->offer / 100);
      }
      // $is_today_offer = Category::where('id', $product->category_id)->where('is_today_offer', 1)->first();
      // if($is_today_offer){
      //   $price = $product->product_price - ($product->product_price * $is_today_offer->offer / 100);
      // }


      if ($this->getRoleId($user_id) == 2) {
        $productDetails['product_discount'] = $offer ? (string)$price : $product->seller_discount;
        $amount = $product->product_price - ($offer ? (string)$price : $product->seller_discount);
      } else {
        $productDetails['product_discount'] = $offer ? (string)$price : $product->product_discount;
        $amount = $product->product_price - ($offer ? (string)$price : $product->product_discount);
      }

      $discount = ($amount / $product->product_price) * 100;
      $productDetails['customer_price'] = $offer ? (string)$price : $product->product_discount;
      $productDetails['short_description'] = $product->short_description;
      $productDetails['long_description'] = $product->long_description;
      $productDetails['current_stock'] = $product->current_stock;
      $productDetails['product_sku'] = $product->product_sku;
      $productDetails['discount_percentage'] = round($discount);
      $productDetails['product_image'] = url($product->product_image);
      $productDetails['product_video_url'] = $product->product_video_url;
      $productDetails['product_url'] = url('/product/details/' . $product->id . '/' . $product->product_slug);
      $productDetails['is_favourite'] = $product->is_favourite;
      $productDetails['color_id'] = $product->color_id;
      $productDetails['color_name'] = ($product->color_id ? $product->color->color_name : "");
      $productDetails['color_code'] = ($product->color_id ? $product->color->color_code : "");

      $colors = Product::with('color')->where('group_id', $product->group_id)->get();
      $colorDetails = [];
      $productDetails['colors'] = [];

      foreach ($colors as $item) {
        $colorDetails['product_id'] = $item->id;
        $colorDetails['color_name'] = ($item->color_id ? $item->color->color_name : "");
        $colorDetails['color_code'] = ($item->color_id ? $item->color->color_code : "");
        array_push($productDetails['colors'], $colorDetails);
      }

      $variants = ProductVariants::where('product_id', $product->id)->get();
      $variantsDetail = [];
      $productDetails['variants'] = [];

      foreach ($variants as $item) {
        $variantsDetail['variant_id'] = $item->id;
        $variantsDetail['product_id'] = $item->product_id;
        $variantsDetail['size'] = $item->size;
        $variantsDetail['product_price'] = $item->price;

        if($offer){
          $vprice = $item->price - ($item->price * $category->offer / 100);
        }

        if ($this->getRoleId($user_id) == 2) {
          $variantsDetail['product_discount'] = $offer ? (string)$vprice : $item->seller_price;
          $amount = $item->price - ($offer ? (string)$vprice : $item->seller_price);
        } else {
          $variantsDetail['product_discount'] = $offer ? (string)$vprice : $item->customer_price;
          $amount = $item->price - ($offer ? (string)$vprice : $item->customer_price);
        }
        $discount = ($amount / $item->price) * 100;

        $variantsDetail['customer_price'] = $offer ? (string)$vprice :$item->customer_price;
        $variantsDetail['discount_percentage'] = round($discount);
        $variantsDetail['current_stock'] = $item->stock;
        $variantsDetail['product_sku'] = $item->product_sku;

        if ($user_id) {
          $wishlist = Wishlist::where('product_id', $item->product_id)->where('variant_id', $item->id)->where('user_id', $user_id)->first();
          $variantsDetail['is_favourite'] = $wishlist ? $wishlist->is_favourite : null;
        } else {
          $variantsDetail['is_favourite'] = null;
        }

        if ($user_id) {
          $cart = Cart::where('product_id', $product->id)->where('variant_id', $item->id)->where('user_id', $user_id)->first();
          $variantsDetail['cart'] = $cart ? true : false;
          $variantsDetail['cart_qty'] = $cart ? $cart->qty : "1";
        } else {
          $variantsDetail['cart'] = false;
          $variantsDetail['cart_qty'] = "1";
        }
        array_push($productDetails['variants'], $variantsDetail);
      }

      $multiImage = ProductMultipleImage::where('product_id', $product->id)->get();
      $multiImagedetails = [];
      $productDetails['product_multiple_image'] = [];

      foreach ($multiImage as $item) {
        $multiImagedetails['id'] = $item->id;
        $multiImagedetails['product_id'] = $item->product_id;
        $multiImagedetails['product_multiple_image'] = url($item->product_mult_image);
        array_push($productDetails['product_multiple_image'], $multiImagedetails);
      }

      $productDetails['cart'] = ($cart != null ? true : false);
      $productDetails['cart_qty'] = ($cart != null ? $cart->qty : "1");
      $productDetails['cart_qty'] = ($cart != null ? $cart->qty : "1");
      $productDetails['is_today_offer'] = $offer ? true : false;

      array_push($responseData['product'], $productDetails);

      return response()->json(['data' => $responseData], 200);
    } catch (\Exception $e) {
      return $this->error($e->getMessage(), $e->getCode());
    }
  }

  public function colorSort(Request $request, $color_id)
  {
    try {

      $user_id = ($request->user_id > 0 ? array($request->user_id) : 0);
      $product_type = ($request->product_type != null ? $request->product_type : 0);
      $category_id = ($request->category_id != null ? $request->category_id : 0);

      if ($request->product_type == 0) {
        $products = $this->getProductsColorByProductType($user_id, $color_id, $product_type)->paginate($this->recordsperpage);
      } else if ($request->product_type == 1) {
        $products = $this->getProductsColorByProductType($user_id, $color_id, $product_type)->paginate($this->recordsperpage);
      } else if ($request->product_type == 2) {
        $products = $this->getProductsColorByProductType($user_id, $color_id, $product_type)->where('is_featured', '=', 1)->paginate($this->recordsperpage);
      } else if ($request->product_type == 3) {
        $products = $this->getProductsColorByProductType($user_id, $color_id, $product_type)->where('is_bestSelling', '=', 1)->paginate($this->recordsperpage);
      } else if ($request->product_type == 4) {
        $products = $this->getProductsColorByProductType($user_id, $color_id, $product_type)->where('is_newArrival', '=', 1)->paginate($this->recordsperpage);
      } else if ($request->product_type == 5) {
        $products = $this->getProductsColorByProductType($user_id, $color_id, $product_type)->where('category_id', '=', $category_id)->paginate($this->recordsperpage);
      }

      $responseData = $this->getProductLists($products, $user_id);

      return response()->json(['data' => $responseData, 'count' => $products->count(), 'total' => $products->total(), 'currentPage' => $products->currentPage(), 'lastPage' => $products->lastPage()], 200);
    } catch (Exception $e) {
      return $this->error($e->getMessage(), $e->getCode());
    }
  }

  public function productSort(Request $request, $sort_by)
  {
    try {

      $user_id = ($request->user_id > 0 ? array($request->user_id) : 0);
      $product_type = ($request->product_type != null ? $request->product_type : 0);
      $category_id = ($request->category_id != null ? $request->category_id : 0);
      if ($request->product_type == 0) {
        $products = $this->getProductsSortByProductType($user_id, $sort_by, $product_type)->paginate($this->recordsperpage);
      } else if ($request->product_type == 1) {
        $products = $this->getProductsSortByProductType($user_id, $sort_by, $product_type)->paginate($this->recordsperpage);
      } else if ($request->product_type == 2) {
        $products = $this->getProductsSortByProductType($user_id, $sort_by, $product_type)->where('is_featured', '=', 1)->paginate($this->recordsperpage);
      } else if ($request->product_type == 3) {
        $products = $this->getProductsSortByProductType($user_id, $sort_by, $product_type)->where('is_bestSelling', '=', 1)->paginate($this->recordsperpage);
      } else if ($request->product_type == 4) {
        $products = $this->getProductsSortByProductType($user_id, $sort_by, $product_type)->where('is_newArrival', '=', 1)->paginate($this->recordsperpage);
      } else if ($request->product_type == 5) {
        $products = $this->getProductsSortByProductType($user_id, $sort_by, $product_type)->where('category_id', '=', $category_id)->paginate($this->recordsperpage);
      }


      $responseData = $this->getProductLists($products, $user_id);

      return response()->json(['data' => $responseData, 'count' => $products->count(), 'total' => $products->total(), 'currentPage' => $products->currentPage(), 'lastPage' => $products->lastPage()], 200);
    } catch (Exception $e) {
      return $this->error($e->getMessage(), $e->getCode());
    }
  }

  public function search(Request $request, $search_value)
  {
    try {

      $user_id = ($request->user_id > 0 ? array($request->user_id) : 0);
      $products = $this->getAllProducts($user_id)
        ->where('products.product_name', 'like', '%' . $search_value . '%')
        ->orWhere('product_sku', 'LIKE', '%' . $search_value . '%')
        ->orderBy('id', 'DESC')->paginate($this->recordsperpage);

      $responseData = $this->getProductLists($products, $user_id);

      return response()->json(['data' => $responseData, 'count' => $products->count(), 'total' => $products->total(), 'currentPage' => $products->currentPage(), 'lastPage' => $products->lastPage()], 200);
    } catch (Exception $e) {
      return $this->error($e->getMessage(), $e->getCode());
    }
  }
}
