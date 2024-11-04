<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Traits\Utils;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignRoleToUserController extends Controller
{
    use Utils;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Admin::with('roles')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Assign Roles'), Auth::user()->id)) {
            return view('admin.assign_users_roles.list', compact('users'));
        } else {
            return view('401');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = Admin::get();
        $roles = Role::get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Assign Roles'), Auth::user()->id)) {
            return view('admin.assign_users_roles.create', compact('users', 'roles'));
        } else {
            return view('401');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Admin::find($request->user_id);
        $user->syncRoles($request->role_names);

        return redirect()->back()->with('message', " Role Assigned to $user->email Successfully!");
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $selecteduser = Admin::with('roles')->find($id);
        $users = Admin::get();
        $roles = Role::get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Assign Roles'), Auth::user()->id)) {
            return view('admin.assign_users_roles.edit', compact('selecteduser', 'users', 'roles'));
        } else {
            return view('401');
        }
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
        $user = Admin::find($id);
        // $role = Role::find($request->role_id); 
        $user->syncRoles($request->role_id);

        return redirect('/assign_role_users')->with('message', " Role Updated to $user->email Successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Admin::find($id);
        $user->delete();

        return back()->with('message', " Role Deleted Successfully!");
    }
}
