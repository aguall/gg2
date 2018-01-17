<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use File;
use Slug;
use Image;
use Validator;

class ProductImage extends Model
{
    protected $table = 'products_images';
	
	protected $guarded = [];
	
	// Удаление изображений
	public static function deleteProductImages( $ids ){
		
		$images = self::whereIn('id', $ids)->get();

		foreach( $images as $image )
		{
			File::delete( public_path() . '/uploads/shop/products/' . $image->image );
			File::delete( public_path() . '/uploads/shop/products/thumbs/' . $image->image );
		}
		
		self::destroy( $ids );
	}
	
	// Загрузка изображений
	public static function uploadProductImages( $images, $product_id ){
		
		$messages = [];
			
		foreach( $images as $key => $image )
		{
			$validator = Validator::make( ['images' => $image], ['images' => 'required|image']);
			
			if( $validator->passes() )
			{
				
				$imageName = time() . '_' . Slug::make(basename($image->getClientOriginalName(), '.' . $image->getClientOriginalExtension())) . '.' . $image->getClientOriginalExtension();

				Image::make( $image )->save( 'uploads/shop/products/' . $imageName );
				Image::make( $image )->widen(250)->save( 'uploads/shop/products/thumbs/' . $imageName );
				
				self::create([ 'product_id' => $product_id, 'image' => $imageName ]);
				
				$messages['success'] = 'Изображения добавлены!';
			}
			else
				$messages['errors']  = $validator->messages();
		}
		
		return $messages;
	}
	
	// Главное изображение товара
	public static function activeProductImage( $id, $product_id ){
		
		$image  = self::find( $id )->active;
			
		$active = empty($image) ? 1 : 0;
			
		self::find( $id )->update(['active' => $active]);
		
		// Главным может быть только одно изображение
		if( self::whereProductId( $product_id )->count() > 1 )
			foreach( self::whereProductId( $product_id )->get() as $image )
				if( $image->id != $id )
					self::whereId( $image->id )->update([ 'active' => 0 ]);
	}
	
	// Определяем главное изображение для товара (фронтенд)
	public static function getActiveImage( $product_images ){
		$image = '';
		foreach( $product_images as $key => $value )
			if( $value->active || $key == 0 )
				$image = $value->image;
		return $image;
	}
}
