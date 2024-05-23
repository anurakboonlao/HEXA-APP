<?php

use Illuminate\Database\Seeder;

class Country extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $getCountry = json_decode(file_get_contents(database_path('json/countries.json')), true);

        $country = [];
       

        foreach ($getCountry as $key => $value) {
            $country[] = [
                'id' => $value['COUNTRY_ID'],
                'name' => $value['COUNTRY_NAME']
            ];
        }

        DB::table('countries')->insert($country);

    }
}
