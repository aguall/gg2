<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Product;
use App\Models\ShopXML;
use App\Models\ProductCategory;
use App\Models\ProductFeatureGroup;
use App\Models\ProductFeature;

class AdminShopController extends AdminBaseController
{
    // Опции магазина
	public function index(){
		
		$title = 'Магазин';
		
		$categoriesCount = ProductCategory::count();
		
		$productsCount = Product::count();
		
		return view('admin.showShopOptions', compact(['title', 'categoriesCount', 'productsCount']));
    }
	
	// Опции параметров фильтрации
 	public function getFilters(){
		
		$title = 'Параметры фильтрации';
		
		$featuresGroupsCount = ProductFeatureGroup::count();
		
		$featuresCount = ProductFeature::count();
		
		return view('admin.showShopFiltersOptions', compact(['title', 'featuresGroupsCount', 'featuresCount']));
    }
	
	// Опции XML парсера
	public function getParser(){
		
		$title = 'XML парсер';
		
		$shopsXMLCount = ShopXML::count();
		
		return view('admin.showShopXMLOptions', compact(['title', 'shopsXMLCount']));
	}
}
