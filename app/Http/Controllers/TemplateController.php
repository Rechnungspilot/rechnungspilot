<?php

namespace App\Http\Controllers;

use App\Templates\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
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
     * Display the specified resource.
     *
     * @param  \App\Templates\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Templates\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        return view('template.edit')
            ->with('templates', Template::AVAILABLE)
            ->with('headers', Template::HEADER_OPTIONS)
            ->with('template', Template::first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Templates\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {
        $validatedData = $request->validate([
            'show_footer' => 'nullable|boolean',
            'type' => 'nullable|string',
            'header_type' => 'nullable|numeric',
        ]);

        dump($validatedData);

        $template->update($validatedData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Templates\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        //
    }
}
