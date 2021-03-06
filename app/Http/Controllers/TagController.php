<?php

namespace App\Http\Controllers;

use App\Support\Type;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type)
    {
        $class = Type::class($type);

        if ($request->wantsJson()) {
            return Tag::where('type', $class)
                       ->search($request->input('searchtext'))
                       ->get();
        }

        return view('tag.index')
            ->with('index_path_attributes', $class == \App\Receipts\Abos\Abo::class ? ['settings_type' => \App\Receipts\Abos\Abo::TYPE] : [])
            ->with('type', $type)
            ->with('class', $class);
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
    public function store(Request $request, string $type)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        $validatedData['company_id'] = auth()->user()->company_id;
        $validatedData['type'] = Type::class($type);

        return Tag::create($validatedData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        $tag->update($validatedData);

        return $tag;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Tag $tag)
    {
        $tag->delete();
    }
}
