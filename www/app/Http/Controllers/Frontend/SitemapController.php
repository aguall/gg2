<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Frontend\BaseController;

use App\Models\Seo;
use App\Models\Page;
use App\Models\Shop;
use App\Models\BlogCategory;
use App\Models\ProductCategory;

use App;

class SitemapController extends BaseController
{
    public function index()
    {
		$page = Page::whereSlug('sitemap')->firstOrFail();
		
		// Страницы
		$sitemapPages = Page::whereNotIn('slug', ['', '404', 'search', 'sitemap'])->get();
		
		// Блог
		$sitemapBlog = BlogCategory::select('id', 'slug', 'name_' . App::getLocale())
								   ->with(['posts' => function($query){
											$query->select('category_id', 'slug', 'name_' . App::getLocale())
												  ->whereVisible( 1 )
												  ->orderBy('date', 'DESC');
										 }])
								   ->get();
		
		// Категории товаров
		$sitemapProductsCategories = ProductCategory::all()->toHierarchy();
		$sitemapProductsCategoriesSEO = Seo::all();
		
		// Магазины
		$sitemapShops = Shop::whereVisible(1)->get();
		
		return view('frontend.showSitemap', compact([
														'page', 
														'sitemapPages', 
														'sitemapBlog',
														'sitemapProductsCategories', 
														'sitemapProductsCategoriesSEO',
														'sitemapShops'
													]));
    }
}
