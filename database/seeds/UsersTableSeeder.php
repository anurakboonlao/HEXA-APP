<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'developer',
                'email' => 'mytepper@gmail.com',
                'role' => 1,
                'password' => bcrypt('mytepper@gmail.com'),
            ],
            [
                'name' => 'admin',
                'email' => 'admin@hexa.com',
                'role' => 1,
                'password' => bcrypt('admin@hexa.com'),
            ]
        ]);
    }
}
