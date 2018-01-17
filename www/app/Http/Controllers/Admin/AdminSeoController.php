<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SeoPostRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Seo;

class AdminSeoController extends AdminBaseController
{
    // Получаем записи
    public function index(){
       
        $title = 'SEO модуль';
        
        $posts = Seo::paginate(25);
        
        return view('admin.showSeoPosts', compact(['title', 'posts']));
    }

    // Удаляем записи
    public function deletePosts( Request $request ){
        
        $ids = $request->get('check');
        
        if( $request->get('action') == 'delete' )
            Seo::destroy( $ids );

        return redirect()->back();
    }

    // Создаем запись (форма)
    public function getAdd(){
        
        $post  = new SEO;
        
        $title = 'Создание записи';
        
        return view('admin.editSeoPost', compact(['title', 'post']));
    }
    
    // Создаем запись
    public function postAdd( SeoPostRequest $request ){
        
        $post = Seo::create( $request->except('_token') );

        return redirect('/master/seo/edit/' . $post->id);
    }

    // Редактируем запись (форма)
    public function editPost( $id ){
        
        $post  = Seo::find( $id );
        
        $title = 'Редактирование записи';

        return view('admin.editSeoPost', compact(['title', 'post']) );
    }
    
    // Редактируем запись
    public function postEdit( SeoPostRequest $request, $id ){
        
        Seo::find($id)->update( $request->except('_token') );

        return redirect('/master/seo/edit/' . $id)->with('success', 'Запись успешно обновлена!');
    }
}
