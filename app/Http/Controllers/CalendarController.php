<?php

namespace App\Http\Controllers;

use App\Contacts\Contact;
use App\Receipts\Receipt;
use App\Todos\Todo;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            $date = $request->has('date') ? new Carbon($request->input('date')) : today();

            $calendar = [
                'days' => [],
                'events' => [],
                'date' => $date,
                'week_of_year' => $date->weekOfYear,
                'month_name' => $date->monthName,
                'year' => $date->year,
            ];

            $period = $this->getDays($request->input('type') ?? 'default', $date, $request->input('show_weekend') == 'false' ? false : $request->input('show_weekend'));
            foreach ($period as $day) {
                $format = $day->format('Y-m-d');
                $calendar['days'][$format] = [
                    'date' => $day,
                    'day' => $day->format('d'),
                    'name' => $day->minDayName,
                    'week_of_year' => $day->weekOfYear,
                    'month_name' => $day->monthName,
                    'dateFormat' => $format,
                ];
            }

            $todos = Todo::with([
                'contacts.pivot.user',
                'item',
                'team',
            ])
            ->receipt($request->input('order_id'))
            ->whereNotNull('end_at')
            ->where( function ($query) use ($request) {
                $query->whereIn('user_id', $request->input('users'));
                if (array_search('0', $request->input('users')) !== false)
                {
                    $query->orWhereNull('user_id');
                }
            })

                ->where(function ($query) use ($period) {
                    $query->orWhereBetween('start_at', [
                            $period->getStartDate(),
                            $period->getEndDate()->endOfDay(),
                        ])
                        ->orWhereBetween('end_at', [
                            $period->getStartDate(),
                            $period->getEndDate()->endOfDay(),
                        ])
                        ->orWhereRaw('("' . $period->getStartDate() . '" BETWEEN todos.start_at AND todos.end_at)')
                        ->orWhereRaw('("' . $period->getEndDate()->endOfDay() . '" BETWEEN todos.start_at AND todos.end_at)');
                })
                ->orderBy('start_at', 'ASC')
                ->get();

            foreach ($todos as $key => $todo)
            {
                $date = $todo->start_at;
                $calendar['events'][] = $todo;
            }

            return $calendar;
        }

        $users = User::all();

        return view('calendar.index')
            ->with('users', $users);
    }

    protected function getDays(string $type, Carbon $date, bool $showWeekend) : CarbonPeriod
    {
        switch ($type)
        {
            case 'customday':
                $start_at = $date;
                $end_at = $date->copy()->add(3, 'days')->endOfDay();
                break;
            case 'day':
                $start_at = $date;
                $end_at = $date;
                break;
            case 'week':
                if ($date->isWeekend())
                {
                    $showWeekend = true;
                }
                $start_at = $date->copy()->startOfWeek();
                $end_at = $date->copy()->endOfWeek($showWeekend ? Carbon::SUNDAY : Carbon::FRIDAY);
                break;
            case 'month':
                $start_at = $date->copy()->startOfMonth()->startOfWeek();
                $end_at = $date->copy()->endOfMonth()->endOfWeek();
                break;
            default:
                $start_at = $date;
                $end_at = $date;
                break;
        }

        return CarbonPeriod::create($start_at, $end_at);
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
        $validatedData = $request->validate([
            'contact_id' => 'required|integer',
            'end_at' => 'required|date|after:start_at',
            'item_id' => 'required|integer',
            'name' => 'required|string',
            'note' => 'nullable|string',
            'start_at' => 'required|date',
            'user_id' => 'nullable|integer',
            'receipt_id' => 'sometimes|integer',
        ]);

        $validatedData['company_id'] = auth()->user()->company_id;
        $validatedData['start_at'] = new Carbon($validatedData['start_at']);
        $validatedData['end_at'] = new Carbon($validatedData['end_at']);
        $validatedData['duration'] = $validatedData['end_at']->diffInSeconds($validatedData['start_at']);
        $validatedData['priority'] = 1;

        $todo = Todo::make($validatedData);

        if ($validatedData['receipt_id'] > 0)
        {
            $todo->todoable()->associate(Receipt::find($validatedData['receipt_id']));
        }

        $todo->save();

        if ($validatedData['contact_id'] > 0)
        {
            $contact = Contact::findOrFail($validatedData['contact_id']);
            $todo->attach($contact);
        }

        return $todo->fresh()->load([
            'contacts.pivot.user',
            'item',
            'team',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todos\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Todos\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todos\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'item_id' => 'required|numeric',
            'note' => 'nullable|string',
            'user_id' => 'nullable|integer',
        ]);

        $validatedData['start_at'] = new Carbon($validatedData['start_at']);
        $validatedData['end_at'] = new Carbon($validatedData['end_at']);
        $validatedData['duration'] = $validatedData['end_at']->diffInSeconds($validatedData['start_at']);

        $todo->update($validatedData);

        return $todo->load([
            'contacts.pivot.user',
            'item',
            'team',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todos\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        //
    }
}
