<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryAddressToRedemptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('redemptions', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('district')->nullable();
            $table->string('postcode')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('redemptions', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('district')->nullable();
            $table->string('postcode')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
        });
    }
}
