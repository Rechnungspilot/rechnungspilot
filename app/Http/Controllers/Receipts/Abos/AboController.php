<?php

namespace App\Http\Controllers\Receipts\Abos;

use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Item;
use App\Jobs\CacheContact;
use App\Receipts\Abos\Abo;
use App\Receipts\Abos\Settings;
use App\Receipts\Boilerplate;
use App\Receipts\Statuses\Created;
use App\Receipts\Statuses\Draft;
use App\Receipts\Term;
use App\Support\Type;
use App\Tag;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AboController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type)
    {
        if ($request->wantsJson()) {

            $class_name = Type::class($type);

            return Abo::select('receipts.*')
                ->with([
                    'contact',
                    'status'
                ])
                ->join('abo_settings', 'abo_settings.abo_id', 'receipts.id')
                ->where('abo_settings.type', $class_name)
                ->search($request->input('searchtext'))
                ->contact($request->input('contact_id'))
                ->status($request->input('status_type'))
                ->withAllTags($request->input('tags'), Abo::class)
                ->orderBy('date', 'DESC')
                ->paginate(15);
        }

        return view('abo.index')
            ->with('contacts', Contact::all())
            ->with('statuses', Abo::AVAILABLE_STATUSES)
            ->with('labels', Abo::labels())
            ->with('tags', Tag::withType(Abo::class)->get())
            ->with('type', $type);
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
    public function store(Request $request, string $type)
    {
        $receipt = Abo::create([
            'settings_type' => Type::class($type),
            'address' => NULL,
            'company_id' => auth()->user()->company_id,
        ]);

        if ($request->has('contact_id')) {
            $receipt->contacts()->attach($request->input('contact_id'));
        }

        if ($request->wantsJson()) {
            $receipt->cache();
            return $receipt;
        }

        return redirect($receipt->edit_path);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipts\abo  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(string $type, Abo $subscription)
    {
        $subscription->load([
            'tags',
            'settings',
            'contacts',
        ]);

        $subscription->statuses = $subscription->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();
        $subscription->calculateTax();

        $settings = \App\Receipts\Abos\Settings::firstWhere([
            'abo_id' => $subscription->id,
        ]);

        $subscription->contacts_count = count($subscription->contacts);

        return view('abo.show')
            ->with('abo', $subscription);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipts\abo  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(string $type, Abo $subscription)
    {
        $subscription->load([
            'company',
            'contacts',
            'items',
            'settings',
            'tags',
        ]);

        $subscription->statuses = $subscription->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();
        $subscription->calculateTax();

        return view('abo.edit')
            ->with('abo', $subscription)
            ->with('contacts', Contact::all())
            ->with('units', Unit::all())
            ->with('items', Item::all())
            ->with('intervalUnits', Settings::INTERVAL_UNITS)
            ->with('sendMailOptions', Settings::SEND_MAIL_OPTIONS);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipts\abo  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $type, Abo $subscription)
    {
        $validatedData = $request->validate([
            'email' => 'nullable|string',
            'interval_unit' => 'required|string',
            'interval_value' => 'required|gt:0',
            'last_at' => 'nullable|date_format:d.m.Y',
            'last_count' => 'required',
            'last_type' => 'required',
            'next_at' => 'date_format:d.m.Y',
            'number' => 'required|string',
            'send_mail' => 'required|boolean',
            'start_at' => 'date_format:d.m.Y',
            'is_partial' => 'nullable|boolean',
        ]);

        $validatedData['start_at'] = Carbon::createFromFormat('d.m.Y', $validatedData['start_at'])->startOfDay();
        $validatedData['next_at'] = Carbon::createFromFormat('d.m.Y', $validatedData['next_at'])->startOfDay();
        $validatedData['date'] = $validatedData['start_at'];
        $validatedData['contact_id'] = $subscription->contact_id;
        $validatedData['is_partial'] = (Arr::has($validatedData, 'is_partial') ? (bool) $validatedData['is_partial'] : false);
        if ($validatedData['last_at']) {
            $validatedData['last_at'] = Carbon::createFromFormat('d.m.Y', $validatedData['last_at'])->startOfDay();
        }
        else {
            $validatedData['last_at'] = null;
        }
        $validatedData = collect($validatedData);

        $subscription->update($validatedData->only([
            'number',
            'date',
            'contact_id',
            'is_partial',
        ])->toArray());

        $subscription->settings()->update($validatedData->except([
            'number',
            'date',
            'contact_id',
            'is_partial',
        ])->toArray());

        $subscription->cache();

        return redirect($subscription->path)
            ->with('status', 'Abo gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipts\abo  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $type, Abo $subscription)
    {
        $subscription->delete();

        if ($request->wantsJson()) {
            return;
        }

        return redirect($subscription->index_path);
    }
}
