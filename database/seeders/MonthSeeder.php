<?php

namespace Database\Seeders;

use App\Models\Month;
use Illuminate\Database\Seeder;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['month' => 'JAN'],
            ['month' => 'FEB'],
            ['month' => 'MAR'],
            ['month' => 'APR'],
            ['month' => 'MAY'],
            ['month' => 'JUN'],
            ['month' => 'JUL'],
            ['month' => 'AUG'],
            ['month' => 'SEP'],
            ['month' => 'OCT'],
            ['month' => 'NOV'],
            ['month' => 'DEC']
        ];

        $data = Month::insert($data); 
    }
}
