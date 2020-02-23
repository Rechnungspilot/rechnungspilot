<?php

namespace App\Http\Controllers\Todos;

use App\Http\Controllers\Controller;
use App\Todos\Todo;
use Illuminate\Http\Request;

class CompletedController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Todo $todo)
    {
        $todo->complete()
            ->save();

        if ($todo->isInProject())
        {
            $todo->project->cache();
        }

        if ($request->wantsJson())
        {
            return $todo;
        }

        return back()
            ->with('status', 'Aufgabe erledigt');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Projects\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Todo $todo)
    {
        $todo->incomplete()
            ->save();

        if ($todo->isInProject())
        {
            $todo->project->cache();
        }

        if ($request->wantsJson())
        {
            return $todo;
        }

        return back()
            ->with('status', 'Aufgabe unerledigt');
    }
}
