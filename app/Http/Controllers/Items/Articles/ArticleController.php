<?php

namespace App\Http\Controllers\Items\Articles;

use App\Http\Controllers\Controller;
use App\Item;
use App\Models\Items\Article;
use App\Models\Items\Articles\rticle;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Item $item)
    {
        return $item->articles()
            ->with([
                'item',
            ])
            ->createdAtDate($request->input('created_at_date'))
            ->latest()
            ->paginate(25);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Item $item)
    {
        $item->load([
            'unit',
        ]);

        return view('item.article.create')
            ->with('model', $item);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Item $item)
    {
        $attributes = $request->validate([
            'unit_value_formatted' => 'required|formated_number',
            'unit_price_formatted' => 'required|formated_number',
        ]);

        return $item->articles()->create([
            'company_id' => $item->company_id,
        ] + $attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Models\Items\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Models\Items\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\Items\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item, Article $article)
    {
        $attributes = $request->validate([
            'unit_value_formatted' => 'required|formated_number',
            'unit_price_formatted' => 'required|formated_number',
        ]);

        $article->update($attributes);

        return $article->load([
            'item',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Models\Items\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Item $item, Article $article)
    {
        if ($isDeletable = $article->isDeletable()) {
            $article->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $article->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $article->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($article->index_path)
            ->with('status', $status);
    }
}
