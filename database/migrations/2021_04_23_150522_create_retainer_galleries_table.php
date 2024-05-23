<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetainerGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retainer_galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('image');
            $table->string('url', 2000)->nullable();
            $table->integer('sort')->default(0);
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
        Schema::dropIfExists('retainer_galleries');
    }
}
