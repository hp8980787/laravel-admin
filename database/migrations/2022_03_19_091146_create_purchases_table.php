<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('orders_no')->nullable()->comment('订单单号');
            $table->unsignedBigInteger('product_id')->comment('产品编号');
            $table->unsignedInteger('storehouse_id');
            $table->integer('quantity')->comment('采购数量');
            $table->decimal('price',10,2)->comment('单价');
            $table->decimal('total',10,2)->comment('总价');
            $table->text('remark')->comment('备注')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
