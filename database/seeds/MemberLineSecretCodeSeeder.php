<?php

use Illuminate\Database\Seeder;

class MemberLineSecretCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = DB::table('members')->get();
        foreach($members as $member){
            DB::table('members')
                ->where('id', $member->id)
                ->update([
                    'line_secret_code' => uniqid(),
            ]);
        }
        
    }
}
