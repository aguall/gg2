<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App;

class ProductOptionVariant extends Model
{
    protected $table = 'products_options_variants';
	
	protected $guarded = [];
	
	// Название
	public function getNameAttribute(){
		$locale = App::getLocale();
		$column = 'name_' . $locale;
		return $this->{$column};
    }
}
