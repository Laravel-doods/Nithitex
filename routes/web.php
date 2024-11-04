<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\User\AllUserController;
use App\Http\Controllers\Frontend\User\CartPageController;
use App\Http\Controllers\Frontend\User\CashController;
use App\Http\Controllers\Frontend\User\WishlistController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\Frontend\BuyNowController;
use App\Http\Controllers\Frontend\User\RazorpayController;
use App\Http\Controllers\SELLER\ForgotPasswordController;
use App\Http\Controllers\UserForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require base_path('routes/admin.php');

Route::get('otp/verification/{user_id}/{fcm_token}', [App\Http\Controllers\Frontend\LoginController::class, 'verification'])->name('otp.verification');
Route::post('otp/login', [App\Http\Controllers\Frontend\LoginController::class, 'loginWithOtp'])->name('otp.getlogin');

//user-login
Route::get('/login', [\App\Http\Controllers\Frontend\LoginController::class, 'loginForm'])->name('user.login');
Route::post('/store', [\App\Http\Controllers\Frontend\LoginController::class, 'login'])->name('user.storing');
Route::post('/register/store', [\App\Http\Controllers\Frontend\LoginController::class, 'regiterStore'])->name('user.register.store');

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/about', [IndexController::class, 'about'])->name('about');
Route::get('/contact', [IndexController::class, 'contact'])->name('contact');
Route::get('/terms', [IndexController::class, 'terms'])->name('terms');
Route::get('/privacy', [IndexController::class, 'privacy'])->name('privacy');
Route::get('/return', [IndexController::class, 'return'])->name('return');
Route::get('/support', [IndexController::class, 'support'])->name('support');
Route::get('/track-your-order', [IndexController::class, 'track_order'])->name('track_order');

Route::get('/user/profile', [IndexController::class, 'UserProfile'])->name('user.profile');
Route::post('/user/profile/store', [IndexController::class, 'UserProfileStore'])->name('user.profile.store');
Route::get('/user/change/password', [IndexController::class, 'UserChangePassword'])->name('change.password');
Route::post('/user/password/update', [IndexController::class, 'UserPasswordUpdate'])->name('user.password.update');

//seler forget-password
Route::get('seller-forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('seller-forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('seller-reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('seller-reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

//user forget-password
Route::get('user-forget-password', [UserForgotPasswordController::class, 'showForgetPasswordForm'])->name('user.forget.password.get');
Route::post('user-forget-password', [UserForgotPasswordController::class, 'submitForgetPasswordForm'])->name('user.forget.password.post');
Route::get('user-reset-password/{token}', [UserForgotPasswordController::class, 'showResetPasswordForm'])->name('user.reset.password.get');
Route::post('user-reset-password', [UserForgotPasswordController::class, 'submitResetPasswordForm'])->name('user.reset.password.post');

//Shop Page
Route::get('/product/shop', [IndexController::class, 'ProductShop'])->name('product.shop');

//Offers Page
Route::get('/product/offers', [IndexController::class, 'ProductOffers'])->name('product.offers');

//Today Offer Page
Route::get('/today/offer', [IndexController::class, 'TodayOffer'])->name('today.offer');

//Tag Products Page
Route::get('/product/tag/{tags}', [IndexController::class, 'ProductsbyTags'])->name('product.tag');

//Frontend Product Details Load
Route::get('/product/details/{id}/{slug}', [IndexController::class, 'ProductDetails']);
Route::get('/getVariantData', [IndexController::class, 'getVariantData']);

//Category Products Load
Route::get('maincategory/product/{id}', [IndexController::class, 'MainCategoryDetails']);

//Category Products Load
Route::get('category/product/{id}', [IndexController::class, 'CategoryDetails']);

Route::get('/product/view/modal/{id}', [IndexController::class, 'ProductViewAjax']);
Route::post('/cart/data/store/{id}', [CartController::class, 'AddToCart']);
Route::post('/simplecart/store/{id}', [CartController::class, 'simpleAddToCart']);
Route::get('/product/mini/cart/', [CartController::class, 'AddMiniCart']);
Route::get('/minicart/product-remove/{rowId}', [CartController::class, 'RemoveMiniCart']);
Route::post('/add-to-wishlist/{product_id}', [CartController::class, 'AddToWishlist']);
// Buy Now Button
Route::post('/productdetails/buynow/{id}', [BuyNowController::class, 'ProductDetailsBuyNow']);
Route::get('/product/buynow/{id}', [BuyNowController::class, 'ProductBuyNow']);

//coupon-calculation
Route::post('/coupon/discount', [CartController::class, 'couponCalculation']);
Route::get('/mycart', [CartPageController::class, 'MyCart'])->name('mycart');
Route::get('/wishlist', [WishlistController::class, 'ViewWishlist'])->name('wishlist');

Route::group(['prefix' => 'user', 'middleware' => ['user', 'auth'], 'namespace' => 'User'], function () {
    Route::get('/dashboard', [IndexController::class, 'dashboard'])->name('user.dashboard');
    
    Route::get('user/logout', [\App\Http\Controllers\Frontend\LoginController::class, 'logout'])->name('user.logout');

    Route::post('/cash/order', [CashController::class, 'CashOrder'])->name('cash.order');
    Route::post('/razorpay/order', [RazorpayController::class, 'RazorpayOrder'])->name('razorpay.order');
    Route::post('/razorpay/complete', [RazorpayController::class, 'RazorpayComplete'])->name('razorpay.complete');
    Route::get('/my/orders', [AllUserController::class, 'MyOrders'])->name('my.orders');
    Route::get('/order_details/{order_id}', [AllUserController::class, 'OrderDetails']);
    Route::get('/invoice_download/{order_id}', [AllUserController::class, 'InvoiceDownload']);
    Route::post('/order/tracking', [AllUserController::class, 'OrderTraking'])->name('order.tracking');
    Route::post('/return/order/{order_id}', [AllUserController::class, 'ReturnOrder'])->name('return.order');
    Route::get('/return/order/list', [AllUserController::class, 'ReturnOrderList'])->name('return.order.list');
    Route::get('/cancel/orders', [AllUserController::class, 'CancelOrders'])->name('cancel.orders');
    Route::post('/cancel/request/{order_id}', [AllUserController::class, 'allCancelRequest'])->name('alll.cancel.request');
    
});

Route::get('/wishlist-remove/{id}', [WishlistController::class, 'RemoveWishlistProduct']);
Route::get('/get-wishlist-product', [WishlistController::class, 'GetWishlistProduct']);

Route::get('/checkout', [CartController::class, 'CheckoutCreate'])->name('checkout');
Route::post('/checkout/store', [CheckoutController::class, 'CheckoutStore'])->name('checkout.store');
Route::get('/get-cart-product', [CartPageController::class, 'GetCartProduct']);
Route::get('/cart-remove/{rowId}', [CartPageController::class, 'RemoveCartProduct']);
Route::get('/cart-increment/{id}', [CartPageController::class, 'CartIncrement']);
Route::get('/cart-decrement/{id}', [CartPageController::class, 'CartDecrement']);

//payment-status
Route::get('/paymentstatus', [CheckoutController::class, 'paymentstatus'])->name('paymentstatus');

//Social Login
Route::get('auth/google', [\App\Http\Controllers\Frontend\GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [\App\Http\Controllers\Frontend\GoogleController::class, 'handleGoogleCallback']);
Route::get('auth/facebook', [\App\Http\Controllers\Frontend\FacebookController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [\App\Http\Controllers\Frontend\FacebookController::class, 'handleFacebookCallback']);

//product-search
Route::get('/product/search', [\App\Http\Controllers\Frontend\IndexController::class, 'ProductSearch'])->name('product.search');

//product-sort
Route::get('/product/sort', [\App\Http\Controllers\Frontend\IndexController::class, 'productSort'])->name('product.sort');

//product-color
Route::get('/product/color/sort', [\App\Http\Controllers\Frontend\IndexController::class, 'productColor'])->name('product.color.sort');

//firebase
Route::get('phone_auth', [\App\Http\Controllers\Frontend\FirebaseController::class, 'phone_auth']);

// ======================  SELLER ROUTES START ================================ //

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/seller/dashboard', function () {
        return view('seller.dashboard.seller_dashboard');
    })->name('seller.dashboard');

    Route::group(['prefix' => 'seller'], function () {

        //seller dashboard
        Route::get('/profile', [\App\Http\Controllers\SELLER\IndexController::class, 'profile'])->name('seller.profile');
        Route::post('/profile/store', [\App\Http\Controllers\SELLER\IndexController::class, 'profileStore'])->name('seller.profile.store');
        Route::get('/change/password', [\App\Http\Controllers\SELLER\IndexController::class, 'password'])->name('seller.password');
        Route::post('/update/password', [\App\Http\Controllers\SELLER\IndexController::class, 'updatePassword'])->name('seller.update.password');
        Route::get('/orders', [\App\Http\Controllers\SELLER\IndexController::class, 'orders'])->name('seller.orders');
        Route::get('return/orders', [\App\Http\Controllers\SELLER\IndexController::class, 'returnOrders'])->name('seller.return');
        Route::get('cancel/orders', [\App\Http\Controllers\SELLER\IndexController::class, 'cancelOrder'])->name('seller.cancel');
        Route::get('/logout', [\App\Http\Controllers\SELLER\SellerController::class, 'logout'])->name('seller.logout');
        Route::get('/my/orders', [\App\Http\Controllers\SELLER\OrderController::class, 'MyOrders'])->name('seller.my.orders');
        Route::get('/order_details/{order_id}', [\App\Http\Controllers\SELLER\OrderController::class, 'OrderDetails'])->name('seller.invoice');
        Route::get('/invoice_download/{order_id}', [\App\Http\Controllers\SELLER\OrderController::class, 'InvoiceDownload'])->name('seller.invoice.download');
        Route::post('/return/order/{order_id}', [\App\Http\Controllers\SELLER\OrderController::class, 'ReturnOrder'])->name('seller.return.order');
        Route::get('/return/order/list', [\App\Http\Controllers\SELLER\OrderController::class, 'ReturnOrderList'])->name('seller.return.order.list');
        Route::get('/cancel/orders/list', [\App\Http\Controllers\SELLER\OrderController::class, 'CancelOrders'])->name('seller.cancel.orders');
        Route::post('/cancel/request/{order_id}', [\App\Http\Controllers\SELLER\OrderController::class, 'sellerCancelRequest'])->name('reseller.cancel.request');

        //order-traking
        Route::post('/order/tracking', [\App\Http\Controllers\SELLER\IndexController::class, 'OrderTraking'])->name('seller.order.tracking');

        //wishlist
        Route::get('/wishlist', [\App\Http\Controllers\SELLER\WishlistController::class, 'ViewWishlist'])->name('seller.wishlist');
        Route::post('/add-to-wishlist/{product_id}', [\App\Http\Controllers\SELLER\WishlistController::class, 'AddToWishlist'])->name('seller.addwishlist');

        //cart
        Route::get('/mycart', [\App\Http\Controllers\SELLER\CartController::class, 'MyCart'])->name('seller.mycart');
        Route::get('/checkout', [\App\Http\Controllers\SELLER\CartController::class, 'CheckoutCreate'])->name('seller.checkout');
        Route::post('/checkout/store', [\App\Http\Controllers\SELLER\CheckoutController::class, 'CheckoutStore'])->name('seller.checkout.store');

        Route::get('/product/mini/cart/', [\App\Http\Controllers\SELLER\CartController::class, 'AddMiniCart'])->name('seller.add.minicart');
        Route::post('/cash/order', [\App\Http\Controllers\SELLER\CashController::class, 'CashOrder'])->name('seller.cash.order');

        //razorpay 
        Route::post('/razorpay/order', [\App\Http\Controllers\SELLER\RazorpayController::class, 'RazorpayOrder'])->name('seller.razorpay.order');
        Route::post('/razorpay/complete', [\App\Http\Controllers\SELLER\RazorpayController::class, 'RazorpayComplete'])->name('seller.razorpay.complete');

        // Buy Now Button
        Route::post('/productdetails/buynow/{id}', [\App\Http\Controllers\SELLER\BuynowController::class, 'ProductDetailsBuyNow'])->name('seller.buynowpost');
        Route::get('/product/buynow/{id}', [\App\Http\Controllers\SELLER\BuynowController::class, 'ProductBuyNow'])->name('seller.buynow');

        /**********************Shop Routes Start***************/
        //product detail
        Route::get('/product/details/{id}/{slug}', [IndexController::class, 'ProductDetails']);

        //product shop
        Route::get('/product/shop', [IndexController::class, 'ProductShop'])->name('seller.product.shop');

        //Main Category Products Load
        Route::get('maincategory/product/{id}', [IndexController::class, 'MainCategoryDetails'])->name('seller.maincategory');

        //Category Products Load
        Route::get('category/product/{id}', [IndexController::class, 'CategoryDetails'])->name('seller.category');

        //Tag Products Page
        Route::get('/product/tag/{tags}', [IndexController::class, 'ProductsbyTags'])->name('seller.product.tag');

        //product-search
        Route::get('/product/search', [IndexController::class, 'ProductSearch'])->name('seller.product.search');

        //product-sort
        Route::get('product/sort', [IndexController::class, 'productSort'])->name('seller.product.sort');

        //Offers Page
        Route::get('/product/offers', [IndexController::class, 'ProductOffers'])->name('seller.product.offers');

        //product-color
        Route::get('/product/color/sort', [IndexController::class, 'productColor'])->name('seller.product.color.sort');

        /**********************Shop Routes End***************/

        //seller about
        Route::get('/about', [IndexController::class, 'about'])->name('seller.about');
        //contact page
        Route::get('/contact', [IndexController::class, 'contact'])->name('seller.contact');
        //footer-section
        Route::get('/terms', [IndexController::class, 'terms'])->name('seller.terms');
        Route::get('/privacy', [IndexController::class, 'privacy'])->name('seller.privacy');
        Route::get('/return', [IndexController::class, 'return'])->name('seller.return.policy');
        Route::get('/support', [IndexController::class, 'support'])->name('seller.support');
        Route::get('/track-your-order', [IndexController::class, 'track_order'])->name('seller.track_order');

        //seller-home
        Route::get('/home', [IndexController::class, 'index'])->name('seller.home');
    });
});

Route::group(['prefix' => 'seller'], function () {
    //Seller Login
    Route::get('/login', [\App\Http\Controllers\SELLER\SellerController::class, 'loginFormshow'])->name('seller.login');
    Route::post('/store', [\App\Http\Controllers\SELLER\SellerController::class, 'login'])->name('seller.store');
    Route::get('/register', [\App\Http\Controllers\SELLER\SellerController::class, 'registerFormshow'])->name('seller.register');
    Route::post('/register/store', [\App\Http\Controllers\SELLER\SellerController::class, 'regiterStore'])->name('seller.register.store');
});
// ======================  SELLER ROUTES END ================================ //