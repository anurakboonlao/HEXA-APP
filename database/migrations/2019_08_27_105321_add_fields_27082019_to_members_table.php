<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFields27082019ToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->text('content')->nullable();
            $table->string('eo_cus_id')->nullable();
            $table->integer('point')->default(0);
            $table->date('point_expire')->nullable();
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
            $table->text('content')->nullable();
            $table->string('eo_cus_id')->nullable();
            $table->integer('point')->default(0);
            $table->date('point_expire')->nullable();
        });
    }
}