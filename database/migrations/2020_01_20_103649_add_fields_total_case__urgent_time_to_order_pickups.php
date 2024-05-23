<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsTotalCaseUrgentTimeToOrderPickups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_pickups', function (Blueprint $table) {
            $table->integer('total_case')->default(0);
            $table->string('urgent_time')->nullable();
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
            $table->integer('total_case')->default(0);
            $table->string('urgent_time')->nullable();
        });
    }
}
