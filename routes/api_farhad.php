<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\FrontendAdminSettingController;
use App\Http\Controllers\Api\FrontendLandingPageController;
use App\Http\Controllers\Api\FrontendProductCondoditionController;
use App\Http\Controllers\Api\FrontendProductController;
use App\Http\Controllers\Api\MyorderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\PayPalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;






Route::middleware(['setLang'])->group(function () {
    // Public routes or lang specific
});

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/login', [AuthController::class, 'login']);

// Forgot Password for customer
Route::post('/password/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/password/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);



// Contact Us Message route
Route::post('/pages/contact-us-message', [ContactMessageController::class, 'store']);

//Profile Settings both Customer and Driver/Deliveryman
Route::middleware('auth:api')->group(function () {

    // language toggle update
    Route::post('/language-toggle', [ProfileController::class, 'toggleLanguage']);

    // Profile routes
    Route::get('/profile-info', [ProfileController::class, 'profileInfo']);
    Route::post('/profile-update', [ProfileController::class, 'profileUpdate']);
    Route::post('/profile-change-password', [ProfileController::class, 'changePassword']);
    Route::post('/profile-change-address', [ProfileController::class, 'changeAddress']);
    Route::post('/profile-delete', [ProfileController::class, 'profileDelete']);
    Route::post('/profile-update-location', [ProfileController::class, 'updateLocation']); // update location lat long
  // Need for app publications
   Route::post('app-account-delete', [ProfileController::class, 'appAccountDelete']);

   Route::controller(FrontendLandingPageController::class)->group(function(){
     Route::get('landing-page', 'index')->name('landing-page.index');
   });

   Route::controller(FrontendProductController::class)->group(function(){
     Route::get('products', 'index')->name('products.index');
     Route::get('products/{product}', 'show')->name('products.show');
     Route::get('products/{product}/variations', 'getVariations')->name('products.getVariations');
   });


   Route::controller(PayPalController::class)->group(function(){
     Route::post('paypal/payment', 'handlePayment')->name('paypal.create-payment');
     Route::get('paypal/success', 'paymentSuccess')->name('paypal.success');
     Route::get('paypal/cancel', 'paymentCancel')->name('paypal.cancel');
   });

   Route::controller(FrontendProductCondoditionController::class)->group(function(){
     Route::get('product-conditions', 'index')->name('productCondition.index');
   });

   Route::controller(MyorderController::class)->group(function(){
     Route::get('my-orders', 'index')->name('my-orders.index');
     Route::get('my-orders/{order}', 'show')->name('my-orders.show');
   });

   Route::get('admin-data', [FrontendAdminSettingController::class, 'getAdminData'])->name('admin-data');

});

 //apps delete account inside public folder html: account-delete.html


