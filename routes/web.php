<?php

use Illuminate\Support\Facades\Route;

//use IndexController
use App\Http\Controllers\{
    IndexController,
    SettingsController,
    WidgetsController,
    WebhookController,
    PricingController,
    DashboardController,
    AuthController
};

use App\Http\Controllers\Api\WebhookController as ApiWebhookController;



Route::match(['get', 'post'], '/auth-shop', [AuthController::class, 'authenticate'])->name('auth-shop');

Route::group(['middleware' => ['verify.shopify']], function () {
    Route::get('/', [IndexController::class, 'index'])->name('home');
    // Route::get('/', [SettingsController::class, 'index'])->name('home');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::match(['get', 'post'], '/settings/rule-create', [SettingsController::class, 'ruleCreate'])->name('settings.rule.create');
    Route::match(['get', 'post'], '/app-activate', [SettingsController::class, 'AppActivate'])->name('settings.app-activate');

    //dashboard route
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    // multiple method route
    Route::match(['get', 'post'], '/filter-dashboard', [DashboardController::class, 'dashboardFilter'])->name('filter.dashboard');
    Route::match(['get', 'post'], '/filter-dashboard-new', [DashboardController::class, 'dashboardFilterNew'])->name('filter.dashboard-new');

    //widgets route
    Route::match(['get', 'post'], '/widgets', [WidgetsController::class, 'index'])->name('widgets.index');

    // pricing route
    Route::get('/app-pricing', [PricingController::class, 'plan'])->name('app.pricing');
    Route::post('/app-plan-create', [PricingController::class, 'createPlan'])->name('app.plan-create');

});

// Route::group(['middleware' => ['auth.webhook']], function () {
    //app uninstall webhook
    Route::post('/public/webhook/app/uninstalled', [ApiWebhookController::class, 'webhookAppUninstalled'])->name('webhook.app.uninstalled');
    //orders paid webhook
    Route::post('/public/webhook/orders/paid', [ApiWebhookController::class, 'webhookOrdersPaid'])->name('webhook.orders.paid');

    //cart udpate webhook
    Route::post('public/webhook/carts/update', [ApiWebhookController::class, 'webhookCartsUpdate'])->name('webhook.carts.update');
// });


