<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\Setting;
use App\Models\Page;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shop;

use Cache;
use Response;

class AdminSettingsController extends AdminBaseController
{
    // Покажем настройки
	public function index(){
       
		$title = 'Настройки';
		
		$allSettings = Setting::all();
		
		$settings = (object)['email' => old('email'), 'site_offline' => old('site_offline')];
		
		if( $allSettings )
		    foreach( $allSettings as $setting )
		    	$settings->{$setting->name} = $setting->value;
		
		return view('admin.showSettings', compact(['title', 'settings']));
    }

    // Редактируем настройки
	public function postEdit( Request $request ){
		
		if( !$request->ajax() )
		{
			// Удалим все настройки
			Setting::truncate();

			// Занесем заново
			foreach( $request->except('_token') as $key => $value )
				Setting::updateOrCreate(['name' => $key], ['value' => $value]);

	    	return redirect()->back()->with('success', 'Настройки сохранены');
	    }
		else
		{
			Cache::flush();
			
			return Response::json(['success' => 'Кэш обновлен']);
		}
    }

    // Генерируем XML карты сайта
    public function getSitemapXML( Request $request )
    {
    	if( $request->ajax() )
		{
			$start = microtime(true);

			// Страницы
	        $pages = Page::whereNotIn('slug', ['', '404', 'search'])->get();

	        // Рубрики блога
	        $blogCategories = BlogCategory::all();

	        // Записи блога
	        $blogPosts = BlogPost::select('blog_posts.*', 'blog_categories.slug AS category_slug')
	                             ->join('blog_categories', 'blog_categories.id', '=', 'blog_posts.category_id')
	                             ->where('blog_posts.visible', 1)
	                             ->get();

	        // Категории товаров
	        $productsCategories = ProductCategory::get();

	        // Товары
	        $products = Product::whereVisible(1)->get();

	        // Магазины
	        $shops = Shop::whereVisible(1)->get();
			
			$data = [
						['db_result' => $pages, 'url_prefix' => '/%s'],
						['db_result' => $blogCategories, 'url_prefix' => '/blog/%s'],
						['db_result' => $blogPosts, 'url_prefix' => '/blog/%s/%s'],
						['db_result' => $productsCategories, 'url_prefix' => '/category/%s'],
						['db_result' => $products, 'url_prefix' => '/product/%s'],
						['db_result' => $shops, 'url_prefix' => '/shops/%s']
					];
			
			Setting::getSitemapsXML('ru', $data);
			Setting::getSitemapsXML('ua', $data);
			Setting::getSitemapsXML('en', $data);

	        $time = microtime(true) - $start;

	        return Response::json(['success' => sprintf('XML карты сайта сгенерированы. Время генерирования: %.4F сек.', $time)]);
		}
    } 
}