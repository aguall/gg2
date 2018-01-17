@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		<li class="active">{{ $page->name }}</li>
	</ol>
	<div class="page-title">{{ $page->name }}</div>
	<div class="search-results">
		
		@if( (isset($searchPagesResult) && count($searchPagesResult)) || (isset($searchShopsResult) && count($searchShopsResult)) || (isset($searchBlogResult) && count($searchBlogResult)) || (isset($searchProductsResult) && count($searchProductsResult)) )
			
			@if( isset($searchPagesResult) && count($searchPagesResult) )
				<div class="search-section">{{ trans('design.pages') }} ({{ $searchPagesResult->count() }})</div>
				<ol>
					@foreach($searchPagesResult as $item)
						<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, $item->slug) }}">{{ $item->name }}</a></li>
					@endforeach
				</ol>
			@endif
			
			@if( isset($searchShopsResult) && count($searchShopsResult) )
				<div class="search-section">{{ trans('design.shops') }} ({{ $searchShopsResult->count() }})</div>
				<ol>
					@foreach($searchShopsResult as $shop)
						<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'shops/' . $shop->slug) }}">{{ $shop->name }}</a></li>
					@endforeach
				</ol>
			@endif
			
			@if( isset($searchBlogResult) && count($searchBlogResult) )
				<div class="search-section">{{ trans('design.blog') }} ({{ $searchBlogResult->count() }})</div>
				<ol>
					@foreach($searchBlogResult as $post)
						<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'blog/' . $post->category_slug . '/' . $post->slug) }}">{{ $post->name }}</a></li>
					@endforeach
				</ol>
			@endif
			
			@if( isset($searchProductsResult) && count($searchProductsResult) )
				<div class="search-section">{{ trans('design.products') }} ({{ $searchProductsResult->count() }})</div>
				<ol>
					@foreach($searchProductsResult as $product)
						<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'product/' . $product->slug) }}">{{ $product->name }}</a></li>
					@endforeach
				</ol>
			@endif
		@else
			<div class="user-content">
				@if( Input::has('search') )
					{!! trans('design.message_search_none', [ 'search' => Input::get('search') ]) !!}
				@else
					{{ trans('design.message_search_null') }}
				@endif
			</div>
		@endif	
		
	</div>

@endsection