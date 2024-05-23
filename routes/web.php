<?php

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
Route::get('/', function() {
    return redirect('front');
});

Route::any('admin', 'UserController@adminLogin')->name('login');
Route::any('admin/reset_password', 'UserController@resetPassword');

Route::get('files/{path}-{file_name}', 'Controller@showFile');

Route::any('payment/{id}', 'PaymentController@show');
Route::get('payment_redirect', 'Api\PaymentController@paymentRedirect');
Route::any('payment/{id}/{from}', 'PaymentController@update');

Route::get('bill/{id}', 'Api\MemberController@getInvoiceDetailPDF');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {

    Route::get('transfer/confirm', ['as'=>'admin.transfer.confirm','uses'=>'TransferController@updateApprove']);
    Route::get('transfer/file', ['as'=>'admin.transfer.file','uses'=>'TransferController@showTransferFile']);
    
    Route::any('voucher/import', 'VoucherController@importVouchers');
    Route::any('product/import', 'ProductController@importProducts');

    Route::resource('category', 'ProductCategoryController');
    Route::resource('voucher', 'VoucherController');
    Route::resource('product', 'ProductController');
    Route::resource('payment', 'PaymentController');
    Route::resource('order', 'OrderController');
    Route::resource('setting', 'SettingController');
    Route::resource('product_discount', 'ProductDiscountController');
    Route::resource('promotion', 'PromotionController');
    Route::resource('member', 'MemberController');
    Route::resource('order_pickup', 'OrderPickupController');
    Route::resource('checking', 'CheckingController');
    Route::resource('redemption', 'RedemptionController');
    Route::resource('zone', 'ZoneController');
    Route::resource('bank', 'BankController');
    Route::resource('voucher_option', 'VoucherOptionController');
    Route::resource('comment', 'EorderCommentController');
    Route::resource('voucher_banner', 'VoucherBannerController');
    Route::resource('zone_member', 'ZoneMemberController');
    Route::resource('retainer_gallery', 'RetainerGalleryController');

    Route::get('customer', 'MemberController@customers');
    Route::get('customer-sync-zone', 'MemberController@reSyncMemberZone');

    Route::get('edit_customer/{id}', 'MemberController@editCustomer');
    Route::any('update_customer_account/{id}', 'MemberController@updateCustomerAccount');

    Route::get('category/delete/{id}', 'ProductCategoryController@delete');
    Route::get('voucher/delete/{id}', 'VoucherController@delete');
    Route::get('product/delete/{id}', 'ProductController@delete');
    Route::get('product_discount/delete/{id}', 'ProductDiscountController@delete');
    Route::get('promotion/delete/{id}', 'PromotionController@delete');
    Route::get('order_pickup/delete/{id}', 'OrderPickupController@delete');
    Route::get('order/delete/{id}', 'OrderController@delete');
    Route::get('checking/delete/{id}', 'CheckingController@delete');
    Route::get('redemption/delete/{id}', 'RedemptionController@delete');
    Route::get('zone/delete/{id}', 'ZoneController@delete');
    Route::get('bank/delete/{id}', 'BankController@delete');
    Route::get('voucher_option/delete/{id}', 'VoucherOptionController@delete');
    Route::get('member/delete/{id}', 'MemberController@delete');
    Route::get('voucher_banner/delete/{id}', 'VoucherBannerController@delete');
    Route::get('zone_member/delete/{id}', 'ZoneMemberController@delete');
    Route::get('retainer_gallery/delete/{id}', 'RetainerGalleryController@delete');

    Route::get('order_pickup/update/{id}', 'OrderPickupController@update');

    Route::get('payment/{id}/updateStatus', 'PaymentController@updateStatus');
    Route::get('payment/delete/{id}', 'PaymentController@delete');
    
    

    Route::get('order/sendmail/{id}/{type}', 'OrderController@sendEmailOrder');
    Route::get('order/{id}/update_stock', 'OrderController@updateStock');

    Route::get('redemption_export', 'RedemptionController@export');

    Route::any('edit_field', 'Controller@editField');

    Route::get('logout', function() {
        Auth::logout();
        return redirect('admin');
    });

    Route::any('dashboard', 'Controller@dashboard');

    Route::get('laravel-filemanager', '\Unisharp\Laravelfilemanager\controllers\LfmController@show');
    Route::post('laravel-filemanager/upload', '\Unisharp\Laravelfilemanager\controllers\UploadController@upload');

    Route::any('user/profile', 'UserController@profile');
    Route::any('user/change_password', 'UserController@changePassword');

    Route::get('restore/{model}/{id}', 'Controller@restore');

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

Route::group(['prefix' => 'front'], function() {

    Route::get('/', 'Front\MemberController@signin');
    Route::post('/login', 'Front\MemberController@postSignin');
    Route::post('forgotpassword', 'Front\MemberController@forgotPassword');

    Route::get('login/facebook', 'Front\SocialLoginController@facebookRedirectToProvider');
    Route::get('login/facebook/callback', 'Front\SocialLoginController@facebookHandleProviderCallback');

    Route::get('login/google', 'Front\SocialLoginController@googleRedirectToProvider');
    Route::get('login/google/callback', 'Front\SocialLoginController@googleHandleProviderCallback');

    Route::group(['middleware' => 'frontAuth'], function() {

        Route::get('dashboard', 'Front\MemberController@dashboard');

        Route::post('member/profile/{id}', 'Front\MemberController@profile');
        Route::post('member/change_password/{id}', 'Front\MemberController@changePassword');

        Route::post('invite_code', 'Front\MemberController@inviteCode');
        Route::get('get_member_home/{id}', 'Front\MemberController@getHomeMemberData');


        Route::get('orders/{id}', 'Front\EorderController@getOrders');
        Route::get('order/detail', 'Front\EorderController@getOrderDetail');
        Route::post('order/confirm', 'Front\EorderController@confirm');
        Route::post('order/comment', 'Front\EorderController@addComment');

        Route::post('pickup/{id}', 'Front\PickupController@confirm');
        Route::any('pickup/delete/{id}', 'Front\PickupController@cancelOrderPickup');

        Route::get('reward', 'Front\VoucherController@home');
        Route::get('reward/detail/{id}', 'Front\VoucherController@getVoucherDetail');
        Route::get('reward/history/{id}', 'Front\VoucherController@getRewardHistory');
        Route::post('reward/add_cart', 'Front\VoucherController@addVoucherToCart');
        Route::get('reward/remove_voucher/{id}', 'Front\VoucherController@removeVoucherList');
        Route::get('reward/get_voucher_cart', 'Front\VoucherController@getVoucherCart');
        Route::get('reward/get_doctor_address', 'Front\VoucherController@getDoctorAddress');
        Route::post('reward/redeem', 'Front\VoucherController@redeem');
        Route::post('reward/redeem_voucher_cart', 'Front\VoucherController@redeemVoucherCart');
        Route::get('reward/get_reward_to_modal', 'Front\VoucherController@getRewardToModal');

        Route::get('payment/home/{id}', 'Front\PaymentController@getMemberInvoices');

        Route::get('logout', 'Front\MemberController@logout');

        Route::get('bill/home', 'Front\BillController@home')->name('front.bill.home');
        Route::get('bill/detail/{id}', 'Front\BillController@getBillDetail');
        Route::get('bill/preview', 'Front\BillController@getPreviewBills');
        Route::get('bill/delete/{id}', 'PaymentController@delete');

        Route::get('transfer/home', 'Front\TransferController@home')->name('transfer.home');
        Route::post('transfer/upload', ['as'=>'transfer.upload','uses'=>'Front\TransferController@fileUploadPost']);
        Route::get('transfer/delete', ['as'=>'transfer.delete','uses'=>'Front\TransferController@fileDelete']);
        Route::get('transfer/confirm', ['as'=>'transfer.confirm','uses'=>'Front\TransferController@updateConfirm']);

        Route::post('member/loginExternal', 'Front\MemberController@postSigninOnlineOrdering');
        Route::post('member/loginExternalSit', 'Front\MemberController@postSigninOnlineOrderingSit');
    });
});

Route::post('api/line','MemberController@lineWebHook');