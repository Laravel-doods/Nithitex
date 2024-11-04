<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserReferralHistory;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    use Utils;
    public function getReferralSettings()
    {
        try {

            $referral_settings = $this->getReferralSetting();

            $data = [
                'referral_content' => str_replace(["{{referral}}", "{{referrer}}"], [$referral_settings->earnpoints_per_referral, $referral_settings->earnpoints_per_referrer], $referral_settings->referral_content),
                'earnpoints_per_referral' => $referral_settings->earnpoints_per_referral,
                'earnpoints_per_referrer' => $referral_settings->earnpoints_per_referrer,
                'max_redeem_per_order' => $referral_settings->max_redeem_per_order,
                'referral_banner_path' => $this->getBaseUrl() . '/' . $referral_settings->referral_banner_path,
                'play_store_url' => $referral_settings->play_store_url,
                'app_store_url' => $referral_settings->app_store_url
            ];

            $response = [
                'referral_settings' => $data,
            ];

            return response($response, 200);
        } catch (\Exception $e) {
            $response = array(
                'message' => $this->errorMessage,
                'status' => false,
            );
            return response($response, 500);
        }
    }
}
