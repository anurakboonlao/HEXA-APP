<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatLongToOrderPickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_pickups', function (Blueprint $table) {
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
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
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
        });
    }
}
