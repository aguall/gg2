<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ProductsCategoryRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\ProductCategory;

use File;

class AdminProductsCategoriesController extends AdminBaseController
{
	// Список категорий
    public function index(){
		
		$title  = 'Категории';
		
		$select = ['id', 'parent_id', 'lft', 'rgt', 'depth', 'name_ru', 'slug'];
		$categories = json_encode(array_values( ProductCategory::select( $select )->get()->toHierarchy()->toArray() ));
		
		$categoriesCount = ProductCategory::count();
		
		return view('admin.showProductsCategories', compact(['title', 'categories', 'categoriesCount']));
    }
	
	// Action для категорий
	public function actionCategories( Request $request ){

		$ids = $request->get('check');

		if( $request->get('action') == 'delete' )
		{
			// Если есть миниатюра - удалим ее
			foreach(ProductCategory::whereIn('id', $ids)->get() as $category)
				if( $category->image )
					File::delete( public_path() . '/uploads/shop/categories/' . $category->image );
			
			// Удаляем категории
			ProductCategory::destroy( $ids );
		}
		elseif( $request->get('action') == 'rebuild' ) 
		{
			// Обновляем позиции
			ProductCategory::rebuildTree( $request->get('data') );
			return 'rebuilded';
		}
		
		return redirect()->back();
	}
	
	// Добавление категории (форма)
	public function getProductsCategoryAdd(){
		
		$tree = ProductCategory::getNestedList('name_ru', NULL, '&nbsp;&nbsp;&nbsp;');
		$tree = ['0' => '-'] + $tree;
		$parent_id = 0;
		
		$post  = new ProductCategory;
		$post->image = '';
		
		$title = 'Добавление категории';
		
		return view('admin.editProductsCategory', compact(['title', 'post', 'tree', 'parent_id']));
	}
	
	// Добавление категории
	public function postProductsCategoryAdd( ProductsCategoryRequest $request ){
		
		if( $request->get('parent_id') == 0 )
			$input = $request->except(['_token', 'parent_id', 'image']);
		else
			$input = $request->except(['_token', 'image']); 

		$category = ProductCategory::create( $input );

		if( $image = $request->file('image') )
			ProductCategory::thumbCategory( $image, $category->id );

		return redirect( 'master/shop/categories/edit/' . $category->id )->with('success', 'Категория добавлена');
	}
	
	// Редактирование категории (форма)
	public function getProductsCategoryEdit( $id ){

		$post = ProductCategory::find( $id );
		$tree = ProductCategory::getNestedList('name_ru', NULL, '&nbsp;&nbsp;&nbsp;');

		// Уберем себя из списка
		unset( $tree[$id] );
		$tree = ['0' => '-'] + $tree;

		$parent_id = $post->parent_id;

		$title = 'Редактирование категории';
		
		return view('admin.editProductsCategory', compact(['title', 'post', 'tree', 'parent_id']));
	}
	
	// Редактирование категории
	public function postProductsCategoryEdit( ProductsCategoryRequest $request, $id ){

		if( $request->get('parent_id') == 0 )
			$input = $request->except(['_token', 'parent_id', 'image']);
		else
			$input = $request->except(['_token', 'image']);

		ProductCategory::find( $id )->update( $input );

        if( $image = $request->file('image') )
			ProductCategory::thumbCategory( $image, $id );

		return redirect()->back()->with('success', 'Категория обновлена');
	}
}
