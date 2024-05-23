<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTransactionFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transaction_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_transaction_id')->unsigned()->index();
            $table->text('file_path');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('payment_transaction_id')
                  ->references('id')
                  ->on('payment_transactions')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transaction_files');
    }
}
