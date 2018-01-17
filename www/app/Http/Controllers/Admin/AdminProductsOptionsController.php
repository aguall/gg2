<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ProductsOptionRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\ProductOptionVariant;
use App\Models\ProductFeature;

use App;

class AdminProductsOptionsController extends AdminBaseController
{
	// Опции параметра
    public function index( $featureId ){
		
		$title = 'Опции параметра: "' . ProductFeature::find( $featureId )->name . '"';
		
		$options = ProductOptionVariant::whereFeatureId( $featureId )->orderBy('name_' . App::getLocale())->paginate(25);
		
		return view('admin.showProductsOptions', compact(['title', 'options', 'featureId']));
    }
	
	// Удаление опций
	public function deleteProductsOptions( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
			ProductOptionVariant::destroy( $ids );

		return redirect()->back();
	}
	
	// Добавление опции (форма)
    public function getProductsOptionAdd( $featureId ){
        
		$title = 'Добавление опции';
		
		$post = new ProductOptionVariant;
		
		// Текущий параметр
		$feature = ProductFeature::find( $featureId );
		
		return view('admin.editProductsOption', compact(['title', 'post', 'feature']));
	}
	
	// Добавление опции
	public function postProductsOptionAdd( ProductsOptionRequest $request, $featureId ){
		
		$post = ProductOptionVariant::create( $request->except('_token') );

		return redirect('/master/shop/filters/features-options/' . $featureId . '/edit/' . $post->id)->with('success', 'Опция добавлена!');
	}
	
	// Редактирование опции (форма)
	public function getProductsOptionEdit( $featureId, $optionId ){
		
		$title = 'Редактирование опции';
		
		$post = ProductOptionVariant::find( $optionId );
		
		// Текущий параметр
		$feature = ProductFeature::find( $featureId );

		return view('admin.editProductsOption', compact(['title', 'post', 'feature']) );
	}
	
	// Редактирование опции
	public function postProductsOptionEdit( ProductsOptionRequest $request, $featureId, $optionId ){
		
		ProductOptionVariant::find( $optionId )->update( $request->except('_token') );

		return redirect('/master/shop/filters/features-options/' . $featureId . '/edit/' . $optionId)->with('success', 'Опция обновлена!');
	}
}
