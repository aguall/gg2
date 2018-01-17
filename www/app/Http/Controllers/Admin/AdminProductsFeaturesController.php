<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ProductsFeaturesGroupRequest;
use App\Http\Requests\ProductsFeatureRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\ProductFeatureGroup;
use App\Models\ProductFeature;

class AdminProductsFeaturesController extends AdminBaseController
{
	private $groups = [];
	
	public function __construct(){
		parent::__construct();

		// Группы
		if( $groupsAll = ProductFeatureGroup::all() )
			foreach( $groupsAll as $group )
				$this->groups[$group->id] = $group->name;
	}
	
	// Группы параметров
	public function index(){
		
		$title  = 'Группы параметров';
		
		$groups = ProductFeatureGroup::paginate(25);
		
		return view('admin.showProductsFeaturesGroups', compact(['title', 'groups']));
    }
	
	// Удаление групп
	public function deleteProductsFeaturesGroups( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
			ProductFeatureGroup::destroy( $ids );

		return redirect()->back();
	}
	
	// Добавление группы (форма)
    public function getProductsFeaturesGroupAdd(){
        
		$title = 'Добавление группы';
		
		$post  = new ProductFeatureGroup;
		
		return view('admin.editProductsFeaturesGroup', compact(['title', 'post']));
	}
	
	// Добавление группы
	public function postProductsFeaturesGroupAdd( ProductsFeaturesGroupRequest $request ){
		
		$post = ProductFeatureGroup::create( $request->except('_token') );

		return redirect('/master/shop/filters/features-groups/edit/' . $post->id)->with('success', 'Группа добавлена!');
	}
	
	// Редактирование группы (форма)
	public function getProductsFeaturesGroupEdit( $id ){
		
		$title = 'Редактирование группы';
		
		$post  = ProductFeatureGroup::find( $id );

		return view('admin.editProductsFeaturesGroup', compact(['title', 'post']) );
	}
	
	// Редактирование группы
	public function postProductsFeaturesGroupEdit( ProductsFeaturesGroupRequest $request, $id ){
		
		ProductFeatureGroup::find( $id )->update( $request->except('_token') );

		return redirect('/master/shop/filters/features-groups/edit/' . $id)->with('success', 'Группа обновлена!');
	}
	
	// Параметры
	public function getProductsFeatures( Request $request ){
		
		$title = 'Параметры';
		
		
		// Параметры
		$featuresIds = array_keys($this->groups);
		
		if( $request->get('group') )
			$ids = !empty($request->get('group')) ? [ (int)$request->get('group') ] : $featuresIds;
		else
			$ids = $featuresIds;
		
		$features = ProductFeature::select('products_features.*', 'products_features_groups.name_ru AS features_group_name')
								  ->join('products_features_groups', 'products_features_groups.id', '=', 'products_features.feature_group_id')
								  ->whereIn('products_features.feature_group_id', $ids)
								  ->orderBy('products_features.name_ru')
								  ->paginate(25);
		
		// Группы
		$groups = array_add($this->groups, '0', 'Все группы параметров');
		$groups = array_reverse($groups, true);
		
		return view('admin.showProductsFeatures', compact(['title', 'groups', 'features']));
    }
	
	// Удаление параметров
	public function deleteProductsFeatures( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
			ProductFeature::destroy( $ids );

		return redirect()->back();
	}
	
	// Добавление параметра (форма)
    public function getProductsFeatureAdd(){
        
		$title = 'Добавление параметра';
		
		$post  = new ProductFeature;
		$post->feature_group_id = 0;
		
		// Группы
		$groups = array_add($this->groups, '0', 'Выбирите группу параметров...');
		$groups = array_reverse($groups, true);
		
		return view('admin.editProductsFeature', compact(['title', 'post', 'groups']));
	}
	
	// Добавление параметра
	public function postProductsFeatureAdd( ProductsFeatureRequest $request ){
		
		$post = ProductFeature::create( $request->except('_token') );

		return redirect('/master/shop/filters/features/edit/' . $post->id)->with('success', 'Параметр добавлен!');
	}
	
	// Редактирование параметра (форма)
	public function getProductsFeatureEdit( $id ){
		
		$title = 'Редактирование параметра';
		
		$post  = ProductFeature::find( $id );
		
		// Группы
		$groups = array_add($this->groups, '0', 'Выбирите группу параметров...');
		$groups = array_reverse($groups, true);

		return view('admin.editProductsFeature', compact(['title', 'post', 'groups']) );
	}
	
	// Редактирование параметра
	public function postProductsFeatureEdit( ProductsFeatureRequest $request, $id ){
		
		ProductFeature::find( $id )->update( $request->except('_token') );

		return redirect('/master/shop/filters/features/edit/' . $id)->with('success', 'Параметр обновлён!');
	}
}
