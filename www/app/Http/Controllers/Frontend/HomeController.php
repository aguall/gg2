<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;

use App\Models\Page;
use App\Models\BlogCategory;
use App\Models\BlogPost;

class HomeController extends BaseController
{
    public function index(){
        
		$page = Page::whereSlug('')->firstOrFail();
		
		$categories = BlogCategory::whereIn('slug', ['news', 'reviews', 'shares', 'video'])->get();
		$categoriesIds = [];
		foreach($categories as $category)
			$categoriesIds[] = $category->id;
		
		$posts = BlogPost::select('blog_posts.*', 'blog_categories.slug AS category_slug')
						 ->join('blog_categories', 'blog_categories.id', '=', 'blog_posts.category_id')
						 ->whereIn('blog_posts.category_id', $categoriesIds)
						 ->where('blog_posts.visible', 1)
						 ->orderBy('blog_posts.date', 'DESC')
						 ->paginate(6);
		
		return view('frontend.Main', compact(['page', 'posts', 'categories']));
    }
}
