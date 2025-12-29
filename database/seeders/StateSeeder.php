<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\State;
use App\Models\Country;

class StateSeeder extends Seeder
{
    public function run()
    {
        // Get India's ID dynamically
        $india = Country::where('name', 'India')->first();
        
        if (!$india) {
            $this->command->error('India not found in countries table. Please run CountrySeeder first.');
            return;
        }

        $states = [
            ['name' => 'Andhra Pradesh', 'countryid' => $india->id],
            ['name' => 'Arunachal Pradesh', 'countryid' => $india->id],
            ['name' => 'Assam', 'countryid' => $india->id],
            ['name' => 'Bihar', 'countryid' => $india->id],
            ['name' => 'Chhattisgarh', 'countryid' => $india->id],
            ['name' => 'Goa', 'countryid' => $india->id],
            ['name' => 'Gujarat', 'countryid' => $india->id],
            ['name' => 'Haryana', 'countryid' => $india->id],
            ['name' => 'Himachal Pradesh', 'countryid' => $india->id],
            ['name' => 'Jharkhand', 'countryid' => $india->id],
            ['name' => 'Karnataka', 'countryid' => $india->id],
            ['name' => 'Kerala', 'countryid' => $india->id],
            ['name' => 'Madhya Pradesh', 'countryid' => $india->id],
            ['name' => 'Maharashtra', 'countryid' => $india->id],
            ['name' => 'Manipur', 'countryid' => $india->id],
            ['name' => 'Meghalaya', 'countryid' => $india->id],
            ['name' => 'Mizoram', 'countryid' => $india->id],
            ['name' => 'Nagaland', 'countryid' => $india->id],
            ['name' => 'Odisha', 'countryid' => $india->id],
            ['name' => 'Punjab', 'countryid' => $india->id],
            ['name' => 'Rajasthan', 'countryid' => $india->id],
            ['name' => 'Sikkim', 'countryid' => $india->id],
            ['name' => 'Tamil Nadu', 'countryid' => $india->id],
            ['name' => 'Telangana', 'countryid' => $india->id],
            ['name' => 'Tripura', 'countryid' => $india->id],
            ['name' => 'Uttar Pradesh', 'countryid' => $india->id],
            ['name' => 'Uttarakhand', 'countryid' => $india->id],
            ['name' => 'West Bengal', 'countryid' => $india->id],
            ['name' => 'Andaman and Nicobar Islands', 'countryid' => $india->id],
            ['name' => 'Chandigarh', 'countryid' => $india->id],
            ['name' => 'Dadra and Nagar Haveli and Daman and Diu', 'countryid' => $india->id],
            ['name' => 'Delhi', 'countryid' => $india->id],
            ['name' => 'Jammu and Kashmir', 'countryid' => $india->id],
            ['name' => 'Ladakh', 'countryid' => $india->id],
            ['name' => 'Lakshadweep', 'countryid' => $india->id],
            ['name' => 'Puducherry', 'countryid' => $india->id],
        ];

        // Add USA states
        $usa = Country::where('name', 'United States')->first();
        if ($usa) {
            $usaStates = [
                ['name' => 'Alabama', 'countryid' => $usa->id],
                ['name' => 'Alaska', 'countryid' => $usa->id],
                ['name' => 'Arizona', 'countryid' => $usa->id],
                ['name' => 'Arkansas', 'countryid' => $usa->id],
                ['name' => 'California', 'countryid' => $usa->id],
                ['name' => 'Colorado', 'countryid' => $usa->id],
                ['name' => 'Connecticut', 'countryid' => $usa->id],
                ['name' => 'Delaware', 'countryid' => $usa->id],
                ['name' => 'Florida', 'countryid' => $usa->id],
                ['name' => 'Georgia', 'countryid' => $usa->id],
                ['name' => 'Hawaii', 'countryid' => $usa->id],
                ['name' => 'Idaho', 'countryid' => $usa->id],
                ['name' => 'Illinois', 'countryid' => $usa->id],
                ['name' => 'Indiana', 'countryid' => $usa->id],
                ['name' => 'Iowa', 'countryid' => $usa->id],
                ['name' => 'Kansas', 'countryid' => $usa->id],
                ['name' => 'Kentucky', 'countryid' => $usa->id],
                ['name' => 'Louisiana', 'countryid' => $usa->id],
                ['name' => 'Maine', 'countryid' => $usa->id],
                ['name' => 'Maryland', 'countryid' => $usa->id],
                ['name' => 'Massachusetts', 'countryid' => $usa->id],
                ['name' => 'Michigan', 'countryid' => $usa->id],
                ['name' => 'Minnesota', 'countryid' => $usa->id],
                ['name' => 'Mississippi', 'countryid' => $usa->id],
                ['name' => 'Missouri', 'countryid' => $usa->id],
                ['name' => 'Montana', 'countryid' => $usa->id],
                ['name' => 'Nebraska', 'countryid' => $usa->id],
                ['name' => 'Nevada', 'countryid' => $usa->id],
                ['name' => 'New Hampshire', 'countryid' => $usa->id],
                ['name' => 'New Jersey', 'countryid' => $usa->id],
                ['name' => 'New Mexico', 'countryid' => $usa->id],
                ['name' => 'New York', 'countryid' => $usa->id],
                ['name' => 'North Carolina', 'countryid' => $usa->id],
                ['name' => 'North Dakota', 'countryid' => $usa->id],
                ['name' => 'Ohio', 'countryid' => $usa->id],
                ['name' => 'Oklahoma', 'countryid' => $usa->id],
                ['name' => 'Oregon', 'countryid' => $usa->id],
                ['name' => 'Pennsylvania', 'countryid' => $usa->id],
                ['name' => 'Rhode Island', 'countryid' => $usa->id],
                ['name' => 'South Carolina', 'countryid' => $usa->id],
                ['name' => 'South Dakota', 'countryid' => $usa->id],
                ['name' => 'Tennessee', 'countryid' => $usa->id],
                ['name' => 'Texas', 'countryid' => $usa->id],
                ['name' => 'Utah', 'countryid' => $usa->id],
                ['name' => 'Vermont', 'countryid' => $usa->id],
                ['name' => 'Virginia', 'countryid' => $usa->id],
                ['name' => 'Washington', 'countryid' => $usa->id],
                ['name' => 'West Virginia', 'countryid' => $usa->id],
                ['name' => 'Wisconsin', 'countryid' => $usa->id],
                ['name' => 'Wyoming', 'countryid' => $usa->id],
            ];
            $states = array_merge($states, $usaStates);
        }

        // Insert in chunks
        foreach (array_chunk($states, 50) as $chunk) {
            DB::table('states')->insert($chunk);
        }

        $this->command->info('States seeded successfully!');
    }
}
