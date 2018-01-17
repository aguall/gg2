@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		<li class="active">{{ trans('design.password_throw') }}</li>
	</ol>
	<div class="page-title text-center">{{ trans('design.password_throw') }}</div>
	
	{!! Form::open(['url' => 'password/reset', 'class' => 'form-registration']) !!}
		
		@if( count($errors) > 0 )
			<div class="alert danger">
				<ul>
					@foreach( $errors->all() as $error )
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
		<input type="hidden" name="token" value="{{ $token }}">
		<label>E-mail</label>
		<input type="email" name="email" value="{{ old('email') }}" required />
		<label>{{ trans('design.input_password') }}</label>
		<input type="password" name="password" required />
		<label>{{ trans('design.input_pass_confirm') }}</label>
		<input type="password" name="password_confirmation" required />
		<button type="submit">{{ trans('design.password_update') }}</button>
	{!! Form::close() !!}

@endsection