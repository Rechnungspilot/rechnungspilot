<?php

namespace App\Http\Controllers;

use App\Templates\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateLogoController extends Controller
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
    public function store(Request $request, Template $template)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|max:51200',
        ]);

        // Altes Logo Löschen
        if ($template->logo)
        {
            Storage::disk('s3')->delete('logos/' . $template->logo);
        }

        $file = $request->file('file');
        $template->setLogo($file->extension());

        if ($path = $file->storeAs('logos', $template->logo, ['disk' => 's3']))
        {
            $template->save();
        }

        if ($request->wantsJson()) {
            return $template;
        }

        return back()
            ->with('status', 'Logo hochgeladen!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Template $template)
    {
        if (Storage::disk('s3')->delete('logos/' . $template->logo))
        {
            $template->logo = '';
            $template->save();
        }
    }
}
