<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Request;

use App\Http\Requests;

use App\Http\Controllers\Frontend\BaseController;

use App\User;
use App\Models\BlogCategory;
use App\Models\BlogPost;

use Auth;

class BlogController extends BaseController
{
	public function index( $category_url ){
        
		$page  = BlogCategory::whereSlug( $category_url )->firstOrFail();
		
		$posts = BlogPost::select('blog_posts.*', 'blog_categories.slug AS category_slug')
						 ->join('blog_categories', 'blog_categories.id', '=', 'blog_posts.category_id')
						 ->where('blog_posts.category_id', $page->id)
						 ->where('blog_posts.visible', 1)
						 ->orderBy('blog_posts.date', 'DESC')
						 ->paginate(6);
						 
		$categories = '';
		if( in_array($category_url, ['news', 'reviews', 'shares', 'video']) )
			$categories = BlogCategory::whereIn('slug', ['news', 'reviews', 'shares', 'video'])->get();
		
		return view('frontend.Main', compact(['page', 'posts', 'categories']));
    }
	
	public function getPost( $category_url, $post_url ){
		
		$page = BlogPost::whereSlug( $post_url )->firstOrFail();
		
		$category = BlogCategory::whereSlug( $category_url )->first();
		
		$view = view('frontend.showPost', compact(['page', 'category']));
		
		if( !empty($page->user_id) && !Request::cookie('post_view_' . $page->id) )
		{
			// Накручиваем рейтинг пользователю за просмотр материала
			if( (Auth::check() && Auth::user()->id != $page->user_id) || !Auth::check() )
			{
				User::find( $page->user_id )->increment('rating');
			
				$response = new \Illuminate\Http\Response( $view );
				$response->withCookie(cookie()->forever('post_view_' . $page->id, $page->id));
			
				return $response;
			}
		}
		else
			return $view;
	}
}
