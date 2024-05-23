<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsTransferConfirmedToPaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->boolean('is_transfer_confirmed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->boolean('is_transfer_confirmed')->default(0);
        });
    }
}
