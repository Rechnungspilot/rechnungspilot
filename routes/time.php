<?php

use App\Contacts\Contact;
use App\Receipts\Order;
use App\Receipts\Quote;
use App\Receipts\Receipt;
use App\Time;
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

Route::middleware(['auth.time'])->group(function () {

    // Zeiterfassung
    Route::get('/', 'TimeRecordingController@index')->name('time.recording.index');
    Route::post('zeiterfassung', 'TimeRecordingController@store')->name('time.recording.store');
    Route::delete('zeiterfassung/{time}', 'TimeRecordingController@destroy')->name('time.recording.destroy');

    // Aufgaben
    Route::post('aufgaben', 'Todos\TodoController@store')->name('todo.store');

    include('raw.php');
});



Route::get('login', function () {
    return view('time.auth.login');
})->name('time.login');