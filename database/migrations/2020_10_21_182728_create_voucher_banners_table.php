<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('url', 2000)->nullable();
            $table->string('image')->nullable();
            $table->string('sort')->nullable();
            $table->boolean('public')->default(0);
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
        Schema::dropIfExists('voucher_banners');
    }
}
