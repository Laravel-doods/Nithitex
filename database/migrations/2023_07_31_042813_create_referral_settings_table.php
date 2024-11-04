<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_settings', function (Blueprint $table) {
            $table->id();
            $table->string('referral_content')->nullable();
            $table->integer('earnpoints_per_referral')->nullable();
            $table->integer('earnpoints_per_referrer')->nullable();
            $table->integer('max_redeem_per_order')->default(0);
            $table->string('referral_banner_path')->nullable();
            $table->longText('play_store_url')->nullable();
            $table->longText('app_store_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referral_settings');
    }
}
