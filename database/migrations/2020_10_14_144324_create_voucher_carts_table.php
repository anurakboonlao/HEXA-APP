<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_carts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->default(0);
            $table->integer('voucher_id')->default(0);
            $table->string('point')->nullable();
            $table->string('voucher_value')->nullable();
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
        Schema::dropIfExists('voucher_carts');
    }
}
