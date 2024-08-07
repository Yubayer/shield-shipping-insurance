<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Api\SettingsController as ApiSettingsController;
use App\Http\Controllers\Api\WidgetsController as ApiWidgetsController;
use App\Http\Controllers\Api\WebhookController as ApiWebhookController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// rules sync with shop metafields
Route::post('/rules/sync', [SettingsController::class, 'syncRuleWithShopMetafields'])->name('rules.sync');
Route::post('/protection/variant-check', [SettingsController::class, 'ProtectionVariantCheck']);
Route::post('/protection/variant-delete', [SettingsController::class, 'ProtectionVariantDelete']);

Route::match(['get', 'post'], '/checkout-ui/config', [SettingsController::class, 'configCheckoutUi']);
Route::match(['get', 'post'], '/app-activate-api', [ApiSettingsController::class, 'AppActivate'])->name('settings.api.app-activate');
Route::match(['get', 'post'], '/app-is-modal', [ApiSettingsController::class, 'otpInModalOrCheckbox'])->name('settings.api.is-modal-activate');

Route::match(['get', 'post'], '/app-widgets-update', [ApiWidgetsController::class, 'index'])->name('app.widgets.update');

//widget sync with shop metafields
Route::post('/widget/sync', [ApiWidgetsController::class, 'syncWidgetWithShopMetafields'])->name('api.widget.sync');
