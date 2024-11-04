<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seller = [
            'name' => 'Seller',
            'email' => 'seller@gmail.com',
            'password' => Hash::make('Adhoc@2022')
        ];
        Seller::insert($seller);
    }
}
