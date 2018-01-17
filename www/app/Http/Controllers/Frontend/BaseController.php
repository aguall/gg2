<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Page;
use App\Models\Menu;
use App\Models\Setting;
use App\Models\BlogCategory;
use App\Models\ProductCategory;
use App\Models\ProductShopPrice;
use App\Models\Shop;
use App\Models\Banner;
use App\Models\FriendsAndPartners;

use App;
use HTML;
use Auth;
use Cache;
use LaravelLocalization;

class BaseController extends Controller
{
	protected $request;
	
	protected $robotsIndex = 'INDEX';
	
	protected $canonicalUrl; 
	
	public function __construct(){
		
		$this->request = App::make('Illuminate\Http\Request');
		
		// Залогинен ли пользователь?
		$user = Auth::check() ? TRUE : FALSE;
		
		// Если сайт выключен (отключаем доступ для всех кроме админа)
   		if( !in_array( $this->request->segment(1), ['master', 'logout'] ) && Setting::getSetting( 'site_offline' ) == 'true' )
		{
			if( $user && Auth::user()->permissions == 'admin' )
				view()->share('site_offline', 1 );
			else
				die( view('frontend.Offline') );
   		}
		
		// Первое меню
		$firstMenu = Cache::rememberForever('firstMenu', function(){
			return Menu::whereVisible(1)->orderBy('position')->get();
		});
		
		// Второе меню
		$secondMenu = Cache::rememberForever('secondMenu', function(){
			return BlogCategory::whereIn('slug', ['workshops', 'shooting', 'clubs'])->get();
		});
		
		// Текущая локаль
		$currentLocale = App::getLocale();
		
		// Категории товаров
		$productsCategories = Cache::rememberForever('productsCategories', function(){
			return ProductCategory::all()->toHierarchy();
		});
		
		// Топ 10 магазинов
		$shopsTop10 = Shop::whereVisible(1)->take(10)->orderBy('rating', 'DESC')->get();

		// Количество предложений на сайте
		$offers = ProductShopPrice::count();

		// Канонические URL
		$canonicalUrl = $this->request->getRequestUri();
		if( preg_match('/\?page\=.*/', $canonicalUrl) )
			$canonicalUrl = preg_replace('/\?page\=.*/', '', $canonicalUrl);
		if( HTML::getLocalizedSegment(1) == 'category' )
			$canonicalUrl = 'category/' . HTML::getLocalizedSegment(2);
		$canonicalUrl = LaravelLocalization::getLocalizedURL($currentLocale, $canonicalUrl);
		$this->canonicalUrl = $canonicalUrl;

		// Баннеры
		$bannersTop = Banner::showBannersUri( $this->request->url(), 'top' );
		$bannersLeft = Banner::showBannersUri( $this->request->url(), 'left' );
		$bannersRight = Banner::showBannersUri( $this->request->url(), 'right' );
		$bannersBottom = Banner::showBannersUri( $this->request->url(), 'bottom' );

		// Друзья и партнеры
		$partners = Cache::rememberForever('partners', function(){
			return FriendsAndPartners::whereVisible( 1 )->orderBy('position')->take(5)->get();
		});
		
		// Индексация поисковыми ботами
		$robotsIndex = $this->robotsIndex;
		if( $this->request->get('page') || $this->request->get('filter') || $this->request->get('sort') )
		{
			$this->robotsIndex = 'NOINDEX';
			$robotsIndex = 'NOINDEX';
		}
		
		view()->share(compact([
								'user', 
								'firstMenu', 
								'secondMenu', 
								'currentLocale', 
								'productsCategories', 
								'shopsTop10', 
								'offers', 
								'canonicalUrl',
								'bannersTop',
								'bannersLeft',
								'bannersRight',
								'bannersBottom',
								'partners',
								'robotsIndex'
							  ]));
	}
}
