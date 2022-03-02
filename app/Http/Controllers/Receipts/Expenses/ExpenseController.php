<?php

namespace App\Http\Controllers\Receipts\Expenses;

use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Item;
use App\Receipts\Boilerplate;
use App\Receipts\Expense;
use App\Receipts\Statuses\Draft;
use App\Receipts\Term;
use App\Tag;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Expense::with(['contact', 'status'])
                ->search($request->input('searchtext'))
                ->contact($request->input('contact_id'))
                ->year($request->input('year'))
                ->status($request->input('status_type'))
                ->withAllTags($request->input('tags'), Expense::class)
                ->orderBy('date', 'DESC')
                ->paginate(15);
        }

        return view('expense.index')
            ->with('contacts', Contact::all())
            ->with('statuses', Expense::AVAILABLE_STATUSES)
            ->with('labels', Expense::labels())
            ->with('tags', Tag::withType(Expense::class)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contact = $request->has('contact_id') ? Contact::find($request->input('contact_id')) : Contact::first();

        $term = Term::default(Expense::class, $contact->expense_term_id);

        $receipt = Expense::create([
            'address' => $contact->billing_address,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
            'term_id' => $term->id,
            'date_due' => now()->add($term->days, 'days'),
            'name' => '',
        ]);

        if ($request->wantsJson()) {
            $receipt->cache();
            return $receipt;
        }

        return redirect($receipt->edit_path);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        $expense->load([
            'items',
            'tags',
            'status',
        ]);

        $expense->statuses = $expense->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();

        return view('expense.show')
            ->with('expense', $expense);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipts\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        $expense->load([
            'company',
            'items',
            'tags',
            'previewFile',
            'term',
        ]);

        $expense->statuses = $expense->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();
        $expense->calculateTax();

        return view('expense.edit')
            ->with('expense', $expense)
            ->with('contacts', Contact::all())
            ->with('units', Unit::all())
            ->with('items', Item::all())
            ->with('boilerplates', Boilerplate::all())
            ->with('placeholders', Boilerplate::PLACEHOLDER)
            ->with('terms', Term::where('type', Expense::class)
                ->orderBy('name', 'ASC')
                ->get()
                ->keyBy('id')
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipts\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $validatedData = $request->validate([
            'contact_id' => 'required',
            'date' => 'date_format:d.m.Y',
            'date_due' => 'date_format:d.m.Y',
            'name' => 'nullable|string',
            'term_id' => 'required',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d.m.Y', $validatedData['date']);
        $validatedData['date_due'] = Carbon::createFromFormat('d.m.Y', $validatedData['date_due']);

        $oldContactId = $expense->contact_id;

        $expense->update($validatedData);
        $expense->cache();

        return back()
            ->with('status', 'Ausgabe gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipts\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Expense $expense)
    {
        $expense->statuses()->delete();
        $expense->delete();

        if ($request->wantsJson()) {
            return;
        }

        return redirect($expense->index_path);
    }
}
