<?php

namespace App\Http\Controllers\API\StaffOrder;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\ProductMultipleImage;
use App\Models\ProductVariants;
use App\Models\Slider;
use App\Models\StaffCart;
use App\Models\StaffOrder;
use App\Models\StaffOrderItems;
use App\Traits\ResponseAPI;
use App\Traits\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class StaffOrderController extends Controller
{
    use ResponseAPI;
    use Utils;
    private $recordsperpage = 10;
    public function staffLogin(Request $request)
    {
        $validator = validator::make($request->all(), [
            'staffname' => 'required',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode(",", $validator->errors()->all()), 'isApproved' => true], 400);
        }

        $data = [
            'email' => $request->staffname,
            'password' => $request->password,
        ];
        $staff = Auth::guard('admin');
        if (Auth::guard('admin')->attempt($data)) {
            $token = $staff->user()->createToken('Auth Token')->accessToken;
            $staff_id = $staff->user()->id;
            $staff_name = $staff->user()->name;

            $responseData['status'] = true;
            $responseData['message'] = 'Logged in Successfully';
            $staffCollection = array(
                "id" => $staff_id,
                "name" => $staff_name,
                "token" => $token,
            );
            $responseData['data'] = $staffCollection;
            return response()->json($responseData, 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid Credentials', 'isApproved' => true], 401);
        }
    }

    public function getStaffProfile(Request $request)
    {
        try {
            $responseData = [];
            $staff_details = Admin::where('id', $request->staff_id)->first();

            $responseData['staff_id'] = $staff_details->id;
            $responseData['staff_name'] = $staff_details->name;
            $responseData['staff_email'] = $staff_details->email;

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function slider()
    {
        try {
            $responseData = [];

            $responseData['slider'] = [];
            $slider = Slider::get();
            foreach ($slider as $item) {
                $sliderDetails['id'] = $item->id;
                $sliderDetails['slider_image'] = url($item->slider_image);
                array_push($responseData['slider'], $sliderDetails);
            }
            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function mainCategory()
    {
        try {
            $responseData = [];

            $responseData['main_category'] = [];
            $main_category = MainCategory::get();
            foreach ($main_category as $item) {
                $main_categoryDetails['id'] = $item->id;
                $main_categoryDetails['main_category_name'] = $item->main_category_name;
                $main_categoryDetails['main_category_image'] = url($item->main_category_image);
                array_push($responseData['main_category'], $main_categoryDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

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

    public function category()
    {
        try {
            $responseData = [];

            $responseData['category'] = [];
            $category = Category::get();
            foreach ($category as $item) {
                $categoryDetails['id'] = $item->id;
                $categoryDetails['category_name'] = $item->category_name;
                $categoryDetails['category_image'] = url($item->category_image);
                array_push($responseData['category'], $categoryDetails);
            }

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function productsByCategory($id)
    {
        try {
            $user_id = 0;

            $query = $this->getProducts($user_id)
                ->where('category_id', $id)
                ->inRandomOrder()
                ->orderBy('id', 'DESC');

            $products = $query->paginate($this->recordsperpage);

            $responseData = $this->getProductLists($products, $user_id);

            return response()->json(['data' => $responseData, 'count' => $products->count(), 'total' => $products->total(), 'currentPage' => $products->currentPage(), 'lastPage' => $products->lastPage()], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function productdetail(Request $request, $id)
    {
        try {
            $responseData = [];

            $responseData['product'] = [];
            $product = Product::with('color')->where('products.id', '=', $id)->first();
            $productDetails['id'] = $product->id;

            $cart = StaffCart::where('product_id', $product->id)->where('staff_id', $request->staff_id)->first();

            $category = Category::where('id', $product->category_id)->pluck('category_name')->first();

            $productDetails['category_id'] = $product->category_id;
            $productDetails['category_name'] = $category;
            $productDetails['product_name'] = $product->product_name;
            $productDetails['product_price'] = $product->product_price;
            $productDetails['product_discount'] = $product->product_discount;
            $amount = $product->product_price - $product->product_discount;

            $discount = ($amount / $product->product_price) * 100;
            $productDetails['customer_price'] = $product->product_discount;
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
                $variantsDetail['product_discount'] = $item->customer_price;
                $variantsDetail['customer_price'] = $item->customer_price;
                $amount = $item->price - $item->customer_price;
                $discount = ($amount / $item->price) * 100;

                $variantsDetail['discount_percentage'] = round($discount);
                $variantsDetail['current_stock'] = $item->stock;
                $variantsDetail['product_sku'] = $item->product_sku;

                if ($request->staff_id) {
                    $cart = StaffCart::where('product_id', $product->id)->where('variant_id', $item->id)->where('staff_id', $request->staff_id)->first();
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

            array_push($responseData['product'], $productDetails);

            return response()->json(['data' => $responseData], 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function search($search_value)
    {
        try {
            $user_id = 0;
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

    public function cart(Request $request, $id)
    {
        $variant_id = $request->variant_id;
        if ($variant_id == null) {
            $product = Product::where('is_product_variant', 1)->where('id', $id)->first();
            if ($product) {
                $variant = ProductVariants::where('product_id',  $product->id)->first();
                $variant_id = $variant->id;
            } else {
                $variant_id = null;
            }
        } else {
            $variant = ProductVariants::where('id', $variant_id)->first();
        }
        $product = Product::find($id);
        $exists = StaffCart::where('staff_id', $request->staff_id)->where('variant_id', $request->variant_id ?? $variant_id)->where('product_id', $id)->first();
        if (!$exists) {

            $quantity = $request->qty;
            if ($variant_id) {
                $price = $variant->customer_price;
                $current_stock = $variant->stock;
            } else {
                $price = $product->product_discount;
                $current_stock = $product->current_stock;
            }
            $cart_total = $price * $quantity;

            if ($current_stock < $quantity) {
                return response()->json(['error' => 'You cannot buy more than current stock ' . $current_stock . '']);
            }
            StaffCart::create([
                'product_id' => $id,
                'variant_id' => $variant_id,
                'staff_id' => $request->staff_id,
                'name' => $product->product_name,
                'qty' => $quantity,
                'price' => $price,
                'total' => $cart_total,
                'image' => $product->product_image
            ]);

            $message = "Cart Stored Successfully";
            return response()->json(['status' => true, 'message' => $message], 200);
        } else {
            return response()->json(['status' => false, 'error' => 'Item Already Presented On Your Cart']);
        }
    }

    public function cartList(Request $request)
    {
        $responseData = [];
        $responseData['cart'] = [];

        // StaffCart::join('products', 'products.id', 'staff_carts.product_id')
        //     ->leftjoin('product_variants', 'product_variants.id', 'staff_carts.variant_id')
        //     ->where('staff_id', $request->staff_id)
        //     ->where('current_stock', '<=', 0)
        //     ->where(function ($query) {
        //         $query->where('product_variants.stock', '<=', 0)
        //             ->orWhereNull('staff_carts.variant_id');
        //     })
        //     ->select('staff_carts.*', 'products.current_stock')
        //     ->delete();

        // $cartList = StaffCart::select('staff_carts.*', 'products.current_stock', 'product_variants.stock')
        //     ->leftjoin('product_variants', 'product_variants.id', 'staff_carts.variant_id')
        //     ->join('products', 'products.id', 'staff_carts.product_id')
        //     ->where('staff_id', $request->staff_id)
        //     ->get();

        // foreach ($cartList as $item) {
        //     if ($item->variant_id != null) {
        //         if ($item->qty > $item->stock) {
        //             StaffCart::findOrFail($item->id)->update([
        //                 'qty' => $item->stock,
        //                 'total' => $item->stock * $item->price
        //             ]);
        //         }
        //     } else {
        //         if ($item->qty > $item->current_stock) {
        //             StaffCart::findOrFail($item->id)->update([
        //                 'qty' => $item->current_stock,
        //                 'total' => $item->current_stock * $item->price
        //             ]);
        //         }
        //     }
        // }

        $carts = StaffCart::join('products', 'products.id', 'staff_carts.product_id')
            ->leftjoin('product_variants', 'product_variants.id', 'staff_carts.variant_id')
            ->where('staff_id', $request->staff_id)
            ->where('current_stock', '>=', 0)
            ->where(function ($query) {
                $query->where('product_variants.stock', '>=', 0)
                    ->orWhereNull('staff_carts.variant_id');
            })
            ->select('staff_carts.*', 'products.current_stock', 'products.product_image', 'product_variants.size', 'product_variants.id as variant_id')
            ->get();

        $cart_total = StaffCart::join('products', 'products.id', 'staff_carts.product_id')
            ->leftjoin('product_variants', 'product_variants.id', 'staff_carts.variant_id')
            ->where('staff_id', $request->staff_id)
            ->where('current_stock', '>=', 0)
            ->where(function ($query) {
                $query->where('product_variants.stock', '>=', 0)
                    ->orWhereNull('staff_carts.variant_id');
            })
            ->select('staff_carts.*', 'products.current_stock')
            ->sum('total');

        $quantity = StaffCart::join('products', 'products.id', 'staff_carts.product_id')
            ->leftjoin('product_variants', 'product_variants.id', 'staff_carts.variant_id')
            ->where('staff_id', $request->staff_id)
            ->where('current_stock', '>=', 0)
            ->where(function ($query) {
                $query->where('product_variants.stock', '>=', 0)
                    ->orWhereNull('staff_carts.variant_id');
            })
            ->select('staff_carts.*', 'products.current_stock')
            ->sum('qty');

        foreach ($carts as $item) {
            $variant = ProductVariants::where('id', $item->variant_id)->first();

            $product = Product::where('id', $item->product_id)->pluck('product_image')->first();
            $products = Product::find($item->product_id);
            $cartDetails['cart_id'] = $item->id;
            $cartDetails['staff_id'] = $item->staff_id;
            $cartDetails['product_id'] = $item->product_id;
            $cartDetails['name'] = $item->name;
            $cartDetails['variant_id'] = $item->variant_id;
            $cartDetails['size'] = $variant ? $variant->size : null;
            $cartDetails['qty'] = $item->qty;
            $cartDetails['product_price'] = $variant ? $variant->price : $products->product_price;
            $cartDetails['product_discount'] = $variant ? $variant->customer_price : $products->product_discount;
            $cartDetails['available_quantity'] = $variant ? $variant->stock : $products->current_stock;
            $cartDetails['image'] = url($product);

            array_push($responseData['cart'], $cartDetails);
        }

        return response()->json(['data' => $responseData, 'quantity' => $quantity, 'total' => $cart_total], 200);
    }

    public function CartIncrement($id)
    {
        $row = StaffCart::find($id);
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
        StaffCart::findOrFail($id)->update([
            'qty' => $qtys,
            'total' => $cart_total
        ]);

        $message = "Quantity Increased Successfully";
        return response()->json(['status' => true, 'message' => $message], 200);
    }

    public function CartDecrement($id)
    {
        $row = StaffCart::find($id);
        $qtys = $row->qty - 1;
        $cart_total = $row->price * $qtys;
        StaffCart::findOrFail($id)->update([
            'qty' => $qtys,
            'total' => $cart_total
        ]);
        $message = "Quantity Decreased Successfully";
        return response()->json(['status' => true, 'message' => $message], 200);
    }

    public function cartDelete($id)
    {
        $cartDelete = StaffCart::find($id);
        $cartDelete->delete();
        $message = "Cart Removed Successfully";
        return response()->json(['status' => true, 'message' => $message], 200);
    }

    public function placeOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('products')->lockForUpdate()->get();
            $cart_true = $request->cart_true;
            // $shippingCharge = $request->shipping_charge;

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
                $totQty = StaffCart::where('staff_id', $request->staff_id)->sum('qty');
            } else {
                $total_amount = $buy_total;
                $sub_total = $buy_price;
                $totQty = $buy_product_qty;
            }

            $order_id = StaffOrder::insertGetId([
                'staff_id' => $request->staff_id,
                'name' => $request->name,
                'email' => $request->email,
                'payment_type' => 'Staff Order',
                'payment_status' => 'Unpaid',
                'currency' =>  'INR',
                'amount' => $total_amount,
                'sub_total' => $sub_total,
                'order_number' => 'NTXOR' . mt_rand(10000000, 99999999),
                'order_date' => Carbon::now()->format('d F Y'),
                'order_month' => Carbon::now()->format('F'),
                'order_year' => Carbon::now()->format('Y'),
                'status' => 'pending',
                'tot_Qty' => $totQty,
                'note' => $request->note,
                'created_at' => Carbon::now(),
                // 'door_no' => $request->door_no,
                // 'street_address' => $request->street_address,
                // 'city_name' => $request->city_name,
                // 'state_name' => $request->state_name,
                // 'phone' => $request->phone,
                // 'alternative_number' => $request->alternative_number,
                // 'pin_code' => $request->pin_code,
                // 'r_order_id' => $request->r_order_id,
                // 'r_payment_id' => $request->r_payment_id,
                // 'shipping_charge' => $shippingCharge,
            ]);

            if ($request->cart_true == 1) {

                $out_of_stock = $this->validateStaffOutOfStock(1, $request->staff_id, 0, 0);
                // dd($out_of_stock);
                if ($out_of_stock) {
                    throw new Exception("something happened");
                }

                $carts = StaffCart::where('staff_id', $request->staff_id)->get();
                foreach ($carts as $cart) {
                    $variant = ProductVariants::where('id', $cart->variant_id)->first();
                    StaffOrderItems::insert([
                        'staff_order_id' => $order_id,
                        'product_id' => $cart->product_id,
                        'qty' => $cart->qty,
                        'price' => $cart->total,
                        'variant_size' => $variant->size ?? null,
                        'created_at' => Carbon::now()
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

                $out_of_stock = $this->validateStaffOutOfStock(0, $request->staff_id, $buy_product_id, $buy_product_qty, $buy_variant_id);
                // dd($out_of_stock);
                if ($out_of_stock) {
                    throw new Exception("something happened");
                }

                $variant = ProductVariants::where('id', $buy_variant_id)->first();
                StaffOrderItems::insert([
                    'staff_order_id' => $order_id,
                    'product_id' => $buy_product_id,
                    'qty' => $buy_product_qty,
                    'price' => $buy_price,
                    'variant_size' => $variant->size ?? null,
                    'created_at' => Carbon::now()
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

            if ($request->cart_true == 1) {
                $rowId = StaffCart::where('staff_id', $request->staff_id)->get();
                StaffCart::destroy($rowId);
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

    public function orderList(Request $request)
    {
        try {
            $responseData = [];

            $responseData['orders'] = [];
            $staffOrder = StaffOrder::where('staff_id', $request->staff_id)->orderby('id', 'DESC')->get();

            foreach ($staffOrder as $item) {
                $orderDetails['order_id'] = $item->id;
                $orderDetails['order_number'] = $item->order_number;
                $orderDetails['qty'] = $item->tot_Qty;
                $orderDetails['order_date'] = ($item->created_at)->format('d/m/y');
                $orderDetails['sub_total'] = $item->sub_total;
                $orderDetails['payment_type'] = $item->payment_type;
                $orderDetails['status'] = $item->status;
                $orderDetails['total'] = $item->amount;
                $orderDetails['status'] = $item->status;

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

            $orders = StaffOrder::where('id', $order_id)->first();

            $invoicedetails['order_date'] = ($orders->created_at)->format('d/m/y');
            $invoicedetails['order_id'] = $order_id;
            $invoicedetails['order_number'] = $orders->order_number;
            $invoicedetails['sub_total'] = $orders->sub_total;
            $invoicedetails['amount'] = $orders->amount;
            $invoicedetails['payment_type'] = $orders->payment_type;
            $invoicedetails['status'] = $orders->status;
            $invoicedetails['name'] = $orders->name;
            $invoicedetails['tot_Qty'] = $orders->tot_Qty;
            $invoicedetails['notes'] = $orders->note;


            $responseData['orders_detail'] = [];
            $order = StaffOrderItems::where('staff_order_id', $order_id)->get();
            foreach ($order as $item) {
                $orderdetails['product_id'] = $item->product_id;
                $orderdetails['qty'] = $item->qty;
                $orderdetails['product_size'] = $item->variant_size;

                $products = Product::where('id', $item->product_id)->get();
                foreach ($products as $product) {
                    $orderdetails['product_name'] = $product->product_name;
                    $orderdetails['product_price'] = round($item->price / $item->qty);
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

    public function orderSummary(Request $request)
    {
        try {
            $responseData = [];
            $responseData['order_summary'] = [];
            $order_details = StaffCart::select('products.id', 'staff_carts.qty', 'staff_carts.variant_id', 'staff_carts.total', 'products.product_name', 'products.product_image', 'staff_carts.price', 'product_variants.size')
            ->leftjoin('product_variants', 'product_variants.id', 'staff_carts.variant_id')
                ->join('products', 'staff_carts.product_id', 'products.id')
                ->where('staff_id', '=', $request->staff_id)
                ->get();

            foreach ($order_details as $item) {

                $orderDetails['product_id'] = $item->id;
                $orderDetails['qty'] = $item->qty;
                $orderDetails['product_name'] = $item->product_name;
                $orderDetails['product_size'] = $item->size;
                $orderDetails['product_image'] = url($item->product_image);
                $orderDetails['price'] = $item->price;

                array_push($responseData['order_summary'], $orderDetails);
            }
            $responseData['orders_total'] = [];
            $total = StaffCart::where('staff_id', $request->staff_id)->sum('total');
            $qty = StaffCart::where('staff_id', $request->staff_id)->sum('qty');
            $totalDetails['subtotal'] = $total;
            $totalDetails['total'] = $total;
            $totalDetails['qty'] = $qty;

            array_push($responseData['orders_total'], $totalDetails);

            return response()->json(['data' => $responseData], 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
