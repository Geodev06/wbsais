<?php

use App\Http\Controllers\Productcontroller;
use App\Http\Controllers\Salecontroller;
use App\Http\Controllers\Usercontroller;
use Illuminate\Support\Facades\Auth;
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

Auth::routes(
    ['verify' => true]
);
//index
Route::get('/', function () {
    if (!Auth::check()) {
        return view('main.login');
    } else {
        return redirect()->route('user.dash');
    }
})->name('wbsais.login');

//login user
Route::post('/wbsais/login', [Usercontroller::class, 'login'])->name('login.auth');
//register routes
Route::get('/register/form', [Usercontroller::class, 'register'])->name('register.view');

Route::post('register/store', [Usercontroller::class, 'store'])->name('register.store');
//user dash
Route::get('dashboard', function () {
    $data = ['fullname' => Auth::user()->name];
    return view('main.dash-user', compact('data'));
})->name('user.dash')->middleware(['auth', 'verified']);

Route::get('logout', [Usercontroller::class, 'logout'])->name('logout');

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('forbiddenrequest', function () {
    return view('error.forbidden');
})->name('forbidden');
//fragment routes
Route::get('dashboard_fragment', [Usercontroller::class, 'dashboard_fragment'])->name('dashboard.fragment');
Route::get('product_fragment', [Usercontroller::class, 'load_product_fragment'])->name('product.fragment');
Route::get('supplier_fragment', [Usercontroller::class, 'load_supplier_fragment'])->name('supplier.fragment');
Route::get('category_fragment', [Usercontroller::class, 'load_category_fragment'])->name('category.fragment');
Route::get('setting_fragment', [Usercontroller::class, 'load_setting_fragment'])->name('setting.fragment');
Route::get('sale_fragment', [Usercontroller::class, 'load_sale_fragment'])->name('sale.fragment');
Route::get('report_fragment', [Usercontroller::class, 'load_report_fragment'])->name('report.fragment');
Route::get('analysis_fragment', [Usercontroller::class, 'load_analysis_fragment'])->name('analysis.fragment');
Route::get('logs_fragment', [Usercontroller::class, 'load_logs_fragment'])->name('logs.fragment');
Route::get('expenses_fragment', [Usercontroller::class, 'load_expenses_fragment'])->name('expenses.fragment');
//report
Route::post('reports', [Usercontroller::class, 'report'])->name('report.get');
Route::get('user/salesoverview/{category}', [Usercontroller::class, 'getsales_overview'])->name('overview.get');
Route::get('user/transactions/data', [Usercontroller::class, 'transactions_chart_data'])->name('transactionschart_data.get');
Route::get('user/analysis', [Usercontroller::class, 'analysis'])->name('analysis.get');
Route::get('user/analysis/composition', [Usercontroller::class, 'inventory_composition'])->name('products_composition.get');
Route::get('user/analysis/qtydata', [Usercontroller::class, 'products_qty_data'])->name('productsqtydata.get');
//user expenses
Route::post('storeexpenses', [Usercontroller::class, 'store_expense'])->name('expense.store');
Route::get('getexpenses', [Usercontroller::class, 'get_expenses'])->name('expenses.get');

Route::get('expensessummary', [Usercontroller::class, 'get_expenses_summary'])->name('expenses_summary.get');

Route::get('expenses/destroy/{id}', [Usercontroller::class, 'destroy_expenses'])->name('expenses.destroy');
Route::post('expenses/update/{id}', [Usercontroller::class, 'update_expenses'])->name('expenses.update');

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
Route::get('user/receipt/recent/{transaction_id}', [Usercontroller::class, 'open_recent'])->name('recent.get');
//misc
Route::get('product/top', [Usercontroller::class, 'get_top_products'])->name('top_products.get');
Route::post('user/revenue', [Usercontroller::class, 'get_revenue'])->name('revenue.get');
//user settings
Route::post('user/basicinfor', [Usercontroller::class, 'info_update'])->name('basicinfo.update');
Route::post('user/setting/passwordupdate', [Usercontroller::class, 'password_update'])->name('settingpassword.update');
Route::post('user/storeinfo', [Usercontroller::class, 'storeinfo_update'])->name('storeinfo.update');
Route::get('user/delete', [Usercontroller::class, 'destroy_user'])->name('user.destroy');
Route::post('user/bind/userinventory', [Usercontroller::class, 'bind_inventory'])->name('inventory.bind');
Route::get('user/inventory/keygenerate', [Usercontroller::class, 'generate_key'])->name('key.generate');
