<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RateController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

Route::get('locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
});


// Route::group(['prefix' => 'admin', 'middleware'=>['auth','MessageCountMiddleware', 'IsAccessToDashboard']], function(){
Route::group([ 'middleware' => ['auth']], function () {
    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    require __DIR__ . '/all/product_routes.php';
    require __DIR__ . '/all/category_routes.php';
    require __DIR__ . '/all/currency_routes.php';
    require __DIR__ . '/all/client_routes.php';
    require __DIR__ . '/all/vendor_routes.php';
    require __DIR__ . '/all/account_routes.php';
    require __DIR__ . '/all/purchase_routes.php';
    require __DIR__ . '/all/sell_routes.php';
    require __DIR__ . '/all/unit_routes.php';
    require __DIR__ . '/all/shareholder_routes.php';
    require __DIR__ . '/all/expense_routes.php';
    require __DIR__ . '/all/expense_category_routes.php';
    require __DIR__ . '/all/account_transaction_routes.php';
    require __DIR__ . '/all/unit_routes.php';
    require __DIR__ . '/all/account_transfer_routes.php';
    require __DIR__ . '/all/staff_routes.php';
    require __DIR__ . '/all/staff_transaction_routes.php';
    require __DIR__ . '/all/sell_return_routes.php';
    require __DIR__ . '/all/report_routes.php';
    require __DIR__ . '/all/stock_routes.php';
    require __DIR__ . '/all/stock_products_routes.php';
    require __DIR__ . '/all/main_transfer_routes.php';
    require __DIR__ . '/all/stock_transfer_routes.php';
    require __DIR__ . '/all/branch_routes.php';
    require __DIR__ . '/all/assets_routes.php';
    require __DIR__ . '/all/document_routes.php';
    require __DIR__ . '/all/document_category_routes.php';
    require __DIR__ . '/all/assets_category_routes.php';
    require __DIR__ . '/all/receive_routes.php';

    Route::get('/journal', [DashboardController::class, 'journal'])->name('journal.get');
    Route::post('/journal', [DashboardController::class, 'filterJournal'])->name('journal.post');
    Route::resource('setting', SettingController::class);
    Route::resource('backups', BackupController::class);
    Route::resource('/rate', RateController::class);
    Route::post('rateupdate', [RateController::class, 'update']);

    Route::resource('users',  UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::get('/setting', [SettingController::class, 'getSetting'])->name('setting.get');
    Route::get('/language', [SettingController::class, 'LanguageSetting'])->name('language.get');
    Route::post('/setting/save', [SettingController::class, 'saveSetting'])->name('setting.post');
    Route::get('/profile', [LoginController::class, 'getForm'])->name('profile.get');
    Route::post('/profile', [LoginController::class, 'saveForm'])->name('profile.post');

    Route::get('/clear', function() {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
         return "Cache is cleared";
     });
});

Route::middleware(['guest'])->group(function () {
    Route::post('/login', [LoginController::class, 'store'])->name('login');
    Route::get('/forgot', [LoginController::class, 'showForgetPasswordForm'])->name('auth.forgot.get');
    Route::post('/forgetpost', [LoginController::class, 'submitForgetPasswordForm'])->name('auth.forget.post');
    Route::get('reset-password/{token}', [LoginController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [LoginController::class, 'submitResetPasswordForm'])->name('reset.password.post');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/create-symlink', function () {
    symlink(storage_path('/app/public'), public_path('storage'));
    echo "Symlink Created. Thanks";
});
