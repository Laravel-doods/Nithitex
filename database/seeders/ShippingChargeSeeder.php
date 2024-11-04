<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\ShippingCharge;
use Illuminate\Database\Seeder;

class ShippingChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=([
            ['shipping_payment_method' => 'cash'],
            ['shipping_payment_method' => 'online'],
        ]);
        ShippingCharge::insert($data);
    }
}
