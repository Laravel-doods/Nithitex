<?php

namespace App\Http\Controllers;

use App\Models\Loyalty;
use App\Traits\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Traits\HasPermissions;

class LoyaltyController extends Controller
{
    use Utils;
    use HasPermissions;
    public function loyaltyManagement()
    {
        try {

            $loyalty = Loyalty::first();
            if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Loyalty Management'), Auth::user()->id)) {
                return view('backend.settings.loyalty.loyalty', compact('loyalty'));
            } else {
                return view('401');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function addLoyaltyManagement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loyaltyRate' => 'required',
            'type' => 'required',
            'earnValue' => 'required',
            'pointsRedeemPerOrder' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            if ($request->id == null) {
                Loyalty::create([
                    'loyalty_rate' => $request->loyaltyRate,
                    'type' => $request->type,
                    'earn_per_order' => $request->earnValue,
                    'max_redeem_per_order' => $request->pointsRedeemPerOrder,
                    'created_at' => Carbon::now()
                ]);

                $notification = array(
                    'message' => 'LoyaltySettings Created Successfully!',
                    'alert-type' => 'success'
                );
            } else {
                Loyalty::findOrFail($request->id)->update([
                    'loyalty_rate' => $request->loyaltyRate,
                    'type' => $request->type,
                    'earn_per_order' => $request->earnValue,
                    'max_redeem_per_order' => $request->pointsRedeemPerOrder,
                    'updated_at' => Carbon::now()
                ]);

                $notification = array(
                    'message' => 'LoyaltySettings Updated Successfully!',
                    'alert-type' => 'success'
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $notification = array(
                'message' => 'LoyaltySettings Not Updated!',
                'alert-type' => 'error'
            );
            return $e->getMessage();
        }
        return redirect()->back()->with($notification);
    }
}
