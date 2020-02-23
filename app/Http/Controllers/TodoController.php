<?php

namespace App\Http\Controllers;

use App\Todos\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function raw(Request $request)
    {
        return Todo::with([
                'item',
                'todoable',
            ])->search($request->input('searchtext'))
            ->where('completed', false)
            ->orderBy('name', 'ASC')
            ->get();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $validatedData = $request->validate([
            'address' => 'string',
            'contact_id' => 'required',
            'date' => 'date_format:d.m.Y',
            'description' => 'nullable|string',
            'name' => 'string',
            'note' => 'nullable|string',
            'user_id' => 'required',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d.m.Y', $validatedData['date']);

        $todo->update($validatedData);
        $todo->contacts->first()->update([
            'contact_id' => $validatedData['contact_id'],
            'address' => $validatedData['address'],
        ]);

        return back()
            ->with('status', 'Änderungen gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todos\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->subtodos()->delete();
        $todo->delete();
        if ($todo->isInProject())
        {
            $todo->project->cache();
        }
    }
}
