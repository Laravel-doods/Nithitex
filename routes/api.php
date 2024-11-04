<?php

use App\Http\Controllers\API\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Support\Jsonable;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*          <-------------------------USER------------------------->          */
Route::post('register', [App\Http\Controllers\API\UserController::class, 'register']);
Route::post('sellerregister', [App\Http\Controllers\API\UserController::class, 'sellerregister']);
Route::post('login', [App\Http\Controllers\API\UserController::class, 'login']);
Route::post('sendotp', [App\Http\Controllers\API\UserController::class, 'sendOTP']);
Route::post('verifyotp', [App\Http\Controllers\API\UserController::class, 'verifyOTP']);


Route::get('mainCategorylist', [App\Http\Controllers\API\MainCategoryController::class, 'mainCategory']);
Route::post('productsbymaincategory/{id}', [App\Http\Controllers\API\ProductController::class, 'productsByMainCategory']);
Route::get('categorylist', [App\Http\Controllers\API\CategoryController::class, 'category']);
Route::post('productsbycategory/{id}', [App\Http\Controllers\API\ProductController::class, 'productsByCategory']);

Route::post('productsbyoffer', [App\Http\Controllers\API\ProductController::class, 'productsByTodayOffer']);

Route::post('allproducts', [App\Http\Controllers\API\ProductController::class, 'allProducts']);
Route::post('product/detail/{id}', [App\Http\Controllers\API\ProductController::class, 'productdetail']);

Route::post('color/sort/{color_id}', [App\Http\Controllers\API\ProductController::class, 'colorSort']);
Route::post('product/sort/{sort_by}', [App\Http\Controllers\API\ProductController::class, 'productSort']);

Route::post('search/{search_value}', [App\Http\Controllers\API\ProductController::class, 'search']);
Route::get('contact', [App\Http\Controllers\API\ApiController::class, 'contact']);
Route::get('aboutcontent', [App\Http\Controllers\API\ApiController::class, 'getAboutContent']);
Route::post('policiescontent', [App\Http\Controllers\API\ApiController::class, 'getPoliciesContent']);
Route::post('slider', [App\Http\Controllers\API\ApiController::class, 'slider']);
Route::get('/getreferralsettings', [App\Http\Controllers\API\ReferralController::class, 'getreferralsettings']);

Route::get('color/list', [App\Http\Controllers\API\ApiController::class, 'colorList']);
Route::get('statelist', [App\Http\Controllers\API\ApiController::class, 'state']);

Route::post('paging', [App\Http\Controllers\API\ProductController::class, 'paging']);

//track
Route::post('track/order', [App\Http\Controllers\API\OrderController::class, 'trackOrder']);

//Generate Razorpay order before initiating the payment
Route::post('razorpay/generateorder', [App\Http\Controllers\API\OrderController::class, 'generateRazorpayOrder']);

//forget-password
Route::post('forget/password/link/generate', [App\Http\Controllers\API\ApiController::class, 'forgetPasswordLinkGenerate']);

//Check Role Id for User
Route::post('checkrolechanged', [App\Http\Controllers\API\UserController::class, 'checkRoleChanged']);

//default-content
Route::get('defaultcontent', [App\Http\Controllers\API\ApiController::class, 'getDefaultContent']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', [App\Http\Controllers\API\UserController::class, 'logout']);
    Route::post('/deleteAccount', [App\Http\Controllers\API\UserController::class, 'deleteAccount']);
    Route::post('wishlist/add', [App\Http\Controllers\API\WishlistController::class, 'addToWishlist']);
    Route::get('wishlist/product', [App\Http\Controllers\API\WishlistController::class, 'wishlistProducts']);
    Route::post('cart/{id}', [App\Http\Controllers\API\CartController::class, 'cart']);
    Route::get('cart/delete/{id}', [App\Http\Controllers\API\CartController::class, 'cartDelete']);
    Route::get('get/cartlist', [App\Http\Controllers\API\CartController::class, 'cartlist']);
    Route::post('cart/increase/{id}', [App\Http\Controllers\API\CartController::class, 'CartIncrement']);
    Route::post('cart/decrease/{id}', [App\Http\Controllers\API\CartController::class, 'CartDecrement']);
    Route::get('order/list', [App\Http\Controllers\API\OrderController::class, 'orderList']);
    Route::post('return/order/{id}', [App\Http\Controllers\API\OrderController::class, 'returnOrder']);
    Route::get('return/orderlist', [App\Http\Controllers\API\OrderController::class, 'returnOrderList']);
    Route::get('cancel/request/{order_id}', [App\Http\Controllers\API\OrderController::class, 'cancelRequest']);
    Route::get('cancel/orderlist', [App\Http\Controllers\API\OrderController::class, 'cancelOrders']);
    Route::post('/user/password/update', [App\Http\Controllers\API\ApiController::class, 'UserPasswordUpdate']);
    Route::post('/user/profile/update', [App\Http\Controllers\API\ApiController::class, 'UserProfileStore']);
    Route::get('order/details/{order_id}', [App\Http\Controllers\API\OrderController::class, 'orderDetail']);
    Route::get('get/userprofile', [App\Http\Controllers\API\ApiController::class, 'userProfileGet']);
    Route::get('purchase/history', [App\Http\Controllers\API\ApiController::class, 'purchaseHistory']);
    Route::get('/invoice/download/{order_id}', [App\Http\Controllers\API\ApiController::class, 'invoiceDownload']);
    Route::get('order/summary', [App\Http\Controllers\API\OrderController::class, 'orderSummary']);
    Route::post('payment/status', [App\Http\Controllers\API\RazorpayController::class, 'paymentStatus']);
    Route::post('place/order', [App\Http\Controllers\API\OrderController::class, 'placeOrder']);
    Route::post('place/order/v1', [App\Http\Controllers\API\OrderController::class, 'placeOrderv1']);
    Route::get('marginsummary', [App\Http\Controllers\API\ApiController::class, 'marginSummary']);
    Route::post('verifycoupon', [App\Http\Controllers\API\ApiController::class, 'verifyCoupon']);
    Route::post('update/paymentstatus', [App\Http\Controllers\API\OrderController::class, 'updatePaymetStatus']);
});


/*          <-------------------------STAFF------------------------->          */
Route::prefix('staff')->group(function () {
    Route::post('login', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'staffLogin']);
    Route::get('slider', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'slider']);
    Route::get('mainCategorylist', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'mainCategory']);
    Route::get('productsbymaincategory/{id}', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'productsByMainCategory']);
    Route::get('categorylist', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'category']);
    Route::get('productsbycategory/{id}', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'productsByCategory']);
    Route::post('product/detail/{id}', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'productdetail']);
    Route::get('search/{search_value}', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'search']);

    Route::post('getstaffprofile', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'getStaffProfile']);
    Route::post('cart/{id}', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'cart']);
    Route::post('get/cartlist', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'cartlist']);
    Route::post('cart/increase/{id}', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'CartIncrement']);
    Route::post('cart/decrease/{id}', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'CartDecrement']);
    Route::post('cart/delete/{id}', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'cartDelete']);
    Route::post('placeorder', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'placeOrder']);
    Route::post('orderlist', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'orderList']);
    Route::post('orderdetail/{order_id}', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'orderDetail']);
    Route::post('ordersummary', [App\Http\Controllers\API\StaffOrder\StaffOrderController::class, 'orderSummary']);
});
