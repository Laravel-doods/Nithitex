<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Utils;
use App\Models\ReferralSettings;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use phpseclib3\Crypt\EC\Formats\Keys\Common;
use Spatie\Permission\Traits\HasPermissions;

class RefferalSettingsController extends Controller
{
    use Utils;
    use HasPermissions;
    public function referralSettings()
    {
        try {

            $refferalsettings = ReferralSettings::first();
            if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Referral Settings'), Auth::user()->id)) {
                return view('backend.settings.refferalsettings.refferalsettings', compact('refferalsettings'));
            } else {
                return view('401');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function addReferralPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'txtReferralContent' => 'required',
            'txtPointsforEachreferral' => 'required',
            'txtPointsforEachreferrer' => 'required',
            'txtPointsRedeemPerOrder' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            if ($request->id == null) {

                if ($request->hasFile('fileReferral_banner')) {
                    $file = $request->file('fileReferral_banner');
                    if ($file != null) {
                        $extension = $file->getClientOriginalExtension();
                        $fileName = $this->generateRandom(16) . '.' . $extension;
                    } else {
                        throw new Exception('Banner Image Is Null');
                    }
                }
                ReferralSettings::create([
                    'referral_content' => $request->txtReferralContent,
                    'earnpoints_per_referral' => $request->txtPointsforEachreferral,
                    'earnpoints_per_referrer' => $request->txtPointsforEachreferrer,
                    'max_redeem_per_order' => $request->txtPointsRedeemPerOrder,
                    'referral_banner_path' => $this->fileUpload($file, 'upload/settings/referral', $fileName),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                $notification = array(
                    'message' => 'RefferalSettings Created Successfully!',
                    'alert-type' => 'success'
                );
            } else {

                $oldImage = $request->hdfileReferralbanner;
                $folderPath = "";
                if ($request->hasFile('fileReferral_banner')) {
                    $file = $request->file('fileReferral_banner');
                    if ($file != null) {
                        @unlink($oldImage);
                        $extension = $file->getClientOriginalExtension();
                        $fileName = $this->generateRandom(16) . '.' . $extension;
                        $folderPath = dirname($oldImage);
                    } else {
                        throw new Exception('Banner Image Is Null');
                    }
                }

                ReferralSettings::findOrFail($request->id)->update([
                    'referral_content' => $request->txtReferralContent,
                    'earnpoints_per_referral' => $request->txtPointsforEachreferral,
                    'earnpoints_per_referrer' => $request->txtPointsforEachreferrer,
                    'max_redeem_per_order' => $request->txtPointsRedeemPerOrder,
                    'referral_banner_path' => $request->hasFile('fileReferral_banner') ? $this->fileUpload($file, $folderPath, $fileName) : $oldImage,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                $notification = array(
                    'message' => 'RefferalSettings Updated Successfully!',
                    'alert-type' => 'success'
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $notification = array(
                'message' => 'RefferalSettings Not Updated!',
                'alert-type' => 'error'
            );
            return $e->getMessage();
        }
        return redirect()->back()->with($notification);
    }
}
