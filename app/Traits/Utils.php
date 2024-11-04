<?php

namespace App\Traits;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\FcmToken;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Models\ReferralSettings;
use App\Models\StaffCart;
use App\Models\User;
use App\Models\UserReferralHistory;
use App\Models\Wishlist;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

trait Utils
{
    public $errorMessage = "Something went wrong!. Please try again.";
    public function getBaseUrl()
    {
        return url('/');
    }
    /**
     * This will return all products
     */
    public function getAllProducts($user_id)
    {
        if ($user_id == 0) {
            $query = Product::where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        } else {
            $query = Product::select('wishlists.is_favourite', 'wishlists.variant_id', 'wishlists.user_id', 'products.*');
            $query->leftJoin('wishlists', function ($join) {
                $join->on('wishlists.product_id', '=', 'products.id')
                    ->where("wishlists.user_id", '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), $user_id));

            $query = $query->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        }

        return $query;
    }

    /**
     * This will return products without offers
     */
    public function getProducts($user_id)
    {
        if ($user_id == 0) {
            $query = Product::where('is_offers', '=', 0)
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        } else {
            $query = Product::select('wishlists.is_favourite', 'wishlists.user_id', 'products.*');
            $query->leftJoin('wishlists', function ($join) {
                $join->on('wishlists.product_id', '=', 'products.id')
                    ->where("wishlists.user_id", '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), $user_id));

            $query = $query->where('is_offers', '=', 0)
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        }

        return $query;
    }
    /**
     * This will return offer products only 
     */
    public function getOfferProducts($user_id)
    {
        if ($user_id == 0) {
            $query = Product::where('is_offers', '=', 1)
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        } else {
            $query = Product::select('wishlists.is_favourite', 'wishlists.user_id', 'products.*');
            $query->leftJoin('wishlists', function ($join) {
                $join->on('wishlists.product_id', '=', 'products.id')
                    ->where("wishlists.user_id", '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), $user_id));

            $query = $query->where('is_offers', '=', 1)
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        }
        return $query;
    }

    /**
     * This will return single product details
     */
    public function getProductDetails($id, $user_id)
    {
        $isoffer = Product::where('id', $id)->pluck('is_offers')->first();
        if ($user_id == 0) {
            $query = Product::where('current_stock', '>=', 0)
                ->where('products.id', '=', $id);
        } else {
            $query = Product::select('wishlists.is_favourite', 'wishlists.user_id', 'products.*');
            $query->leftJoin('wishlists', function ($join) {
                $join->on('wishlists.product_id', '=', 'products.id')
                    ->where("wishlists.user_id", '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), $user_id));

            $query = $query->where('current_stock', '>=', 0)
                ->where('products.id', '=', $id);
        }
        if ($isoffer) {
            $query = $query->where('is_offers', '=', 1);
        } else {
            $query = $query->where('is_offers', '=', 0);
        }

        return $query;
    }
    /**
     * This will return related products under category except viewing product
     */
    public function getRelatedProducts($cat_id, $id, $user_id)
    {
        if ($user_id == 0) {
            $query = Product::where('is_offers', '=', 0)
                ->where('current_stock', '>=', 0)
                ->where('products.id', '!=', $id)
                ->where('products.category_id', $cat_id)
                ->orderBy('current_stock', 'DESC')
                ->inRandomOrder()
                ->limit(15);
        } else {
            $query = Product::select('wishlists.is_favourite', 'wishlists.user_id', 'products.*');
            $query->leftJoin('wishlists', function ($join) {
                $join->on('wishlists.product_id', '=', 'products.id')
                    ->where("wishlists.user_id", '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), $user_id));

            $query = $query->where('is_offers', '=', 0)
                ->where('current_stock', '>=', 0)
                ->where('products.id', '!=', $id)
                ->where('products.category_id', $cat_id)
                ->orderBy('current_stock', 'DESC')
                ->inRandomOrder()
                ->limit(15);
        }
        return $query;
    }
    /**
     * This will return products by colors
     */
    public function getProductsByColor($user_id, $sel_color)
    {
        if ($user_id == 0) {
            $query = Product::where('is_offers', '=', 0)
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        } else {
            $query = Product::select('wishlists.is_favourite', 'wishlists.user_id', 'products.*');
            $query->leftJoin('wishlists', function ($join) {
                $join->on('wishlists.product_id', '=', 'products.id')
                    ->where("wishlists.user_id", '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), $user_id));

            $query = $query->where('is_offers', '=', 0)
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        }

        if ($sel_color != "") {
            if ($user_id == 0) {
                $query = $query->join('colors', 'colors.id', 'products.color_id')
                    ->where('color_id', $sel_color)
                    ->select('products.*', 'colors.color_name');
            } else {
                $query = $query->join('colors', 'colors.id', 'products.color_id')
                    ->where('color_id', $sel_color)
                    ->select('wishlists.is_favourite', 'wishlists.user_id', 'products.*', 'colors.color_name');
            }
        }
        return $query;
    }

    /**
     * This will return products by colors against product types(all (0), offers (1), featured (2), bestselling (3) and new arrivals (4))
     */
    public function getProductsColorByProductType($user_id, $sel_color, $product_type)
    {
        if ($user_id == 0) {
            $query = Product::where('is_offers', '=', ($product_type == 1 ? 1 : 0))
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        } else {
            $query = Product::select('wishlists.is_favourite', 'wishlists.user_id', 'products.*');
            $query->leftJoin('wishlists', function ($join) {
                $join->on('wishlists.product_id', '=', 'products.id')
                    ->where("wishlists.user_id", '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), $user_id));

            $query = $query->where('is_offers', '=', ($product_type == 1 ? 1 : 0))
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        }

        if ($sel_color != "") {
            if ($user_id == 0) {
                $query = $query->join('colors', 'colors.id', 'products.color_id')
                    ->where('color_id', $sel_color)
                    ->select('products.*', 'colors.color_name');
            } else {
                $query = $query->join('colors', 'colors.id', 'products.color_id')
                    ->where('color_id', $sel_color)
                    ->select('wishlists.is_favourite', 'wishlists.user_id', 'products.*', 'colors.color_name');
            }
        }
        return $query;
    }

    /**
     * This will return products by sort order (latest product, product name, price low to high, price high to low, qty low to high, qty high to low)
     */
    public function getProductsBySort($user_id, $sort_by)
    {
        if ($user_id == 0) {
            $query = Product::where('is_offers', '=', 0)
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        } else {
            $query = Product::select('wishlists.is_favourite', 'wishlists.user_id', 'products.*');
            $query->leftJoin('wishlists', function ($join) {
                $join->on('wishlists.product_id', '=', 'products.id')
                    ->where("wishlists.user_id", '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), $user_id));

            $query = $query->where('is_offers', '=', 0)
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        }

        if ($sort_by == 'latest_product') {
            $query = $query->orderBy('id', 'DESC');
        } elseif ($sort_by == 'product_name') {
            $query = $query->orderBy('product_name', 'ASC');
        } elseif ($sort_by == 'p_low_to_high') {
            $query = $query->orderBy('product_discount', 'ASC');
        } elseif ($sort_by == 'p_high_to_low') {
            $query = $query->orderBy('product_discount', 'DESC');
        } elseif ($sort_by == 'q_low_to_high') {
            $query = $query->orderBy('current_stock', 'ASC');
        } elseif ($sort_by == 'q_high_to_low') {
            $query = $query->orderBy('current_stock', 'DESC');
        }
        return $query;
    }

    /**
     * This will return products by sort order against product types(all (0), offers (1), featured (2), bestselling (3) and new arrivals (4))
     */
    public function getProductsSortByProductType($user_id, $sort_by, $product_type)
    {
        if ($user_id == 0) {
            $query = Product::where('is_offers', '=', ($product_type == 1 ? 1 : 0))
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        } else {
            $query = Product::select('wishlists.is_favourite', 'wishlists.user_id', 'products.*');
            $query->leftJoin('wishlists', function ($join) {
                $join->on('wishlists.product_id', '=', 'products.id')
                    ->where("wishlists.user_id", '=', DB::raw('?'));
            })->setBindings(array_merge($query->getBindings(), $user_id));

            $query = $query->where('is_offers', '=', ($product_type == 1 ? 1 : 0))
                ->where('current_stock', '>=', 0)
                ->orderBy('current_stock', 'DESC');
        }

        if ($sort_by == 'latest_product') {
            $query = $query->orderBy('id', 'DESC');
        } elseif ($sort_by == 'product_name') {
            $query = $query->orderBy('product_name', 'ASC');
        } elseif ($sort_by == 'p_low_to_high') {
            $query = $query->orderBy('product_discount', 'ASC');
        } elseif ($sort_by == 'p_high_to_low') {
            $query = $query->orderBy('product_discount', 'DESC');
        } elseif ($sort_by == 'q_low_to_high') {
            $query = $query->orderBy('current_stock', 'ASC');
        } elseif ($sort_by == 'q_high_to_low') {
            $query = $query->orderBy('current_stock', 'DESC');
        }
        return $query;
    }

    /**
     * This will return product details as list array
     */
    public function getProductLists($products, $user_id, $offer = null)
    {
        $responseData = [];
        $responseData['product_list'] = [];

        foreach ($products as $item) {
            $categories = Category::where('id', $item->category_id)->first();

            if ($offer == 1) {
                $price = $item->product_price - ($item->product_price * $categories->offer / 100);
                $is_today_offer = true;
            } else {
                $is_today_offer = false;
            }

            // $variant = ProductVariants::where('id', $item->variant_id)->first();
            $productDetails['product_id'] = $item->id;
            $productDetails['category_id'] = $item->category_id;

            $productDetails['category_name'] = $categories->category_name;
            $productDetails['product_name'] = $item->product_name;
            // $productDetails['variant_id'] = $variant ? $item->variant_id : null;
            // $productDetails['size'] = $variant ? $variant->size : null;
            $productDetails['product_price'] = $item->product_price;
            if ($this->getRoleId($user_id) == 2) {
                $productDetails['product_discount'] = $offer == 1 ? (string)$price : $item->seller_discount;
            } else {
                $productDetails['product_discount'] = $offer == 1 ? (string)$price : $item->product_discount;
            }
            $productDetails['product_image'] = $item->product_image ? url($item->product_image) : null;
            $productDetails['is_favourite'] = $item->is_favourite;
            $productDetails['is_today_offer'] = $is_today_offer;
            array_push($responseData['product_list'], $productDetails);
        }

        return $responseData;
    }
    /**
     * This will return role id for given user id
     */
    public function getRoleId($user_id)
    {
        return User::where('id', $user_id)->pluck('userrole_id')->first();
    }
    /**
     * This will return role id for given user email
     */
    public function getRoleIdByEmail($email)
    {
        return User::where('email', $email)->pluck('userrole_id')->first();
    }
    /**
     * This will return role id for given user phone
     */
    public function getRoleIdByPhone($phone)
    {
        return User::where('phone', $phone)->pluck('userrole_id')->first();
    }

    /**
     * This will check the admin panel menu permissions for staffs
     */
    public function checkUserPermission($formPermission, $user_id)
    {
        if ($formPermission || $user_id == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function couponCalc($coupon_code, $userrole_id, $user_coupon_id)
    {
        $current_date = Carbon::today();
        $coupon_id = Coupon::where('coupon_code', $coupon_code)->pluck('id')->first();
        if ($userrole_id == 2) {
            if ($user_coupon_id == $coupon_id) {
                return User::join('coupons', 'coupons.id', 'users.coupon_id')->where('coupon_id', $coupon_id)
                    ->where('coupons.start_date', '<=', $current_date)
                    ->where('coupons.end_date', '>=', $current_date)
                    ->first();
            } else {
                return Coupon::where('id', $coupon_id)->where('coupons.start_date', '<=', $current_date)
                    ->where('coupons.end_date', '>=', $current_date)
                    ->where('is_common', 1)->where('is_active', 1)
                    ->first();
            }
        } else {
            return Coupon::where('coupon_code', $coupon_code)
                ->where('start_date', '<=', $current_date)
                ->where('end_date', '>=', $current_date)
                ->where('is_active', 1)->where('is_common', 1)
                ->first();
        }
    }

    public function validateOutOfStock($cart_true, $user_id, $buy_product_id, $buy_product_qty, $variant_id = null)
    {
        if ($cart_true == 1) {
            $carts = Cart::where('user_id', $user_id)->get();
            // dd($carts);
            if ($carts->count() > 0) {
                foreach ($carts as $cart) {
                    $product = Product::where('id', $cart->product_id)->first();

                    if ($cart->variant_id != null && $product->is_product_variant == 1) {

                        $variant = ProductVariants::find($cart->variant_id);
                        if ($cart->qty > $variant->stock) {
                            return true;
                        }
                    } else {
                        if ($cart->qty > $product->current_stock) {
                            return true;
                        }
                    }
                }
            } else {
                return true;
            }
        } else {
            $product = Product::where('id', $buy_product_id)->first();
            if ($product->is_product_variant == 1 && $variant_id != 0) {
                $variant = ProductVariants::find($variant_id);
                if ($buy_product_qty > $variant->stock) {
                    return true;
                }
            } else {
                if ($buy_product_qty > $product->current_stock) {
                    return true;
                }
            }
        }
        return false;
    }

    public function validateStaffOutOfStock($cart_true, $staff_id, $buy_product_id, $buy_product_qty)
    {
        if ($cart_true == 1) {
            $carts = StaffCart::where('staff_id', $staff_id)->get();
            // dd($carts);
            if ($carts->count() > 0) {
                foreach ($carts as $cart) {
                    $product = Product::where('id', $cart->product_id)->first();
                    // dd($product->current_stock >= 0 && $cart->qty >= $product->current_stock);
                    if ($cart->qty > $product->current_stock) {
                        return true;
                    }
                }
            } else {
                return true;
            }
        } else {
            $product = Product::where('id', $buy_product_id)->first();
            if ($buy_product_qty > $product->current_stock) {
                return true;
            }
        }
        return false;
    }

    public function generateRandom($digit)
    {
        $min = pow(10, $digit - 1);
        $max = pow(10, $digit) - 1;
        return rand($min, $max);
    }

    public function fileUpload($fileinput, $filepath, $fileName)
    {
        $fileinput->move(public_path($filepath), $fileName);
        return $filepath . '/' . $fileName;
    }

    public function getReferralSetting()
    {
        return ReferralSettings::first();
    }

    public function generateReferralCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while (\App\Models\User::where('referral_code', $code)->exists());

        return $code;
    }

    public function addReferralHistory($user_id, $referred_by)
    {
        UserReferralHistory::create([
            'user_id' => $user_id,
            'referred_by' => $referred_by,
            'referred_on' => Carbon::today()
        ]);
    }
    public function addReferralPoints($user_id)
    {
        $earnpoints_per_referral = $this->getReferralSetting()->earnpoints_per_referral;

        User::where('id', $user_id)->update([
            'referral_points' => DB::raw('referral_points + ' . $earnpoints_per_referral)
        ]);
    }

    public function addReferrerPoints($referred_by)
    {
        $earnpoints_per_referrer = $this->getReferralSetting()->earnpoints_per_referrer;

        User::where('id', $referred_by)->update([
            'referral_points' => DB::raw('referral_points + ' . $earnpoints_per_referrer)
        ]);
    }

    public function getProductVariant($id)
    {
        return ProductVariants::where('product_id', $id)->where('stock', '>=', 0)->get();
    }

    public function getProductGroup($group_id)
    {
        return Product::where('group_id', $group_id)->get();
    }

    private function calculatePrice($product, $variant, $offer)
    {
        if ($offer == 1) {
            return $variant
                ? (int)$variant->price - ((int)$variant->price * $product->category->offer / 100)
                : (int)$product->product_price - ((int)$product->product_price * $product->category->offer / 100);
        } else {
            if (Auth::check()) {
                return $variant
                    ? (Auth::user()->userrole_id == 1 ? $variant->customer_price : $variant->seller_price)
                    : (Auth::user()->userrole_id == 1 ? $product->product_discount : $product->seller_discount);
            } else {
                return $variant ? $variant->price : $product->product_price;
            }
        }
    }

    private function updateGuestToUser($device_id)
    {
        $carts = Cart::where('device_id', $device_id)->get();
        if ($carts) {
            foreach ($carts as $cart) {
                $exist = Cart::where('user_id', Auth::id())->where('product_id', $cart->product_id)->where('variant_id', $cart->variant_id)->first();
                if ($exist) {
                    $exist->delete();
                }
                $cart->device_id = null;
                if (Auth::user()->userrole_id == 1) {
                    $cart->user_id = Auth::id();
                } else {
                    $cart->seller_id = Auth::id();
                }
                $cart->update();
            }
        }
        $wishlists = Wishlist::where('device_id', $device_id)->get();
        if ($wishlists) {
            foreach ($wishlists as $wishlist) {
                $exist = Wishlist::where('user_id', Auth::id())->where('product_id', $wishlist->product_id)->where('variant_id', $cart->variant_id)->first();
                if ($exist) {
                    $exist->delete();
                }
                $wishlist->device_id = null;
                $wishlist->user_id = Auth::id();
                $wishlist->update();
            }
        }
    }

    private function updateAppGuestToUser($data)
    {
        $carts = $data->cart;
        $wishlists = $data->wishlist;

        foreach ($carts as $cart) {
            $exist = Cart::where('user_id', Auth::id())->where('product_id', $cart['product_id'])->where('variant_id', $cart['variant_id'])->first();
            if ($exist) {
                $exist->delete();
            }
            $product = Product::where('id', $cart['product_id'])->first();
            if ($product) {
                $price = $product->product_discount;
                if ($cart['variant_id']) {
                    $variant = ProductVariants::where('id', $cart['variant_id'])->first();
                    if ($variant) {
                        $price = $variant->customer_price;
                    }
                }
                $total = $price * $cart['qty'];
                Cart::create([
                    'product_id' => $cart['product_id'],
                    'variant_id' => $cart['variant_id'],
                    'user_id' => Auth::user()->id,
                    'name' => $product->product_name,
                    'qty' => $cart['qty'],
                    'price' => $price,
                    'total' => $total,
                    'image' => $product->product_image
                ]);
            }
        }

        foreach ($wishlists as $wishlist) {
            $exist = Wishlist::where('user_id', Auth::id())->where('product_id', $wishlist['product_id'])->first();
            if (!$exist) {
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'product_id' => $wishlist['product_id'],
                    'is_favourite' => 1,
                    'created_at' => Carbon::now(),
                ]);
            }
        }
    }

    public function updateFCMToken($token)
    {
        $exist_token = FcmToken::where('token', $token)->first();

        if ($exist_token) {
            if ($exist_token->user_id != Auth::id()) {
                $exist_token->user_id = Auth::id();
                $exist_token->save();
            }
        } else {
            FcmToken::create([
                'user_id' => Auth::id(),
                'token' => $token
            ]);
        }
    }

    public function getAccessToken()
    {
        $credentialsPath = public_path('nithitex-8c776-4f44eee0f8a3.json');
        $credentials = json_decode(file_get_contents($credentialsPath), true);

        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $jwtHeader = ["alg" => "RS256", "typ" => "JWT"];

        $now = time();
        $jwtPayload = [
            "iss" => $credentials['client_email'],
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => $tokenUrl,
            "iat" => $now,
            "exp" => $now + 3600
        ];

        $headerEncoded = rtrim(strtr(base64_encode(json_encode($jwtHeader)), '+/', '-_'), '=');
        $payloadEncoded = rtrim(strtr(base64_encode(json_encode($jwtPayload)), '+/', '-_'), '=');

        $signature = '';
        openssl_sign("$headerEncoded.$payloadEncoded", $signature, $credentials['private_key'], 'SHA256');
        $jwt = "$headerEncoded.$payloadEncoded." . rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        $postData = [
            "grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
            "assertion" => $jwt
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        curl_close($ch);

        $token = json_decode($response, true);
        return $token['access_token'] ?? null;
    }

    public function pushNotification($fcmtokens, $title, $body, $notifyImage, $action)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return false;
        }

        foreach ($fcmtokens as $token) {
            $response = Http::withToken($accessToken)
                ->post('https://fcm.googleapis.com/v1/projects/nithitex-8c776/messages:send', [
                    'message' => [
                        'token' => $token->token,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                            'image' => $notifyImage,
                        ],
                        'webpush' => [
                            'fcm_options' => [
                                'link' => $action,
                            ],
                        ],
                    ],
                ]);
            $this->createNotification($token->user_id, $title, $body, $notifyImage);

            if ($response->failed()) {
                return false;
            }
        }

        return true;
    }

    private function createNotification($user_id, $title, $content, $image)
    {
        Notification::create([
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'image' => $image
        ]);
    }

    private function fileUploadS3Bucket($filePath, $image, $is_pdf = 0)
    {
        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $uploadParams = [
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => $filePath,
            'Body'   => (string) $image,
            'ACL'    => 'public-read',
        ];

        if ($is_pdf === 1) {
            $uploadParams['ContentType'] = 'application/pdf';
        }

        $result = $s3->putObject($uploadParams);

        // Get the S3 URL
        $save_url = $result->get('ObjectURL');

        return $save_url;
    }

    private function deleteFromS3Bucket($filePath)
    {
        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $filePath = ltrim($filePath, '/');

        $result = $s3->deleteObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => $filePath,
        ]);
    }

    private function checkFileExistsOnS3($filePath)
    {
        try {
            $s3 = new S3Client([
                'version' => 'latest',
                'region' => env('AWS_DEFAULT_REGION'),
                'credentials' => [
                    'key'    => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);

            $filePath = ltrim($filePath, '/');

            return $s3->doesObjectExist(env('AWS_BUCKET'), $filePath);
        } catch (Exception $e) {
            return false;
        }
    }
}
