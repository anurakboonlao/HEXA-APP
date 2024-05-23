<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->default(0);
            $table->string('image')->nullable();
            $table->string('name')->nullable();
            $table->string('details', 2000)->nullable();
            $table->integer('price')->default(0);
            $table->boolean('recommended')->default(0);
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
        Schema::dropIfExists('products');
    }
}
