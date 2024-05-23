<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsSocialLoginToMemberLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_logins', function (Blueprint $table) {
            $table->string('social_type')->nullable(); // google , facebook
            $table->string('social_token')->nullable();
            $table->string('social_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_logins', function (Blueprint $table) {
            $table->string('social_type')->nullable(); // google , facebook
            $table->string('social_token')->nullable();
            $table->string('social_id')->nullable();
        });
    }
}
