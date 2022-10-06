<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Drivers\{AuthController,
    OrderController,
    StatusController,
    NotificationController
};
use App\Http\Controllers\Api\V1\Vendors\{VendorsOrderController};
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

Route::pattern('id', '[0-9]+'); // only id its number

//*****************[ drivers ]******************
Route::prefix('v1/drivers')->group(function () {

    //*****************[ drivers-auth ]******************//
    Route::post('login', [AuthController::class, 'login'])->name('api.driver.login');
    
    //*****************[ slider-apk ]******************//
    Route::get('slider_apk', [NotificationController::class, 'sliderApk'])->name('api.driver.slider');

    //*****************[ drivers-routes-with-auth ]******************//
    Route::middleware(['auth:sanctum'])->group(function () {
    // Route::group(['middleware' => ['auth:sanctum']], function () {


        //*****************[ drivers-logout ]******************//
        Route::post('logout', [AuthController::class, 'logout'])->name('api.driver.logout');

        //*****************[ drivers-logout ]******************//
        Route::put('change-location', [AuthController::class, 'changeLocation'])->name('api.driver.change.location');

        //*****************[ drivers-logout ]******************//
        Route::put('check-shift', [AuthController::class, 'checkShift'])->name('api.driver.online');

        //*****************[ drivers-update-password ]******************//
        Route::post('update-password', [AuthController::class, 'updatePassword'])->name('api.driver.update.password');

        //*****************[ all-orders ]******************//
        Route::get('all_orders', [OrderController::class, 'index'])->name('api.driver.all.orders');

        //*****************[ pending-orders ]******************//
        Route::get('pending_orders', [OrderController::class, 'pendingOrders'])->name('api.driver.pending.orders');

        //*****************[ show-info-orders ]******************//
        Route::get('orders/{id}/show', [OrderController::class, 'show'])->name('api.driver.orders.show.info');

        //*****************[ change-status-orders ]******************//
        Route::put('orders/{id}/status', [OrderController::class, 'changeStatus'])->name('api.driver.orders.change.status');
        
        //*****************[ change-status-orders ]******************//
        Route::get('orders/active', [OrderController::class, 'orderActive'])->name('api.driver.orders.active');

        //*****************[ status ]******************//
        Route::get('status', [StatusController::class, 'index'])->name('api.driver.get.status');

        //*****************[ get-all-notification ]******************//
        Route::get('all_notification', [NotificationController::class, 'index'])->name('api.driver.get.notification');

        //*****************[ read-notification ]******************//
        Route::put('read_notification', [NotificationController::class, 'read_at_notification'])->name('api.driver.read.notification');
        
        //*****************[ count-notification ]******************//
        Route::get('count_notification', [NotificationController::class, 'countNotification'])->name('api.driver.count.notification');
    });
});

//*****************[ vendors ]******************
Route::prefix('v1/vendors')->group(function () {
    
    //*****************[ vendors-auth ]******************//
    Route::post('login', [AuthController::class, 'login_vendor'])->name('api.vendor.login');
    
    //*****************[ drivers-routes-with-auth ]******************//
    Route::middleware(['auth:sanctum'])->group(function () {
    
        //*****************[ vendors-create-order ]******************//
        Route::post('create_order', [VendorsOrderController::class, 'create'])->name('api.vendor.create.order');
    });
    
});

//*****************[ vendors ]******************
Route::prefix('v1/lock_up')->group(function () {
    
    //*****************[ lock up ]******************//
    Route::get('', [VendorsOrderController::class, 'lockUp'])->name('api.vendor.login');
    
});
