<?php

namespace App\Http\Controllers\Todos;

use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Projects\Section;
use App\Tag;
use App\Todos\Todo;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $todos = Todo::with(['tags', 'team', 'todoable'])
                ->search($request->input('searchtext'))
                ->completed($request->input('completed'))
                ->team($request->input('team'))
                ->contact($request->input('contact_id'))
                ->withAllTags($request->input('tags'), 'aufgaben')
                ->orderBy('completed', 'ASC')
                ->orderBy('priority', 'ASC')
                ->orderBy('name', 'ASC')
                ->paginate($request->input('perPage'));

            foreach ($todos as $key => $todo) {
                $todos[$key]->append('tagsString');
                if ($todo->todoable_type == Section::class)
                {
                    $todo->todoable->load('project');
                }
            }

            return $todos;
        }

        return view('todo.task.index')
            ->with('tags', Tag::withType('aufgaben')->get())
            ->with('team', User::all());
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
            ->authUsers($request->input('auth_users'))
            ->team($request->input('team_id'))
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
        $attributes = [
            'company_id' => auth()->user()->company_id,
            'end_at' => null,
            'item_id' => $request->has('item_id') ? $request->input('item_id') : null,
            'name' => $request->has('name') ? $request->input('name') : 'Neue Aufgabe',
            'priority' => Todo::DEFAULT_PRIORITY,
            'start_at' => now(),
            'user_id' => auth()->user()->id,
        ];

        if ($request->has('todo_id'))
        {
            $attributes['todoable_type'] = Todo::class;
            $attributes['todoable_id'] = $request->input('todo_id');
        }

        $todo = Todo::create($attributes)
            ->load(['team']);

        if ($todo->isInProject())
        {
            $todo->project->cache();
        }

        return $todo;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todos\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Todo $todo)
    {
        $todo->load([
            'contacts.pivot.user',
            'subtodos',
            'tags',
            'team',
            'times',
            'todoable',
        ]);

        if ($todo->todoable_type == Section::class)
        {
            $todo->todoable->load('project');
        }

        return view('todo.task.show')
            ->with('todo', $todo)
            ->with('users', User::all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Todos\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        return view('todo.task.edit')
            ->with('todo', $todo->load(['item']))
            ->with('users', User::all());
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
            'description' => 'sometimes|nullable|string',
            'name' => 'sometimes|required|string',
            'start_at' => 'sometimes|nullable|date',
            'end_at' => 'sometimes|nullable|date|after:start_at',
            'item_id' => 'sometimes|nullable|numeric',
            'note' => 'sometimes|nullable|string',
            'user_id' => 'sometimes|nullable|integer',
            'priority' => 'sometimes|required|numeric',
        ]);

        if ($request->has('start_at'))
        {
            $validatedData['start_at'] = new Carbon($validatedData['start_at']);
            $validatedData['end_at'] = is_null($validatedData['end_at']) ? null : new Carbon($validatedData['end_at']);
            $validatedData['duration'] = is_null($validatedData['end_at']) ? 0 : $validatedData['end_at']->diffInSeconds($validatedData['start_at']);
        }

        $todo->update($validatedData);

        if ($request->wantsJson())
        {
            return $todo;
        }

        return redirect($todo->path)
            ->with('status', 'Änderungen gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todos\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Todo $todo)
    {
        $todo->subtodos()->delete();
        $todo->delete();
        if ($todo->isInProject())
        {
            $todo->project->cache();
        }

        if ($request->wantsJson())
        {
            return;
        }

        return redirect(route('todo.index'));
    }
}
