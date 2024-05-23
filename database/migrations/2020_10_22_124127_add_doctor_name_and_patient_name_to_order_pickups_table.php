<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDoctorNameAndPatientNameToOrderPickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_pickups', function (Blueprint $table) {
            $table->string('doctor_name')->nullable();
            $table->string('patient_name')->nullable();
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
            $table->string('doctor_name')->nullable();
            $table->string('patient_name')->nullable();
        });
    }
}
