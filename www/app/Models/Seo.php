<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App;

class Seo extends Model
{
	protected $table = 'seo';
	
	protected $guarded = [];
	
	// Название
	public function getNameAttribute(){
		$locale = App::getLocale();
		$column = 'name_' . $locale;
		return $this->{$column};
    }
	
	// Описание
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

    // Метод отдает SEO данные по URL вне зависимости от языка
    public static function getSeoData( $current_url ){

    	if( in_array(App::getLocale(), ['ua', 'en']) )
    	{
    		$current_url = trim($current_url, '/');
    		$current_url = explode('/', $current_url);
    		array_shift($current_url);
    		$current_url = '/' . implode('/', $current_url);
    	}

    	// Приводим к нужному виду url c фильтрами в категориях
		if( $urlQuery = parse_url($current_url, PHP_URL_QUERY) )
		{
			$urlQuery = urldecode($urlQuery);
			$urlQuery = str_replace(['[', ']'], ['%5B', '%5D'], $urlQuery);
			$current_url = '/' . \Request::path() . '?' . $urlQuery;
		}
		
		if( $seo_data = self::whereSlug( $current_url )->first() )
    		return $seo_data;
    	else
    		return FALSE;
    }
}
