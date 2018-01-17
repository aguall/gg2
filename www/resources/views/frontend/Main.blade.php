@extends('frontend.layout')

@section('main')

	@if( empty(HTML::getLocalizedSegment(1)) || in_array(HTML::getLocalizedSegment(2), ['news', 'reviews', 'shares', 'video']) )
		<ul class="blog-category">
			@foreach($categories as $category)
				<li @if(HTML::getLocalizedSegment(1) == 'blog' && HTML::getLocalizedSegment(2) == $category->slug) class="active" @endif>
					<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'blog' . '/' . $category->slug) }}">
						{{ $category->name }}
					</a>
				</li>
			@endforeach
		</ul>
	@else
		<ol class="breadcrumb">
			<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
			<li class="active">{{ $page->name }}</li>
		</ol>
		<div class="page-title">{{ $page->name }}</div>
	@endif
	
	@if( count($posts) > 0 )	
		<ul class="posts">
			@foreach($posts as $post)
				<li>
					<div class="top">
						<div class="date">
							<span class="day">{{ date('d', strtotime($post->date)) }}</span>
							<span class="month">{{ trans('design.month_' . strtolower(date('F', strtotime($post->date)))) }}</span>
							<span class="year">{{ date('Y', strtotime($post->date)) }}</span>
						</div>
						@if( empty(HTML::getLocalizedSegment(1)) || in_array(HTML::getLocalizedSegment(2), ['news', 'reviews', 'shares', 'video']) )
							<span class="type {{ $post->category_slug }}" title="{{ trans('design.heading') }} {{ trans('design.' . $post->category_slug) }}"></span>
						@endif
					</div>
					<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'blog' . '/' . $post->category_slug . '/' . $post->slug) }}" class="thumb">
						@if( $post->image )
							<img src="/uploads/blog/{{ $post->image }}" alt="{{{ $post->name }}}" />
						@else
							<img src="http://placehold.it/160x160" alt="{{{ $post->name }}}" />
						@endif
					</a>
					<div class="info">
						<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'blog' . '/' . $post->category_slug . '/' . $post->slug) }}" class="title">
							@if( !empty($post->name) )
								{{ str_limit($post->name, $limit = 75, $end = '...') }}
							@else
								(RU) {{ str_limit($post->name_ru, $limit = 75, $end = '...') }}
							@endif
						</a>
						<div class="annotation">
							@if( !empty($post->annotation) )
								{{ str_limit($post->annotation, $limit = 210, $end = '...') }}
							@else
								@if( LaravelLocalization::getCurrentLocale() == 'en' )
									Sorry, this entry is only available in <a href="{{ LaravelLocalization::getLocalizedURL('ru', 'blog' . '/' . $post->category_slug . '/' . $post->slug) }}">Russian</a>
								@elseif( LaravelLocalization::getCurrentLocale() == 'ua' )
									На жаль, цей запис доступний тільки на <a href="{{ LaravelLocalization::getLocalizedURL('ru', 'blog' . '/' . $post->category_slug . '/' . $post->slug) }}">Російській</a>
								@endif
							@endif
						</div>
						<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'blog' . '/' . $post->category_slug . '/' . $post->slug) }}" class="more">
							{{ trans('design.more') }}
						</a>
					</div>
					<div class="clear"></div>
				</li>
			@endforeach
		</ul>
		<div class="text-center">
			{!! $posts->render() !!}
		</div>	
	@endif
	
	@if( !empty($page->body) )
		<div class="user-content">
			{!! $page->body !!}
		</div>
	@endif

@endsection