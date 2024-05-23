<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToEorderCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eorder_comments', function (Blueprint $table) {
            $table->integer('member_id')->default(0);
            $table->integer('rate')->default(0);
            $table->string('message', 500)->nullable();
            $table->boolean('confirmed')->default(0);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eorder_comments', function (Blueprint $table) {
            $table->integer('member_id')->default(0);
            $table->integer('rate')->default(0);
            $table->string('message', 500)->nullable();
            $table->boolean('confirmed')->default(0);
            $table->softDeletes();
        });
    }
}
