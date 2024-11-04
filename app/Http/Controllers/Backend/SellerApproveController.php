<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Seller;
use App\Models\User;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerApproveController extends Controller
{
    use Utils;
    public function ResellerView()
    {
        $data = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'sellers.bank_name',
            'sellers.bank_account_name',
            'sellers.bank_account_number',
            'sellers.bank_ifsc',
            'coupons.coupon_name'
        )
            ->join('sellers', 'sellers.email', 'users.email')->leftjoin('coupons', 'coupons.id', 'users.coupon_id')
            ->where('sellers.is_verified', 1)->get();

        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('All Resellers'), Auth::user()->id)) {
            return view('admin.admin_resellers_view', compact('data'));
        } else {
            return view('401');
        }
    }

    public function customerView()
    {
        $data = User::where('userrole_id', 1)->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('All Customers'), Auth::user()->id)) {
            return view('admin.customer', compact('data'));
        } else {
            return view('401');
        }
    }

    private function isCustomer($email)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return false;
        } else {
            return true;
        }
    }

    public function ResellerRequest()
    {
        $data = Seller::where('is_verified', 0)->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller Requests'), Auth::user()->id)) {
            return view('admin.admin_resellers_request', compact('data'));
        } else {
            return view('401');
        }
    }

    public function ResellerApprove($id)
    {
        Seller::where('id', $id)->update([
            'is_verified' => 1
        ]);
        $seller = Seller::where('id', $id)->first();
        $user = User::where('email', $seller->email)->orWhere('phone', $seller->phone)->first();

        if ($user) {
            User::where('id', $user->id)->update([
                'email' => $seller->email,
                'password' => $seller->password,
                'phone' => $seller->phone,
                'userrole_id' => 2,
                'referral_code' => $seller->referral_code
            ]);

            //Delete cart products
            $cartDelete = Cart::where('user_id', $user->id);
            $cartDelete->delete();
        } else {
            $new_seller = User::create([
                'name' => $seller->name,
                'email' => $seller->email,
                'password' => $seller->password,
                'phone' => $seller->phone,
                'userrole_id' => 2
            ]);

            User::where('id', $new_seller->id)->update([
                'referral_code' => $this->generateReferralCode()
            ]);
        }
        $notification = array(
            'message' => 'Seller Approved Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function ResellerDelete($id)
    {
        $seller = Seller::find($id);
        $seller->delete();

        $notification = array(
            'message' => 'Seller Deleted Successfully',
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
    }

    public function sellerCouponUpdate(Request $request)
    {

        User::where('id', $request->hidUserId)->update([
            'coupon_id' => $request->ddlCouponType
        ]);
        $notification = array(
            'message' => 'Coupon Applied Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
