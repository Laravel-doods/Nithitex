<?php

namespace Database\Seeders;

use App\Models\Bill;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'bill_name' => 'GRN',
                'bill_prefix' => 'GR',
                'bill_length' => '6',
                'bill_start' => '1',
                'bill_example' => 'GR000001'
            ],
            [
                'bill_name' => 'Purchase Order',
                'bill_prefix' => 'PO',
                'bill_length' => '3',
                'bill_start' => '1',
                'bill_example' => 'PO001'
            ],
            [
                'bill_name' => 'Purchase Invoice',
                'bill_prefix' => 'PI',
                'bill_length' => '6',
                'bill_start' => '1',
                'bill_example' => 'PR000001'
            ]
        ];

        Bill::insert($data);
    }
}
