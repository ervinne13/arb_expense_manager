<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Security\StoreRoleRequest;
use App\Models\Security\Role;
use Exception;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: Use yajrabox instead
        return Role::all()->makeHidden(['updated_at']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();
        return ["message" => "Saved", "role" => $role];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        // We don't seem to need this for now
        throw new Exception("Not yet implemented");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        $role = Role::find($name);
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();
        return ["message" => "Updated", "role" => $role];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        $role = Role::destroy($name);
        return ["message" => "Deleted", "role" => $role];
    }
}
