@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		<li class="active">{{ trans('design.password_reset') }}</li>
	</ol>
	<div class="page-title text-center">{{ trans('design.password_reset') }}</div>
	
	{!! Form::open(['url' => 'password/email', 'class' => 'form-registration']) !!}
		
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
		
		<div class="alert info">
			{{ trans('design.message_pass_reset') }}
		</div>
		<label>E-mail</label>
		<input type="email" name="email" value="{{ old('email') }}" required />
		<button type="submit">{{ trans('design.reestablish') }}</button>
	{!! Form::close() !!}

@endsection
