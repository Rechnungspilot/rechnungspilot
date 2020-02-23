<?php

namespace App\Http\Controllers;

use App\Receipts\Inquiries\Inquiry;
use App\Receipts\Invoice;
use App\Receipts\Quote;
use App\Receipts\Statuses\Completed;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\MorphedTo;
use App\Receipts\Statuses\Received;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $inquiries_count = DB::table('receipts')
            ->select('latest_status_type', DB::raw('count(*) as count'))
            ->where('type', Inquiry::class)
            ->whereIn('latest_status_type', [Draft::class, Received::class, InProgress::class])
            ->groupBy('latest_status_type')
            ->get();

        $order_count = DB::table('receipts')
            ->select('latest_status_type', DB::raw('count(*) as count'))
            ->where('type', Order::class)
            ->whereNotIn('latest_status_type', [Completed::class, MorphedTo::class])
            ->groupBy('latest_status_type')
            ->get();

        return view('home')
            ->with('outstandingInvoices', Invoice::outstandingBalance())
            ->with('openQuotes', Quote::open())
            ->with('inquiriesCount', $inquiries_count)
            ->with('orderCount', $order_count);
    }
}
