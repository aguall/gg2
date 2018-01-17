<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\BannerRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Banner;

use Response;

class AdminBannersController extends AdminBaseController
{
    private $zones = [];
    
    public function __construct(){
        parent::__construct();

        // Зоны отображения баннеров
        $this->zones = [ 'top' => 'Сверху', 'left' => 'Слева', 'right' => 'Справа', 'bottom' => 'Снизу' ];
    }

    // Список баннеров
    public function index( Request $request ){

        $title = 'Баннеры';

        if( $request->get('zone') && !empty($request->get('zone')) )
        {
            $zone  = [ $request->get('zone') ];
            $order = 'position'; 
        }
        else
        {
            $zone  = array_keys($this->zones);
            $order = 'id';
        }

        $banners = Banner::whereIn('zone', $zone)->orderBy($order)->get();

        $zones = ['' => 'Все зоны отображения'] + $this->zones;

        return view('admin.showBanners', compact(['title', 'banners', 'zones']));
    }

    // Удаление баннеров
    public function deleteBanners( Request $request ){
        
        $ids = $request->get('check');
        
        if( $request->get('action') == 'delete' )
            Banner::destroy( $ids );

        return redirect()->back();
    }

    // Добавление баннера (форма)
    public function getBannerAdd(){
        
        $title = 'Добавление баннера';
        
        $post  = new Banner;
        $post->visible = 1;
        
        // Зоны отображения
        $zones = ['' => 'Выберите зону отображения...'] + $this->zones;
        
        return view('admin.editBanner', compact(['title', 'post', 'zones']));
    }

    // Добавление баннера
    public function postBannerAdd( BannerRequest $request ){
        
        $post = Banner::create( $request->except('_token') );

        return redirect('/master/advertising/banners/edit/' . $post->id)->with('success', 'Баннер добавлен!');
    }

    // Редактирование баннера (форма)
    public function getBannerEdit( $id ){
        
        $title = 'Редактирование баннера';
        
        $post  = Banner::find( $id );
        
        // Зоны отображения
        $zones = ['' => 'Выберите зону отображения...'] + $this->zones;

        // Связаныые баннеры для ротации
        $relatedBanners = Banner::whereZone( $post->zone )->whereNotIn('id', [ $post->id ])->get();
        
        $rotation = [];
        if( count($relatedBanners) )
            foreach( $relatedBanners as $banner )
                $rotation[$banner->id] = $banner->description;

        return view('admin.editBanner', compact(['title', 'post', 'zones', 'rotation']));
    }

    // Редактирование баннера
    public function postBannerEdit( BannerRequest $request, $id ){
        
        if( $request->get('rotation') )
        {
            $requestAllFields = [ '_token', 'rotation' ];
            $requestRotation  = serialize($request->get('rotation'));
        }
        else
        {
            $requestAllFields = '_token';
            $requestRotation  = '';
        }

        Banner::find( $id )->update( $request->except($requestAllFields) );
        Banner::find( $id )->update( ['rotation' => $requestRotation] );
        
        return redirect()->back()->with('success', 'Данные баннера обновлены!');
    }

    // Показать/скрыть баннер
    public function visibleBanner( Request $request ){
        
        if( $request->ajax() )
        {
            $bannerVisible = Banner::find( (int)$request->id )->visible;
            
            $visible = empty($bannerVisible) ? 1 : 0;
            
            Banner::whereId( (int)$request->id )->update(['visible' => $visible]);
            
            return Response::json(['message' => 'visible completed']);
        }
    }

    // Сортировка баннеров
    public function sortableBanners( Request $request ){
        
        if( $request->ajax() )
        {
            (int)$i = 1;
    
            foreach($request->get('position') as $item){
                Banner::whereId( $item )->update(['position' => $i]);
                $i++;
            }
        }
    }
}
