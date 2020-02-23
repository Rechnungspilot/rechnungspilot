<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class SetPasswordController extends Controller
{
    public function create(Request $request, User $user)
    {
        return view('auth.passwords.set')
            ->with('user', $user);
    }

    public function store(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'password' => 'required|confirmed|min:8'
        ]);

        $user->setPassword($validatedData['password']);

        return redirect('/login')
            ->with('status', 'Passwort gespeichert');
    }
}