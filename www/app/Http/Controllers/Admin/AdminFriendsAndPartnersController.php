<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\FriendAndPartnerRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\FriendsAndPartners;

use Slug;
use File;
use Image;
use Response;

class AdminFriendsAndPartnersController extends AdminBaseController
{
    // Список друзей и партнеров
    public function index(){
        
        $title = 'Друзья и партнеры';
        
        $posts = FriendsAndPartners::orderBy('position')->get();
        
        return view('admin.showPartners', compact(['title', 'posts']));
    }

    // Удаляем партнеров
    public function deletePartners( Request $request ){
        
        $ids = $request->get('check');
        
        if( $request->get('action') == 'delete' )
        {
            // Если есть логотипы партнеров - удалим
            if( $posts = FriendsAndPartners::whereIn('id', $ids)->get() )
            {
                foreach($posts as $post)
                    if( $post->image )
                        File::delete( public_path() . '/uploads/partners/' . $post->image );
            }

            FriendsAndPartners::destroy( $ids );
        }

        return redirect()->back();
    }

    // Добавляем данные о партнере (форма)
    public function getPartnerAdd(){
        
        $post = new FriendsAndPartners;
        $post->visible = 1;
        
        $title = 'Добавление данных о партнере';
        
        return view('admin.editPartner', compact(['title', 'post']));
    }
    
    // Добавляем данные о партнере
    public function postPartnerAdd( FriendAndPartnerRequest $request ){
        
        $post = FriendsAndPartners::create( $request->except(['_token', 'image']) );

        if( $file = $request->file('image') )
        {
            $fileName = time() . '_' . Slug::make(basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension())) . '.' . $file->getClientOriginalExtension();
            
            Image::make( $file )->resize(123, 50)->save( 'uploads/partners/' . $fileName );
            
            FriendsAndPartners::find( $post->id )->update(['image' => $fileName]);
        }

        return redirect('/master/advertising/friends-and-partners/edit/' . $post->id);
    }

    // Редактируем данные о партнере (форма)
    public function getPartnerEdit( $id ){
        
        $post  = FriendsAndPartners::find( $id );
        
        $title = 'Редактирование данных о партнере';

        return view('admin.editPartner', compact(['title', 'post']));
    }

    // Редактируем данные о партнере
    public function postPartnerEdit( FriendAndPartnerRequest $request, $id ){

        FriendsAndPartners::find( $id )->update($request->except(['_token', 'image']));
        
        if( $file = $request->file('image') )
        {
            // Удаляем старый логотип, если есть
            $oldLogo = FriendsAndPartners::find( $id )->image;
            if( !empty($oldLogo) )
                File::delete( public_path() . '/uploads/partners/' . $oldLogo );

            $newLogo = time() . '_' . Slug::make(basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension())) . '.' . $file->getClientOriginalExtension();
            
            Image::make( $file )->resize(123, 50)->save( 'uploads/partners/' . $newLogo );
            
            FriendsAndPartners::find( $id )->update(['image' => $newLogo]);
        }

        return redirect()->back()->with('success', 'Данные о партнере успешно обновлены!');
    }
    
    // Показать/скрыть патнера
    public function visiblePartner( Request $request ){
        
        if( $request->ajax() )
        {
            $partner = FriendsAndPartners::find( (int)$request->id )->visible;
            
            $visible = empty($partner) ? 1 : 0;
            
            FriendsAndPartners::find( (int)$request->id )->update(['visible' => $visible]);
            
            return Response::json(['message' => 'completed']);
        }
    }
    
    // Сортировка партнеров
    public function sortablePartners( Request $request ){
        
        if( $request->ajax() )
        {
            (int)$i = 1;
            foreach($request->get('position') as $item){
                FriendsAndPartners::find( $item )->update(['position' => $i]);
                $i++;
            }
        }
    }
}
