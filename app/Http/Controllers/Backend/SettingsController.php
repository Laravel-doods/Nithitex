<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{
    use Utils;
    public function aboutView()
    {
        $about = About::first();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Company About'), Auth::user()->id)) {
            return view('backend.settings.about-us.about-us', compact('about'));
        } else {
            return view('401');
        }
    }

    public function store(Request $request)
    {
        $aboutcount = About::get()->count();

        //About Us Image
        $image = $request->file('about_image');
        if ($image) {
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->save('upload/products/about-us/' . $name_gen);
            $save_url = 'upload/products/about-us/' . $name_gen;
        } else {
            $save_url = About::where('id', 1)->pluck('about_image')->first();
        }

        //PopUp Image
        $popup_image = $request->file('popup_image');
        if ($popup_image) {
            $popup_name_gen = hexdec(uniqid()) . '.' . $popup_image->getClientOriginalExtension();
            Image::make($popup_image)->save('upload/products/about-us/' . $popup_name_gen);
            $popup_url = 'upload/products/about-us/' . $popup_name_gen;
        } else {
            $popup_url = About::where('id', 1)->pluck('popup_image')->first();
        }

        if ($aboutcount > 0) {
            About::findOrFail(1)->update([
                'about_description' => $request->about_description,
                'about_image' => $save_url,
                'popup_image' => $popup_url
            ]);
        } else {
            About::create([
                'about_description' => $request->about_description,
                'about_image' => $save_url,
                'popup_image' => $popup_url
            ]);
        }

        $notification = array(
            'message' => 'About Us ' . (($aboutcount > 0) ? 'Updated' : 'Created') . ' Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function deletePopupImage(Request $request)
    {

        try {
            $delte_poup_image = About::findorfail($request->about_id)->update([
                'popup_image' => null
            ]);

            if ($delte_poup_image) {
                $notification = array(
                    'message' => 'Popup image deleted successfully',
                    'alert' => 'success'
                );
            }
            return response()->json([
                'delete_popup' => $notification
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
