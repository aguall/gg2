<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;

use App\Models\Page;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\Shop;

use App;

class SearchController extends BaseController
{
    // Мультимодульный поиск по сайту
	public function index( Request $request ){
		
		$page = Page::whereSlug('search')->firstOrFail();
		
		$search = '';
		if( $request->isMethod('post') && !empty($request->get('search')) )
			$search = $request->get('search');
		
		if( $search )
		{
			// Поиск по страницам
			$searchPagesResult = Page::select([ 'slug', 'name_' . App::getLocale() ])
									 ->where('name_' . App::getLocale(), 'LIKE', '%' . $search . '%')
									 ->orWhere('body_' . App::getLocale(), 'LIKE', '%' . $search . '%')
									 ->get();
			
			// Поиск по магазинам
			$searchShopsResult = Shop::select([ 'slug', 'name_' . App::getLocale() ])
									 ->where('name_' . App::getLocale(), 'LIKE', '%' . $search . '%')
									 ->orWhere('body_' . App::getLocale(), 'LIKE', '%' . $search . '%')
									 ->whereVisible( 1 )
									 ->get();
			
			// Поиск по блогу
			$searchBlogResult = BlogPost::select([ 'blog_posts.slug', 'blog_posts.name_' . App::getLocale(), 'blog_categories.slug AS category_slug' ])
										->leftJoin('blog_categories', 'blog_posts.category_id', '=', 'blog_categories.id')
										->where('blog_posts.name_' . App::getLocale(), 'LIKE', '%' . $search . '%')
										->orWhere('blog_posts.body_' . App::getLocale(), 'LIKE', '%' . $search . '%')
										->where('blog_posts.visible', 1)
										->orderBy('blog_posts.date', 'DESC')
										->get();
			
			// Поиск по товарам
			$searchProductsResult = Product::select([ 'slug', 'name_' . App::getLocale() ])
										   ->where('name_' . App::getLocale(), 'LIKE', '%' . $search . '%')
										   ->orWhere('body_' . App::getLocale(), 'LIKE', '%' . $search . '%')
										   ->whereVisible( 1 )
										   ->get();
		}
										
		return view('frontend.showSearchResults', compact(['page', 'searchBlogResult', 'searchProductsResult', 'searchShopsResult', 'searchPagesResult']));
    }
}
