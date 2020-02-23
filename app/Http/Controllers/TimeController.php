<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Time;
use App\Todos\Todo;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Time::with([
                'item',
                'receiptitems',
                'timeable',
                'user',
            ])
                ->withAllTags($request->input('tags'), 'zeiterfassung')
                ->orderBy('start_at', 'DESC')
                ->paginate($request->input('perPage'));
        }

        return view('time.index')
            ->with('tags', Tag::withType('zeiterfassung')->get())
            ->with('team', User::all());
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
            'start_at' => 'sometimes|required|date',
            'end_at' => 'sometimes|required|date|after:start_at',
            'user_id' => 'required|numeric',
            'item_id' => 'required|numeric|exists:items,id',
            'todo_id' => 'sometimes|required|numeric',
            'note' => 'sometimes|nullable|string',
        ]);

        $validatedData['company_id'] = auth()->user()->company_id;
        $validatedData['start_at'] = array_key_exists('start_at', $validatedData) ? new Carbon($validatedData['start_at']) : now();
        $validatedData['end_at'] = array_key_exists('end_at', $validatedData) ? new Carbon($validatedData['end_at']) : null;

        $time = Time::make($validatedData);
        if ($validatedData['todo_id'])
        {
            $todo = Todo::find($validatedData['todo_id']);
            $time->timeable()->associate($todo);
        }
        $time->save();

        $time->load([
            'item',
            'user',
            'timeable',
        ]);

        return $time;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function show(Time $time)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function edit(Time $time)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Time $time)
    {
        $validatedData = $request->validate([
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'user_id' => 'required|numeric',
            'item_id' => 'required|numeric|exists:items,id',
            'todo_id' => 'required|numeric',
            'note' => 'string',
        ]);

        $validatedData['company_id'] = auth()->user()->company_id;
        $validatedData['start_at'] = new Carbon($validatedData['start_at']);
        $validatedData['end_at'] = new Carbon($validatedData['end_at']);

        if ($validatedData['todo_id'])
        {
            $todo = Todo::find($validatedData['todo_id']);
            $time->timeable()->associate($todo);
        }
        else
        {
            $validatedData['timeable_id'] = 0;
            $validatedData['timeable_type'] = null;
        }

        $time->update($validatedData);

        $time->load([
            'item',
            'user',
            'timeable',
        ]);

        return $time;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function destroy(Time $time)
    {
        $time->delete();
    }
}
