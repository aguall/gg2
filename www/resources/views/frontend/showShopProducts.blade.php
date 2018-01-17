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
	
	@if( count($products) )
		
		{!! Form::open(['url' => LaravelLocalization::getLocalizedURL($currentLocale, 'search'), 'class' => 'products-search']) !!}
			<input type="search" name="search" placeholder="{{ trans('design.search_by_goods') }}" minlength="3" required autocomplete="off" value="{{ !empty(Input::get('search')) ? Input::get('search') : '' }}" />
			<button type="submit"></button>
		{!! Form::close() !!}
		
		<div class="sorting">
			<div class="item f-left">
				<span>{{ trans('design.sorting') }}:</span><a href="#">{{ Input::has('sort') ? $sorting[Input::get('sort')] : $sorting['offers_desc'] }}</a>
				<ul>
					@foreach( $sorting as $key => $value )
						@if( Input::get('sort') != $key )
							@if( !Input::has('sort') && $key == 'offers_desc' )
								<?php unset($sorting[$key]); ?>
							@else
								<li><a href="{{ \App\Models\Product::addOrUpdateUrlParam('sort', $key) }}">{{ $value }}</a></li>
							@endif
						@endif
					@endforeach
				</ul>
			</div>
			<div class="clear"></div>
		</div>
		<ul class="products-list">
			
			@foreach( $products as $product )
				<li @if( $product->product_advertising ) class="advertising" @endif>
					<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'product' . '/' . $product->product_url) }}" class="thumb">
						@if( count($product->product_images) )
							<img src="/uploads/shop/products/thumbs/{{ App\Models\ProductImage::getActiveImage( $product->product_images ) }}" alt="{{{ $product->product_name }}}" />
						@else
							<img src="http://placehold.it/155x100" alt="{{{ $product->product_name }}}" />
						@endif
					</a>
					<div class="info">
						<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'product' . '/' . $product->product_url) }}" class="title">
							{{ $product->product_name }}
						</a>
						@if( count($product->product_options) )
							<div class="properties">
								@foreach( $product->product_options as $key => $value )
									@if( $key <= 1 )
										{{ $value->option_name }} - {{ $value->option_value }} @if( $key == 0 ) • @endif
									@endif
								@endforeach
							</div>
						@endif
						<div class="count-media">
							<span class="no-padding">{{ trans('design.video') }} ({{ count($product->product_video) }})</span>
						</div>
					</div>
					<div class="price-block">
						@if( $product->product_offers )
							@if( $product->product_price )
								<div class="text">{{ trans('design.price_from') }}:</div>
								<div class="price">
									{{ $product->product_price }}<span> {{ trans('design.uah') }}</span>
								</div>
							@else
								<div class="price"><span>{{ trans('design.price_is_given') }}</span></div>
							@endif
							<div class="proposals">{{ trans('design.proposals') }}: {{ $product->product_offers }}</div>
							<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'product' . '/' . $product->product_url) }}" class="compare-btn">{{ trans('design.compare_prices') }}</a>
						@else
							<div class="no-proposals">{{ trans('design.no_proposals') }}</div>
						@endif
					</div>
					<div class="clear"></div>
				</li>
			@endforeach
			
		</ul>
		<div class="text-center">
			{!! $products->appends(['filter' => Input::get('filter'), 'sort' => Input::get('sort')])->render() !!}
		</div>
	@endif
	
	@if( !empty($page->body) )
		<div class="text-catalog">
			{!! $page->body !!}
		</div>
	@endif

@endsection