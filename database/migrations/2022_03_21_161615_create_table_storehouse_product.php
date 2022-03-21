<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStorehouseProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storehouse_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('storehouse_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('amount');
            $table->index('storehouse_id');
            $table->index('product_id');
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
        Schema::dropIfExists('storehouse_product');
    }
}
