<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Projects\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
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
            return Project::where('project_group_id', $request->input('project_group_id'))
                ->get();
        }

        return view('project.index');
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
            'project_group_id' => 'required',
        ]);

        $validatedData['company_id'] = auth()->user()->company_id;
        $validatedData['creator_id'] = auth()->user()->id;
        $validatedData['name'] = 'Neues Projekt';
        $validatedData['incompleted_todos_count'] = 0;
        $validatedData['completed_todos_count'] = 0;
        $validatedData['todos_count'] = 0;

        return Project::create($validatedData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todos\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('project.show')
            ->with('project', $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Todos\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todos\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'string',
        ]);

        $project->update($validatedData);

        return $project;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todos\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        foreach ($project->sections as $key => $section)
        {
            foreach($section->todos as $todo)
            {
                $todo->subtodos()->delete();
                $todo->delete();
            }
            $section->delete();
        }

        $project->delete();
    }
}
