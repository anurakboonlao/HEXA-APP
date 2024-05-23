<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_pickups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id');
            $table->integer('branch_id')->default(0);
            $table->integer('clinic_id')->default(0);
            $table->integer('sale_id')->default(0);
            $table->string('address');
            $table->string('time', 200);
            $table->boolean('checked')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_pickups');
    }
}
