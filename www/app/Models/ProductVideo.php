<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App;

class ProductVideo extends Model
{
	protected $table = 'products_video';
	
	protected $guarded = [];
	
	// Название
	public function getNameAttribute(){
		$locale = App::getLocale();
		$column = 'name_' . $locale;
		return $this->{$column};
    }
	
	// Описание
	public function getDescriptionAttribute(){
		$locale = App::getLocale();
		$column = 'description_' . $locale;
		return $this->{$column};
    }
	
	// Код видео с YouTube
	public static function videoCode( $video ){
		parse_str( parse_url( $video, PHP_URL_QUERY ), $code );
		return $code['v'];
	}
}
