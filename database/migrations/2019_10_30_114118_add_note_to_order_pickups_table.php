<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoteToOrderPickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_pickups', function (Blueprint $table) {
            $table->string('note', 1000)->nullable();
            $table->string('status', 100)->default('success');
            $table->string('clinic_note', 1000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_pickups', function (Blueprint $table) {
            $table->string('note', 1000)->nullable();
            $table->string('status', 100)->default('success');
            $table->string('clinic_note', 1000)->nullable();
        });
    }
}
