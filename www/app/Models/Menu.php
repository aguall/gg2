<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App;

class Menu extends Model
{
    protected $table = 'menu';
	
	protected $guarded = [];
	
	// Название меню
	public function getNameAttribute(){
		$locale = App::getLocale();
		$column = 'name_' . $locale;
		return $this->{$column};
    }
}
