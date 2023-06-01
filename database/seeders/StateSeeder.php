<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\City;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            [
                'name' => 'Gujarat',
                'cities' => ['Vadodara', 'Ahmedabad', 'Surat', 'Rajkot', 'Gandhinagar']
            ],
            [
                'name' => 'Maharashtra',
                'cities' => ['Mumbai', 'Pune', 'Nagpur', 'Nashik', 'Aurangabad']
            ],
            [
                'name' => 'Rajasthan',
                'cities' => ['Jaipur', 'Jodhpur', 'Udaipur', 'Ajmer', 'Kota']
            ]
        ];

        foreach ($states as $stateData) {
            $state = State::create(['name' => $stateData['name']]);

            if (isset($stateData['cities'])) {
                foreach ($stateData['cities'] as $cityName) {
                    City::create([
                        'name' => $cityName,
                        'state_id' => $state->id
                    ]);
                }
            }
        }
    }
}
