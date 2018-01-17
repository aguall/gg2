<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\CreateUserRequest;

use App\Http\Controllers\Admin\AdminBaseController;

use App\User;
use App\Models\UserVideo;
use App\Models\UserArticle;

use Auth;
use Hash;

class AdminUsersController extends AdminBaseController
{
    // Метод выбора опираций
	public function index(){
		
		$title = 'Пользователи';
		
		$usersCount = User::count();
		
		$usersVideoCount = UserVideo::count();
		
		$usersArticlesCount = UserArticle::count();
		
		return view('admin.showUsersOptions', compact(['title', 'usersCount', 'usersVideoCount', 'usersArticlesCount']));
    }
	
	// Удаление пользователей
	public function deleteUsers( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
			User::destroy( $ids );

		return redirect()->back();
	}

    // Список пользователей
	public function getUsers(){
		
		$title = 'Список пользователей';
		
		$usersList = User::orderBy('permissions', 'ASC')->paginate(25);
		
		return view('admin.showUsers', compact(['title', 'usersList']));
    }
	
	// Редактируем данные пользователя (форма)
	public function getEdit( $id ) {

		$title = 'Редактирование пользователя';
		
		$post  = User::find( $id );

		return view('admin.editUser', compact(['title', 'post']));
	}
	
	// Редактируем данные пользователя
	public function postEdit( UpdateUserRequest $request, $id ){

		$user = User::find($id);
		
		$user->name			= $request->get('name');
		$user->email		= $request->get('email');
		$user->login		= $request->get('login');
		$user->city			= $request->get('city');
		$user->birthday		= $request->get('birthday');
		$user->about		= $request->get('about');
		$user->rating		= $request->get('rating');
		$user->permissions	= $request->get('permissions');

		if( $request->get('password') != '' )
			$user->password = Hash::make( $request->get('password') );

		$user->save();

		return redirect()->back()->with('success', 'Информация успешно обновлена!');
	}
	
	// Добавление пользователя (форма)
	public function getAdd() {

		$post = new User;
		
		$title = 'Добавление пользователя';
		
		$post->rating = 0;

		return view('admin.editUser', compact(['title', 'post']));
	}
	
	// Добавление пользователя
	public function postAdd( CreateUserRequest $request ){

		$user = User::create([
			'name'			=> $request->get('name'),
			'email'   		=> $request->get('email'),
			'login'			=> $request->get('login'),
			'city'			=> $request->get('city'),
			'birthday'		=> $request->get('birthday'),
			'about'			=> $request->get('about'),
			'rating'		=> $request->get('rating'),
			'password' 		=> Hash::make($request->get('password')),
			'activated'		=> 1,
			'permissions'	=> $request->get('permissions')
		]);

		return redirect('/master/users/show');
	}
	
	// Видео от пользователей
	public function getUsersVideo(){
		
		$title = 'Видео от пользователей';
		
		$video = UserVideo::select(['users_video.*', 'users.name AS user_name'])
						  ->join('users', 'users.id', '=', 'users_video.user_id')
						  ->orderBy('users_video.created_at', 'DESC')
						  ->paginate(25);
		
		return view('admin.showUsersVideo', compact(['title', 'video']));
	}
	
	// Удаление видео
	public function deleteUsersVideo( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
			UserVideo::destroy( $ids );

		return redirect()->back();
	}
	
	// Получим все данные о видео от пользователя
	public function getInfoUserVideo( Request $request ){
		
		if( $request->ajax() )
		{
			$videoInfo = UserVideo::find( (int)$request->get('id') );
			
			parse_str( parse_url( $videoInfo->video, PHP_URL_QUERY ), $videoCode );
			$videoInfo->video = $videoCode['v'];
			
			echo json_encode([ 'video' => $videoInfo->video, 'product_url' => $videoInfo->product_url, 'comment' => $videoInfo->comment ]);
			
			exit;
		}
	}
	
	// Статьи от пользователей
	public function getUsersArticles(){
		
		$title = 'Статьи от пользователей';
		
		$articles = UserArticle::select(['users_articles.*', 'users.name AS user_name'])
							   ->join('users', 'users.id', '=', 'users_articles.user_id')
							   ->orderBy('users_articles.created_at', 'DESC')
							   ->paginate(25);
		
		return view('admin.showUsersArticles', compact(['title', 'articles']));
	}
	
	// Удаление статьи
	public function deleteUsersArticles( Request $request ){
		
		$ids = $request->get('check');
		
		if( $request->get('action') == 'delete' )
			UserArticle::destroy( $ids );

		return redirect()->back();
	}
	
	// Получим все данные о статье от пользователя
	public function getInfoUserArticle( Request $request ){
		
		if( $request->ajax() )
		{
			$articleInfo = UserArticle::find( (int)$request->get('id') );
			
			echo json_encode([ 'name' => $articleInfo->name, 'article' => $articleInfo->article ]);
		
			exit;
		}
	}
}
