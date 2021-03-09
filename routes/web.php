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

Route::get('/d15r', function () {
    return view('d15r.home');
});

Route::get('/d15r/notes/{path}', function (Illuminate\Http\Request $request, string $path) {
    dump($path);
})->where('path', '(.*)');

Route::get('/hof-sundermeier', function () {
    return view('hof-sundermeier.app');
});

Route::middleware(['auth', 'company.locked'])->group(function () {



});

Route::middleware(['guest', 'signed'])->group(function () {
    Route::get('rechnungen/keepseven/create', 'Receipts\Invoices\KeepsevenController@create')->name('receipt.invoice.keepseven.create');
    Route::post('rechnungen/keepseven', 'Receipts\Invoices\KeepsevenController@store')->name('receipt.invoice.keepseven.store');
});

Route::middleware(['auth:sanctum'])->prefix('api')->group(function () {
    Route::apiResource('companies.invoices', 'Api\Receipts\InvoiceController', ['as' => 'api']);
    Route::apiResource('companies.contacts', 'Api\Contacts\ContactController', ['as' => 'api']);
    Route::apiResource('companies.items.lines', 'Api\Items\LineController', ['as' => 'api']);
});