<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;

use App\Models\Page;
use App\Models\Shop;

use Carbon\Carbon;

class ShopsController extends BaseController
{
    public function index(){
		
		$page  = Page::whereSlug( 'shops' )->firstOrFail();
		
		$shops = Shop::whereVisible(1)->orderBy('rating', 'DESC')->get();
		
		return view('frontend.showShops', compact(['page', 'shops']));
    }

    public function getShop( $shop_url ){
		
		$page = Shop::whereSlug( $shop_url )->firstOrFail();
		
		$view = view('frontend.showShop', compact(['page']));
		
		if( !Request::cookie('shop_view_' . $page->id) )
		{
			// Накручиваем рейтинг магазину за просмотр
			Shop::find( $page->id )->increment('rating');
			
			// Подсчитваем оставшиеся количество минут к концу дня
			$date = Carbon::now('Europe/Kiev');
			$currentTime = time();
			$setTime = mktime(23, 59, 59, $date->month, $date->day, $date->year);
			$minutes = floor(($setTime - $currentTime) / 60);
			
			$response = new \Illuminate\Http\Response( $view );
			$response->withCookie( cookie('shop_view_' . $page->id, $page->id, $minutes) );
			
			return $response;
		}
		else
			return $view;
	}
}
