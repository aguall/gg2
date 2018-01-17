@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		<li class="active">{{ $page->name }}</li>
	</ol>
	<h1 class="page-title">{{ $page->name }}</h1>
	
	@if( count($shops) > 0 )
		<ul class="shops-all">
			@foreach( $shops as $key => $value )
				<li>
					<span class="number">{{ $key + 1 }}</span>
					<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'shops' . '/' . $value->slug) }}" class="logo">
						@if( $value->logo )
							<img src="/uploads/shops/{{ $value->logo }}" alt="{{{ $value->name }}}" title="{{{ $value->name }}}" />
						@else
							<img src="http://placehold.it/70x26" alt="{{{ $value->name }}}" title="{{{ $value->name }}}" />
						@endif
					</a>
					<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'shops' . '/' . $value->slug) }}" class="name">
						@if( !empty($value->name) )
							{{ $value->name }}
						@else
							(RU) {{ $value->name_ru }}
						@endif
					</a>
					<span class="rating">{{ $value->rating }}</span>
					<div class="clear"></div>
				</li>
			@endforeach
		</ul>
	@endif
	
	@if( !empty($page->body) )
		<div class="user-content">
		  {!! $page->body !!}
		</div>
	@endif

@endsection