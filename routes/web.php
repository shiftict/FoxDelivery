<?php
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\{DeliveryController,
    AreaController,
    CityController,
    MethodShippingConroller,
    OrderController,
    VendorController,
    ReportesControllers,
    UsersController,
    PackagesController};
use App\Http\Resources\Delivery\Deliveryresource;
use App\Models\Delivery;
use App\Http\Resources\Delivery\DeliveryVendorresource;
use App\Models\{Drivers, Order};
use Carbon\Carbon;

Route::get('',function (){return redirect()->route('dashboard.index');});

Route::get('change-language/{locale}', [LocaleController::class, 'switch_lang'])->name('change.language');
/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    Route::get('/', function() {
        if(Auth::user()->hasRole('superadministrator') || Auth::user()->hasRole('administrator')) {
            $drivers = Delivery::where('status', '1')
            ->with('acitve_driver','active_order')
                ->whereHas('acitve_driver')
                // ->orWhereHas('active_order')
                ->get();
                
            // return $drivers;
            $drivers_collection = Deliveryresource::collection($drivers);
            return view('panel.home.index', compact('drivers_collection'));
        } else {
            $drivers = Drivers::query()
            ->where('user_id', auth()->id())
            ->with('user')
            ->whereHas('user', function($q) {
                $q->where('is_online', '1');
            })
            ->get();
            $drivers_collection = DeliveryVendorresource::collection($drivers);
            return view('panel.home.index', compact('drivers_collection'));
        }
        
    })->name('dashboard.index');

    /*************[ route delivery ]***********/
    Route::get('vendor/datatable', [VendorController::class, 'datatable'])->name('vendor.datatable');
    Route::post('vendor/status', [VendorController::class, 'status'])->name('vendor.status');
    Route::post('vendor/attacheds', [VendorController::class, 'uploade']);
    Route::get('vendor/{id}/city', [VendorController::class, 'city'])->name('vendor.city');
    Route::post('vendor/city', [VendorController::class, 'addCity'])->name('vendor.city.add');
    Route::post('V1/vendor', [VendorController::class, 'store'])->name('vendor.storeApi');
    Route::get('vendor/setting', [VendorController::class, 'setting'])->name('vendor.settings');
    Route::post('vendor/update_passwords', [VendorController::class, 'updatePassword'])->name('vendor.update.password');
    Route::get('vendor/{id}/file', [VendorController::class, 'showFile'])->name('vendor.show.file');
    Route::get('employee/vendors', [VendorController::class, 'employeeVendors'])->name('employee.vendors');
    Route::get('employee_vendor/datatable', [VendorController::class, 'employeeDatatable'])->name('employee.vendor.datatable');
    Route::get('employee_active', [VendorController::class, 'employeeActive'])->name('employee.active');
    Route::post('vendor/edit_file', [VendorController::class, 'editFile'])->name('vendor.edit.file');
    Route::get('delete/{id}/attachment', [VendorController::class, 'deleteFile'])->name('delete.attachment');
    Route::get('vendor/get_vendor', [VendorController::class, 'getAllVendor'])->name('vendor.get.vendor');
    Route::post('vendor/get_vendor_new', [VendorController::class, 'getAllVendorWithPackages'])->name('vendor.get.vendor.new');
    Route::post('vendor/get_vendor_details_new', [VendorController::class, 'getAllVendorDetails'])->name('vendor.details.vendor.new');
    Route::post('vendor/get_vendor_peer_hours', [VendorController::class, 'getAllVendorForPeerHours'])->name('vendor.for.schedule');
    Route::get('vendor/get_map_admin', [VendorController::class, 'mapAdmin'])->name('map.driver');
    Route::get('vendor/get_map_vendor', [VendorController::class, 'mapVendorTracking'])->name('map.my.driver.vendors');
    Route::resource('vendor', VendorController::class);

    /*************[ route order ]***********/
    Route::get('order/datatable', [OrderController::class, 'datatable'])->name('order.datatable');
    Route::post('order/status', [OrderController::class, 'status'])->name('order.status');
    Route::post('order/status/delivery', [OrderController::class, 'statusOrder'])->name('order.status.order');
    Route::post('order/attacheds', [OrderController::class, 'uploade']);
    Route::get('order/{id}/driver', [OrderController::class, 'driver'])->name('order.driver');
    Route::post('order/driver', [OrderController::class, 'setDriver'])->name('order.set.driver');
    Route::get('order/{id}/notification', [OrderController::class, 'readNotification'])->name('read.order');
    Route::get('order/{id}/notification_vendor', [OrderController::class, 'readNotificationVendor'])->name('read.order.vendor');
    Route::get('order/employee/show', [OrderController::class, 'employeeOrderindex'])->name('read.order.employee');
    Route::get('order/employee_order/datatable', [OrderController::class, 'employeeOrderDatatable'])->name('employee.order.datatable');
    Route::post('order/peer-order', [OrderController::class, 'peerOrder'])->name('vendor.storeApi.peer.order');
    Route::post('order/peer-hours', [OrderController::class, 'peerHours'])->name('vendor.storeApi.peer.hours');
    Route::post('order/admin-order', [OrderController::class, 'adminCreateOrder'])->name('vendor.storeApi.admin.order');
    Route::resource('order', OrderController::class);

    /*************[ route area ]***********/
    Route::get('area/datatable', [AreaController::class, 'datatable'])->name('area.datatable');
    Route::post('area/status', [AreaController::class, 'status'])->name('area.status');
    Route::get('area/with-city', [AreaController::class, 'all'])->name('get_rea');
    Route::resource('area', AreaController::class);

    /*************[ route city ]***********/
    Route::get('city/datatable', [CityController::class, 'datatable'])->name('city.datatable');
    Route::post('city/status', [CityController::class, 'status'])->name('city.status');
    Route::resource('city', CityController::class);

    /*************[ route delivery ]***********/
    Route::get('delivery/datatable', [DeliveryController::class, 'datatable'])->name('delivery.datatable');
    Route::post('delivery/status', [DeliveryController::class, 'status'])->name('delivery.status');

    // vue.js
    Route::post('get_delivery', [DeliveryController::class, 'vueDrivers'])->name('get_drivers_vue');
    Route::post('get_delivery_new', [DeliveryController::class, 'vueDriversNew'])->name('get_drivers_vue_order');
    Route::get('scheduling_drivers', [DeliveryController::class, 'schedulingDrivers'])->name('drivers.scheduling.drivers');
    Route::post('scheduling_drivers', [DeliveryController::class, 'schedulingDriversInsert'])->name('drivers.scheduling.drivers.post');
    Route::post('scheduling_drivers_by_date', [DeliveryController::class, 'schedulingDriversInsertByDate'])->name('drivers.scheduling.drivers.post.by.date');
    Route::post('scheduling_drivers_vue', [DeliveryController::class, 'schedulingDriversVue'])->name('drivers.scheduling.drivers.vue');
    Route::post('get_delivery_admin_new', [DeliveryController::class, 'vueDriversAdminNew'])->name('get.drivers.admin');

    Route::resource('delivery', DeliveryController::class);

    /*************[ route MethodShipping ]***********/
    Route::get('method_shipping/datatable', [MethodShippingConroller::class, 'datatable'])->name('method_shipping.datatable');
    Route::post('method_shipping/status', [MethodShippingConroller::class, 'status'])->name('method_shipping.status');
    Route::resource('method_shipping', MethodShippingConroller::class);

    /*************[ route delivery ]***********/
    Route::get('users/datatable', [UsersController::class, 'datatable'])->name('users.datatable');
    Route::get('users/{id}/permission', [UsersController::class, 'permission'])->name('users.permission');
    Route::post('users/permission', [UsersController::class, 'setPermission'])->name('users.update.permission');
    Route::post('users/status', [UsersController::class, 'status'])->name('users.status');
    Route::get('users/index/vendor', [UsersController::class, 'indexEmployee'])->name('users.index.vendor');
    Route::get('users/create/vendor', [UsersController::class, 'createEmployee'])->name('users.create.vendor');
    Route::post('users/store/vendor', [UsersController::class, 'storeEmployee'])->name('users.store.vendor');
    Route::get('users/{id}/edit/vendor', [UsersController::class, 'editEmployee'])->name('users.edit.vendor');
    Route::put('users/update/vendor', [UsersController::class, 'updateEmployee'])->name('users.update.vendor');
    Route::get('users/update/vendor', [UsersController::class, 'datatableEmployee'])->name('users.datatable.vendor');
    Route::resource('users', UsersController::class);

    /*************[ route Package ]***********/
    Route::get('packages/datatable', [PackagesController::class, 'datatable'])->name('packages.datatable');
    Route::post('packages/status', [PackagesController::class, 'status'])->name('packages.status');
//    Route::get('packages/{id}/new', [PackagesController::class, 'create'])->name('packages.newPackage');
    // route new package
    Route::get('packages/subscription', [PackagesController::class, 'createPackeage'])->name('packages.newPackage.new.subscription');
    Route::post('packages/subscription', [PackagesController::class, 'storePackeage'])->name('packages.newPackage.new.subscription.post');
    Route::get('packages/{id}', [PackagesController::class, 'create'])->name('packages.newPackage.for.user');
    Route::get('packages/{id}/renewal', [PackagesController::class, 'renewal'])->name('packages.rnewPackage.for.user');
    Route::post('packages/renewal', [PackagesController::class, 'renewalPost'])->name('packages.rnewPackage.post.user');
    Route::put('packages/order/{id}', [PackagesController::class, 'updatePeerOrder'])->name('packages.order.edit');
    Route::get('packages/{id}/show', [PackagesController::class, 'show'])->name('packages.edit.show');
    Route::post('packages/update/vlaue', [PackagesController::class, 'updateActivePackage'])->name('packages.edit.update');
    Route::resource('packages', PackagesController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    /*************[ route pdf ]***********/
    Route::get('orders/reports', [ReportesControllers::class, 'orderReports'])->name('order.report');
    Route::get('orders/datatable/reports', [ReportesControllers::class, 'ordersDatatable'])->name('orders.datatable');

    Route::group(['prefix' => 'deliveries', 'middleware' => ['role:superadministrator']], function() {
        Route::get('reports', [ReportesControllers::class, 'deliveryReports'])->name('deliveries.report');
        Route::get('datatable/reports', [ReportesControllers::class, 'deliveryDatatable'])->name('deliveries.datatable');
    });
    Route::group(['prefix' => 'vendors', 'middleware' => ['role:superadministrator']], function() {
        Route::get('reports', [ReportesControllers::class, 'vendorsReports'])->name('vendors.report');
        Route::get('custom/reports', [ReportesControllers::class, 'vendorsCustomReports'])->name('vendors.custom.report');
        Route::get('datatable/reports', [ReportesControllers::class, 'vendorsDatatable'])->name('vendors.datatable');
    });

    /*************[ route pdf ]***********/
    Route::get('printPdf/order/{id}', [ReportesControllers::class, 'orderPdf'])->name('order.printPdf');
    Route::get('printPdf/order/{id}/employee', [ReportesControllers::class, 'orderPdfEmployee'])->name('order.printPdf.employee');
    Route::get('printPdf/delivery/{id}', [ReportesControllers::class, 'deliveryPdf'])->name('deliveries.printPdf');
    Route::get('printPdf/vendors/{id}', [ReportesControllers::class, 'vendorsPdf'])->name('vendors.printPdf');
    
    // report by filter
    Route::post('printPdf/order/filter', [ReportesControllers::class, 'orderPdfFilter'])->name('order.printPdfFilter');
    Route::post('printXLS/order/filter', [ReportesControllers::class, 'orderXLSFilter'])->name('order.orderXLSFilter');
    Route::post('printPdf/vendors/filter', [ReportesControllers::class, 'vendorsPdfFilter'])->name('vendor.printPdfFilter');
    Route::post('printXLS/vendors/filter', [ReportesControllers::class, 'vendorsXLSFilter'])->name('vendor.vendorXLSFilter');
    Route::post('printXLS/vendors/custom/filter', [ReportesControllers::class, 'vendorsCustomXLSFilter'])->name('vendor.custom.vendorXLSFilter');
    Route::post('printPdf/delivery/filter', [ReportesControllers::class, 'deliveryPdfFilter'])->name('deliveries.printPdfFilter');
    Route::post('printXLS/delivery/filter', [ReportesControllers::class, 'deliveryXLSFilter'])->name('deliveries.printXLSFilter');
    
    /*************[ slider apk ]***********/
    Route::get('apk/attacheds', [ReportesControllers::class, 'apkUploadePage'])->name('apk.attacheds');
    Route::post('apk/attacheds', [ReportesControllers::class, 'apkUploade'])->name('apk.attacheds.post');
    Route::get('apk/attacheds/{id}/delete', [ReportesControllers::class, 'apkDeleteImage'])->name('delete.attachment.apk');
});

Auth::routes([
    'register' => false, // Registration Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/home', function() {
    return redirect()->route('dashboard.index');
})->name('home');

Route::get('tracking-order/{id}', function ($id) {
    $order = Order::with(['delivery'])->findOrFail($id)->format();
    return view('track_order', compact('order')); 
})->name('genrate.order');


Route::get('lat_long/{id}', function ($id) {
    $order = Order::with(['delivery'])->findOrFail($id)->format();
    return $order; 
})->name('map.track.order');

Route::get('clear/route', function() {
    // return \Artisan::call('storage:link', []);
    // return \Artisan::call('storage:link');
    \Artisan::call('route:clear');
    \Artisan::call('optimize:clear');
    \Artisan::call('view:clear');
    \Artisan::call('config:cache');
});

