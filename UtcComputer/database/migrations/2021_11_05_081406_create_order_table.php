<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->integer('shopping_cart_id')->nullable()->unique();
            $table->integer('total')->default(0);

            $table->string('email')->nullable();
            $table->string('fullname')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('province_id')->default("");
            $table->string('district_id')->default("");
            $table->string('ward_id')->default("");
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
        Schema::dropIfExists('order');
    }
}