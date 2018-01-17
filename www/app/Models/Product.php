<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App;
use Input;

class Product extends Model
{
	protected $table = 'products';
	
	protected $guarded = [];
	
	// Название товара
	public function getNameAttribute(){
		$locale = App::getLocale();
		$column = 'name_' . $locale;
		return $this->{$column};
    }
	
	// Описание товара
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
	
	// Изображения товара
	public function product_images() {
		return $this->hasMany( 'App\Models\ProductImage', 'product_id' );
	}
	
	// Опции товара
	public function product_options(){
		return $this->hasMany( 'App\Models\ProductOption', 'product_id' );
    }

    // Цены товара
	public function product_prices(){
		return $this->hasMany( 'App\Models\ProductShopPrice', 'product_id' );
	}

	// Видео товара
	public function product_video(){
		return $this->hasMany( 'App\Models\ProductVideo', 'product_id' );
	}
	
	// Формируем url для сортировки товаров
	public static function addOrUpdateUrlParam($name, $value){
		$params = Input::all();
		unset($params[$name]);
		$params[$name] = $value;
		return '?' . http_build_query($params);
	}
}
