<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_logins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id');
            $table->text('token');
            $table->date('expire_date');
            $table->string('device_type');
            $table->string('device_token');
            $table->boolean('status');
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
        Schema::dropIfExists('member_logins');
    }
}
