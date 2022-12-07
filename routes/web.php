<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Customers_Report;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\Invoices_Report;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoiceSoftController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use App\Models\invoiceSoft;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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


Route::get('/regitser', function () {
    return view('welcome');
});


Route::get('/', function () {
    return view('auth.login');
});
require __DIR__.'/auth.php'; 

route::get('/index' , [HomeController::class , 'index'])
->middleware(['auth'])->name('index');

// Start resource Routes 
Route::resource('invoices', InvoicesController::class);
Route::resource('sections', SectionController::class);
Route::resource('products', ProductController::class);
Route::resource('atachment', InvoiceAttachmentController::class);
Route::resource('soft', InvoiceSoftController::class);
// End resource Routes 


// Start InvoicesController Routes 
Route::controller(InvoicesController::class)->group(function () {
Route::get('section/{file}', 'getproducts' );
Route::post('updateStatus',  'updateStatus')->name('updateStatus');
Route::get('archive', 'archive')->name('archive');
Route::get('print/{id}', 'print')->name('print');
Route::get('invoice-paid', 'invoice_paid')->name('invoice-paid');
Route::get('invoice-unpaid',  'invoice_unpaid')->name('invoice-unpaid');
Route::get('invoice-partial',  'invoice_partial')->name('invoice-partial');
Route::get('invoice/{id}',  'destroy')->name('invoicedelete');
Route::get('export', 'export')->name('export');
});
// End InvoicesController Routes 


// Start InvoiceDetailsController Routes 
Route::controller(InvoiceDetailsController::class)->group(function () {
Route::get('invoicedetails/{id}',  'invoicedetails')->name('invoicedetails');
Route::get('invoice-soft/restore',  'restore')->name('restore');
Route::get('export_users',  'export')->name('export_users');
});
// End InvoicesController Routes 


// Start Customers_Report Routes 
Route::controller(Customers_Report::class)->group(function () {
    Route::get('customers_report' ,  'index')->name('customers_report');
    Route::post('customers_report' ,  'Search_customers')->name('serch_customers');
    });
// End Customers_Report Routes 

// Start Invoices_Report Routes 
Route::controller(Invoices_Report::class)->group(function () {
    Route::get('Search_invoices' ,  'index')->name('customers_report');
    Route::post('Search_invoices' ,  'Search_invoices')->name('Search_invoices');
    
 });
// End Invoices_Report Routes 


Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class );
});


Route::get('/{page}', [AdminController::class , 'index']);

