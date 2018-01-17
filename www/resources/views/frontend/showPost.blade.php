@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'blog' . '/' . $category->slug) }}">{{ $category->name }}</a></li>
		<li class="active">
			@if( !empty($page->name) )
				{{ $page->name }}
			@elseif( !empty($page->name_ru) )
				(RU) {{ $page->name_ru }}
			@endif
		</li>
	</ol>
	<h1 class="page-title">
		@if( !empty($page->name) )
			{{ $page->name }}
		@elseif( !empty($page->name_ru) )
			(RU) {{ $page->name_ru }}
		@endif
	</h1>
	<div class="user-content">
		@if( !empty($page->body) )
			{!! $page->body !!}
		@else
			@if( LaravelLocalization::getCurrentLocale() == 'en' )
				Sorry, this entry is only available in <a href="{{ LaravelLocalization::getLocalizedURL('ru', 'blog/' . HTML::getLocalizedSegment(2) . '/' . $page->slug) }}">Russian</a>
			@elseif( LaravelLocalization::getCurrentLocale() == 'ua' )
				На жаль, цей запис доступний тільки на <a href="{{ LaravelLocalization::getLocalizedURL('ru', 'blog/' . HTML::getLocalizedSegment(2) . '/' . $page->slug) }}">Російській</a>
			@endif
		@endif
	</div>

@endsection