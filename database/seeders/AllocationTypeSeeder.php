<?php

namespace Database\Seeders;

use App\Models\AllocationType;
use Illuminate\Database\Seeder;

class AllocationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['allocation_type' => 'Total Import'],
            ['allocation_type' => 'Total Export'],
            ['allocation_type' => 'Net Export'],
            ['allocation_type' => 'Allocated Units'],
            ['allocation_type' => 'Units Taken'],
            ['allocation_type' => 'Total Units Taken'],
            ['allocation_type' => 'Pre Banking'],            
            ['allocation_type' => 'Banking'],
            ['allocation_type' => 'Total Banking']
            
        ];

        $data = AllocationType::insert($data);
    }
}
