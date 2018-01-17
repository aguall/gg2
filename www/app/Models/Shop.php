<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App;

class Shop extends Model
{
    protected $table = 'shops';
	
	protected $guarded = [];
	
	// Название магазина
	public function getNameAttribute(){
		$locale = App::getLocale();
		$column = 'name_' . $locale;
		return $this->{$column};
    }
	
	// Описание магазина
	public function getBodyAttribute(){
		$locale = App::getLocale();
		$column = 'body_' . $locale;
		return $this->{$column};
    }
	
	// Мета заглавие
	public function getMetaTitleAttribute(){
		$locale = App::getLocale();
		$column = 'meta_title_' . $locale;
		return $this->{$column};
    }
	
	// Мета ключевые слова
	public function getMetaKeywordsAttribute(){
		$locale = App::getLocale();
		$column = 'meta_keywords_' . $locale;
		return $this->{$column};
    }
	
	// Мета описание
	public function getMetaDescriptionAttribute(){
		$locale = App::getLocale();
		$column = 'meta_description_' . $locale;
		return $this->{$column};
    }
}
