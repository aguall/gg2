<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
	protected $table = 'products_options';
		
	protected $guarded = [];
	
	public $timestamps = false;
	
	// Возвращаем выбранные опции товара
	public static function getOptions( $id ){

		$options = [];

		foreach( self::whereProductId( $id )->get() as $option )
		{
			if( isset($options[$option->feature_id]) )
				array_push($options[$option->feature_id], $option->option_variant_id );
			else
				$options[$option->feature_id] = [$option->option_variant_id];
		}
		
		return $options;
	}
	
	// Сохраняем опции товара
	public static function saveOptions( $id, $feature ){

		self::whereProductId( $id )->delete();

		if( isset($feature) )
		{
			foreach( $feature as $feature_id => $option_variant_id )
			{
				foreach($option_variant_id as $item_id)
				{
					if( $item_id != '' )
						$insert[] = [ 'product_id' => $id, 'feature_id' => $feature_id, 'option_variant_id' => $item_id ];
				}
			}

			if( isset( $insert ) )
				self::insert( $insert );
		}
	}
}
