<?php

namespace Database\Seeders;

use App\Models\LedgerType;
use Illuminate\Database\Seeder;

class LedgerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['ledger_type' => 'consumer'],
            ['ledger_type' => 'generator'],
            ['ledger_type' => 'leads'],
        ];

        $data = LedgerType::insert($data);
    }
}
