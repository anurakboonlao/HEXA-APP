<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsClinicToRedemptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('redemptions', function (Blueprint $table) {
            $table->string('clinic', 200)->nullable();
            $table->string('address', 1000)->nullable();
            $table->string('province', 50)->nullable();
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
            $table->string('clinic', 200)->nullable();
            $table->string('address', 1000)->nullable();
            $table->string('province', 50)->nullable();
        });
    }
}
