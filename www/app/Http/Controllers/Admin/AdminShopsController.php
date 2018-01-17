<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ShopRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Shop;

use File;
use Slug;
use Image;
use Response;

class AdminShopsController extends AdminBaseController
{
	// Список магазинов
    public function index(){
        
		$title = 'Список магазинов';
		
		$shops = Shop::paginate(25);
		
		return view('admin.showShops', compact(['title', 'shops']));
    }
	
	// Удаление магазинов
	public function deleteShops( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
		{
			// Если есть логотипы у выбранных магазинов
			foreach(Shop::whereIn('id', $ids)->get() as $shop)
				if( $shop->logo )
					File::delete( public_path() . '/uploads/shops/' . $shop->logo );
			
			Shop::destroy( $ids );
		}

		return redirect()->back();
	}
	
	// Добавляем магазин (форма)
	public function getShopAdd(){
		
		$post  = new Shop;
		$post->visible = 1;
		$post->rating = 0;
		
		$title = 'Добавление магазина';
		
		return view('admin.editShop', compact(['title', 'post']));
	}
	
	// Добавляем магазин
	public function postShopAdd( ShopRequest $request ){
		
		$post = Shop::create( $request->except(['_token', 'logo']) );
		
		// Если загрузка изображения
		if( $file = $request->file('logo') )
		{
			$fileName = time() . '_' . Slug::make(basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension())) . '.' . $file->getClientOriginalExtension();
			
			Image::make( $file )->widen(95)->save( 'uploads/shops/' . $fileName );
			
			Shop::find( $post->id )->update(['logo' => $fileName]);
		}

		return redirect('/master/shops/edit/' . $post->id)->with('success', 'Магазин добавлен!');
	}
	
	// Редактируем магазин (форма)
	public function getShopEdit( $id ){
		
		$post  = Shop::find( $id );
		
		$title = 'Редактирование магазина';

		return view('admin.editShop', compact(['title', 'post']));
	}
	
	// Редактируем магазин
	public function postShopEdit( ShopRequest $request, $id ){
		
		Shop::find( $id )->update($request->except(['_token', 'logo']));
		
		// Если загрузка изображения
		if( $file = $request->file('logo') )
		{
			// Удаляем старое изображение, если есть
			if( $oldImage = Shop::find( $id )->logo )
				File::delete( public_path() . '/uploads/shops/' . $oldImage );
			
			$fileName = time() . '_' . Slug::make(basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension())) . '.' . $file->getClientOriginalExtension();
			
			Image::make( $file )->widen(95)->save( 'uploads/shops/' . $fileName );
			
			Shop::find( $id )->update(['logo' => $fileName]);
		}

		return redirect('/master/shops/edit/' . $id)->with('success', 'Данные магазина обновлены!');
	}
	
	// Показать/скрыть магазин
	public function visibleShop( Request $request ){
		
		if( $request->ajax() )
		{
			$shopVisible = Shop::find( (int)$request->id )->visible;
			
			$visible = empty($shopVisible) ? 1 : 0;
			
			Shop::find( (int)$request->id )->update(['visible' => $visible]);
			
			return Response::json(['message' => 'completed']);
		}
	}
}
