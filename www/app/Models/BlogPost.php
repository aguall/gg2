<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App;

class BlogPost extends Model
{
    protected $table = 'blog_posts';
	
	protected $guarded = [];
	
	// Название записи
	public function getNameAttribute(){
		$locale = App::getLocale();
		$column = 'name_' . $locale;
		return $this->{$column};
    }
	
	// Превью записи
	public function getAnnotationAttribute(){
		$locale = App::getLocale();
		$column = 'annotation_' . $locale;
		return $this->{$column};
    }
	
	// Тело записи
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
