<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsShopsPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_shops_prices', function (Blueprint $table){
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('product_id')->unsigned();
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
			$table->integer('shop_id')->unsigned();
			$table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade')->onUpdate('cascade');
			$table->string('shop_product_name');
			$table->string('shop_product_link');
			$table->decimal('shop_product_price', 10, 2);
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
		Schema::drop('products_shops_prices');
    }
}
