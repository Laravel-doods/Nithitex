<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Staff;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    use Utils;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Admin::get();
        
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('All Staffs'), Auth::user()->id)) {
            return view('admin.users.list', compact('users'));
        }
        else {
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
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('All Staffs'), Auth::user()->id)) {
            return view('admin.users.create');
        }
        else {
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
            'name' => 'required|unique:admins',
            'email' => 'required|unique:admins',
            'password' => 'required|min:8'
        ]);
        $user = new Admin();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); 
        $user->save();

        // return redirect()->back()->with('message','User Created Successfully');
        $notification = array(
			'message' => 'Staff Created Successfully',
			'alert-type' => 'success'
		);
        return redirect('user')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	$user = Admin::findOrFail($id);
        return view('admin.users.edit', compact('user'));
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
            'name' => 'required|unique:admins,name,'.$id,
            'email' => 'required|unique:admins,email,'.$id,
            'password' => 'required|min:8'
        ]);
        $user = Admin::findorfail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $notification = array(
			'message' => 'Staff Updated Successfully',
			'alert-type' => 'success'
		);

		return redirect('user')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Admin::findorfail($id);
        $user->delete();
        $notification = array(
			'message' => 'Staff Deleted Successfully',
			'alert-type' => 'error'
		);

		return redirect('user')->with($notification);
    }
}
