<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\LogInRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\EditUserInfoRequest;
use App\Http\Requests\AddUserVideoRequest;
use App\Http\Requests\AddUserArticleRequest;

use App\Http\Controllers\Frontend\BaseController;

use App\User;
use App\Models\Page;
use App\Models\UserVideo;
use App\Models\UserArticle;
use App\Models\Setting;

use Faker\Factory as Faker;

use App;
use Auth;
use Hash;
use Socialite;
use Slug;
use LaravelLocalization;

class ProfileController extends BaseController
{
    // Личный кабинет
	public function index(){
        
		$user  = Auth::check() ? Auth::user() : new User;
		
		// E-mail администратора
		$email = Setting::getSetting('email');
		
		$page = new Page;
		$page->{'meta_title_' . App::getLocale()} = trans('design.personal_area');

        if( $user->id != '' )
			return view('frontend.profile', compact(['user', 'email', 'page']));
		else
			return redirect( LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), '/') );
    }
	
	// Форма регистрации
	public function getRegister(){
		
		$page = new Page;
		$page->{'meta_title_' . App::getLocale()} = trans('design.user_registration');
		
		return view('frontend.profileRegister', compact(['page']));
	}
	
	// Регистрация
    public function postRegister( RegistrationRequest $request ){

		$user = User::create([
            'name'          => $request->get('name'),
			'email'   		=> $request->get('email'),
			'login'			=> strtolower( Slug::make($request->get('login')) ),
			'city'			=> $request->get('city'),
            'birthday'      => $request->get('birthday'),
			'about'			=> $request->get('about'),
			'password' 		=> Hash::make($request->get('password')),
			'activated'		=> 1
		]);
		
		Auth::attempt(['login' => $request->get('login'), 'password' => $request->get('password')]);

		return redirect( LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), 'profile') );
    }
	
	// Вход в профиль
    public function postLogin( LogInRequest $request ){

    	if( $request->ajax() )
		{
			if( Auth::attempt(['login' => $request->get('login'), 'password' => $request->get('password')]) )
				return json_encode([ 'success' => trans('design.message_success_login') ]);
			else
				return json_encode([ 'errors'  => trans('design.message_error_login') ]);
		}
    }
	
	// Выход из профиля
    public function logout(){
    	
		Auth::logout();
		
		return redirect( LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), '/') );
    }
	
	// Провайдер соц. сети 
	public function redirectToProvider( $provider ){
        
		return Socialite::driver( $provider )->redirect();
	}
	
	// Данные от соц. сети + авторизация/регистрация
	public function handleProviderCallback( $provider ){
		
		$socialUser = Socialite::driver( $provider )->user();
		
		if( $user = User::where('email', $socialUser->getEmail())->orWhere('social_id', $socialUser->getId())->first() )
		{
			if( empty($user->avatar) || $user->avatar != $socialUser->getAvatar() )
			{
				$user->avatar = $socialUser->getAvatar();
				$user->save();
			}
			
			Auth::loginUsingId( $user->id );
			
			return redirect( LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), 'profile') );
		}
		else
		{
			$faker = Faker::create();
			
			$user = User::create([
				'social_id' => $socialUser->getId(),
				'name'		=> !empty($socialUser->getName()) ? $socialUser->getName() : $faker->name,
				'email'		=> !empty($socialUser->getEmail()) ? $socialUser->getEmail() : $faker->email,
				'login'		=> !empty($socialUser->getNickname()) ? $socialUser->getNickname() : $faker->userName,
				'avatar'	=> $socialUser->getAvatar(),
				'activated'	=> 1
			]);
			
			Auth::loginUsingId( $user->id );
			
			return redirect( LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), 'profile') );
		}
	}
	
	// Редактирование личной информации
    public function postEditInfo( EditUserInfoRequest $request ){

		if( $user = User::find( Auth::user()->id ) )
		{
            $user->name		= $request->get('name');
			$user->login	= strtolower( Slug::make($request->get('login')) );
			$user->city		= $request->get('city');
			$user->birthday = $request->get('birthday');
			$user->email	= $request->get('email');
            $user->about	= $request->get('about');
			
			if( $request->get('password') != '' )
				$user->password = Hash::make( $request->get('password') );

			$user->save();
		}
		
		return redirect()->back()->with('status', trans('design.message_save_info'));
    }
	
	// Добавление видеообзора
	public function postAddVideo( AddUserVideoRequest $request ){
		
		UserVideo::create([
			'user_id'		=> Auth::user()->id,
			'video' 		=> $request->get('video'),
			'product_url'	=> $request->get('product_url'),
			'comment'		=> $request->get('comment')
		]);
		
		return redirect()->back()->with('status', trans('design.message_send_video'));
	}
	
	// Добавление статьи
	public function postAddArticle( AddUserArticleRequest $request ){
		
		UserArticle::create([
			'user_id'	=> Auth::user()->id,
			'name'		=> $request->get('name'),
			'article'	=> $request->get('article')
		]);
		
		return redirect()->back()->with('status', trans('design.message_send_article'));
	}
}
