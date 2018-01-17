<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PageRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Page;

class AdminPagesController extends AdminBaseController
{
	// Получаем страницы
	public function index(){
		
		$title = 'Страницы';
		
		$pages = Page::paginate(25);
		
		return view('admin.showPages', compact(['title', 'pages']));
    }
	
	// Удаляем страницы
	public function deletePages( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
			Page::destroy( $ids );

		return redirect()->back();
	}

	// Создаем страницу (форма)
	public function getAdd(){
		
		$post = new Page;
		
		$title = 'Создание страницы';
		
		return view('admin.editPage', compact(['title', 'post']));
	}
	
	// Создаем страницу
	public function postAdd( PageRequest $request ){
		
		$page = Page::create( $request->except('_token') );

		return redirect('/master/pages/edit/' . $page->id);
	}
	
	// Редактируем страницу (форма)
	public function editPost( $id ){
		
		$post = Page::find( $id );
		
		$title = 'Редактирование страницы';

		return view('admin.editPage', compact(['title', 'post']) );
	}
	
	// Редактируем страницу
	public function postEdit( PageRequest $request, $id ){
		
		Page::find($id)->update($request->except('_token'));

		return redirect('/master/pages/edit/' . $id)->with('success', 'Страница успешно обновлена!');
	}
}
