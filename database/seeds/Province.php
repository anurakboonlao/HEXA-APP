<?php

use Illuminate\Database\Seeder;

class Province extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $getProvinces = json_decode(file_get_contents(database_path('json/provinces.json')), true);

        $province = [];
       

        foreach ($getProvinces as $key => $value) {
            $province[] = [
                'id' => $value['PROVINCE_ID'],
                'name' => $value['PROVINCE_NAME']
            ];
        }

        DB::table('provinces')->insert($province);
    }
}
