<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ShopInformation;
use App\Models\State;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;

class ShopInformationController extends Controller
{
    use Utils;
    public function index()
    {
        $shopInformation = ShopInformation::find(1);
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Shop Information'), Auth::user()->id)) {
            return view('backend.settings.shopinformation.shopinformation_view', compact('shopInformation'));
        } else {
            return view('401');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'contact_image'     =>  'max:2048'
        ]);

        $oldImage = $request->old_img;

        $id = $request->id;
        $shopInformation = ShopInformation::find($id);
        $shopInformation->announcement = $request->announcement;
        $shopInformation->address_line_1 = $request->address1;
        $shopInformation->address_line_2 = $request->address2;
        $shopInformation->pincode = $request->pincode;
        $shopInformation->mobile_number = $request->mobile;
        $shopInformation->email = $request->email;
        $shopInformation->andriod_link = $request->andriod;
        $shopInformation->ios_link = $request->ios;
        $shopInformation->facebook = $request->facebook;
        $shopInformation->twitter = $request->twitter;
        $shopInformation->instagram = $request->instagram;
        $shopInformation->youtube = $request->youtube;

        if ($request->file('contact_image')) {
            @unlink($oldImage);
            $image = $request->file('contact_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(800, 700)->save('upload/products/contact/' . $name_gen);
            $save_url = 'upload/products/contact/' . $name_gen;
            $shopInformation->contact_image = $save_url;
        }
        $shopInformation->save();

        $notification = array(
            'message' => 'Shop Information Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function stateView()
    {
        $state = State::orderby('state_name', 'ASC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('State Master'), Auth::user()->id)) {
            return view('backend.settings.state.state', compact('state'));
        } else {
            return view('401');
        }
    }

    public function stateStore(Request $request)
    {
        $request->validate([
            'state_name' => 'required',
            'short_name' => 'required',
            'shipping_charge' => 'required',
            'cod_charge' => 'required'
        ]);

        State::Create([
            'iso2' => $request->short_name,
            'state_name' => $request->state_name,
            'shipping_charge' => $request->shipping_charge,
            'cod_charge' => $request->cod_charge
        ]);

        $notification = array(
            'message' => 'State Name Added Successfully',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }

    public function stateEdit($id)
    {
        $state = State::findorfail($id);
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('State Master'), Auth::user()->id)) {
            return view('backend.settings.state.edit', compact('state'));
        } else {
            return view('401');
        }
    }

    public function stateUpdate(Request $request)
    {
        $request->validate([
            'state_name' => 'required',
            'short_name' => 'required',
            'shipping_charge' => 'required',
            'cod_charge' => 'required'
        ]);

        $id = $request->id;

        State::findorfail($id)->update([
            'iso2' => $request->short_name,
            'state_name' => $request->state_name,
            'shipping_charge' => $request->shipping_charge,
            'cod_charge' => $request->cod_charge
        ]);
        $notification = array(
            'message' => 'State Name Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect(route('state.all'))->with($notification);
    }

    public function freedelivery(Request $request)
    {
        $itemId = $request->input('id');
        $status = $request->input('status');

        $item = State::find($itemId);
        if ($item) {
            $item->is_free_delivery = $status;
            $item->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
