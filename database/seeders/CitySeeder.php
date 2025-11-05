<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\City;
use App\Models\State;
use App\Models\Country;

class CitySeeder extends Seeder
{
    public function run()
    {
        // Get India and Kerala dynamically
        $india = Country::where('name', 'India')->first();
        $kerala = State::where('name', 'Kerala')->first();
        
        if (!$india || !$kerala) {
            $this->command->error('India or Kerala not found. Please run CountrySeeder and StateSeeder first.');
            return;
        }

        // Kerala Cities (Your State!)
        $keralaCities = [
            // Major Cities
            ['name' => 'Thiruvananthapuram', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Kochi', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Kozhikode', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Kollam', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Thrissur', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Alappuzha', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Palakkad', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Kannur', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Malappuram', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Kottayam', 'stateid' => $kerala->id, 'countryid' => $india->id],
            
            // Other Kerala Cities
            ['name' => 'Pathanamthitta', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Idukki', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Ernakulam', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Wayanad', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Kasaragod', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Thalassery', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Manjeri', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Tirur', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Kanhangad', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Payyanur', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Koyilandy', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Parappanangadi', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Kalamassery', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Neyyattinkara', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Tanur', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Kayamkulam', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Thrippunithura', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Aluva', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Attingal', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Adoor', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Ponnani', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Vatakara', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Cherthala', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Paravur', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Pathanapuram', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Changanassery', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Perinthalmanna', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Mattanur', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Punalur', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Nilambur', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Cherpulassery', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Pandalam', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Manjeshwar', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Ottappalam', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Thodupuzha', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Perumbavoor', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Chalakudy', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Payyoli', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Kodungallur', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Chittur-Thathamangalam', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Muvattupuzha', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Adimali', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Ramanattukara', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Koothattukulam', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Pandikkad', 'stateid' => $kerala->id, 'countryid' => $india->id],
            ['name' => 'Wadakkancherry', 'stateid' => $kerala->id, 'countryid' => $india->id],
        ];

        // Add cities from other major Indian states
        $cities = $keralaCities;

        // Add Maharashtra cities
        $maharashtra = State::where('name', 'Maharashtra')->first();
        if ($maharashtra) {
            $maharashtraCities = [
                ['name' => 'Mumbai', 'stateid' => $maharashtra->id, 'countryid' => $india->id],
                ['name' => 'Pune', 'stateid' => $maharashtra->id, 'countryid' => $india->id],
                ['name' => 'Nagpur', 'stateid' => $maharashtra->id, 'countryid' => $india->id],
                ['name' => 'Thane', 'stateid' => $maharashtra->id, 'countryid' => $india->id],
                ['name' => 'Nashik', 'stateid' => $maharashtra->id, 'countryid' => $india->id],
                ['name' => 'Aurangabad', 'stateid' => $maharashtra->id, 'countryid' => $india->id],
                ['name' => 'Solapur', 'stateid' => $maharashtra->id, 'countryid' => $india->id],
                ['name' => 'Navi Mumbai', 'stateid' => $maharashtra->id, 'countryid' => $india->id],
                ['name' => 'Kolhapur', 'stateid' => $maharashtra->id, 'countryid' => $india->id],
            ];
            $cities = array_merge($cities, $maharashtraCities);
        }

        // Add Karnataka cities
        $karnataka = State::where('name', 'Karnataka')->first();
        if ($karnataka) {
            $karnatakaC = [
                ['name' => 'Bangalore', 'stateid' => $karnataka->id, 'countryid' => $india->id],
                ['name' => 'Mysore', 'stateid' => $karnataka->id, 'countryid' => $india->id],
                ['name' => 'Hubli', 'stateid' => $karnataka->id, 'countryid' => $india->id],
                ['name' => 'Mangalore', 'stateid' => $karnataka->id, 'countryid' => $india->id],
                ['name' => 'Belgaum', 'stateid' => $karnataka->id, 'countryid' => $india->id],
                ['name' => 'Gulbarga', 'stateid' => $karnataka->id, 'countryid' => $india->id],
            ];
            $cities = array_merge($cities, $karnatakaC);
        }

        // Add Delhi cities
        $delhi = State::where('name', 'Delhi')->first();
        if ($delhi) {
            $delhiCities = [
                ['name' => 'New Delhi', 'stateid' => $delhi->id, 'countryid' => $india->id],
                ['name' => 'Delhi', 'stateid' => $delhi->id, 'countryid' => $india->id],
                ['name' => 'Dwarka', 'stateid' => $delhi->id, 'countryid' => $india->id],
                ['name' => 'Rohini', 'stateid' => $delhi->id, 'countryid' => $india->id],
            ];
            $cities = array_merge($cities, $delhiCities);
        }

        // Add Tamil Nadu cities
        $tamilNadu = State::where('name', 'Tamil Nadu')->first();
        if ($tamilNadu) {
            $tnCities = [
                ['name' => 'Chennai', 'stateid' => $tamilNadu->id, 'countryid' => $india->id],
                ['name' => 'Coimbatore', 'stateid' => $tamilNadu->id, 'countryid' => $india->id],
                ['name' => 'Madurai', 'stateid' => $tamilNadu->id, 'countryid' => $india->id],
                ['name' => 'Tiruchirappalli', 'stateid' => $tamilNadu->id, 'countryid' => $india->id],
                ['name' => 'Salem', 'stateid' => $tamilNadu->id, 'countryid' => $india->id],
                ['name' => 'Tirunelveli', 'stateid' => $tamilNadu->id, 'countryid' => $india->id],
            ];
            $cities = array_merge($cities, $tnCities);
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($cities, 100) as $chunk) {
            DB::table('cities')->insert($chunk);
        }

        $this->command->info('Cities seeded successfully! Total: ' . count($cities));
    }
}



