<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    use Utils;
    public function coupon()
    {
        $coupon = Coupon::get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Generate Coupon'), Auth::user()->id)) {
            return view('backend.settings.coupon.coupon', compact('coupon'));
        } else {
            return view('401');
        }
    }

    public function couponStore(Request $request)
    {
        $request->validate([
            'coupon_name' => 'required|unique:coupons',
            'coupon_code' => 'required|unique:coupons',
            'color_code' => 'required',
            'discount_percentage' => 'required|numeric',
            'start_date'  => 'required|after:yesterday',
            'end_date'    => 'required|after:start_date'
        ]);
        Coupon::create([
            'coupon_name' => $request->coupon_name,
            'coupon_code' => $request->coupon_code,
            'color_code' => $request->color_code,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_common' => ($request->common == 1 ? True : false),
            'discount_percentage' => $request->discount_percentage
        ]);

        $notification = array(
            'message' => 'Coupon Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function couponEdit($id)
    {
        $coupon = Coupon::findOrFail($id);
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Generate Coupon'), Auth::user()->id)) {
            return view('backend.settings.coupon.coupon_edit', compact('coupon'));
        } else {
            return view('401');
        }
    }

    public function couponUpdate(Request $request)
    {
        $coupon_id = $request->id;
        $request->validate([
            'coupon_name' => 'required|unique:coupons,coupon_name,' . $coupon_id,
            'coupon_code' => 'required|unique:coupons,coupon_code,' . $coupon_id,
            'color_code' => 'required',
            'discount_percentage' => 'required|numeric',
            'start_date'  => 'required',
            'end_date'    => 'required'
        ]);

        Coupon::findOrFail($coupon_id)->update([
            'coupon_name' => $request->coupon_name,
            'coupon_code' => $request->coupon_code,
            'color_code' => $request->color_code,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_common' => ($request->common == 1 ? True : false),
            'discount_percentage' => $request->discount_percentage
        ]);
        $notification = array(
            'message' => 'Coupon Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('coupon.all')->with($notification);
    }

    public function couponUpdateStatus(Request $request)
    {
        Coupon::where('id', $request->id)->update([
            'is_active' => ($request->active == 1 ? True : false),
            // 'is_common' => ($request->common == 1 ? True : false)
        ]);

        $notification = array(
            'message' => 'Coupon Status Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('coupon.all')->with($notification);
    }

    public function couponUpdateCommonStatus(Request $request)
    {
        Coupon::where('id', $request->id)->update([
            'is_common' => ($request->common == 1 ? True : false)
        ]);

        $notification = array(
            'message' => 'Common Coupon Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('coupon.all')->with($notification);
    }
}
