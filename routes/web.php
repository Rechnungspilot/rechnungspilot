<?php

use App\Contacts\Contact;
use App\Receipts\Order;
use App\Receipts\Quote;
use App\Receipts\Receipt;
use App\Todos\Todo;
use App\User;
use App\Userfile;
use Fhp\FinTs;
use Illuminate\Support\Facades\Storage;

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
    return view('home.start');
})->name('home.start');

Route::get('/funktionen', function () {
    return view('home.features');
})->name('home.features');

Route::get('/preise', function () {
    return view('home.pricing');
})->name('home.pricing');

Route::get('/impressum', function () {
    return view('impressum');
})->name('impressum');

Route::post('/contact', 'Guests\ContactController@store');

Auth::routes();

Route::get('rechnungen/keepseven/create', 'Receipts\Invoices\KeepsevenController@create')->name('receipt.invoice.keepseven.create');
Route::post('rechnungen/keepseven', 'Receipts\Invoices\KeepsevenController@store')->name('receipt.invoice.keepseven.store');

// Deployment
Route::post('deploy', 'DeploymentController@store');