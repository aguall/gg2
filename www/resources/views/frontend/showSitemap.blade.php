@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		<li class="active">{{ $page->name }}</li>
	</ol>
	<h1 class="page-title">{{ $page->name }}</h1>
	<div class="sitemap">
		
		<!-- Страницы -->
		@if( count($sitemapPages) )
			<div class="sitemap-title">{{ trans('design.pages') }}</div>
			<ul class="sitemap-list">
				@foreach( $sitemapPages as $item )
					@if( $item->name )
						<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, $item->slug) }}">{{ $item->name }}</a></li>
					@endif
				@endforeach
			</ul>
		@endif
		
		<!-- Блог -->
		@if( count($sitemapBlog) )
			<div class="sitemap-title">{{ trans('design.blog') }}</div>
			<ul id="blog-list">
				@foreach( $sitemapBlog as $sitemapBlogCategory )
					<li>
						@if( $sitemapBlogCategory->name )
							<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/blog/' . $sitemapBlogCategory->slug) }}">{{ $sitemapBlogCategory->name }}</a>
						@endif
						@if( count($sitemapBlogCategory->posts) )
							<ul>
								@foreach( $sitemapBlogCategory->posts as $sitemapBlogPost )
									@if( $sitemapBlogPost->name )
										<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/blog/' . $sitemapBlogCategory->slug . '/' . $sitemapBlogPost->slug ) }}">{{ $sitemapBlogPost->name }}</a></li>
									@endif
								@endforeach
							</ul>
						@endif
					</li>
				@endforeach
			</ul>
		@endif
		
		<!-- Каталог товаров -->
		@if( count($sitemapProductsCategories) )
			<div class="sitemap-title">{{ strip_tags(trans('design.catalog')) }}</div>
			<ul id="category-list">
				{{ HTML::categories_tree( $sitemapProductsCategories, $currentLocale ) }}
				<!-- SEO -->
				@if( count($sitemapProductsCategoriesSEO) )
					@foreach( $sitemapProductsCategoriesSEO as $item )
						@if( $item->name )
							<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, $item->slug) }}">{{ $item->name }}</a></li>
						@endif
					@endforeach
				@endif
			</ul>
		@endif
		
		<!-- Магазины -->
		@if( count($sitemapShops) )
			<div class="sitemap-title">{{ trans('design.shops') }}</div>
			<ul class="sitemap-list">
				@foreach( $sitemapShops as $item )
					<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/shops/' . $item->slug) }}">{{ $item->name }}</a></li>
				@endforeach
			</ul>
		@endif
	
	</div>
	
	@if( !empty($page->body) )
		<div class="user-content">
		  {!! $page->body !!}
		</div>
	@endif

@endsection