<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_content',
        'earnpoints_per_referral',
        'earnpoints_per_referrer',
        'max_redeem_per_order',
        'referral_banner_path',
        'play_store_url',
        'app_store_url'
    ];
}
