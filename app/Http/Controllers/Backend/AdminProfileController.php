<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function AdminProfile()
	{
		$id = Auth::user()->id;
		$adminData = Admin::find($id);
        return view('admin.admin_profile_view',compact('adminData'));
    }

    public function AdminProfileEdit()
	{
        $id = Auth::user()->id;
		$editData = Admin::find($id);
        return view('admin.admin_profile_edit',compact('editData'));
    }
	
    public function AdminProfileStore(Request $request)
	{
		$id = Auth::user()->id;
		$data = Admin::find($id);
		$data->name = $request->name;
		$data->email = $request->email;


		if ($request->file('profile_photo_path')) {
			$file = $request->file('profile_photo_path');
			@unlink(public_path('upload/admin_images/'.$data->profile_photo_path));
			$filename = date('YmdHi').$file->getClientOriginalName();
			$file->move(public_path('upload/admin_images'),$filename);
			$data['profile_photo_path'] = $filename;
		}
		$data->save();

        $notification = array(
			'message' => 'Admin Profile Updated Successfully',
			'alert-type' => 'success'
		);

        return redirect()->route('admin.dashboard')->with($notification);
    }

    public function AdminChangePassword()
	{
		return view('admin.admin_change_password');
	}

    public function AdminUpdateChangePassword(Request $request)
	{
		$request->validate([
			'oldpassword' => 'required',
			'password' => 'required|min:6|confirmed',
		]);

		$hashedPassword = Auth::user()->password;
		if (Hash::check($request->oldpassword,$hashedPassword)) {
			$admin = Admin::find(Auth::user()->id);
			$admin->password = Hash::make($request->password);
			$admin->save();
			Auth::guard('admin')->logout();
			$notification = array(
				'message' => 'Admin Password Changed Successfully',
				'alert-type' => 'success'
			);
			return redirect()->route('admin.form')->with($notification);
		}else{
			$notification = array(
				'message' => 'Password Mismatch',
				'alert-type' => 'error'
			);
			return redirect()->route('admin.change.password')->with($notification);
		}
	}

	public function getLiveUsersCount()
    {
        $activeThreshold = Carbon::now()->subMinutes(5); 

        $liveUsersCount = DB::table('live_users')
            ->where('last_activity', '>=', $activeThreshold)
            ->count();

        return response()->json(['count' => $liveUsersCount]);
    }
}
