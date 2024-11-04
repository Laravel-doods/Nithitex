<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            // $table->id();
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone');
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('door_no')->nullable();
            $table->string('street_address')->nullable();
            $table->string('city_name')->nullable();
            $table->string('state_name')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->string('referral_code')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        Artisan::call('db:seed', [
            '--class' => 'SellerSeeder'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sellers');
    }
}
