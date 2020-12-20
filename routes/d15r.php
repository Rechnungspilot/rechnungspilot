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

Route::get('/impressum', function () {
    return view('impressum');
})->name('impressum');

Route::post('/contact', 'Guests\ContactController@store');

Route::get('/', function () {
    return view('d15r.app');
});