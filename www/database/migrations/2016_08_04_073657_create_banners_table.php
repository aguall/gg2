<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('description');
            $table->enum('zone', ['top', 'left', 'right', 'bottom']);
            $table->text('include_urls');
            $table->text('exclude_urls');
            $table->text('code');
            $table->text('rotation_ids');
            $table->integer('position');
            $table->tinyInteger('visible')->default(1);
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
        Schema::drop('banners');
    }
}
