<?php

namespace App\Http\Controllers\SELLER;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
	
class IndexController extends Controller
{
	use Utils;
	private $sel_color = 0;
	private $sort_by = "";

    public function profile()
    {
        $id = Auth::user()->id;
        $seller = User::find($id);

        return view('seller.dashboard.profile_update_view',compact('seller'));
    }

    public function profileStore(Request $request)
    {
        $data = User::find(Auth::user()->id); 
		$data->name = $request->name;
		$data->email = $request->email;
		$data->phone = $request->phone;
		$data->door_no = $request->door_no;
		$data->street_address = $request->street;
		$data->city_name = $request->city;
		$data->state_name = $request->state_name;
		$data->pin_code = $request->pincode;

		if ($request->file('profile_photo_path')) {
			$file = $request->file('profile_photo_path');
			@unlink(public_path('upload/user_images/'.$data->profile_photo_path));
			$filename = date('YmdHi').$file->getClientOriginalName();
			$file->move(public_path('upload/user_images'),$filename);
			$data['profile_photo_path'] = $filename;
		}

		$data->save();

		Seller::where('email',Auth::user()->email)->update([
			'shop_name' => $request->shop_name,
			'bank_name' => $request->bank_name,
			'bank_account_name' => $request->bank_account_name,
			'bank_account_number' => $request->bank_account_number,
			'bank_ifsc' => $request->bank_ifsc
		]);

        $notification = array(
			'message' => 'Seller Profile Updated Successfully',
			'alert-type' => 'success'
		);

        return redirect()->route('seller.dashboard')->with($notification);
    }

    public function password()
    {
        $id = Auth::user()->id;
        $seller = User::find($id);

        return view('seller.dashboard.change_password',compact('seller'));
    }

    public function  updatePassword(Request $request){

		$request->validate([
			'oldpassword' => 'required',
			'password' => 'required|string|min:8|confirmed',
		]);
		$hashedPassword = Auth::user()->password;
		if (Hash::check($request->oldpassword,$hashedPassword)) {
			$user = User::find(Auth::user()->id);
			$user->password = Hash::make($request->password);
			$user->save();
			Auth::guard('seller')->logout();
			return redirect()->route('seller.logout');
		}else{
			return redirect()->back();
		}
	}

    public function orders()
    {
        $orders = Order::with('qty')->where('user_id',Auth::user()->id)->orderBy('id','DESC')->get();
    	return view('seller.dashboard.order_view',compact('orders'));
    }

    public function returnOrders()
    {
        $orders = Order::where('user_id',Auth::user()->id)->where('return_reason','!=',NULL)->orderBy('id','DESC')->get();
        return view('seller.dashboard.return_order_view',compact('orders'));
    }

    public function cancelOrder()
    {
        $orders = Order::where('user_id',Auth::user()->id)->where('status','cancelled')->orderBy('id','DESC')->get();
        return view('seller.dashboard.cancel_order_view',compact('orders'));
    }


    public function OrderTraking(Request $request){

        $track_no = $request->code;

        $track = Order::where('track_number',$track_no)->first();

        if ($track) {

        return view('seller.traking.track_order',compact('track'));

        }else{

            $notification = array(
            'message' => 'Invoice Code Is Invalid',
            'alert-type' => 'error'
        );
        return redirect()->back()->with($notification);
        }
    }
}
