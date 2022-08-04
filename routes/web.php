<?php

use App\Http\Controllers\Productcontroller;
use App\Http\Controllers\Salecontroller;
use App\Http\Controllers\Usercontroller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;

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

Auth::routes([
    'verify' => true
]);
//index
Route::get('/wbsais', function () {
    if (!Auth::check()) {
        return view('main.login');
    }
    return redirect()->route('user.dash');
})->name('wbsais.login');

//login user
Route::post('/wbsais/login', [Usercontroller::class, 'login'])->name('login.auth');
//register routes
Route::get('/register/form', [Usercontroller::class, 'register'])->name('register.view');

Route::post('register/store', [Usercontroller::class, 'store'])->name('register.store');
//user dash
Route::get('dashboard', function () {

    return view('main.dash-user');
})->name('user.dash')->middleware(['auth', 'verified']);

Route::get('logout', [Usercontroller::class, 'logout'])->name('logout');

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//fragment routes
Route::get('dashboard_fragment', [Usercontroller::class, 'dashboard_fragment'])->name('dashboard.fragment');
Route::get('product_fragment', [Usercontroller::class, 'load_product_fragment'])->name('product.fragment');
Route::get('supplier_fragment', [Usercontroller::class, 'load_supplier_fragment'])->name('supplier.fragment');
Route::get('category_fragment', [Usercontroller::class, 'load_category_fragment'])->name('category.fragment');
Route::get('setting_fragment', [Usercontroller::class, 'load_setting_fragment'])->name('setting.fragment');
Route::get('sale_fragment', [Usercontroller::class, 'load_sale_fragment'])->name('sale.fragment');
Route::get('report_fragment', [Usercontroller::class, 'load_report_fragment'])->name('report.fragment');
//report
Route::post('reports', [Usercontroller::class, 'report'])->name('report.get');

//product routes
Route::get('product/getall', [Productcontroller::class, 'get_product'])->name('product.get');
Route::post('product/store', [Productcontroller::class, 'store_product'])->name('product.store');
Route::post('product/destroy/{id}', [Productcontroller::class, 'destroy_product'])->name('product.destroy');

//store category
Route::post('category/store', [Productcontroller::class, 'store_category'])->name('category.store');
Route::post('category/update/{id}', [Productcontroller::class, 'update_category'])->name('category.update');
Route::post('category/destroy/{id}', [Productcontroller::class, 'destroy_category'])->name('category.destroy');
Route::get('category/get', [Productcontroller::class, 'get_category'])->name('category.get');

//update product
Route::post('product/update', [Productcontroller::class, 'update_product'])->name('product.update');

Route::get('supplier/getall', [Productcontroller::class, 'get_supplier'])->name('supplier.get');
//supplier routes
Route::post('suppplier/store', [Productcontroller::class, 'store_supplier'])->name('supplier.store');
Route::post('suppplier/update/{id}', [Productcontroller::class, 'update_supplier'])->name('supplier.update');
Route::post('supplier/destroy/{id}', [Productcontroller::class, 'destroy_supplier'])->name('supplier.destroy');

//sale functionality routes
Route::get('productlist/get', [Salecontroller::class, 'get_products'])->name('product_list.get');
/*
* -----------------
* stash operations
* -----------------
*/
Route::post('product/addtocart/{id}', [Salecontroller::class, 'add_to_cart'])->name('product.add_to_cart');
Route::post('product/updatecart/{id}', [Salecontroller::class, 'update_cart'])->name('product.update_cart');
Route::post('product/removetocart/{id}', [Salecontroller::class, 'remove_to_cart'])->name('product.remove_to_cart');
Route::get('transaction/store/{useramount}', [Salecontroller::class, 'store_transaction'])->name('transaction.store');
Route::get('print/receipt/{transaction_id}/{user_id}', [Salecontroller::class, 'receipt'])->name('receipt.print');
Route::get('product/stash', [Salecontroller::class, 'stash_get'])->name('stash.get');
//misc
Route::get('product/top', [Usercontroller::class, 'get_top_products'])->name('top_products.get');
Route::post('user/revenue', [Usercontroller::class, 'get_revenue'])->name('revenue.get');
//user settings
Route::post('user/basicinfor', [Usercontroller::class, 'info_update'])->name('basicinfo.update');
Route::post('user/passwordupdate', [Usercontroller::class, 'password_update'])->name('password.update');
Route::post('user/storeinfo', [Usercontroller::class, 'storeinfo_update'])->name('storeinfo.update');
Route::get('user/delete', [Usercontroller::class, 'destroy_user'])->name('user.destroy');
