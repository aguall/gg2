<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App;

class ProductFeature extends Model
{
	protected $table = 'products_features';
	
	protected $guarded = [];
	
	// Название
	public function getNameAttribute(){
		$locale = App::getLocale();
		$column = 'name_' . $locale;
		return $this->{$column};
    }
	
	// Значения парамаетров
	public function variants(){
		return $this->hasMany( 'App\Models\ProductOptionVariant' , 'feature_id' );
	}
	
	// Связь названия парметров и значений параметров
	public function options() {
		return $this->hasMany( 'App\Models\ProductOption' , 'feature_id' );
	}
}
