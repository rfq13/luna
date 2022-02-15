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
	return view('welcome');
})->middleware("guest");

Route::get('/login', 'AuthManageController@viewLogin')->middleware("guest")->name('login');
Route::post('/verify_login', 'AuthManageController@verifyLogin');
Route::post('/first_account', 'UserManageController@firstAccount');

Route::group(['middleware' => ['auth', 'checkRole:admin,kasir']], function () {
	Route::get('/logout', 'AuthManageController@logoutProcess');
	Route::get('/dashboard', 'ViewManageController@viewDashboard');
	Route::get('/dashboard/chart/{filter}', 'ViewManageController@filterChartDashboard');
	Route::post('/market/update', 'ViewManageController@updateMarket');
	// ------------------------- Fitur Cari -------------------------
	Route::get('/search/{word}', 'SearchManageController@searchPage');
	// ------------------------- Profil -------------------------
	Route::get('/profile', 'ProfileManageController@viewProfile');
	Route::post('/profile/update/data', 'ProfileManageController@changeData');
	Route::post('/profile/update/password', 'ProfileManageController@changePassword');
	Route::post('/profile/update/picture', 'ProfileManageController@changePicture');
	// ------------------------- Kelola Akun -------------------------
	// > Akun
	Route::get('/account', 'UserManageController@viewAccount');
	Route::get('/account/new', 'UserManageController@viewNewAccount');
	Route::post('/account/create', 'UserManageController@createAccount');
	Route::get('/account/edit/{id}', 'UserManageController@editAccount');
	Route::post('/account/update', 'UserManageController@updateAccount');
	Route::get('/account/delete/{id}', 'UserManageController@deleteAccount');
	Route::get('/account/filter/{id}', 'UserManageController@filterTable');
	// > Akses
	Route::get('/access', 'AccessManageController@viewAccess')->middleware("pusat");
	Route::get('/access/change/{user}/{access}', 'AccessManageController@changeAccess');
	Route::get('/access/check/{user}', 'AccessManageController@checkAccess');
	Route::get('/access/sidebar', 'AccessManageController@sidebarRefresh');
	// ------------------------- Kelola Barang -------------------------
	// > Barang
	Route::group(["prefix" => "product"], function () {
		Route::get('/', 'ProductManageController@viewProduct');
		Route::get('/new', 'ProductManageController@viewNewProduct');
		Route::post('/create', 'ProductManageController@createProduct');
		Route::post('/import', 'ProductManageController@importProduct');
		Route::get('/edit/{id}', 'ProductManageController@editProduct');
		Route::post('/update', 'ProductManageController@updateProduct');
		Route::get('/delete/{id}', 'ProductManageController@deleteProduct');
		Route::get('/filter/{id}', 'ProductManageController@filterTable');
		Route::get('/settings', 'ProductManageController@settings')->name("product.settings");
		Route::post('/unit', 'ProductManageController@set_unit')->name("product.unit.set");
		Route::get('/unit', 'ProductManageController@get_unit')->name("product.unit.get");
		Route::post('/ppn', 'ProductManageController@set_ppn')->name("product.ppn.set");
	});
	// > Pasok
	Route::group(["prefix" => "supply"], function () {
		Route::get('/system/{id}', 'SupplyManageController@supplySystem');
		Route::get('/new', 'SupplyManageController@viewNewSupply');
		Route::get('/check/{id}', 'SupplyManageController@checkSupplyCheck');
		Route::get('/data/{id}', 'SupplyManageController@checkSupplyData');
		Route::post('/create', 'SupplyManageController@createSupply');
		Route::post('/import', 'SupplyManageController@importSupply');
		Route::get('/statistics', 'SupplyManageController@statisticsSupply');
		Route::get('/statistics/product/{id}', 'SupplyManageController@statisticsProduct');
		Route::get('/statistics/users/{id}', 'SupplyManageController@statisticsUsers');
		Route::get('/statistics/table/{id}', 'SupplyManageController@statisticsTable');
		Route::post('/statistics/export', 'SupplyManageController@exportSupply');
		Route::get('/', 'SupplyManageController@viewSupply');
	});
	// > Supplier
	Route::group(["prefix" => "supplier"], function () {
		Route::get("/", "SupplierController@index");
		Route::get("/{id}/edit", "SupplierController@edit");
		Route::post("/update", "SupplierController@update");
		Route::get("/delete/{id}", "SupplierController@delete");
		Route::get("/new", "SupplierController@new");
		Route::get("/data/{filter?}", "SupplierController@data");
	});

	// > Branch
	Route::group(["prefix" => "branch"], function () {
		Route::get("/", "BranchController@index");
		Route::post("/", "BranchController@store");
		Route::post("/update", "BranchController@update");
		Route::get("/create", "BranchController@create");
		Route::get("/edit/{id}", "BranchController@edit");
		Route::delete("/delete", "BranchController@destroy");
	});
	// ------------------------- Transaksi -------------------------
	Route::get('/transaction', 'TransactionManageController@viewTransaction');
	Route::get('/transaction/product/{id}', 'TransactionManageController@transactionProduct');
	Route::get('/transaction/product/check/{id}', 'TransactionManageController@transactionProductCheck');
	Route::post('/transaction/process', 'TransactionManageController@transactionProcess');
	Route::get('/transaction/receipt/{id}', 'TransactionManageController@receiptTransaction');
	// ------------------------- Kelola Laporan -------------------------
	Route::get('/report/transaction', 'ReportManageController@reportTransaction');
	Route::post('/report/transaction/filter', 'ReportManageController@filterTransaction');
	Route::get('/report/transaction/chart/{id}', 'ReportManageController@chartTransaction');
	Route::post('/report/transaction/export', 'ReportManageController@exportTransaction');
	Route::get('/report/workers', 'ReportManageController@reportWorker');
	Route::get('/report/workers/filter/{id}', 'ReportManageController@filterWorker');
	Route::get('/report/workers/detail/{id}', 'ReportManageController@detailWorker');
	Route::post('/report/workers/export/{id}', 'ReportManageController@exportWorker');
});

// Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');