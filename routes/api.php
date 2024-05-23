<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('test_push_notification', 'Controller@testPushNotification');
Route::get('doctor-point/{code}', 'Api\MemberController@getDoctorPoint');

Route::post('member', 'Api\MemberController@store');

Route::post('member/update-zone', 'Api\MemberController@updateZone');

Route::group(['prefix' => 'member'], function() {

    Route::post('/', 'Api\MemberController@store');

    Route::post('login', 'Api\MemberController@login');
    Route::get('logout', 'Api\MemberController@logout');
    Route::post('forgotpassword', 'Api\MemberController@forgotPassword');
    Route::post('update_image_profile', 'Api\MemberController@updateImageProfile');
    Route::post('update_profile', 'Api\MemberController@updateProfile');
    Route::post('change_password', 'Api\MemberController@changePassword');
    Route::get('profile', 'Api\MemberController@getProfile');
    Route::get('image_popup_ads', 'Api\MemberController@getImagePopUpAds');

    Route::get('home', 'Api\SiteController@getHomeMemberData');

    Route::get('invoices', 'Api\MemberController@getMemberInvoices');
    Route::get('invoice/detail', 'Api\MemberController@getInvoiceDetail');

    Route::post('bank_tranfer_payment', 'Api\MemberController@bankTranferPayment');
    Route::post('credit_payment', 'Api\MemberController@creditPayment');

    Route::get('payment_histories', 'Api\PaymentController@getPaymentHistory');
    Route::get('payment/detail', 'Api\PaymentController@getPaymentDetail');

    Route::get('dat_to_zone', 'Api\MemberController@getMembersToZone');
    Route::post('social_login', 'Api\MemberController@socialLogin');
    Route::get('check_verify_key', 'Api\MemberController@checkVerifyKey');
    Route::get('get_setting_contact', 'Api\MemberController@getSettingContact');
    Route::post('invite_code', 'Api\MemberController@inviteCode');

    Route::get('doctor-contact', 'Api\MemberController@doctorGetContactErp');
});

Route::get('zone/add_member', 'ZoneController@addMember');
Route::get('zone/delete_member/{id}', 'ZoneController@deleteMember');

Route::group(['prefix' => 'shop'], function() {
    Route::get('home', 'Api\SiteController@getHomeShop');
    Route::get('product/detail', 'Api\ProductController@getProductDetail');
    Route::get('product/histories', 'Api\ProductHistoryController@getProductHistories');
    Route::post('favorite/add_favorite', 'Api\FavoriteController@addToFavorite');
    Route::post('favorite/remove_favorite', 'Api\FavoriteController@removeFavorite');
    Route::get('favorite/favorites', 'Api\FavoriteController@getFavorites');

    Route::get('product/price', 'Api\ProductController@getProductPrice');

    Route::post('cart/add', 'Api\CartController@addToCart');
    Route::post('cart/update', 'Api\CartController@updateCart');
    Route::post('cart/delete', 'Api\CartController@deleteCart');
    Route::get('cart', 'Api\CartController@getCart');
    Route::post('cart/checkout', 'Api\CartController@checkout');

    Route::get('orders/{status}', 'Api\OrderController@getMyOrders');
    Route::get('order/detail', 'Api\OrderController@getOrderDetail');

    Route::post('order/confirm', 'Api\OrderController@confirmOrder');

    Route::get('order/detail', 'Api\OrderController@getOrderDetail');

});

Route::group(['prefix' => 'order'], function() {

    Route::get('incoming', 'Api\OrderController@getIncomingOrder');
    Route::get('confirmed', 'Api\OrderController@getConfirmOrder');

    Route::post('confirm', 'Api\OrderController@confirmOrder');

    Route::get('detail', 'Api\OrderController@getOrderDetail');

});

Route::group(['prefix' => 'voucher'], function() {
    Route::get('home', 'Api\SiteController@getHomeVoucher');
    Route::get('detail', 'Api\VoucherController@getVoucherDetail');
    Route::post('redeem', 'Api\VoucherController@redeem');
    Route::post('redeem_voucher_cart', 'Api\VoucherController@redeemVoucherCart');
    Route::post('add_voucher', 'Api\VoucherController@addVoucherToCart');
    Route::get('voucher_cart', 'Api\VoucherController@getVoucherMyCart');
    Route::get('address', 'Api\VoucherController@getMemberAddressRedemption');
    Route::post('remove_voucher', 'Api\VoucherController@removeVoucherList');
});

Route::group(['prefix' => 'eorder'], function() {
    Route::get('orders/{status}', 'Api\EorderController@getOrders');
    Route::get('order/detail', 'Api\EorderController@getOrderDetail');
    Route::post('order/confirm', 'Api\EorderController@confirm');
    Route::post('order/comment', 'Api\EorderController@addComment');

    Route::get('order/feedback/all', 'Api\EorderController@getFeedback');
});

Route::group(['prefix' => 'pickup'], function() {
    Route::get('locations', 'Api\PickupController@getLocations');
    Route::get('branches', 'Api\PickupController@getBranches');
    Route::get('times', 'Api\SiteController@getPickupTimes');
    Route::get('home', 'Api\PickupController@home');
    Route::post('confirm', 'Api\PickupController@confirm');
    Route::post('remove_pickup', 'Api\PickupController@removeOrderPickup');

    Route::get('sale/home', 'Api\PickupController@getCurrentPickups');
    Route::get('sale/map', 'Api\PickupController@getCurrentPickups');
    Route::get('sale/popup_check_in', 'Api\PickupController@getPopupCheckIn');
    Route::get('sale/search_order', 'Api\PickupController@searchOrderPickups');

    Route::post('check_in/pickup', 'Api\PickupController@confirmPickup');

    Route::post('check_in', 'Api\CheckingController@store');
    Route::get('check_in_history', 'Api\CheckingController@index');
});

Route::group(['prefix' => 'payment'], function() {
    Route::get('home/{status}', 'Api\PaymentController@getPaymentHome');
    Route::get('detail', 'Api\PaymentController@getPaymentDetail');
    Route::post('confirm', 'Api\PaymentController@confirm');
    Route::post('transaction', 'Api\PaymentController@transaction');
    Route::post('transaction/creditcard', 'Api\PaymentController@transactionCreditCard');

    Route::post('make_payment', 'Api\PaymentController@makePayment');
});

Route::resource('checking', 'Api\CheckingController');
Route::resource('redemption', 'Api\RedemptionController');

Route::get('site', 'Api\SiteController@index');
Route::get('provinces', 'Api\SiteController@getProvinces');