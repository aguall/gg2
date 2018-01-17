<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo', function (Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('slug');
            $table->string('name_ru');
            $table->string('name_ua');
            $table->string('name_en');
            $table->text('body_ru');
            $table->text('body_ua');
            $table->text('body_en');
            $table->string('meta_title_ru');
            $table->string('meta_title_ua');
            $table->string('meta_title_en');
            $table->string('meta_keywords_ru');
            $table->string('meta_keywords_ua');
            $table->string('meta_keywords_en');
            $table->string('meta_description_ru');
            $table->string('meta_description_ua');
            $table->string('meta_description_en');
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
        Schema::drop('seo');
    }
}
