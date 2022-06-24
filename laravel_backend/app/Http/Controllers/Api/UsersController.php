<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Security\ChangePasswordRequest;
use App\Http\Requests\Security\SaveUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: Use yajrabox instead
        return User::all()->makeHidden(['email_verified_at', 'updated_at']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveUserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();
        return ["message" => "Saved", "user" => $user];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // We don't seem to need this for now
        throw new Exception("Not yet implemented");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SaveUserRequest $request, $id)
    {
        $user = User::find($id);

        if ($user->role == 'Administrator' && $request->role != $user->role && User::where('role', '=', 'Administrator')->count() <= 1) {
            return response('At least one administrator must remain', 403);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // $user->password = Hash::make($request->password);
        $user->save();
        return ["message" => "Saved", "user" => $user];
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        if (!Hash::check($request->current_password, $user->password)) {
            // TODO: add throttling here later
            return response('Incorrect password', 403);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        return ["message" => "Saved", "user" => $user];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (User::count() <= 1) {
            return response('At least one user must remain', 403);
        }
        $user = User::destroy($id);
        return ["message" => "Deleted", "user" => $user];
    }
}
