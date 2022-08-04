<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stashes', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('product_name');
            $table->string('category');
            $table->integer('qty');
            $table->decimal('price', 18, 2);
            $table->integer('user_id');
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
        Schema::dropIfExists('stashes');
    }
}
