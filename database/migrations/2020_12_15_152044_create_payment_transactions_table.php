<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id');
            $table->text('bill_content')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('ref1')->nullable();
            $table->string('ref2')->nullable();
            $table->string('ref3')->nullable();
            $table->string('total')->nullable();
            $table->string('note')->nullable();
            $table->text('payment_return')->nullable();
            $table->boolean('is_success')->default(0);
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
        Schema::dropIfExists('payment_transactions');
    }
}
