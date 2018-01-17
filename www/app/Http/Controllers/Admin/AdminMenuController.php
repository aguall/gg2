<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\MenuRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Menu;

use Response;

class AdminMenuController extends AdminBaseController
{
    // Выводим меню
    public function index(){
       
		$title = 'Меню';
		
		$menu = Menu::orderBy('position')->get();
		
		return view('admin.showMenu', compact(['title', 'menu']));
    }
	
	// Удаляем меню
	public function deleteMenu( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
			Menu::destroy( $ids );

		return redirect()->back();
	}

	// Создаем пункт меню (форма)
	public function getAdd(){
		
		$post = new Menu;
		$post->visible = 1;
		
		$title = 'Добавление пункта меню';
		
		return view('admin.editMenu', compact(['title', 'post']));
	}
	
	// Создаем пункт меню
	public function postAdd( MenuRequest $request ){
		
		$menuItem = Menu::create( $request->except('_token') );

		return redirect('/master/menu/edit/' . $menuItem->id);
	}
	
	// Редактируем пункт меню (форма)
	public function editPost( $id ){
		
		$post = Menu::find( $id );
		
		$title = 'Редактирование пункта меню';

		return view('admin.editMenu', compact(['title', 'post']) );
	}
	
	// Редактируем пункт меню
	public function postEdit( MenuRequest $request, $id ){
		
		Menu::find($id)->update($request->except('_token'));

		return redirect('/master/menu/edit/' . $id)->with('success', 'Пункт меню обновлён!');
	}
	
	// Показать/скрыть пункт меню
	public function visibleMenuItem( Request $request ){
		
		if( $request->ajax() )
		{
			$itemMenuVisible = Menu::find( (int)$request->id )->visible;
			
			$visible = empty($itemMenuVisible) ? 1 : 0;
			
			Menu::whereId( (int)$request->id )->update(['visible' => $visible]);
			
			return Response::json(['message' => 'completed']);
		}
	}
	
	// Сортировка пунктов меню
	public function sortableMenu( Request $request ){
		
		if( $request->ajax() )
		{
			(int)$i = 1;
			foreach($request->get('position') as $item){
				Menu::whereId( $item )->update(['position' => $i]);
				$i++;
			}
		}
	}
}
