<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hexa_price_list', 2000)->nullable();
            $table->string('hexa_line', 2000)->nullable();
            $table->string('hexa_email', 2000)->nullable();
            $table->string('hexa_facebook', 2000)->nullable();
            $table->string('shipping_cover_page', 2000)->nullable();
            $table->text('terms')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
