<?php

namespace App\Http\Controllers\Receipts\Payments;

use App\Banks\Account;
use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Receipts\Expense;
use App\Receipts\Income;
use App\Receipts\Invoice;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Payed;
use App\Receipts\Statuses\Received;
use App\Receipts\Statuses\Send;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = basename(url()->current());

        if ($request->wantsJson()) {
            switch ($type) {
                case 'forderungen':
                    return $this->getClaims($request);
                    break;
                case 'verbindlichkeiten':
                    return $this->getLiabilities($request);
                    break;
                default:
                    abort();
                    break;
            }
        }

        return view('receipt.payment.index')
            ->with('type', $type)
            ->with('accounts', Account::all())
            ->with('labels', Invoice::labels())
            ->with('contacts', Contact::all())
            ->with('statuses', Invoice::AVAILABLE_STATUSES);
    }

    protected function getClaims(Request $request)
    {
        return Receipt::with([
                'contact',
            ])
            ->whereIn('type', [Invoice::class, Income::class])
            ->whereDoesntHave('statuses', function (Builder $query) {
                $query->where('type', Payed::class);
            })
            ->whereHas('statuses', function (Builder $query) {
                $query->where('type', Send::class);
            })
            ->search($request->input('searchtext'))
            ->status($request->input('status_type'))
            ->paginate(15);
    }

    protected function getLiabilities(Request $request)
    {
        return Receipt::with([
                'contact',
            ])
            ->whereIn('type', [Expense::class])
            ->whereDoesntHave('statuses', function (Builder $query) {
                $query->where('type', Payed::class);
            })
            ->whereHas('statuses', function (Builder $query) {
                $query->where('type', Received::class);
            })
            ->search($request->input('searchtext'))
            ->status($request->input('status_type'))
            ->paginate(15);
    }
}
