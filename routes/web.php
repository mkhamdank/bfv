<?php

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

Route::get('/', function () {

    // return('Hello World');
    return redirect()->route('login');
});

// tes

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//WPOS
Route::get('wpos', 'App\Http\Controllers\VendorController@wpos');
Route::post('post/wpos', 'App\Http\Controllers\VendorController@inputWpos');

Route::group(['middleware' => ['auth'], 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'DashboardController@index')->name('admin.dashboard');

    // route group prefix admin/role
    Route::group(['prefix' => 'role'], function () {

        Route::get('/', 'RoleController@index')->name('admin.role.index');
        Route::get('/create', 'RoleController@create')->name('admin.role.create');
        Route::post('/store', 'RoleController@store')->name('admin.role.store');
        Route::get('/show/{id}', 'RoleController@show')->name('admin.role.show');
        Route::get('/edit/{id}', 'RoleController@edit')->name('admin.role.edit');
        Route::put('/update/{id}', 'RoleController@update')->name('admin.role.update');
        Route::delete('/destroy/{id}', 'RoleController@destroy')->name('admin.role.destroy');

    });

    // route group prefix admin/permission
    Route::group(['prefix' => 'permission'], function () {

        Route::get('/', 'PermissionController@index')->name('admin.permission.index');
        Route::get('/create', 'PermissionController@create')->name('admin.permission.create');
        Route::post('/store', 'PermissionController@store')->name('admin.permission.store');
        Route::get('/show/{id}', 'PermissionController@show')->name('admin.permission.show');
        Route::get('/edit/{id}', 'PermissionController@edit')->name('admin.permission.edit');
        Route::put('/update/{id}', 'PermissionController@update')->name('admin.permission.update');
        Route::delete('/destroy/{id}', 'PermissionController@destroy')->name('admin.permission.destroy');

    });

    // route group prefix admin/permission
    Route::group(['prefix' => 'user', 'middleware' => ['can:view users']], function () {

        Route::get('/', 'UserController@index')->name('admin.user.index');
        Route::get('/create', 'UserController@create')->name('admin.user.create');
        Route::post('/store', 'UserController@store')->name('admin.user.store');
        Route::get('/show/{id}', 'UserController@show')->name('admin.user.show');
        Route::get('/edit/{username}', 'UserController@edit')->name('admin.user.edit');
        Route::put('/update/{id}', 'UserController@update')->name('admin.user.update');
        Route::delete('/destroy/{id}', 'UserController@destroy')->name('admin.user.delete');

        Route::get('/getUsers', 'UserController@getUsers')->name('admin.user.getUsers');
        Route::get('/getPermissions', 'UserController@getPermissions')->name('admin.user.getPermissions');
        // Route::get('/getPermissionsUser/{id}', 'UserController@getPermissionsUser')->name('admin.user.getPermissionsUser');

    });

    // route group prefix admin/vfi
    Route::group(['prefix' => 'vfi'], function () {

        Route::get('/', 'VFIController@index')->name('admin.vfi.index');

        Route::get('index/true', 'VFIController@indexVFITrue');
        Route::post('input/true', 'VFIController@inputVFITrue')->name('admin.vfi.input_true');
        Route::get('fetch/true', 'VFIController@fetchVFITrue')->name('admin.vfi.fetch_true');

        Route::get('fetch/kensa/serial_number/{vendor}', 'VFIController@fetchKensaSerialNumber');

        Route::get('index/ng_rate/{vendor}', 'VFIController@indexVFINGRate');
        Route::get('fetch/ng_rate/{vendor}', 'VFIController@fetchVFINGRate')->name('admin.vfi.fetch_ng_rate');
        Route::get('fetch/ng_rate/detail/{vendor}', 'VFIController@fetchVFINGRateDetail')->name('admin.vfi.fetch_detail_ng_rate');

        Route::get('index/pareto/{vendor}', 'VFIController@indexVFIPareto');
        Route::get('fetch/pareto/{vendor}', 'VFIController@fetchVFIPareto');
        Route::get('fetch/pareto/detail/{vendor}', 'VFIController@fetchVFIParetoDetail');

        Route::get('index/true/report', 'VFIController@indexVFIReportTrue');
        Route::get('fetch/true/report', 'VFIController@fetchVFIReportTrue');

    });
});

Route::group(['middleware' => ['auth'], 'namespace' => 'App\Http\Controllers'], function () {

    Route::get('/forgot/password', function () {
        return view('auth.passwords.email')->with('success');
    })->middleware('guest')->name('password.request');

    Route::post('request/reset/password', 'PasswordController@requestResetPassword');
    Route::get('reset/password/{id}', 'PasswordController@resetPassword');
    Route::post('reset/password/confirm', 'PasswordController@resetPasswordConfirm');

    Route::get('register', 'PasswordController@register');
    Route::post('register/confirm', 'PasswordController@confirmRegister');

    Route::get('404', function () {
        // return view('404');
    });

    Route::get('terms', 'PasswordController@terms');
    Route::get('policy', 'PasswordController@policy');

    Route::group(['nav' => 'A1', 'middleware' => 'permission'], function () {
        Route::get('index/batch_setting', 'BatchSettingController@index');
        Route::get('create/batch_setting', 'BatchSettingController@create');
        Route::post('create/batch_setting', 'BatchSettingController@store');
        Route::get('destroy/batch_setting/{id}', 'BatchSettingController@destroy');
        Route::get('edit/batch_setting/{id}', 'BatchSettingController@edit');
        Route::post('edit/batch_setting/{id}', 'BatchSettingController@update');
        Route::get('show/batch_setting/{id}', 'BatchSettingController@show');
    });

    Route::group(['nav' => 'A6', 'middleware' => 'permission'], function () {
        Route::get('index/user', 'UserController@index');
        Route::get('create/user', 'UserController@create');
        Route::post('create/user', 'UserController@store');
        Route::get('destroy/user/{id}', 'UserController@destroy');
        Route::get('edit/user/{id}', 'UserController@edit');
        Route::post('edit/user/{id}', 'UserController@update');
        Route::get('show/user/{id}', 'UserController@show');
    });

    Route::group(['nav' => 'A11', 'middleware' => 'permission'], function () {
        Route::get('index/rio', 'RioController@indexrio');
    });

    Route::group(['nav' => 'A7', 'middleware' => 'permission'], function () {
        Route::get('index/daily_report', 'DailyReportController@index');
        Route::post('create/daily_report', 'DailyReportController@create');
        Route::post('update/daily_report', 'DailyReportController@update');
        Route::post('delete/daily_report', 'DailyReportController@delete');
        Route::get('fetch/daily_report', 'DailyReportController@fetchDailyReport');
        Route::get('download/daily_report', 'DailyReportController@downloadDailyReport');
        Route::get('fetch/daily_report_detail', 'DailyReportController@fetchDailyReportDetail');
        Route::get('edit/daily_report', 'DailyReportController@edit');
    });

    Route::get('setting/user', 'UserController@index_setting');
    Route::post('setting/user', 'UserController@setting');
    Route::post('register', 'RegisterController@register')->name('register');

    Route::group(['nav' => 'A3', 'middleware' => 'permission'], function () {
        Route::get('index/navigation', 'NavigationController@index');
        Route::get('create/navigation', 'NavigationController@create');
        Route::post('create/navigation', 'NavigationController@store');
        Route::get('destroy/navigation/{id}', 'NavigationController@destroy');
        Route::get('edit/navigation/{id}', 'NavigationController@edit');
        Route::post('edit/navigation/{id}', 'NavigationController@update');
        Route::get('show/navigation/{id}', 'NavigationController@show');
    });

    Route::group(['nav' => 'A4', 'middleware' => 'permission'], function () {
        Route::get('index/role', 'RoleController@index');
        Route::get('create/role', 'RoleController@create');
        Route::post('create/role', 'RoleController@store');
        Route::get('destroy/role/{id}', 'RoleController@destroy');
        Route::get('edit/role/{id}', 'RoleController@edit');
        Route::post('edit/role/{id}', 'RoleController@update');
        Route::get('show/role/{id}', 'RoleController@show');
    });

    // Route::group(['nav' => 'S0', 'middleware' => 'permission'], function () {
    //ALL
    Route::get('index/outgoing/{vendor}', 'OutgoingController@index');
    Route::get('index/incoming/{vendor}/report', 'OutgoingController@indexReportIncoming');
    Route::get('fetch/incoming/{vendor}/report', 'OutgoingController@fetchReportIncoming');

    //ARISA
    Route::get('index/outgoing/arisa/input', 'OutgoingController@indexInputArisa');
    Route::get('fetch/outgoing/arisa/point_check', 'OutgoingController@fetchPointCheck');
    Route::get('fetch/kensa/arisa/serial_number', 'OutgoingController@fetchKensaSerialNumber');
    Route::post('index/outgoing/arisa/confirm', 'OutgoingController@confirmInputArisa');
    Route::get('index/kensa/arisa', 'OutgoingController@indexKensaArisa');
    Route::get('fetch/inspection_level', 'OutgoingController@fetchInspectionLevel');
    Route::get('fetch/kensa/serial_number/{vendor}', 'OutgoingController@kensaSerialNumber');
    Route::get('fetch/final/serial_number/{vendor}', 'OutgoingController@finalSerialNumber');
    Route::post('index/kensa/arisa/confirm', 'OutgoingController@confirmKensaArisa');

    Route::get('index/kensa/arisa/report', 'OutgoingController@indexReportKensaArisa');
    Route::get('fetch/kensa/arisa/report', 'OutgoingController@fetchReportKensaArisa');

    Route::get('index/outgoing/arisa/report', 'OutgoingController@indexReportQcArisa');
    Route::get('fetch/outgoing/arisa/report', 'OutgoingController@fetchReportQcArisa');
    Route::get('input/outgoing/arisa/so_number', 'OutgoingController@inputSONumberArisa');

    Route::get('index/outgoing/arisa/input/lot_out/{serial_number}/{check_date}', 'OutgoingController@indexInputArisaRecheck');
    Route::post('index/outgoing/arisa/confirm/recheck', 'OutgoingController@confirmInputArisaRecheck');

    //KBI

    Route::get('index/serial_number/kbi', 'OutgoingController@indexUploadSerialNumberKbi');
    Route::get('fetch/serial_number/kbi', 'OutgoingController@fetchSerialNumberKbi');
    Route::post('upload/serial_number/kbi', 'OutgoingController@uploadSerialNumberKbi');
    Route::get('download/serial_number/kbi', 'OutgoingController@downloadSerialNumberKbi');

    Route::get('index/kensa/kbi', 'OutgoingController@indexKensaKbi');
    Route::get('scan/kensa/kbi', 'OutgoingController@scanKensaKbi');
    Route::get('fetch/kensa/serial_number/{vendor}', 'OutgoingController@kensaSerialNumber');
    Route::post('index/kensa/kbi/confirm', 'OutgoingController@confirmKensaKbi');

    Route::get('index/kensa/kbi/report', 'OutgoingController@indexReportKensaKbi');
    Route::get('fetch/kensa/kbi/report', 'OutgoingController@fetchReportKensaKbi');

    //TRUE
    Route::get('index/outgoing/true/input', 'OutgoingController@indexInputTrue');
    Route::post('index/outgoing/true/confirm', 'OutgoingController@confirmInputTrue');
    Route::get('fetch/outgoing/true/material', 'OutgoingController@fetchMaterialTrue');

    Route::get('index/outgoing/true/input/lot_out/{serial_number}/{check_date}', 'OutgoingController@indexInputTrueRecheck');
    Route::post('index/outgoing/true/confirm/recheck', 'OutgoingController@confirmInputTrueRecheck');

    Route::get('index/outgoing/true/input/sosialisasi/{serial_number}/{check_date}', 'OutgoingController@indexInputTrueSosialisasi');
    Route::post('index/outgoing/true/confirm/sosialisasi', 'OutgoingController@confirmInputTrueSosialisasi');

    Route::get('index/kensa/true/report', 'OutgoingController@indexReportKensaTrue');
    Route::get('fetch/kensa/true/report', 'OutgoingController@fetchReportKensaTrue');

    Route::get('index/serial_number/true', 'OutgoingController@indexUploadSerialNumberTrue');
    Route::get('fetch/serial_number/true', 'OutgoingController@fetchSerialNumberTrue');
    Route::post('upload/serial_number/true', 'OutgoingController@uploadSerialNumberTrue');
    Route::get('download/serial_number/true', 'OutgoingController@downloadSerialNumberTrue');
    Route::get('update/serial_number/true', 'OutgoingController@updateSerialNumberTrue');
    Route::get('delete/serial_number/true', 'OutgoingController@deleteSerialNumberTrue');

    //CRESTEC
    Route::get('index/outgoing/crestec/input', 'OutgoingController@indexInputCrestec');
    Route::post('index/outgoing/crestec/confirm', 'OutgoingController@confirmInputCrestec');
    Route::get('fetch/outgoing/crestec/material', 'OutgoingController@fetchMaterialCrestec');

    Route::get('index/outgoing/crestec/sampling', 'OutgoingController@indexSamplingCrestec');
    Route::post('input/outgoing/crestec/sampling', 'OutgoingController@inputSamplingCrestec');
    Route::post('input/outgoing/crestec/sampling/closing', 'OutgoingController@inputSamplingCrestecClosing');

    Route::get('index/outgoing/crestec/master_defect', 'OutgoingController@indexCrestecMasterDefect');
    Route::get('fetch/outgoing/crestec/master_defect', 'OutgoingController@fetchCrestecMasterDefect');
    Route::post('update/outgoing/crestec/master_defect', 'OutgoingController@updateCrestecMasterDefect');
    Route::post('delete/outgoing/crestec/master_defect', 'OutgoingController@deleteCrestecMasterDefect');
    Route::post('input/outgoing/crestec/master_defect', 'OutgoingController@inputCrestecMasterDefect');

    Route::get('index/kensa/crestec/report', 'OutgoingController@indexReportKensaCrestec');
    Route::get('fetch/kensa/crestec/report', 'OutgoingController@fetchReportKensaCrestec');

    Route::get('index/sampling/crestec/report', 'OutgoingController@indexReportSamplingCrestec');
    Route::get('fetch/sampling/crestec/report', 'OutgoingController@fetchReportSamplingCrestec');

    Route::get('index/sampling/crestec/pdf/{serial_number}', 'OutgoingController@indexPdfSamplingCrestec');

    Route::get('index/outgoing/crestec/input/lot_out/{serial_number}/{check_date}', 'OutgoingController@indexInputCrestecRecheck');
    Route::post('index/outgoing/crestec/confirm/recheck', 'OutgoingController@confirmInputCrestecRecheck');

    //LTI
    Route::get('index/outgoing/lti/input', 'OutgoingController@indexInputLti');
    Route::post('index/outgoing/lti/confirm', 'OutgoingController@confirmInputLti');
    Route::get('fetch/outgoing/lti/material', 'OutgoingController@fetchMaterialLti');

    Route::get('index/kensa/lti/report', 'OutgoingController@indexReportKensaLti');
    Route::get('fetch/kensa/lti/report', 'OutgoingController@fetchReportKensaLti');

    //CPP
    Route::get('index/outgoing/cpp/input', 'OutgoingController@indexInputCpp');
    Route::post('index/outgoing/cpp/confirm', 'OutgoingController@confirmInputCpp');
    Route::get('fetch/outgoing/cpp/material', 'OutgoingController@fetchMaterialCpp');

    Route::get('index/kensa/cpp/report', 'OutgoingController@indexReportKensaCpp');
    Route::get('fetch/kensa/cpp/report', 'OutgoingController@fetchReportKensaCpp');

    Route::get('index/outgoing/cpp/input/lot_out/{serial_number}/{check_date}', 'OutgoingController@indexInputCppRecheck');
    Route::post('index/outgoing/cpp/confirm/recheck', 'OutgoingController@confirmInputCppRecheck');
    // });

    Route::get('sync/stock_control/plan_delivery', 'InventoryController@fetchPlanDelivery');

    Route::group(['nav' => 'S5', 'middleware' => 'permission'], function () {

        //STOCK CONTROL
        Route::get('index/stock_control/dashboard', 'InventoryController@indexDashboard');

        Route::get('fetch/stock_control/plan_delivery', 'InventoryController@fetchPlanDelivery');
        Route::get('fetch/stock_control/availability', 'InventoryController@fetchAvailability');

        Route::get('index/stock_control/{role}', 'InventoryController@indexStockControl');

        Route::get('index/material_master/{role}', 'InventoryController@indexMaterialMaster');
        Route::get('fetch/material_master', 'InventoryController@fetchMaterialMaster');

        Route::get('index/material_bom/{role}', 'InventoryController@indexMaterialBom');
        Route::get('fetch/material_bom', 'InventoryController@fetchMaterialBom');

        Route::get('index/stock_inquiry/{role}', 'InventoryController@indexStockInquiry');
        Route::get('fetch/stock_inquiry', 'InventoryController@fetchStockInquiry');

        Route::get('index/transaction_log/{role}', 'InventoryController@indexTransactionLog');
        Route::get('fetch/transaction_log', 'InventoryController@fetchTransactionLog');

        Route::get('index/forecast/{role}', 'InventoryController@indexForecast');
        Route::get('fetch/forecast', 'InventoryController@fetchForecast');

        Route::get('index/plan_delivery/{role}', 'InventoryController@indexPlanDelivery');
        Route::get('fetch/plan_delivery_data', 'InventoryController@fetchPlanDeliveryData');
        Route::post('input/plan_delivery_data', 'InventoryController@inputPlanDeliveryData');

        Route::get('index/mrp_simulation/{role}', 'InventoryController@indexMrpSimulation');

        Route::get('index/completion/{role}', 'InventoryController@indexCompletionPage');
        Route::get('fetch/completion/inventory_check', 'InventoryController@fetchIventoryCheck');
        Route::post('input/completion', 'InventoryController@inputCompletion');

        Route::get('index/goods_receipt/{role}', 'InventoryController@indexGoodsReceiptPage');
        Route::post('input/goods_receipt', 'InventoryController@inputGoodsReceipt');

        Route::get('index/delivery_order/{role}', 'InventoryController@indexDeliveryOrder');
        Route::get('fetch/delivery_order', 'InventoryController@fetchDeliveryOrder');
        Route::get('download/delivery_order', 'InventoryController@downloadDeliveryOrder');
        Route::get('download/document_order', 'InventoryController@downloadDocumentOrder');
        Route::get('download/bc_doc', 'InventoryController@downloadBcDoc');
        Route::post('input/delivery_order', 'InventoryController@inputDeliveryOrder');
        Route::post('input/bc_document', 'InventoryController@inputBcDocument');
        Route::post('delete/delivery_order', 'InventoryController@deleteDeliveryOrder');
        Route::post('send/delivery_order', 'InventoryController@sendDeliveryOrder');

        Route::get('test_surat_jalan', 'InventoryController@test_surat_jalan');
    });

    Route::group(['nav' => 'S5', 'middleware' => 'permission'], function () {

        Route::get('index/delivery_order_bc', 'InventoryController@indexDeliveryOrderBc');
        Route::get('fetch/delivery_order_bc', 'InventoryController@fetchDeliveryOrderBc');

    });

    // Molding Workshop
    // Route::group(['nav' => 'S7', 'middleware' => 'permission'], function () {
    Route::post('post/workshop/check_molding_vendor/schedule', 'workshopController@postSchedule');
    Route::get('index/workshop/check_molding_vendor', 'workshopController@indexCheckMolding');
    Route::get('fetch/workshop/check_molding_vendor/monitoring', 'workshopController@fetchCheckMoldingMonitoring');
    Route::get('index/workshop/check_molding_vendor/create', 'workshopController@indexCreateCheckMolding');
    Route::post('post/workshop/check_molding_vendor', 'workshopController@postCheckMolding');
    Route::get('fetch/workshop/check_molding_vendor/record', 'workshopController@fetchCheckMolding');
    Route::post('post/workshop/check_molding_vendor/temuan', 'workshopController@postFindingMolding');
    Route::get('fetch/workshop/check_molding_vendor/temuan', 'workshopController@fetchFindingMolding');
    Route::get('fetch/workshop/check_molding_vendor/penanganan/log', 'workshopController@fetchHandlingLog');
    Route::post('post/workshop/check_molding_vendor/penanganan', 'workshopController@postHandling');
    // });

    Route::get('index/outgoing/ng_rate/{vendor}', 'OutgoingController@indexNgRate');
    Route::get('fetch/outgoing/ng_rate/{vendor}', 'OutgoingController@fetchNgRate');
    Route::get('fetch/outgoing/ng_rate/detail/{vendor}', 'OutgoingController@fetchNgRateDetail');

    Route::get('index/outgoing/pareto/{vendor}', 'OutgoingController@indexPareto');
    Route::get('fetch/outgoing/pareto/{vendor}', 'OutgoingController@fetchPareto');
    Route::get('fetch/outgoing/pareto/detail/{vendor}', 'OutgoingController@fetchParetoDetail');

    Route::get('index/outgoing/lot_status/{vendor}', 'OutgoingController@indexLotStatus');
    Route::get('fetch/outgoing/lot_status/{vendor}', 'OutgoingController@fetchLotStatus');
    Route::get('fetch/outgoing/lot_status/detail/{vendor}', 'OutgoingController@fetchLotStatusDetail');

    Route::get('index/incoming/pareto/{vendor}', 'OutgoingController@indexIncomingPareto');
    Route::get('fetch/incoming/pareto/{vendor}', 'OutgoingController@fetchIncomingPareto');
    Route::get('fetch/incoming/pareto/detail/{vendor}', 'OutgoingController@fetchIncomingParetoDetail');

    Route::get('index/incoming/ng_rate/{vendor}', 'OutgoingController@indexIncomingNgRate');
    Route::get('fetch/incoming/ng_rate/{vendor}', 'OutgoingController@fetchIncomingNgRate');
    Route::get('fetch/incoming/ng_rate/detail/{vendor}', 'OutgoingController@fetchIncomingNgRateDetail');

    //MASTER VENDOR, BANK, COST CENTER, GL ACCOUNT
    Route::get('index/vendor', 'AccountingController@indexVendor');
    Route::get('fetch/vendor', 'AccountingController@fetchVendor');

    Route::get('index/bank', 'AccountingController@indexBank');
    Route::get('fetch/bank', 'AccountingController@fetchBank');

    Route::get('index/gl_account', 'AccountingController@indexGLAccount');
    Route::get('fetch/gl_account', 'AccountingController@fetchGLAccount');

    Route::get('index/cost_center', 'AccountingController@indexCostCenter');
    Route::get('fetch/cost_center', 'AccountingController@fetchCostCenter');

    //INVOICE
    Route::get('index/invoice', 'AccountingController@indexInvoice');
    Route::get('fetch/invoice', 'AccountingController@fetchInvoice');
    Route::get('report/invoice/{id}', 'AccountingController@reportInvoice');
    Route::get('request/reject/invoice', 'AccountingController@requestRejectInvoice');

    Route::group(['nav' => 'S1', 'middleware' => 'permission'], function () {
        Route::get('index/upload_invoice', 'AccountingController@uploadInvoice');
        Route::post('post/upload_invoice', 'AccountingController@uploadInvoicePost');
        Route::get('fetch/monitoring/invoice', 'AccountingController@fetchInvoiceMonitoring');
    });

    Route::get('fetch/monitoring_pch/invoice', 'AccountingController@fetchInvoiceMonitoringPch');
    Route::get('fetch/monitoring_acc/invoice', 'AccountingController@fetchInvoiceMonitoringAcc');

    //PAYMENT REQUEST
    Route::get('index/payment_request', 'AccountingController@indexPaymentRequest');
    Route::get('fetch/payment_request', 'AccountingController@fetchPaymentRequest');
    Route::get('fetch/payment_request/list', 'AccountingController@fetchPaymentRequestList');
    Route::get('fetch/payment_request/detail', 'AccountingController@fetchPaymentRequestDetail');
    Route::get('report/payment_request/{id}', 'AccountingController@reportPaymentRequest');
    Route::get('email/payment_request', 'AccountingController@emailPaymentRequest');

    Route::get('index/payment_request/monitoring', 'AccountingController@indexPaymentRequestMonitoring');
    Route::get('fetch/payment_request/monitoring', 'AccountingController@fetchPaymentRequestMonitoring');
    Route::get('fetch/payment_request/table', 'AccountingController@fetchtableInv');

    //Approval Payment Request
    Route::get('payment_request/approvemanager/{id}', 'AccountingController@paymentapprovalmanager');
    Route::get('payment_request/approvegm/{id}', 'AccountingController@paymentapprovalgm');
    Route::get('payment_request/receiveacc/{id}', 'AccountingController@paymentreceiveacc');
    Route::get('payment_request/reject/{id}', 'AccountingController@paymentreject');

    Route::get('payment_request/verifikasi/{id}', 'AccountingController@verifikasi_payment_request');
    Route::post('payment_request/approval/{id}', 'AccountingController@approval_payment_request');
    Route::post('payment_request/notapprove/{id}', 'AccountingController@reject_payment_request');

    Route::group(['nav' => 'S2', 'middleware' => 'permission'], function () {
        Route::get('index/purchasing', 'AccountingController@indexPurchasing');
        Route::get('edit/invoice/{id}', 'AccountingController@editInvoice');
        Route::post('create/payment_request', 'AccountingController@createPaymentRequest');
        Route::get('detail/payment_request', 'AccountingController@fetchPaymentRequestDetailAll');
        Route::post('edit/payment_request', 'AccountingController@editPaymentRequest');
        Route::post('delete/payment_request', 'AccountingController@deletePaymentRequest');
        Route::post('checked/invoice', 'AccountingController@checkInvoice');

        Route::get('index/vendor/registration', 'AccountingController@indexVendorRegistration');
        Route::get('fetch/vendor/registration', 'AccountingController@fetchVendorRegistration');
        Route::post('approve/vendor/registration', 'AccountingController@approveVendorRegistration');
        Route::post('delete/vendor/registration', 'AccountingController@deleteVendorRegistration');
    });

    Route::get('get_supplier', 'AccountingController@getSupplier');
    Route::post('update/invoice', 'AccountingController@editInvoicePost');

    Route::group(['nav' => 'S3', 'middleware' => 'permission'], function () {
        Route::get('index/accounting', 'AccountingController@indexAccounting');
        Route::get('accounting/payment', 'AccountingController@indexAccountingPayment');
        Route::get('fetch/accounting/payment', 'AccountingController@fetchAccountingPayment');
        Route::get('fetch/accounting/payment/after', 'AccountingController@fetchAccountingPaymentAfter');
        Route::post('post/accounting/payment', 'AccountingController@postAccountingPayment');
        Route::get('accounting/jurnal', 'AccountingController@indexAccountingJurnal');
        Route::get('fetch/accounting/jurnal', 'AccountingController@fetchJurnal');
        Route::get('fetch/bank/data', 'AccountingController@getBank');
        Route::get('fetch/bank/id_payment', 'AccountingController@getIDPayment');
        Route::get('fetch/invoice/verification', 'AccountingController@fetchInvoiceVerification');
        Route::get('fetch/jurnal_type', 'AccountingController@get_jurnal_type');
        Route::get('fetch/gl_account/data', 'AccountingController@get_gl_account');
        Route::get('fetch/cost_center/data', 'AccountingController@get_cost_center');
        Route::post('create/jurnal', 'AccountingController@createJurnal');
        Route::get('index/list_bank', 'AccountingController@indexReportJurnal');
        Route::get('fetch/list_bank', 'AccountingController@fetchReportJurnal');;
        Route::get('export/bank/list', 'AccountingController@exportJurnal');
    });

    Route::group(['nav' => 'S4', 'middleware' => 'permission'], function () {
        Route::get('index/warehouse', 'AccountingController@indexWarehouse');
    });

    Route::get('/home', ['middleware' => 'permission', 'nav' => 'Dashboard', 'uses' => 'HomeController@index'])->name('home');

    Route::get('pdf', 'TrialController@trialPdf');

    Route::group(['nav' => 'S5', 'middleware' => 'permission'], function () {
        //ALL - FIXED ASSET
        Route::get('index/fixed_asset', 'AccountingController@indexFixedAsset');
        Route::get('fetch/fixed_asset/list', 'AccountingController@fetchFixedAsset');
        Route::get('fetch/fixed_asset/audit/list', 'AccountingController@fetchAssetAuditList');
        Route::get('index/check/fixed_asset/{check_num}/{section}/{location}/{period}', 'AccountingController@indexAssetCheck');
        Route::get('fetch/fixed_asset/location/list', 'AccountingController@fetchAssetbyLocation');
        Route::post('input/fixed_asset/check/temp', 'AccountingController@inputAssetCheckTemp');
        Route::post('input/fixed_asset/check', 'AccountingController@inputAssetCheck');
        Route::get('index/fixed_asset/auditor_audit/list', 'AccountingController@indexAssetAuditListAuditor');
        Route::post('approval/fixed_asset/check', 'AccountingController@approvalFixedAsset');
        Route::post('upload/fixed_asset/map', 'AccountingController@postAssetMap');
        Route::get('pdf/fixed_asset_check/{location}/{period}', 'AccountingController@pdfFixedAsset');
    });

    Route::get('approval/fixed_asset/audit/approval/{location}/{period}/{stat}/{position}', 'GeneralController@approvalFixedAssetCheck');

    Route::get('reminder_invoice', function () {

        \Artisan::call('reminder:invoice');

        var_dump("Command is running");
    });

    // PO Confirmation
    // Route::get('po_confirmation', 'PoConfirmationController@indexPoConfirmation');
    // Route::get('fetch/po', 'PoConfirmationController@fetchPo');
    // Route::post('input/po_confirmation', 'PoConfirmationController@inputPoConfirmation');
    // Route::get('send/po_notification/{po_number}', 'PoConfirmationController@sendPoNotification');

    // // Equipment PO Confirmation
    // Route::get('po_eq_confirmation', 'PoConfirmationController@indexPoConfirmationEquipment');
    // Route::get('fetch/po_eq_confirmation', 'PoConfirmationController@fetchPoEquipment');
    // Route::post('input/po_eq_confirmation', 'PoConfirmationController@inputPoConfirmationEquipment');
    // Route::get('send/po_eq_confirmation/{po_number}', 'PoConfirmationController@sendPoNotificationEquipment');
});
// START PO MATERIAL
Route::get('po_confirmation', 'App\Http\Controllers\RawMaterialController@indexPoConfirmation');
Route::get('fetch/po', 'App\Http\Controllers\RawMaterialController@fetchPo');
Route::post('input/po_confirmation', 'App\Http\Controllers\RawMaterialController@inputPoConfirmation');
Route::get('send/po_notification/{po_number}', 'App\Http\Controllers\RawMaterialController@sendPoNotification');
// END PO MATERIAL

 // Equipment PO Confirmation
Route::get('po_eq_confirmation', 'App\Http\Controllers\PoConfirmationController@indexPoConfirmationEquipment');
Route::get('fetch/po_eq_confirmation', 'App\Http\Controllers\PoConfirmationController@fetchPoEquipment');
Route::post('input/po_eq_confirmation', 'App\Http\Controllers\PoConfirmationController@inputPoConfirmationEquipment');
Route::get('send/po_eq_confirmation/{po_number}', 'App\Http\Controllers\PoConfirmationController@sendPoNotificationEquipment');