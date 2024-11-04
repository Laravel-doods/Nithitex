<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ResponseAPI;
use App\Models\Cart;
use App\Models\Category;
use App\Models\ProductVariants;
use App\Traits\Utils;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use ResponseAPI;
    use Utils;

    public function cart(Request $request, $id)
    {
        $offer = $request->offer ?? 0;
        $variant_id = $request->variant_id;
        if ($variant_id == null) {
            $product = Product::where('is_product_variant', 1)->where('id', $id)->first();
            if ($product) {
                $variant = ProductVariants::where('product_id',  $product->id)->where('stock', '!=', 0)->first();
                if (!$variant) {
                    return response()->json(['error' => 'All variants of this product are currently out of stock.']);
                }
                $variant_id = $variant->id;
            } else {
                $variant_id = null;
            }
        } else {
            $variant = ProductVariants::where('id', $variant_id)->first();
        }

        $product = Product::find($id);
        $exists = Cart::where('user_id', Auth::id())->where('variant_id', $request->variant_id ?? $variant_id)->where('product_id', $id)->first();
        if (!$exists) {

            $quantity = $request->qty;

            if ($offer == 1) {
                $category = Category::where('id', $product->category_id)->first();
                

                if ($variant_id) {
                    $price =  $variant->price - ($variant->price * $category->offer / 100);
                    $current_stock = $variant->stock;
                } else {
                    $price =  $product->product_price - ($product->product_price * $category->offer / 100);
                    $current_stock = $product->current_stock;
                }
            } else {
                if ($variant_id) {
                    $price = (Auth::user()->userrole_id == 1 ? $variant->customer_price : $variant->seller_price);
                    $current_stock = $variant->stock;
                } else {
                    $price = (Auth::user()->userrole_id == 1 ? $product->product_discount : $product->seller_discount);
                    $current_stock = $product->current_stock;
                }
            }

            $cart_total = $price * $quantity;

            if ($current_stock < $quantity) {
                return response()->json(['error' => 'You cannot add more than current stock ' . $current_stock . '']);
            }
            Cart::create([
                'product_id' => $id,
                'variant_id' => $variant_id,
                'user_id' => Auth::user()->id,
                'is_offer_product' => $offer,
                'name' => $product->product_name,
                'qty' => $quantity,
                'price' => $price,
                'total' => $cart_total,
                'image' => $product->product_image
            ]);

            $message = "Product added to cart successfully";
            return response()->json(['status' => true, 'message' => $message], 200);
        } else {
            return response()->json(['status' => false, 'error' => 'This product already added in cart']);
        }
    }

    public function cartList()
    {
        $responseData = [];
        $responseData['cart'] = [];

        // Cart::join('products', 'products.id', 'carts.product_id')
        //     ->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
        //     ->where('user_id', Auth::user()->id)
        //     ->where('current_stock', '<=', 0)
        //     ->where(function ($query) {
        //         $query->where('product_variants.stock', '<=', 0)
        //             ->orWhereNull('carts.variant_id');
        //     })
        //     ->select('carts.*', 'products.current_stock')
        //     ->delete();

        // $cartList = Cart::select('carts.*', 'products.current_stock', 'product_variants.stock')
        //     ->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
        //     ->join('products', 'products.id', 'carts.product_id')
        //     ->where('user_id', Auth::user()->id)
        //     ->get();

        // foreach ($cartList as $item) {
        //     if ($item->variant_id != null) {
        //         if ($item->qty > $item->stock) {
        //             Cart::findOrFail($item->id)->update([
        //                 'qty' => $item->stock,
        //                 'total' => $item->stock * $item->price
        //             ]);
        //         }
        //     } else {
        //         if ($item->qty > $item->current_stock) {
        //             Cart::findOrFail($item->id)->update([
        //                 'qty' => $item->current_stock,
        //                 'total' => $item->current_stock * $item->price
        //             ]);
        //         }
        //     }
        // }

        $carts = Cart::join('products', 'products.id', 'carts.product_id')
            ->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
            ->where('user_id', Auth::user()->id)
            ->where('current_stock', '>=', 0)
            ->where(function ($query) {
                $query->where('product_variants.stock', '>=', 0)
                    ->orWhereNull('carts.variant_id');
            })
            ->select('carts.*', 'products.current_stock', 'products.product_image', 'product_variants.size', 'product_variants.id as variant_id')
            ->get();

        $cart_total = Cart::join('products', 'products.id', 'carts.product_id')
            ->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
            ->where('user_id', Auth::user()->id)
            ->where('current_stock', '>=', 0)
            ->where(function ($query) {
                $query->where('product_variants.stock', '>=', 0)
                    ->orWhereNull('carts.variant_id');
            })
            ->select('carts.*', 'products.current_stock')
            ->sum('total');

        $quantity = Cart::join('products', 'products.id', 'carts.product_id')
            ->leftjoin('product_variants', 'product_variants.id', 'carts.variant_id')
            ->where('user_id', Auth::user()->id)
            ->where('current_stock', '>=', 0)
            ->where(function ($query) {
                $query->where('product_variants.stock', '>=', 0)
                    ->orWhereNull('carts.variant_id');
            })
            ->select('carts.*', 'products.current_stock')
            ->sum('qty');


        foreach ($carts as $item) {
            $variant = ProductVariants::where('id', $item->variant_id)->first();

            $product = Product::where('id', $item->product_id)->pluck('product_image')->first();
            $products = Product::find($item->product_id);
            $cartDetails['cart_id'] = $item->id;
            $cartDetails['user_id'] = $item->user_id;
            $cartDetails['product_id'] = $item->product_id;
            $cartDetails['name'] = $item->name;
            $cartDetails['variant_id'] = $item->variant_id;
            $cartDetails['size'] = $variant ? $variant->size : null;
            $cartDetails['qty'] = $item->qty;
            $cartDetails['product_price'] = $variant ? $variant->price : $products->product_price;
            if ($this->getRoleId(Auth::user()->id) == 2) {
                if ($item->is_offer_product == 1) {
                    $cartDetails['product_discount'] = $item->price;
                } else {
                    $cartDetails['product_discount'] = $variant ? $variant->seller_price : $products->seller_discount;
                }
            } else {
                if ($item->is_offer_product == 1) {
                    $cartDetails['product_discount'] = $item->price;
                } else {
                    $cartDetails['product_discount'] = $variant ? $variant->customer_price : $products->product_discount;
                }
            }
            $cartDetails['available_quantity'] = $variant ? $variant->stock : $products->current_stock;
            $cartDetails['image'] = url($product);
            $cartDetails['is_today_offer'] = $item->is_offer_product;

            array_push($responseData['cart'], $cartDetails);
        }

        return response()->json(['data' => $responseData, 'quantity' => $quantity, 'total' => $cart_total], 200);
    }

    public function CartIncrement($id)
    {
        $row = Cart::find($id);
        $product_id = $row->product_id;
        $variant_id = $row->variant_id;
        $product = Product::find($product_id);
        $variant = ProductVariants::find($variant_id);

        $qtys = $row->qty + 1;
        if ($row->variant_id != null) {
            $current_stock = $variant->stock;
        } else {
            $current_stock = $product->current_stock;
        }

        if ($current_stock < $qtys) {
            return response()->json(['error' => 'You cannot add more than current stock ' . $current_stock . '']);
        }
        $cart_total = $row->price * $qtys;
        Cart::findOrFail($id)->update([
            'qty' => $qtys,
            'total' => $cart_total
        ]);

        $message = "Quantity Increased Successfully";
        return response()->json(['status' => true, 'message' => $message], 200);
    }

    public function CartDecrement($id)
    {
        $row = Cart::find($id);

        $qtys = $row->qty - 1;
        if ($qtys < 1) {
            return response()->json(['error' => 'You cannot remove more than ' . $row->qty . ' product']);
        }

        $cart_total = $row->price * $qtys;
        Cart::findOrFail($id)->update([
            'qty' => $qtys,
            'total' => $cart_total
        ]);
        $message = "Quantity Decreased Successfully";
        return response()->json(['status' => true, 'message' => $message], 200);
    }

    public function cartDelete($id)
    {
        $cartDelete = Cart::find($id);
        $cartDelete->delete();
        $message = "Cart Removed Successfully";
        return response()->json(['status' => true, 'message' => $message], 200);
    }
}
