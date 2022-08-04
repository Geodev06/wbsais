<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('store_name');
            $table->string('email')->unique();
            $table->string('contact')->nullable(true);
            $table->string('address')->nullable(true);
            $table->string('lat')->nullable(true);
            $table->string('long')->nullable(true);
            $table->integer('role')->nullable(true)->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('inventory_id')->nullable(true);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
