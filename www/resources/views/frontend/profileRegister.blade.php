@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		<li class="active">{{ trans('design.user_registration') }}</li>
	</ol>
	<div class="page-title text-center">{{ trans('design.user_registration') }}</div>
	
	{!! Form::open(['url' => 'register', 'class' => 'form-registration']) !!}
		
		@if( count($errors) > 0 )
			<div class="alert danger">
				<ul>
					@foreach( $errors->all() as $error )
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
		<label>{{ trans('design.input_name') }}</label>
		<input type="text" name="name" value="{{ old('name') }}" required />
		<label>{{ trans('design.input_login') }}</label>
		<input type="text" name="login" value="{{ old('login') }}" required />
		<label>{{ trans('design.input_city') }}</label>
		<input type="text" name="city" value="{{ old('city') }}" />
		<label>{{ trans('design.input_birthday') }}</label>
		<input type="date" name="birthday" min="1930-01-01" value="{{ old('date') }}" />
		<label>E-mail</label>
		<input type="email" name="email" value="{{ old('email') }}" required />
		<label>{{ trans('design.input_password') }}</label>
		<input type="password" name="password" required />
		<label>{{ trans('design.input_about') }}</label>
		<textarea name="about">{{ old('about') }}</textarea>
		<style scoped>
			.captcha{ margin-bottom: 20px }
			.captcha .g-recaptcha > div{ margin: 0 auto }
		</style>
		<div class="captcha">
			<div class="g-recaptcha" data-sitekey="6LcXkQkUAAAAAJOFsA2_uCxoTpZqS4v-gSTbvYWs"></div>
		</div>
		<button type="submit">{{ trans('design.send') }}</button>
	{!! Form::close() !!}	

@endsection