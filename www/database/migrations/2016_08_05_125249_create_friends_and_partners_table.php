<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsAndPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends_and_partners', function (Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('description');
            $table->string('image');
            $table->string('url');
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
        Schema::drop('friends_and_partners');
    }
}
