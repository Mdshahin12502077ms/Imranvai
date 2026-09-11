<?php

use App\Http\Controllers\Backend\Farhad\CategoryController;
use App\Http\Controllers\Backend\Farhad\ContactMessageController as BackendContactMessageController;
use App\Http\Controllers\Backend\Farhad\DashboardController;
use App\Http\Controllers\Backend\Farhad\StatusController;
use App\Http\Controllers\Backend\Setting\AdminSettingController;
use App\Http\Controllers\Backend\Setting\MailSettingController;
use App\Http\Controllers\Backend\Setting\ManagerController;
use App\Http\Controllers\Backend\Setting\ProfileSettingController;
use App\Http\Controllers\Backend\Setting\SocialSettingController;
use App\Http\Controllers\Backend\Setting\StripeSettingController;
use App\Http\Controllers\Backend\Setting\SystemSettingController;
use App\Http\Controllers\Backend\Farhad\EveryconditionTermController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationCController;
use Illuminate\Support\Facades\Route;




Route::middleware(['auth:web', 'role:admin,manager'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard route
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Landing Page Routes
    Route::prefix('landing-page')->name('landing-page.')->group(function () {
        // Banners
        Route::get('banners', [\App\Http\Controllers\Backend\Farhad\LandingPage\BannerController::class, 'index'])->name('banners.index');
        Route::post('banners', [\App\Http\Controllers\Backend\Farhad\LandingPage\BannerController::class, 'store'])->name('banners.store');
        Route::post('banners/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\BannerController::class, 'update'])->name('banners.update');
        Route::delete('banners/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\BannerController::class, 'destroy'])->name('banners.destroy');
        Route::post('banners/status/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\BannerController::class, 'statusUpdate'])->name('banners.statusUpdate');

        // Taglines
        Route::get('taglines', [\App\Http\Controllers\Backend\Farhad\LandingPage\TaglineBarController::class, 'index'])->name('taglines.index');
        Route::post('taglines', [\App\Http\Controllers\Backend\Farhad\LandingPage\TaglineBarController::class, 'store'])->name('taglines.store');
        Route::post('taglines/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\TaglineBarController::class, 'update'])->name('taglines.update');
        Route::delete('taglines/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\TaglineBarController::class, 'destroy'])->name('taglines.destroy');
        Route::post('taglines/status/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\TaglineBarController::class, 'statusUpdate'])->name('taglines.statusUpdate');

        // Features
        Route::get('features', [\App\Http\Controllers\Backend\Farhad\LandingPage\FeatureController::class, 'index'])->name('features.index');
        Route::post('features', [\App\Http\Controllers\Backend\Farhad\LandingPage\FeatureController::class, 'store'])->name('features.store');
        Route::post('features/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\FeatureController::class, 'update'])->name('features.update');
        Route::delete('features/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\FeatureController::class, 'destroy'])->name('features.destroy');
        Route::post('features/status/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\FeatureController::class, 'statusUpdate'])->name('features.statusUpdate');

        // Why EV Systems
        Route::get('why-ev', [\App\Http\Controllers\Backend\Farhad\LandingPage\WhyEvSystemController::class, 'index'])->name('why-ev.index');
        Route::post('why-ev', [\App\Http\Controllers\Backend\Farhad\LandingPage\WhyEvSystemController::class, 'store'])->name('why-ev.store');
        Route::post('why-ev/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\WhyEvSystemController::class, 'update'])->name('why-ev.update');
        Route::delete('why-ev/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\WhyEvSystemController::class, 'destroy'])->name('why-ev.destroy');
        Route::post('why-ev/status/{id}', [\App\Http\Controllers\Backend\Farhad\LandingPage\WhyEvSystemController::class, 'statusUpdate'])->name('why-ev.statusUpdate');
    });

    Route::controller(ProductController::class)->group(function(){
        Route::get('products', 'index')->name('products.index');
        Route::get('products/create', 'create')->name('products.create');
        Route::post('products', 'store')->name('products.store');
        Route::get('products/{product}', 'show')->name('products.show');
        Route::get('products/{product}/edit', 'edit')->name('products.edit');
        Route::put('products/{product}', 'update')->name('products.update');
        Route::delete('products/{product}', 'destroy')->name('products.destroy');
        Route::post('products/status/{id}', 'statusUpdate')->name('products.statusUpdate');
        Route::delete('product-images/{id}', 'destroyImage')->name('product-images.destroy');
    });

    /////////////////////product variation//////////////

    Route::controller(ProductVariationCController::class)->group(function(){
        Route::get('product-variations', 'index')->name('product-variations.index');
        Route::get('product-variations/create', 'create')->name('product-variations.create');
        Route::post('product-variations', 'store')->name('product-variations.store');
        Route::get('product-variations/{product-variation}', 'show')->name('product-variations.show');
        Route::get('product-variations/{product-variation}/edit', 'edit')->name('product-variations.edit');
        Route::put('product-variations/{product-variation}', 'update')->name('product-variations.update');
        Route::delete('product-variations/{product-variation}', 'destroy')->name('product-variations.destroy');
        Route::post('product-variations/status/{id}', 'statusUpdate')->name('product-variations.statusUpdate');
        Route::delete('product-variations/spec/{id}', 'destroySpec')->name('product-variations.destroySpec');
    });

        Route::controller(EveryconditionTermController::class)->group(function(){
        Route::get('product-conditions', 'index')->name('productCondition.index');
        Route::get('product-conditions/create', 'create')->name('productCondition.create');
        Route::post('product-conditions', 'store')->name('productCondition.store');
        Route::get('product-conditions/{id}/edit', 'edit')->name('productCondition.edit');
        Route::post('product-conditions/{id}', 'update')->name('productCondition.update');
        Route::put('product-conditions/{id}', 'update')->name('productCondition.putUpdate');
        Route::delete('product-conditions/{id}', 'destroy')->name('productCondition.destroy');
        Route::post('product-conditions/status/{id}', 'statusUpdate')->name('product-conditions.statusUpdate');
    });

    // Contact Messages routes
    Route::controller(BackendContactMessageController::class)->group(function(){
        Route::get('contact-messages', 'index')->name('contact-messages.index');
        Route::get('contact-messages/{id}', 'show')->name('contact-messages.show');
        Route::delete('contact-messages/{id}', 'destroy')->name('contact-messages.destroy');
        Route::post('contact-messages/status/{id}', 'statusUpdate')->name('contact-messages.statusUpdate');
    });

    // Categories routes
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');


    //Status
    Route::post('/update-status', [StatusController::class, 'update'])->name('status.update');

    // ------------------- Settings routes start ------------------
    // Profile settings routes
    Route::get('settings/profile', [ProfileSettingController::class, 'edit'])->name('profile-settings.edit');
    Route::post('settings/profile/{id}', [ProfileSettingController::class, 'update'])->name('profile-settings.update');
    Route::post('settings/profile/change-password', [ProfileSettingController::class, 'changePassword'])->name('profile-settings.change-password');

    // Manager management routes
    Route::get('settings/managers', [ManagerController::class, 'index'])->name('managers.index');
    Route::post('settings/managers', [ManagerController::class, 'store'])->name('managers.store');
    Route::put('settings/managers/{id}', [ManagerController::class, 'update'])->name('managers.update');
    Route::delete('settings/managers/{id}', [ManagerController::class, 'destroy'])->name('managers.destroy');

    // Social settings routes
    Route::get('settings/social', [SocialSettingController::class, 'edit'])->name('social-settings.edit');
    Route::post('settings/social', [SocialSettingController::class, 'update'])->name('social-settings.update');

    // Mail settings routes
    Route::get('settings/mail', [MailSettingController::class, 'edit'])->name('mail-settings.edit');
    Route::post('settings/mail', [MailSettingController::class, 'update'])->name('mail-settings.update');

    // Stripe Settings routes
    Route::get('settings/stripe', [StripeSettingController::class, 'edit'])->name('stripe-settings.edit');
    Route::post('settings/stripe', [StripeSettingController::class, 'update'])->name('stripe-settings.update');

    // System Settings routes
    Route::get('settings/system', [SystemSettingController::class, 'edit'])->name('system-settings.edit');
    Route::post('settings/system', [SystemSettingController::class, 'update'])->name('system-settings.update');

    // Admin Settings routes
    Route::get('settings/admin', [AdminSettingController::class, 'edit'])->name('admin-settings.edit');
    Route::post('settings/admin', [AdminSettingController::class, 'update'])->name('admin-settings.update');
    // ------------------- Settings routes end ------------------
});
