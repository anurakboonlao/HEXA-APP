<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->default(0);
            $table->integer('approved_by')->default(0);
            $table->string('type')->default('bank_tranfer');
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('tranfer_date')->nullable();
            $table->string('tranfer_time')->nullable();
            $table->string('file')->nullable();
            $table->string('amount_total')->nullable();
            $table->text('return_content')->nullable();
            $table->text('bill_ids')->nullable();
            $table->text('bill_content')->nullable();
            $table->boolean('confirmed')->default(0);
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
        Schema::dropIfExists('payments');
    }
}
