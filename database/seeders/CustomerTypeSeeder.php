<?php

namespace Database\Seeders;

use App\Models\CustomerType;
use Illuminate\Database\Seeder;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['customer_type' => 'cold'],
            ['customer_type' => 'hot'],
            ['customer_type' => 'warm'],
        ];

        $data = CustomerType::insert($data);
    }
}
