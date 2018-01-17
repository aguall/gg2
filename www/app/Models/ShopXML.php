<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App;

class ShopXML extends Model
{
	protected $table = 'shops_xml';
	
	protected $guarded = [];
	
	// Формируем массив слов для поиска
	public static function getWords( $name ){
		
		$string = mb_convert_case($name, MB_CASE_LOWER, "UTF-8");
		$string = explode(' ', $string);

		$words  = [];
		
		foreach($string as $item)
			if( !empty($item) )
				$words[] = trim(addslashes($item));
		
		return $words;
	}
	
	// Строим поисковой запрос на основе массива слов 
	public static function getSearchString( $mas ){
		
		$query = '';
		
		for( $i = 0; $i <= count($mas); $i++ )
			if( !empty($mas[$i]) )
				$query .= ' +' . $mas[$i];
		
		return trim($query);
	}
}
