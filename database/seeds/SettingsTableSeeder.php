<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'id' => 1,
                'hexa_price_list' => "",
                'hexa_line' => "",
                'hexa_email' => "",
                'hexa_facebook' => "",
                'shipping_cover_page' => "",
                'terms' => ""
            ]
        ]);
    }
}
