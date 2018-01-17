<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Baum\Node;

use App;
use Slug;
use Image;
use File;

class ProductCategory extends Node
{
	protected $table = 'products_categories';
	
	protected $guarded = [];
	
	protected static $res = [];
	
	// Название категории
	public function getNameAttribute(){
		$locale = App::getLocale();
		$column = 'name_' . $locale;
		return $this->{$column};
    }
	
	// Тело категории
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
	
	// Создаем/обновляем миниатюру
	public static function thumbCategory( $file, $id ){
		
		// Удаляем старое изображение, если есть
		$oldImage = self::find( $id )->image;
		if( !empty($oldImage) )
			File::delete( public_path() . '/uploads/shop/categories/' . $oldImage );
		
		$fileName = time() . '_' . Slug::make(basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension())) . '.' . $file->getClientOriginalExtension();
		
		if( Image::make( $file )->width() > 175 )
			Image::make( $file )->widen(175)->save( 'uploads/shop/categories/' . $fileName );
		else
			Image::make( $file )->save( 'uploads/shop/categories/' . $fileName );
		
		self::whereId( $id )->update(['image' => $fileName]);
	}
	
	// Родители категории (для хлебных крошек)
	public static function getParentCategories( $id ){
		
		$category = self::find( $id );

		if( $category->parent_id != 0 )
		{
			array_push( self::$res, $category );
			self::getParentCategories( $category->parent_id );
		}
		
		if( $category->parent_id == 0 )
			array_push( self::$res, $category );

		return array_reverse( self::$res );
	}
}
