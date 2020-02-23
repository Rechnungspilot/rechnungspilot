<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Role;
use App\Tag;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return User::with(['tags'])
                ->search($request->input('searchtext'))
                ->withAllTags($request->input('tags'), 'team')
                ->paginate(15);
        }

        return view('user.index')
            ->with('tags', Tag::withType('team')->get());
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
        $team = User::create([
            'company_id' => auth()->user()->company_id,
            'lastname' => 'Mitarbeiter',
            'firstname' => 'Neuer',
        ]);

        if ($request->wantsJson()) {
            return $team;
        }

        return redirect('/team/' . $team->getRouteKey());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = User::with(['roles', 'company', 'tags'])->findOrFail($id);

        return view('user.show')
            ->with('user', $team)
            ->with('roles', Role::all());
    }

    /**
     * Show the form for editing the pecified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $team)
    {
        $validatedData = $request->validate([
            'address' => 'nullable|string|max:255',
            'bankname' => 'nullable|string|max:255',
            'bic' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $team->id,
            'firstname' => 'nullable|string|max:255',
            'hex_color_code' => 'required|regex:/#([a-f0-9]{3}){1,2}\b/i',
            'iban' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'mobilenumber' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:255',
            'phonenumber' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:255',
            'roles' => 'array',
        ]);

        $team->syncRoles($validatedData['roles'] ?? []);
        $team->update($validatedData);

        return back()->with('status', 'Mitarbeiter gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $team)
    {
        $team->delete();

        if ($request->wantsJson()) {
            return;
        }

        return redirect(route('team.index'));
    }
}
