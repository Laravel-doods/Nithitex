<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserReferralPayments;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasPermissions;

class ReferralHistoryController extends Controller
{
    use Utils;
    use HasPermissions;
    public function referralHistory(Request $request)
    {
        try {

            if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Referral History'), Auth::user()->id)) {
                return view('backend.referralhistory.referral_history');
            } else {
                return view('401');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRefferalCustomerdata(Request $request)
    {
        try {
            $customerName = DB::table('users')
                ->join('user_referral_histories', 'user_referral_histories.referred_by', 'users.id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.referral_code',
                    DB::raw('count(user_referral_histories.user_id) as user_id_count'),
                    'user_referral_histories.referred_by',
                    'users.referral_points',
                    'users.referral_paid',
                )
                ->groupBy('users.id');


            $customerName = $customerName->get();

            return datatables()->of($customerName)
                ->addColumn('action', function ($row) {
                    $html = "";
                    $html .= '<a href="referralcode/' . $row->referred_by . '" class="btn btn-xs btn-primary">View</a>';
                    // if ($row->referral_points != $row->referral_paid) {
                    //     $html .= '<button type="button" class="btn btn-xs btn-success ml-2" onclick="updateReferralPaymentPopup(' . $row->id . ');">Add Payment</button>';
                    // }
                    return $html;
                })->toJson();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function referralCode($id)
    {
        try {
            $username = DB::table('user_referral_histories')
                ->join('users', 'users.id', 'user_referral_histories.user_id')
                ->where('user_referral_histories.referred_by', $id)
                ->select('users.name', DB::raw('DATE_FORMAT(referred_on, "%d-%m-%Y") as referred_on'))
                ->get();
            $referral_name = User::where('id', $id)->first();
            return view('backend.referralhistory.referral_code', compact('username', 'referral_name'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getReferralUserInfo($id)
    {
        $referral_user_info = User::where('id', $id)->first();

        return response()->json([
            'referral_user_info' => $referral_user_info
        ]);
    }
    public function updateReferralPaymet(Request $request)
    {
        try {
            UserReferralPayments::create([
                'user_id' => $request->hdUserId,
                'amount_paid' => $request->paidAmount,
                'transaction_id' => $request->txtTransactionId,
            ]);

            User::where('id', $request->hdUserId)->update([
                'referral_paid' => DB::raw('referral_paid + ' . $request->paidAmount)
            ]);

            $notification = array(
                'message' => 'Updated successfully',
                'alert-type' => 'success'
            );
        } catch (\Exception $e) {
            $notification = array(
                'message' => 'Cant update payment!.',
                'alert' => 'success'
            );
            return $e->getMessage();
        }
        return redirect()->back()->with($notification);
    }


    public function getReferralPaymentHistory(Request $request)
    {
        try {

            if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Payment History'), Auth::user()->id)) {
                return view('backend.referralhistory.referral_payment_history');
            } else {
                return view('401');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getReferralPaymentHistoryData(Request $request)
    {
        try {
            $referral_payment_history = DB::table('user_referral_payments')
                ->join('users', 'users.id', 'user_referral_payments.user_id')
                ->select('users.*', 'user_referral_payments.amount_paid', 'user_referral_payments.transaction_id', 'user_referral_payments.created_at');

            $referral_payment_history = $referral_payment_history->get();

            return datatables()->of($referral_payment_history)->toJson();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
