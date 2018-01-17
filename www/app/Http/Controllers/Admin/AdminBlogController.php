<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\BlogPostRequest;
use App\Http\Requests\BlogCategoryRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\User;

use Image;
use File;
use Slug;
use Response;

class AdminBlogController extends AdminBaseController
{
	private $categories = [];
	private $users = [];
	
	public function __construct(){
		parent::__construct();

		// Рубрики
		if( $categoriesAll = BlogCategory::all() )
			foreach( $categoriesAll as $category )
				$this->categories[$category->id] = $category->name;
		
		// Пользователи
		foreach( User::all() as $user )
			$this->users[$user->id] = $user->name;
		$this->users = array_add($this->users, '0', 'Выбирите пользователя, от которого Вы получили материал (видео/статья)');
		$this->users = array_reverse($this->users, true);
	}
	
	// Метод выбора опираций
	public function index(){
        
		$title = 'Блог';
		
		// Количество записей
		$postsCount = BlogPost::count();
		
		// Количество рубрик
		$categoriesCount = BlogCategory::count();
		
		return view('admin.showBlogOptions', compact(['title', 'postsCount', 'categoriesCount']));
    }
	
	// Покажем рубрики блога
	public function getBlogCategories(){
		
		$title = 'Рубрики';
		
		$categories = BlogCategory::paginate(25);
		
		return view('admin.showBlogCategories', compact(['title', 'categories']));
	}
	
	// Удаляем рубрики
	public function deleteBlogCategories( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
		{
			// Если есть миниатюры у записей - удалим
			if( $posts = BlogPost::whereIn('category_id', $ids)->get() )
			{
				foreach($posts as $post)
					if( $post->image )
						File::delete( public_path() . '/uploads/blog/' . $post->image );
			}
			
			BlogCategory::destroy( $ids );
		}

		return redirect()->back();
	}
	
	// Создаем рубрику (форма)
	public function getBlogCategoryAdd(){
		
		$post = new BlogCategory;
		
		$title = 'Создание рубрики';
		
		return view('admin.editBlogCategory', compact(['title', 'post']));
	}
	
	// Создаем рубрику
	public function postBlogCategoryAdd( BlogCategoryRequest $request ){
		
		$post = BlogCategory::create( $request->except('_token') );

		return redirect('/master/blog/categories/edit/' . $post->id);
	}
	
	// Редактируем рубрику (форма)
	public function getBlogCategoryEdit( $id ){
		
		$post = BlogCategory::find( $id );

		$title = 'Редактирование рубрики';

		return view('admin.editBlogCategory', compact( ['title', 'post'] ) );
	}
	
	// Редактируем рубрику
	public function postBlogCategoryEdit( BlogCategoryRequest $request, $id ){
		
		BlogCategory::find($id)->update($request->except('_token'));

		return redirect('/master/blog/categories/edit/' . $id)->with('success', 'Рубрика успешно обновлена!');
	}
	
	// Покажем записи
	public function getBlogPosts( Request $request ){
		
		$title = 'Записи';
		
		// Записи
		$categoriesIds = array_keys($this->categories);
		
		if( $request->get('category') )
			$ids = !empty($request->get('category')) ? [ (int)$request->get('category') ] : $categoriesIds;
		else
			$ids = $categoriesIds;
		
		$posts = BlogPost::select('blog_posts.*', 'blog_categories.slug AS category_slug')
						 ->join('blog_categories', 'blog_categories.id', '=', 'blog_posts.category_id')
						 ->whereIn('blog_posts.category_id', $ids)
						 ->orderBy('blog_posts.date', 'DESC')
						 ->paginate(25);
		
		// Рубрики
		$categories = array_add($this->categories, '0', 'Все рубрики');
		$categories = array_reverse($categories, true);
		
		return view('admin.showBlogPosts', compact(['title', 'posts', 'categories']));
	}
	
	// Удаляем записи
	public function deleteBlogPosts( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
		{
			// Если есть миниатюры - удалим
			foreach(BlogPost::whereIn('id', $ids)->get() as $post)
				if( $post->image )
					File::delete( public_path() . '/uploads/blog/' . $post->image );
			
			BlogPost::destroy( $ids );
		}

		return redirect()->back();
	}
	
	// Создаем запись (форма)
	public function getBlogPostAdd(){
		
		$post = new BlogPost;
		$post->user_id = 0;
		$post->visible = 1;
		$post->category_id = 0;
		
		$title = 'Создание записи';
		
		// Пользователи
		$users = $this->users;
		
		// Рубрики
		$categories = array_add($this->categories, '0', 'Выбирите рубрику...');
		$categories = array_reverse($categories, true);
		
		return view('admin.editBlogPost', compact(['title', 'post', 'users', 'categories']));
	}
	
	// Создаем запись
	public function postBlogPostAdd( BlogPostRequest $request ){
		
		$post = BlogPost::create( $request->except(['_token', 'image']) );
		
		// Если загрузка изображения
		if( $request->file('image') )
			$this->thumbPost( $request->file('image'), $post->id );

		return redirect('/master/blog/posts/edit/' . $post->id);
	}
	
	// Редактируем запись (форма)
	public function getBlogPostEdit( $id ){
		
		$post = BlogPost::find( $id );
		
		$title = 'Редактирование записи';
		
		// Пользователи
		$users = $this->users;
		
		// Рубрики
		$categories = array_add($this->categories, '0', 'Выбирите рубрику...');
		$categories = array_reverse($categories, true);

		return view('admin.editBlogPost', compact(['title', 'post', 'users', 'categories']));
	}
	
	// Редактируем запись
	public function postBlogPostEdit( BlogPostRequest $request, $id ){
		
		BlogPost::find($id)->update($request->except(['_token', 'image']));
		
		// Если загрузка изображения
		if( $request->file('image') )
			$this->thumbPost( $request->file('image'), $id );

		return redirect('/master/blog/posts/edit/' . $id)->with('success', 'Запись успешно обновлена!');
	}
	
	// Создаем/обновляем миниатюру
	public function thumbPost( $file, $id ){
		
		// Удаляем старое изображение, если есть
		$oldImage = BlogPost::find( $id )->image;
		if( !empty($oldImage) )
			File::delete( public_path() . '/uploads/blog/' . $oldImage );
		
		$fileName = time() . '_' . Slug::make(basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension())) . '.' . $file->getClientOriginalExtension();
		
		Image::make( $file )->widen(160)->save( 'uploads/blog/' . $fileName );
		
		BlogPost::whereId( $id )->update(['image' => $fileName]);
	}
	
	// Показать/скрыть запись
	public function visibleBlogPost( Request $request ){
		
		if( $request->ajax() )
		{
			$postVisible = BlogPost::find( (int)$request->id )->visible;
			
			$visible = empty($postVisible) ? 1 : 0;
			
			BlogPost::whereId( (int)$request->id )->update(['visible' => $visible]);
			
			return Response::json(['message' => 'completed']);
		}
	}
}
