@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		<li class="active">{{ trans('design.personal_area') }}</li>
	</ol>
	<div class="page-title">{{{ $user->name }}}</div>
	
	@if( count($errors) > 0 )
		<div class="alert danger">
			<ul>
				@foreach( $errors->all() as $error )
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	
	@if( Session::get('status') )
		<div class="alert success">
			{{ Session::get('status') }}
		</div>
	@endif
	
	<div class="user-info">
		<div class="f-left first-col">
			<div class="avatar">
				@if( $user->avatar )
					<img src="{{ $user->avatar }}" alt="{{{ $user->name }}}" />
				@else
					<img src="{{ Gravatar::src( $user->email ) }}" alt="{{{ $user->name }}}" />
				@endif
			</div>
			<div class="rating">
				<span class="f-left">{{ trans('design.rating_user') }}:</span>
				<span class="f-right number">{{ $user->rating }}</span>
			</div>
			<a href="#form-edit-info" class="edit-info">{{ trans('design.edit_information') }}</a>
		</div>
		<div class="f-right second-col">
			@if( $user->login )
				<div class="field">
					<span>{{ trans('design.input_login') }}:</span> {{{ $user->login }}}
				</div>
			@endif
			@if( $user->city )
				<div class="field">
					<span>{{ trans('design.input_city') }}:</span> {{{ $user->city }}}
				</div>
			@endif
			@if( $user->birthday )
				<div class="field">
					<span>{{ trans('design.input_birthday') }}:</span> {{{ date('d.m.Y', strtotime($user->birthday)) }}}
				</div>
			@endif
			@if( $user->email )
				<div class="field">
					<span>E-mail:</span> {{{ $user->email }}}
				</div>
			@endif
			@if( $user->about )
				<div class="field about">
					<span>{{ trans('design.input_about') }}:</span>
					{{{ $user->about }}}
				</div>
			@endif
		</div>
		<div class="clear"></div>
		<div class="buttons">
			<a href="#form-add-article" class="add-article">{{ trans('design.add_article') }}</a>
			<a href="#form-add-video" class="add-video">{{ trans('design.add_video') }}</a>
			<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'logout') }}" class="logout">&larr; {{ trans('design.logout') }}</a>
		</div>
		
		<!-- Form Edit Info -->
		{!! Form::open(['url' => LaravelLocalization::getLocalizedURL($currentLocale, 'profile'), 'class' => 'white-popup-block mfp-hide', 'id' => 'form-edit-info']) !!}
			<label>{{ trans('design.input_name') }}</label>
			<input type="text" name="name" value="{{{ $user->name }}}" required />
			<label>{{ trans('design.input_login') }}</label>
			<input type="text" name="login" value="{{{ $user->login }}}" required />
			<label>{{ trans('design.input_city') }}</label>
			<input type="text" name="city" value="{{{ $user->city }}}" />
			<label>{{ trans('design.input_birthday') }}</label>
			<input type="date" name="birthday" min="1930-01-01" value="{{{ $user->birthday }}}" />
			<label>E-mail</label>
			<input type="email" name="email" value="{{{ $user->email }}}" required />
			<label>{{ trans('design.input_new_password') }}</label>
			<input type="password" name="password" />
			<label>{{ trans('design.input_about') }}</label>
			<textarea name="about">{{{ $user->about }}}</textarea>
			<button type="submit">{{ trans('design.save') }}</button>
		{!! Form::close() !!}
		
		<!-- Form Add Article -->
		{!! Form::open(['url' => LaravelLocalization::getLocalizedURL($currentLocale, 'add-user-article'), 'class' => 'white-popup-block mfp-hide', 'id' => 'form-add-article']) !!}
			<label>{{ trans('design.message_add_article') }} <a href="mailto:{{ $email }}">{{ $email }}</a></label>
			<input type="text" name="name" placeholder="{{ trans('design.appellation') }}" value="{{ old('name') }}" required />
			<textarea id="user-article" name="article" class="mceEditor"></textarea>
			<button type="submit">{{ trans('design.send') }}</button>
		{!! Form::close() !!}
		
		<!-- Form Add Video -->
		{!! Form::open(['url' => LaravelLocalization::getLocalizedURL($currentLocale, 'add-user-video'), 'class' => 'white-popup-block mfp-hide', 'id' => 'form-add-video']) !!}
			<label>{{ trans('design.input_video_link') }}</label>
			<input type="url" name="video" required />
			<label>{{ trans('design.input_product_link') }}</label>
			<input type="url" name="product_url" required />
			<label>{{ trans('design.input_comment') }}</label>
			<textarea name="comment"></textarea>
			<button type="submit">{{ trans('design.send') }}</button>
		{!! Form::close() !!}
	</div>

@endsection