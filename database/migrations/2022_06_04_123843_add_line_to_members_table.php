<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLineToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('line_id')->nullable();
            $table->string('line_user_id')->nullable();
            $table->string('line_secret_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('line_id')->nullable();
            $table->string('line_user_id')->nullable();
            $table->string('line_secret_code')->nullable();
        });
    }
}
