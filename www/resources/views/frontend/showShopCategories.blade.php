@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		
		@foreach( $breadcrumb as $crumb )
			@if( $page->slug == $crumb->slug )
				<li class="active">{{ $crumb->name }}</li>
			@else
				<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'category' . '/' . $crumb->slug) }}">{{ $crumb->name }}</a></li>
			@endif
		@endforeach

	</ol>
	<h1 class="page-title">{{ $page->name }}</h1>
	<ul class="sub-categories">
		@foreach( $categories as $category )
			<li>
				<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'category' . '/' . $category->slug) }}" class="image">
					@if( $category->image )
						<img src="/uploads/shop/categories/{{ $category->image }}" alt="{{{ $category->name }}}" />
					@else
						<img src="http://placehold.it/175x140" alt="{{{ $category->name }}}" />
					@endif
				</a>
				<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'category' . '/' . $category->slug) }}" class="cat-name">
					{{ $category->name }}
				</a>
			</li>
		@endforeach
	</ul>
	
	@if( !empty($page->body) )
		<div class="text-catalog">
		  {!! $page->body !!}
		</div>
	@endif

@endsection