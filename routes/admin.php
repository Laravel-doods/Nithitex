<?php

use App\Http\Controllers\AssignRoleToUserController;
use App\Http\Controllers\Backend\AdminLoginController;
use App\Http\Controllers\Backend\AdminProfileController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\ColorController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\MainCategoryController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\backend\PolicyController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ReferralHistoryController;
use App\Http\Controllers\Backend\RefferalSettingsController;
use App\Http\Controllers\Backend\ReturnController;
use App\Http\Controllers\Backend\SellerApproveController;
use App\Http\Controllers\backend\SettingsController;
use App\Http\Controllers\Backend\ShopInformationController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\Staff\StaffController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController as ControllersStaffController;
use Illuminate\Support\Facades\Route;

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

/*****************************Admin Route Starts***********************/
Route::get('admin/login', [AdminLoginController::class, 'loginForm'])->name('admin.form');
Route::post('admin/store', [AdminLoginController::class, 'login'])->name('admin.login');

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin']], function () {
    Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admins.logout');
});

//Admin Dashboard
Route::middleware(['auth:admin'])->group(function () {
    Route::middleware(['auth:sanctum,admin', 'verified'])->get('/admin/dashboard', function () {
        return view('admin.index');
    })->name('admin.dashboard');

    // Admin All Routes 
    Route::get('/admin/profile', [AdminProfileController::class, 'AdminProfile'])->name('admin.profile');
    Route::get('/live-users-count', [AdminProfileController::class, 'getLiveUsersCount'])->name('get.users.count');
    Route::get('/admin/profile/edit', [AdminProfileController::class, 'AdminProfileEdit'])->name('admin.profile.edit');
    Route::post('/admin/profile/store', [AdminProfileController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminProfileController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/update/change/password', [AdminProfileController::class, 'AdminUpdateChangePassword'])->name('update.change.password');

    //Stock Maintanence
    Route::get('/product/stockmaintenace', [ProductController::class, 'ProductStock'])->name('product.stock');
    Route::get('/product/getProductVariantStock', [ProductController::class, 'getProductAndVariants'])->name('product.get.product.variant.stock');
    Route::post('/product/update-variant-stock', [ProductController::class, 'updateVariantStock'])->name('product.update.variant.stock');
    Route::post('/product/quantity/update', [ProductController::class, 'stockupdate'])->name('product.quantity.update');
    Route::get('/product/getStockMaintenaceData/{category_id}', [ProductController::class, 'getStockMaintenaceData'])->name('getstockmaintenacedata');

    //Product Out Of Stock Report
    Route::get('/report/out_of_stock', [ProductController::class, 'ReportOutofStock'])->name('report.out_of_stock');
    Route::get('/report/getOutOfStockData/{category_id}', [ProductController::class, 'getOutOfStockData'])->name('getoutofstockdata');
    //Product  Stock  Report
    Route::get('/report/stockreport', [ProductController::class, 'Reportstock'])->name('report.stock');
    Route::get('/product/stock/report/{category_id}', [ProductController::class,'stockReport'])->name('product.stock-wise-report');
    //Product  view analytics
    Route::get('/view/analytics', [ProductController::class, 'viewAnalytics'])->name('view.analytics');
    Route::get('/report/get-view-analytics', [ProductController::class,'getViewAnalyticsData'])->name('getViewAnalyticsData');

    //Reseller-Request
    Route::get('/reseller/allresellers', [SellerApproveController::class, 'ResellerView'])->name('resellers.all');
    Route::get('/customer/allcustomers', [SellerApproveController::class, 'customerView'])->name('customer.all');
    Route::get('/reseller/resellerrequest', [SellerApproveController::class, 'ResellerRequest'])->name('resellers.request');
    Route::get('/reseller/approve/{id}', [SellerApproveController::class, 'ResellerApprove'])->name('reseller.approve');
    Route::get('/reseller/deny/{id}', [App\Http\Controllers\Backend\SellerApproveController::class, 'ResellerDelete'])->name('reseller.deny');

    //seller-coupon-update
    Route::post('/reseller/coupon/update', [SellerApproveController::class, 'sellerCouponUpdate'])->name('sellerCouponUpdate');

    //user-role-permission
    Route::resource('user', ControllersStaffController::class, [
        'only' => ['index', 'create', 'store', 'destroy', 'show', 'update']
    ]);
    Route::resource('role', RoleController::class);
    Route::resource('assign_role_users', AssignRoleToUserController::class);


    Route::prefix('master')->group(function () {
        //Main Categories
        Route::get('main-category', [MainCategoryController::class, 'mainCategoryView'])->name('main-category.all');
        Route::post('main-category/store', [MainCategoryController::class, 'mainCategoryStore'])->name('main-category.store');
        Route::get('main-category/edit/{id}', [MainCategoryController::class, 'mainCategoryEdit'])->name('main-category.edit');
        Route::post('main-category/update', [MainCategoryController::class, 'mainCategoryUpdate'])->name('main-category.update');
        Route::get('main-category/delete/{id}', [MainCategoryController::class, 'mainCategoryDelete'])->name('main-category.delete');
        
        //Categorries
        Route::get('category', [CategoryController::class, 'CategoryView'])->name('category.all');
        Route::post('category/store', [CategoryController::class, 'CategoryStore'])->name('category.store');
        Route::get('category/edit/{id}', [CategoryController::class, 'CategoryEdit'])->name('category.edit');
        Route::post('category/update', [CategoryController::class, 'CategoryUpdate'])->name('category.update');
        Route::get('category/delete/{id}', [CategoryController::class, 'CategoryDelete'])->name('category.delete');
        Route::post('/category/update-weight', [CategoryController::class, 'updateWeight'])->name('category.update-weight');
        
        //Today Offer 
        Route::post('create/todayoffer', [CategoryController::class, 'createTodayOffer'])->name('create.today.offer');
        Route::post('remove/todayoffer', [CategoryController::class, 'removeTodayOffer'])->name('remove.offer');

        //Colors
        Route::get('color', [ColorController::class, 'ColorView'])->name('color.all');
        Route::post('color/store', [ColorController::class, 'ColorStore'])->name('color.store');
        Route::get('color/edit/{id}', [ColorController::class, 'ColorEdit'])->name('color.edit');
        Route::post('color/update', [ColorController::class, 'ColorUpdate'])->name('color.update');
        Route::get('color/delete/{id}', [ColorController::class, 'ColorDelete'])->name('color.delete');
    });

    Route::prefix('product')->group(function () {
        //Colors
        Route::get('', [ProductController::class, 'ProductView'])->name('product.all');
        Route::get('/fetch-categories', [ProductController::class, 'fetchCategories'])->name('fetch.categories');
        Route::post('/getproductsku', [ProductController::class, 'getProductSKU'])->name('getproductsku');
        Route::post('store', [ProductController::class, 'ProductStore'])->name('product.store');
        Route::post('add-product-image', [ProductController::class, 'addProductImage'])->name('add.product.image');
        Route::get('list', [ProductController::class, 'ProductList'])->name('product.list');
        Route::get('productlistdata', [ProductController::class,'productListData'])->name('productlistdata');
        Route::get('edit/{id}', [ProductController::class, 'ProductEdit'])->name('product.edit');
        Route::get('multiimg/delete/{id}', [ProductController::class, 'ProductMultiImageDelete'])->name('product.multiimg.delete');

        Route::post('update', [ProductController::class, 'ProductUpdate'])->name('product.update');

        Route::get('delete/{id}', [ProductController::class, 'ProductDelete'])->name('product.delete');

        Route::get('/color/variant/ajax/{color_id}', [ProductController::class, 'getColorValue']);
    });

    Route::prefix('settings')->group(function () {
        //about_us
        Route::get('about-view', [SettingsController::class, 'aboutView'])->name('about.all');
        Route::post('about/store', [SettingsController::class, 'store'])->name('about.store');
        Route::post('deletepopupimage', [SettingsController::class, 'deletePopupImage'])->name('deletepopupimage');

        //Terms & Condition
        Route::get('policy-view', [PolicyController::class, 'index'])->name('policy.all');
        Route::post('policy/store', [PolicyController::class, 'store'])->name('policy.store');

        //slider
        Route::get('slider-view', [SliderController::class, 'index'])->name('slider.all');
        Route::post('slider/store', [SliderController::class, 'store'])->name('slider.store');
        Route::get('slider/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit');
        Route::post('slider/update', [SliderController::class, 'update'])->name('slider.update');
        Route::get('slider/delete/{id}', [SliderController::class, 'delete'])->name('slider.delete');

        //shop Inforamtion 
        Route::get('information-view', [ShopInformationController::class, 'index'])->name('information.all');
        Route::post('information/update', [ShopInformationController::class, 'update'])->name('information.update');

        //State
        Route::get('state', [ShopInformationController::class, 'stateView'])->name('state.all');
        Route::post('state/store', [ShopInformationController::class, 'stateStore'])->name('state.store');
        Route::post('/free_delivery', [ShopInformationController::class, 'freedelivery']);
        Route::get('state/edit/{id}', [ShopInformationController::class, 'stateEdit'])->name('state.edit');
        Route::post('state/update', [ShopInformationController::class, 'stateUpdate'])->name('state.update');

        //Coupon
        Route::get('coupon', [CouponController::class, 'coupon'])->name('coupon.all');
        Route::post('coupon/store', [CouponController::class, 'couponStore'])->name('coupon.store');
        Route::get('coupon/edit/{id}', [CouponController::class, 'couponEdit'])->name('coupon.edit');
        Route::post('coupon/update', [CouponController::class, 'couponUpdate'])->name('coupon.update');
        Route::post('coupon/update/status', [CouponController::class, 'couponUpdateStatus'])->name('coupon.status');
        Route::post('coupon/update/common/status', [CouponController::class, 'couponUpdateCommonStatus'])->name('coupon.common');

        //Referral Settings
        Route::get('referralsettings', [RefferalSettingsController::class, 'referralSettings'])->name('referralsettings');
        Route::post('addreferralpoints', [RefferalSettingsController::class, 'addReferralPoints'])->name('addreferralpoints');

        Route::get('referralhistory', [ReferralHistoryController::class, 'referralHistory'])->name('referral-history');
        Route::get('refferalcustomer', [ReferralHistoryController::class, 'getRefferalCustomerdata'])->name('get.referralcustomer');
        Route::get('referralcode/{id}', [ReferralHistoryController::class, 'referralCode'])->name('referral-code');
        Route::get('/referraluserinfo/{id}', [ReferralHistoryController::class, 'getReferralUserInfo'])->name('referraluserinfo');
        Route::post('/updatereferralpaymet', [ReferralHistoryController::class, 'updateReferralPaymet'])->name('updatereferralpaymet');
        Route::get('referralpaymenthistory', [ReferralHistoryController::class, 'getReferralPaymentHistory'])->name('referralpaymenthistory');
        Route::get('referralpaymenthistorydata', [ReferralHistoryController::class, 'getReferralPaymentHistoryData'])->name('referralpaymenthistorydata');

        //Notification
        Route::get('notification', [NotificationController::class, 'notificationView'])->name('notification.view');
        Route::post('send-notification', [NotificationController::class, 'sendNotification'])->name('send.notification');

        //Loyalty Management
        Route::get('loyalty-management', [LoyaltyController::class, 'loyaltyManagement'])->name('loyalty.management');
        Route::post('add-loyalty-management', [LoyaltyController::class, 'addLoyaltyManagement'])->name('add.loyalty.management');
    });

    //user-order-list
    Route::prefix('order')->group(function () {
        Route::get('all-customerorders', [OrderController::class, 'orderView'])->name('order.all');
        Route::get('all-getallcustomerordersdata', [OrderController::class,  'getAllCustomerOrders'])->name('getAllCustomerOrders');
        Route::get('details/{order_id}', [OrderController::class, 'OrdersDetails'])->name('order.details');
        Route::get('unpaid_status/update/{order_id}', [OrderController::class, 'PaymentunApprove'])->name('order.unpaid_status.update');
        Route::get('paid_status/update/{order_id}', [OrderController::class, 'PaymentpaidApprove'])->name('order.paid_status.update');
        Route::post('update',  [OrderController::class, 'CustomerOrderStatusUpdate'])->name('order.update');


        Route::get('/customer-pending', [OrderController::class, 'PendingOrders'])->name('orders-pending');
        Route::get('/customer-confirmed', [OrderController::class, 'ConfirmedOrders'])->name('orders-confirmed');
        Route::get('/customer-processing', [OrderController::class, 'ProcessingOrders'])->name('orders-processing');
        Route::get('/customer-picked', [OrderController::class, 'PickedOrders'])->name('orders-picked');
        Route::get('/customer-shipped', [OrderController::class, 'ShippedOrders'])->name('orders-shipped');
        Route::get('/customer-delivered', [OrderController::class, 'DeliveredOrders'])->name('orders-delivered');
        Route::get('customer-getdelivereddata', [OrderController::class, 'getDeliveredOrders'])->name('getDeliveredOrders');
        Route::get('/customer-cancel', [OrderController::class, 'CancelOrders'])->name('orders-cancel');
    });

    Route::get('/pending/update/{order_id}', [OrderController::class, 'pendingApprove'])->name('pending.order.approve');
    Route::get('/confirmed/order/update/{order_id}', [OrderController::class, 'confirmedApprove'])->name('confirmed.order.approve');
    Route::post('/processing/order/update/{order_id}', [OrderController::class, 'processingApprove'])->name('processing.order.approve');
    Route::post('/picked/order/update/{order_id}', [OrderController::class, 'pickedApprove'])->name('picked.order.approve');
    Route::get('/shipped/order/update/{order_id}', [OrderController::class, 'shippedApprove'])->name('shipped.order.approve');

    // seller-order-list
    Route::prefix('seller/order')->group(function () {
        Route::get('allorders', [OrderController::class, 'sellerOrderView'])->name('seller.order.all');
        Route::get('getallsellerordersdata', [OrderController::class, 'getAllSellerOrders'])->name('getAllSellerOrders');
        Route::get('export/excel', [OrderController::class, 'order_export'])->name('order_export');
        Route::get('/unpaid_status/update/{order_id}', [OrderController::class, 'sellerPaymentunApprove'])->name('seller.order.unpaid_status.update');
        Route::get('/paid_status/update/{order_id}', [OrderController::class, 'sellerPaymentpaidApprove'])->name('seller.order.paid_status.update');
        Route::get('/details/{order_id}', [OrderController::class, 'sellerOrdersDetails'])->name('seller.order.details');
        Route::post('update',  [OrderController::class, 'sellerOrderStatusUpdate'])->name('seller.order.update');
        Route::get('/seller-pending', [OrderController::class, 'sellerPendingOrders'])->name('seller.orders-pending');
        Route::get('/pending/update/{order_id}', [OrderController::class, 'sellerPendingApprove'])->name('seller.pending.order.approve');
        Route::get('/seller-confirmed', [OrderController::class, 'sellerConfirmedOrders'])->name('seller.orders-confirmed');
        Route::get('/confirmed/order/update/{order_id}', [OrderController::class, 'sellerConfirmedApprove'])->name('seller.confirmed.order.approve');
        Route::get('/seller-processing', [OrderController::class, 'sellerProcessingOrders'])->name('seller.orders-processing');
        Route::post('/processing/order/update/{order_id}', [OrderController::class, 'sellerProcessingApprove'])->name('seller.processing.order.approve');
        Route::get('/seller-picked', [OrderController::class, 'sellerPickedOrders'])->name('seller.orders-picked');
        Route::post('/picked/order/update/{order_id}', [OrderController::class, 'sellerPickedApprove'])->name('seller.picked.order.approve');
        Route::get('/seller-shipped', [OrderController::class, 'sellerShippedOrders'])->name('seller.orders-shipped');
        Route::get('/shipped/order/update/{order_id}', [OrderController::class, 'sellerShippedApprove'])->name('seller.shipped.order.approve');
        Route::get('/seller-delivered', [OrderController::class, 'sellerDeliveredOrders'])->name('seller.orders-delivered');
        Route::get('getsellerdeliverdordersdata', [OrderController::class, 'getSellerDeliverdOrders'])->name('getSellerDeliverdOrders');
        Route::get('/seller-cancel', [OrderController::class, 'sellerCancelOrders'])->name('seller.orders-cancel');
    });

    //Order print
    Route::get('/order/print/modal/{id}', [OrderController::class, 'OrderprintAjax'])->name('order.print.modal');
    Route::get('/prnpriview/{id}', [OrderController::class, 'prnpriview'])->name('prnpriview');

    // Admin Return Order Routes 
    Route::prefix('admin')->group(function () {
        Route::get('/returnrequest', [ReturnController::class, 'ReturnRequest'])->name('return.request');
        Route::get('/return/approve/{order_id}', [ReturnController::class, 'ReturnRequestApprove'])->name('return.approve');
        Route::get('/return/reject/{order_id}', [ReturnController::class, 'ReturnRequestReject'])->name('return.reject');
        Route::get('/all/returnrequest', [ReturnController::class, 'ReturnAllRequest'])->name('all.request');
    });

    // Admin Return Order Routes 
    Route::prefix('admin')->group(function () {
        Route::get('/sellerreturnrequest', [ReturnController::class, 'sellerReturnRequest'])->name('seller.return.request');
        Route::get('/sellerreturn/approve/{order_id}', [ReturnController::class, 'sellerReturnRequestApprove'])->name('seller.return.approve');
        Route::get('/sellerreturn/reject/{order_id}', [ReturnController::class, 'sellerReturnRequestReject'])->name('seller.return.reject');
    });

    //cancel-request
    Route::prefix('admin')->group(function () {
        Route::get('/cancelrequest', [ReturnController::class, 'cancelRequest'])->name('cancel.request');
        Route::get('/cancel/approve/{order_id}', [ReturnController::class, 'cancelRequestApprove'])->name('cancel.approve');
        Route::get('/all/cancelrequest', [ReturnController::class, 'cancelAllRequest'])->name('all.cancel.request');
    });

    //seller-cancel-request
    Route::prefix('admin')->group(function () {
        Route::get('/sellercancelrequest', [ReturnController::class, 'sellerCancelRequest'])->name('seller.cancel.request');
        Route::get('/sellercancel/approve/{order_id}', [ReturnController::class, 'sellerCancelRequestApprove'])->name('seller.cancel.approve');
    });
});
// ======================  Admin Routes End  ================================ //


// ======================  STAFF ROUTES START ================================ //
Route::group(['prefix' => 'staff', 'middleware' => ['auth:admin']], function () {
    Route::get('/staff/allstaffs', [StaffController::class, 'staffView'])->name('all.staffs');
    Route::get('allorders', [StaffController::class, 'staffOrderView'])->name('staff.order.all');
    Route::get('getallstafforderdata', [StaffController::class, 'getAllStaffOrder'])->name('getAllStaffOrder');
    Route::get('/unpaid_status/update/{order_id}', [StaffController::class, 'paymentUnpaid'])->name('staff_order.unpaid_status.update');
    Route::get('/paid_status/update/{order_id}', [StaffController::class, 'paymentPaid'])->name('staff_order.paid_status.update');
    Route::get('orderdetails/{order_id}', [StaffController::class, 'staffOrdersDetails'])->name('stafforder.details');
    Route::post('order/update',  [StaffController::class, 'staffOrderStatusUpdate'])->name('stafforder.update');
});
// ======================  STAFF ROUTES END ================================ //