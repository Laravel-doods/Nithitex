<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'country:get_country';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => 'WVo5c0hpUWVGNGhkRWNTSzZ2bmpVZkZYWmZFUHNjaENsSm16djE0UQ=='
        ])->get("https://api.countrystatecity.in/v1/countries/IN")->collect();
        $this->info('The Country Process is started!');
        $this->output->progressStart(count($response));

        // foreach ($response as $res) {
            Country::Create([
                'sort_name'         =>  $response['iso2'],
                'country_name'      =>  $response['name'],
                'phone_code'        =>  $response['phonecode'],
                'currency'          =>  $response['currency'],
                'currency_symbol'   =>  $response['currency_symbol'],
                'currency_name'     =>  $response['currency_name']
            ]);
            $this->output->progressAdvance();
        // }
        $this->info('The Country Process is completed!');
        $states = Http::withHeaders([
            'X-CSCAPI-KEY' => 'WVo5c0hpUWVGNGhkRWNTSzZ2bmpVZkZYWmZFUHNjaENsSm16djE0UQ=='
        ])->get("https://api.countrystatecity.in/v1/countries/IN/states")->collect();
        $this->info('The State Process is started!');
        $this->output->progressStart(count($states));
        foreach ($states as $state) {
            State::create([
                'state_name'  => $state['name'],
                'iso2'  => $state['iso2']
            ]); 
        }
        $this->info('The State & City Process is Completed!');

        $cities = Http::withHeaders([
            'X-CSCAPI-KEY' => 'WVo5c0hpUWVGNGhkRWNTSzZ2bmpVZkZYWmZFUHNjaENsSm16djE0UQ=='
        ])->get("https://api.countrystatecity.in/v1/countries/IN/states/TN/cities")->collect();
        $this->info('The City Process is started!');
        $this->output->progressStart(count($cities));
        // $allCities =  json_decode($cities);

        // $cityIndex = 0;
        foreach ($cities as $city) {
            City::create([
                'city_name'  => $city['name']
            ]);
        }   
        $this->output->progressAdvance();
    }
}
