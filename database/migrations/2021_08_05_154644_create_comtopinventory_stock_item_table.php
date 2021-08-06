<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComtopinventoryStockItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comtopinventory_stock_item', function (Blueprint $table) {
            $table->bigIncrements('item_id');
            $table->string('sku', 64)->unique();
            $table->unsignedMediumInteger('cl_productid')->nullable();
            $table->decimal('source_qty');
            $table->decimal('qty');
            $table->timestamps();
            $table->index('cl_productid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comtopinventory_stock_item');
    }
}
