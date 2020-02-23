<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Projects\Project;
use App\Projects\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project)
    {
        if ($request->wantsJson())
        {
            return $project->sections;
        }
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
    public function store(Request $request, Project $project)
    {
        return Section::create([
            'company_id' => auth()->user()->company_id,
            'project_id' => $project->id,
            'name' => 'Neuer Abschnitt',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Projects\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Projects\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Projects\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, Section $section)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $section->update($validatedData);

        return $section;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Projects\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        $section->delete();
    }
}
