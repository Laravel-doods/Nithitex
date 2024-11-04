<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    use Utils;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    public function index()
    {
        $roles = Role::with('permissions')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Roles'), Auth::user()->id)) {
            return view('admin.roles.list', compact('roles'));
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
        $permissionGroups = PermissionGroup::with('permissions')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Roles'), Auth::user()->id)) {
            return view('admin.roles.create', compact('permissionGroups'));
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
        $request->validate([
            'name' => 'required|unique:roles'
        ]);
        $role = new Role;
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permission_ids);
        return redirect('/role')->with('message', 'Role Created With Selected Permissions Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::with('permissions')->find($id);
        $permissionGroups = PermissionGroup::with('permissions')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Roles'), Auth::user()->id)) {
            return view('admin.roles.edit', compact('role', 'permissionGroups'));
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
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id
        ]);
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permission_ids);
        return redirect('/role')->with('message', 'Role Update With Selected Permissions Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = DB::table('model_has_roles')->where('role_id', $id)->get()->count();
        if ($product == 0) {
            $role = Role::find($id);
            $role->delete();

            $notification = array(
                'message' => 'Role deleted successfully',
                'alert-type' => 'success'
            );
        } else {
            $notification = array(
                'message' => 'Delete Failed!. This role is reference with another instance.',
                'alert-type' => 'error'
            );
        }
        return redirect()->back()->with($notification);
    }
}
