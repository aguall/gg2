<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App;

class ProductFeatureGroup extends Model
{
    protected $table = 'products_features_groups';
	
	protected $guarded = [];
	
	// Название
	public function getNameAttribute(){
		$locale = App::getLocale();
		$column = 'name_' . $locale;
		return $this->{$column};
    }
}
