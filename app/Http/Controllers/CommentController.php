<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Receipts\Receipt;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type = '', $model = null)
    {
        if ($model)
        {
            return $model->comments()
                ->with('user')
                ->orderBy('created_at', 'DESC')
                ->paginate(5);
        }

        return Comment::with(['user', 'commentable'])
            ->latest()
            ->paginate(10);
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
    public function store(Request $request, string $type, $model)
    {
        $validatedData = $request->validate([
            'text' => 'required|string',
        ]);

        $comment = new Comment($validatedData);

        $model->comments()->save($comment);
        if (array_key_exists('comments_count', $model->getAttributes()))
        {
            $model->increment('comments_count');
        }

        if ($request->wantsJson()) {
            return $comment->load('user');
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
