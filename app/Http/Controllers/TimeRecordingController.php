<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Item;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Order;
use App\Receipts\Receipt;
use App\Time;
use App\Todos\Todo;
use Illuminate\Http\Request;

class TimeRecordingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $time = Time::with([
                'user',
                'item',
                'timeable.todoable',
            ])->where('end_at', null)
            ->where('user_id', auth()->user()->id)
            ->first();

        return view('time.recording.index')
            ->with('runningTime', $time);
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
            'user_id' => 'required|numeric',
            'item_id' => 'required|numeric|exists:items,id',
            'todo_id' => 'required|numeric',
        ]);

        $validatedData['company_id'] = auth()->user()->company_id;
        $validatedData['start_at'] = now();

        $time = Time::make($validatedData);
        if ($validatedData['todo_id'])
        {
            $todo = Todo::find($validatedData['todo_id']);
            if ($todo->user_id != $validatedData['user_id'])
            {
                $todo->user_id = $validatedData['user_id'];
                $todo->save();
            }
            $time->timeable()->associate($todo);
        }
        $time->save();

        $time->load([
            'item',
            'user',
            'timeable.todoable',
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Time $time)
    {
        $validatedData = $request->validate([
            'note' => 'nullable|string',
            'completeOrder' => 'boolean',
        ]);

        if (is_null($time->end_at))
        {
            $validatedData['end_at'] = now();
            $time->update($validatedData);
        }

        if ($time->timeable_type == Todo::class) {
            $time->timeable->complete()
                ->save();

            if ($time->industryHours > 0 && $time->timeable->todoable_type == Receipt::class)
            {
                $order = $time->timeable->todoable;
                $item = Item::find($time->item_id);
                $receiptItem = $order->addItem($item, [
                    'quantity' => $time->industryHours,
                ], $time);
            }
        }

        if ($validatedData['completeOrder']) {
            $time->timeable->todoable->completed();
            $comment = new Comment([
                'text' => 'Auftrag abgeschlossen',
            ]);
            $time->timeable->todoable->comments()->save($comment);
        }

        $time->load([
            'item',
            'user',
        ]);

        return $time;
    }
}
