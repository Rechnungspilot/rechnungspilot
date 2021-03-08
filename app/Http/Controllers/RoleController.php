<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return view('role.index')->with('roles', $roles);
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
        $role = Role::create([
            'name' => 'Neue Zugriffsrolle',
        ]);

        return redirect('/zugriffsrolle/' . $role->getRouteKey());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::with([
            'permissions'
        ])->findOrFail($id);

        $permissions = Permission::orderBy('group', 'ASC')->orderBy('action', 'ASC')->get();
        $grouped = $permissions->groupBy('group');

        return view('role.show')
            ->with('role', $role)
            ->with('permissions', $grouped);
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
        $role = Role::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255',
            'permissions' => 'required',
        ]);

        $role->syncPermissions($validatedData['permissions']);
        unset($validatedData['permissions']);
        $role->update($validatedData);

        return back()->with('status', 'Zugriffsrolle gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
