<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_id')->nullable()->unique();
            $table->string('customer_verify_key')->nullable()->unique();
            $table->string('username')->nullable()->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('province')->nullable();
            $table->string('type', 10); // doctor, clinic, staff
            $table->string('role', 5); // 1,2,3,4 [member, accounting, sale, marketing]
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
        Schema::dropIfExists('members');
    }
}
