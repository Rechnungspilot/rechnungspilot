<?php

use App\Company;
use App\Contacts\Contact;
use App\Item;
use App\Items\Price;
use App\Mail\CompanyRegistered;
use App\Projects\Project;
use App\Receipt\Letter;
use App\Receipts\Abos\Abo;
use App\Receipts\Boilerplate;
use App\Receipts\Delivery;
use App\Receipts\Dun;
use App\Receipts\Expense;
use App\Receipts\Income;
use App\Receipts\Inquiries\Inquiry;
use App\Receipts\Invoice;
use App\Receipts\Order;
use App\Receipts\Quote;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Payment;
use App\Time;
use App\Todos\Todo;
use App\Transaction;
use App\Unit;
use App\User;
use App\Userfile;
use Fhp\FinTs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

Route::bind('model', function ($id) {
    switch(app()->request->route('type'))
    {
        case 'abos': return Abo::findOrFail($id); break;
        case 'anfragen': return Inquiry::findOrFail($id); break;
        case 'angebote': return Receipt::findOrFail($id); break;
        case 'artikel': return Item::findOrFail($id); break;
        case 'aufgaben': return Todo::findOrFail($id); break;
        case 'auftraege': return Order::findOrFail($id); break;
        case 'ausgaben': return Expense::findOrFail($id); break;
        case 'belege': return Receipt::findOrFail($id); break;
        case 'briefe': return Letter::findOrFail($id); break;
        case 'buchungen': return Transaction::findOrFail($id); break;
        case 'dateien': return Userfile::findOrFail($id); break;
        case 'einnahmen': return Income::findOrFail($id); break;
        case 'firmen': return Company::findOrFail($id); break;
        case 'kontakte': return Contact::findOrFail($id); break;
        case 'lieferscheine': return Delivery::findOrFail($id); break;
        case 'mahnungen': return Dun::findOrFail($id); break;
        case 'rechnungen': return Receipt::findOrFail($id); break;
        case 'team': return User::findOrFail($id); break;
        case 'zeiterfassung': return Time::findOrFail($id); break;
        default: abort(404);
    }
});

Auth::routes();

Route::middleware(['auth', 'company.locked'])->group(function () {

    Route::get('/', 'HomeController@index')->name('app');

    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('artikel', 'ItemController');
    Route::resource('berechtigungen', 'PermissionController');
    Route::resource('kontakte', 'ContactController');
    Route::resource('team', 'Users\UserController');
    Route::resource('zugriffsrollen', 'RoleController');

    // Abos
    Route::get('abos/{abo}', 'Receipts\Abos\AboController@show')->name('receipt.abo.show')->where('abo', '[0-9]+');
    Route::get('abos/{abo}/edit', 'Receipts\Abos\AboController@edit')->name('receipt.abo.edit')->where('abo', '[0-9]+');
    Route::put('abos/{abo}', 'Receipts\Abos\AboController@update')->name('receipt.abo.update')->where('abo', '[0-9]+');
    Route::delete('abos/{abo}', 'Receipts\Abos\AboController@destroy')->name('receipt.abo.destroy')->where('abo', '[0-9]+');
    Route::get('abos/{type}', 'Receipts\Abos\AboController@index')->name('receipt.abo.index');
    Route::post('abos/{type}', 'Receipts\Abos\AboController@store')->name('receipt.abo.store');

    Route::post('abos/{abo}/aktiv', 'Receipts\Abos\ActiveController@store')->name('receipt.abo.active.store');
    Route::delete('abos/{abo}/aktiv', 'Receipts\Abos\ActiveController@destroy')->name('receipt.abo.active.destroy');

    Route::post('abos/aus/{receipt}', 'IncomeFromController@store');
    Route::post('abos/{abo}/kontakte/{contact}', 'AboContactController@store');
    Route::delete('abos/{abo}/kontakte/{contacts}', 'AboContactController@destroy');

    // Abschlagsrechnungen
    Route::post('abschlagsrechnungen/{invoice}', 'PartialInvoiceController@store');
    Route::delete('abschlagsrechnungen/{invoice}', 'PartialInvoiceController@destroy');

    // Anfragen
    Route::get('anfragen', 'Receipts\Inquiries\InquiryController@index')->name('receipt.inquiry.index');
    Route::post('anfragen', 'Receipts\Inquiries\InquiryController@store')->name('receipt.inquiry.store');
    Route::get('anfragen/{inquiry}', 'Receipts\Inquiries\InquiryController@show')->name('receipt.inquiry.show');
    Route::get('anfragen/{inquiry}/edit', 'Receipts\Inquiries\InquiryController@edit')->name('receipt.inquiry.edit');
    Route::put('anfragen/{inquiry}', 'Receipts\Inquiries\InquiryController@update')->name('receipt.inquiry.update');
    Route::delete('anfragen/{inquiry}', 'Receipts\Inquiries\InquiryController@destroy')->name('receipt.inquiry.destroy');

    // Angebote
    Route::get('angebote', 'QuoteController@index')->name('receipt.quote.index');
    Route::post('angebote', 'QuoteController@store')->name('receipt.quote.store');
    Route::get('angebote/{quote}', 'QuoteController@show')->name('receipt.quote.show');
    Route::get('angebote/{quote}/edit', 'QuoteController@edit')->name('receipt.quote.edit');
    Route::put('angebote/{quote}', 'QuoteController@update')->name('receipt.quote.update');
    Route::delete('angebote/{quote}', 'QuoteController@destroy')->name('receipt.quote.destroy');

    Route::post('angebote/aus/{receipt}', 'QuoteFromController@store');

    // Artikel
    Route::get('artikel/{item}/umsatz', 'ItemRevenueController@show');

    // Aufgaben
    Route::get('aufgaben/kontakte', 'Todos\ContactController@index')->name('todo.contacts.index');
    Route::post('aufgaben/{todo}/kontakte/{contact}', 'Todos\ContactController@store')->name('todo.contacts.store');
    Route::delete('aufgaben/{todo}/kontakte/{contact}', 'Todos\ContactController@destroy')->name('todo.contacts.destroy');

    Route::get('aufgaben', 'Todos\TodoController@index')->name('todo.index');
    Route::post('aufgaben', 'Todos\TodoController@store')->name('todo.store');
    Route::get('aufgaben/{todo}', 'Todos\TodoController@show')->name('todo.show');
    Route::get('aufgaben/{todo}/edit', 'Todos\TodoController@edit')->name('todo.edit');
    Route::put('aufgaben/{todo}', 'Todos\TodoController@update')->name('todo.update');
    Route::delete('aufgaben/{todo}', 'Todos\TodoController@destroy')->name('todo.destroy');

    Route::post('aufgaben/{todo}/erledigt', 'Todos\CompletedController@store')->name('todo.completed.store');
    Route::delete('aufgaben/{todo}/erledigt', 'Todos\CompletedController@destroy')->name('todo.completed.destroy');

    // Aufträge
    Route::get('auftraege', 'OrderController@index')->name('receipt.order.index');
    Route::post('auftraege', 'OrderController@store')->name('receipt.order.store');
    Route::get('auftraege/{order}/show', 'OrderController@show')->name('receipt.order.show');
    Route::get('auftraege/{order}/edit', 'OrderController@edit')->name('receipt.order.edit');
    Route::put('auftraege/{order}', 'OrderController@update')->name('receipt.order.update');
    Route::delete('auftraege/{order}', 'OrderController@destroy')->name('receipt.order.destroy');

    Route::post('auftraege/aus/{receipt}', 'OrderFromController@store');

    // Ausgaben
    Route::get('ausgaben', 'ExpenseController@index');
    Route::post('ausgaben', 'ExpenseController@store');
    Route::get('ausgaben/{expense}/edit', 'ExpenseController@edit');
    Route::put('ausgaben/{expense}', 'ExpenseController@update');
    Route::delete('ausgaben/{expense}', 'ExpenseController@destroy');
    Route::post('ausgaben/aus/{receipt}', 'ExpenseFromController@store');

    // Rechnungen
    Route::get('rechnungen', 'Receipts\Invoices\InvoiceController@index')->middleware('can:view,' . Invoice::class)->name('receipt.invoice.index');
    Route::post('rechnungen', 'Receipts\Invoices\InvoiceController@store')->middleware('can:create,' . Invoice::class)->name('receipt.invoice.store');
    Route::get('rechnungen/{invoice}', 'Receipts\Invoices\InvoiceController@show')->middleware('can:view,invoice')->name('receipt.invoice.show');
    Route::get('rechnungen/{invoice}/edit', 'Receipts\Invoices\InvoiceController@edit')->middleware('can:update,invoice')->name('receipt.invoice.edit');
    Route::put('rechnungen/{invoice}', 'Receipts\Invoices\InvoiceController@update')->middleware('can:update,invoice')->name('receipt.invoice.update');
    Route::delete('rechnungen/{invoice}', 'Receipts\Invoices\InvoiceController@destroy')->middleware('can:delete,invoice')->name('receipt.invoice.destroy');

    Route::post('rechnungen/aus/{receipt}', 'InvoiceFromController@store')->middleware('can:create,' . Invoice::class)->name('receipt.invoice.from.store');

    Route::get('rechnungen/{invoice}/mahnungen', 'InvoiceDunController@index')->middleware('can:view,invoice');
    Route::post('rechnungen/{invoice}/mahnungen', 'InvoiceDunController@store')->middleware('can:update,invoice');

    // Bank
    Route::get('bank', 'BankController@index');
    Route::post('bank', 'BankController@store');

    Route::post('bank/konten/{bank_company}/tan', 'Banks\TanController@store');

    Route::get('bank/konten', 'BankCompanyController@index');
    Route::post('bank/konten', 'BankCompanyController@store');

    // Belege
    Route::get('belege/{receipt}/artikel', 'Receipts\ItemController@index')->name('receipt.item.index');
    Route::post('belege/{receipt}/artikel', 'Receipts\ItemController@store')->name('receipt.item.store');
    Route::get('belege/artikel/{receiptItem}/edit', 'Receipts\ItemController@edit')->name('receipt.item.edit');
    Route::put('belege/{receipt}/artikel/{receiptItem}', 'Receipts\ItemController@update')->name('receipt.item.update');
    Route::delete('belege/{receipt}/artikel/{receiptItem}', 'Receipts\ItemController@destroy')->name('receipt.item.destroy');

    Route::get('{type}/{model}/aufgaben', 'Receipts\Receipts\TodoController@index')->name('receipt.receipt.todo.index');

    Route::put('belege/{receipt}/auftrag', 'Receipts\Receipts\OrderController@update')->name('receipt.receipt.order.update');

    Route::post('belege/pdfs', 'DraftController@index');
    Route::get('belege/vorlage/{receipt?}', 'DraftController@show');
    Route::get('belege/pdf/{receipt}', 'DraftController@pdf');

    Route::post('belege/exporte/datev/einzeln', 'Receipts\\Exports\\Datev\\SingleController@index');

    Route::post('belege/status/{receipt}', 'StatusController@store');
    Route::post('belege/{receipt}/status/create', 'StatusController@create');
    Route::delete('belege/status/{status}', 'StatusController@destroy');

    Route::get('briefe', 'LetterController@index');
    Route::post('briefe', 'LetterController@store');
    Route::get('briefe/{letter}/edit', 'LetterController@edit');
    Route::put('briefe/{letter}', 'LetterController@update');
    Route::delete('briefe/{letter}', 'LetterController@destroy');

    Route::post('briefe/aus/{receipt}', 'LetterFromController@store');

    // Buchungen
    Route::get('buchungen', 'TransactionController@index');

    Route::put('buchungen/{transaction}', 'TransactionController@update');

    Route::get('buchungen/belege', 'Transactions\ReceiptController@index');

    // Dateien
    Route::get('dateien', 'UserfileController@index')->name('userfile.index');
    Route::post('dateien', 'UserfileController@store')->name('userfile.store');
    Route::put('dateien/{userfile}', 'UserfileController@update')->name('userfile.update');
    Route::delete('dateien/{userfile}', 'UserfileController@destroy')->name('userfile.destroy');

    Route::get('{type}/{model}/dateien', 'UserfileableController@index');
    Route::post('{type}/{model}/dateien', 'UserfileableController@store');

    // Einheiten
    Route::get('einheiten', 'UnitController@index');
    Route::post('einheiten', 'UnitController@store');
    Route::put('einheiten/{unit}', 'UnitController@update');
    Route::delete('einheiten/{unit}', 'UnitController@destroy');

    // Einnahmen
    Route::get('einnahmen', 'IncomeController@index');
    Route::post('einnahmen', 'IncomeController@store');
    Route::get('einnahmen/{income}/edit', 'IncomeController@edit');
    Route::put('einnahmen/{income}', 'IncomeController@update');
    Route::delete('einnahmen/{income}', 'IncomeController@destroy');

    Route::post('einnahmen/aus/{receipt}', 'IncomeFromController@store');

    // Einstellungen
    Route::get('einstellungen/buchhaltung', 'Settings\AccountingController@edit');
    Route::put('einstellungen/buchhaltung', 'Settings\AccountingController@update');

    Route::put('einstellungen/finanzielles', 'Settings\FinancialController@update');

    Route::get('einstellungen/mahnstufen', 'Settings\DunningController@index');
    Route::post('einstellungen/mahnstufen', 'Settings\DunningController@store');
    Route::get('einstellungen/mahnstufen/{level}/edit', 'Settings\DunningController@edit');
    Route::put('einstellungen/mahnstufen/{level}', 'Settings\DunningController@update');
    Route::delete('einstellungen/mahnstufen/{level}', 'Settings\DunningController@destroy');

    Route::get('einstellungen/nummernkreise', 'Settings\NumbersController@edit');
    Route::put('einstellungen/nummernkreise', 'Settings\NumbersController@update');

    // Firma
    Route::get('firma/edit', 'CompanyController@edit');
    Route::put('firma/{company}', 'CompanyController@update');

    // Individuelle Felder
    Route::get('felder/{type}', 'CustomFields\CustomFieldController@index')->name('customfield.index');
    Route::post('felder/{type}', 'CustomFields\CustomFieldController@store')->name('customfield.store');
    Route::put('felder/{customfield}', 'CustomFields\CustomFieldController@update')->name('customfield.update');
    Route::delete('felder/{customfield}', 'CustomFields\CustomFieldController@destroy')->name('customfield.destroy');

    Route::get('{type}/{model}/felder', 'CustomFields\CustomFieldValueController@index')->name('customfieldvalue.index');
    Route::post('{type}/{model}/felder', 'CustomFields\CustomFieldValueController@store')->name('customfieldvalue.store');
    Route::put('feld/{customfieldvalue}', 'CustomFields\CustomFieldValueController@update')->name('customfieldvalue.update');
    Route::delete('feld/{customfieldvalue}', 'CustomFields\CustomFieldValueController@destroy')->name('customfieldvalue.destroy');

    // Interaktionen
    Route::get('interaktionen/{type?}/{model?}', 'Contacts\InteractionController@index')->where('type', '[A-Za-z]+')->name('interaction.index');
    Route::post('interaktionen/{type}/{model}', 'Contacts\InteractionController@store')->name('interaction.store');
    Route::get('interaktionen/{interaction}', 'Contacts\InteractionController@show')->name('interaction.show');
    Route::get('interaktionen/{interaction}/edit', 'Contacts\InteractionController@edit')->where('interaction', '[0-9]+')->name('interaction.edit');
    Route::put('interaktionen/{interaction}', 'Contacts\InteractionController@update')->name('interaction.update');
    Route::delete('interaktionen/{interaction}', 'Contacts\InteractionController@destroy')->name('interaction.destroy');

    // Import
    Route::get('import/{type}', 'Imports\ImportController@create')->where('type', '[A-Za-z]+')->name('import.create');
    Route::post('import/{type}', 'Imports\ImportController@store')->where('type', '[A-Za-z]+')->name('import.store');

    // Kalender
    Route::get('kalender', 'CalendarController@index');
    Route::post('kalender', 'CalendarController@store');
    Route::put('kalender/{todo}', 'CalendarController@update');

    // Kommentare
    Route::get('kommentare', 'CommentController@index');
    Route::get('{type}/{model}/kommentare', 'CommentController@index');
    Route::post('{type}/{model}/kommentare', 'CommentController@store');

    // Konten
    Route::get('konten', 'AccountController@index');
    Route::post('konten', 'AccountController@store');
    Route::delete('konten/{account}', 'AccountController@destroy');

    // Kontakte
    Route::get('kontakte/adresse/{contact}', 'ContactAddressController@show');
    Route::get('kontakte/{contact}/umsatz', 'ContactRevenueController@show');

    // Einnahmen
    Route::get('mahnungen', 'DunController@index');
    Route::post('mahnungen', 'DunController@store');
    Route::get('mahnungen/{dun}/edit', 'DunController@edit');
    Route::put('mahnungen/{dun}', 'DunController@update')->where('dun', '[0-9]+');
    Route::delete('mahnungen/{dun}', 'DunController@destroy');

    Route::get('mahnungen/rechnungen', 'InvoiceDunController@index');

    // Ansprechpartner
    Route::get('kontakte/{contact}/ansprechpartner', 'PersonController@index')->name('contact.person.index');
    Route::post('kontakte/{contact}/ansprechpartner', 'PersonController@store')->name('contact.person.store');
    Route::get('kontakte/ansprechpartner/{person}/edit', 'PersonController@edit')->name('contact.person.edit');
    Route::put('kontakte/ansprechpartner/{person}', 'PersonController@update')->name('contact.person.update');
    Route::delete('kontakte/ansprechpartner/{person}', 'PersonController@destroy')->name('contact.person.destroy');

    Route::post('kontakte/ansprechpartner/{person}/default/{type}', 'PersonDefaultController@store');
    Route::delete('kontakte/ansprechpartner/{person}/default/{type}', 'PersonDefaultController@destroy');

    // Leiferscheine
    Route::get('lieferscheine', 'DeliveryController@index');
    Route::post('lieferscheine', 'DeliveryController@store');
    Route::get('lieferscheine/{delivery}/edit', 'DeliveryController@edit');
    Route::put('lieferscheine/{delivery}', 'DeliveryController@update');
    Route::delete('lieferscheine/{delivery}', 'DeliveryController@destroy');
    Route::post('lieferscheine/aus/{receipt}', 'DeliveryFromController@store');

    // Projekte
    Route::get('projekte', 'Projects\ProjectController@index')->name('project.index');
    Route::post('projekte', 'Projects\ProjectController@store')->name('project.store');
    Route::get('projekte/{project}', 'Projects\ProjectController@show');
    Route::get('projekte/{project}/edit', 'Projects\ProjectController@edit');
    Route::put('projekte/{project}', 'Projects\ProjectController@update');
    Route::delete('projekte/{project}', 'Projects\ProjectController@destroy');

        // Abschnitte
        Route::get('projekte/{project}/abschnitte', 'Projects\SectionController@index');
        Route::post('projekte/{project}/abschnitte', 'Projects\SectionController@store');
        Route::get('projekte/{project}/abschnitte/{section}', 'Projects\SectionController@show');
        Route::put('projekte/{project}/abschnitte/{section}', 'Projects\SectionController@update');
        Route::delete('projektabschnitte/{section}', 'Projects\SectionController@destroy');

        // Aufgaben
        Route::get('projektabschnitte/{section}/aufgaben', 'Projects\TodoController@index');
        Route::post('projektabschnitte/{section}/aufgaben', 'Projects\TodoController@store');
        Route::get('projektabschnitte/{section}/aufgaben/{todo}', 'Projects\TodoController@show');
        Route::put('projektabschnitte/{section}/aufgaben/{todo}', 'Projects\TodoController@update');

        // Gruppen
        Route::get('projektgruppen', 'Projects\GroupController@index');
        Route::post('projektgruppen', 'Projects\GroupController@store')->name('projectgroup.store');
        Route::get('projektgruppen/{group}', 'Projects\GroupController@show');
        Route::put('projektgruppen/{group}', 'Projects\GroupController@update')->name('projectgroup.update');
        Route::delete('projektgruppen/{group}', 'Projects\GroupController@destroy');


    // Taggable
    Route::post('{type}/{model}/tags/{tag}', 'TaggableController@store')->name('taggable.store');
    Route::delete('{type}/{model}/tags/{tag}', 'TaggableController@destroy')->name('taggable.destroy');

    Route::post('{type}/{model}/kategorien/{tag}', 'TaggableController@store')->name('taggable.store');
    Route::delete('{type}/{model}/kategorien/{tag}', 'TaggableController@destroy')->name('taggable.destroy');

    // Tags
    Route::get('kategorien/{type}', 'TagController@index')->name('tag.index');
    Route::post('kategorien/{type}', 'TagController@store')->name('tag.store');
    Route::put('kategorien/{tag}', 'TagController@update')->name('tag.update');
    Route::delete('kategorien/{tag}', 'TagController@destroy')->name('tag.destroy');

    // Team
    Route::post('team/{user}/einladen', 'Users\InviteController@store')->name('team.invite.store');

    // Terms
    Route::get('terms/{type}', 'TermController@index');
    Route::get('terms/{type}/{term}/edit', 'TermController@edit');
    Route::post('terms/{type}', 'TermController@store');
    Route::put('terms/{term}', 'TermController@update');
    Route::delete('terms/{term}', 'TermController@destroy');

    // Terms Default
    Route::post('terms/{type}/{term}/default', 'TermDefaultController@store');

    // Textbausteine
    Route::get('textbausteine', 'BoilerplateController@index');
    Route::post('textbausteine', 'BoilerplateController@store');
    Route::put('textbausteine/{boilerplate}', 'BoilerplateController@update');
    Route::delete('textbausteine/{boilerplate}', 'BoilerplateController@destroy');

    // Umsatz
    Route::get('umsatz', 'CompanyRevenueController@index');

    // Vorlagen
    Route::get('vorlagen/edit', 'TemplateController@edit');
    Route::put('vorlagen/{template}', 'TemplateController@update');
    Route::post('vorlagen/{template}/logo', 'TemplateLogoController@store');
    Route::delete('vorlagen/{template}/logo', 'TemplateLogoController@destroy');

    // Zahlungen
    Route::get('forderungen', 'Receipts\Payments\PaymentController@index');
    Route::get('verbindlichkeiten', 'Receipts\Payments\PaymentController@index');

    // Zeiten
    Route::get('zeiten', 'TimeController@index');
    Route::post('zeiten', 'TimeController@store');
    Route::get('zeiten/{time}', 'TimeController@show');
    Route::get('zeiten/{time}/edit', 'TimeController@edit');
    Route::put('zeiten/{time}', 'TimeController@update');
    Route::delete('zeiten/{time}', 'TimeController@destroy');

    Route::post('zeiten/{time}/abrechnen', 'Times\InvoiceController@store');
    Route::post('zeiten/abrechnen', 'Times\InvoiceController@store');

    // Zeiterfassung
    Route::post('zeiterfassung', 'TimeRecordingController@store');
    Route::delete('zeiterfassung/{time}', 'TimeRecordingController@destroy');

    include('raw.php');

});

Route::middleware(['auth'])->group(function () {
    Route::get('einstellungen/finanzielles', 'Settings\FinancialController@edit');
    Route::get('firma/guthaben', 'BalanceController@index');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Firmen
    Route::get('firmen', 'CompanyController@index');
    Route::post('firmen', 'CompanyController@store')->name('companies.store');
    Route::get('firmen/{company}', 'CompanyController@show');

    Route::put('firmen/{company}/switch', 'Companies\SwitchController@update')->name('companies.switch.update');

    // Guthaben
    Route::get('guthaben', 'BalanceController@index');
    Route::get('guthaben/create', 'BalanceController@create');
    Route::post('guthaben', 'BalanceController@store');
    Route::put('guthaben/{transaction}', 'BalanceController@update');
});

Route::middleware(['guest', 'signed'])->group(function () {
    // Password
    Route::get('team/passwort/{user}', 'Auth\SetPasswordController@create')->name('password.create');
    Route::post('team/passwort/{user}', 'Auth\SetPasswordController@store')->name('password.store');
});