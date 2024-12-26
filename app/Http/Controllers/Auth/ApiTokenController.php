<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        return view('auth.api_token.index')
            ->with('tokens', $user->tokens);
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
        $attributes = $request->validate([
            'name' => 'required|string',
        ]);

        $token = $request->user()->createToken($attributes['name']);

        if ($request->wantsJson()) {
            return [
                'token' => $token->plainTextToken
            ];
        }

        return back()
            ->with('status', 'Token erstellt')
            ->with('token', $token);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Laravel\Sanctum\PersonalAccessToken  $token
     * @return \Illuminate\Http\Response
     */
    public function destroy(PersonalAccessToken $token)
    {
        $user = auth()->user();
        if ($token->tokenable_id == $user->id) {
            $token->delete();
        }

        return back()
            ->with('status', 'Token gelöscht');
    }
}
