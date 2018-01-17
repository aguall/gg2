@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'shops') }}">{{ trans('design.shops') }}</a></li>
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
				Sorry, this entry is only available in <a href="{{ LaravelLocalization::getLocalizedURL('ru', 'shops/' . $page->slug) }}">Russian</a>
			@elseif( LaravelLocalization::getCurrentLocale() == 'ua' )
				На жаль, цей запис доступний тільки на <a href="{{ LaravelLocalization::getLocalizedURL('ru', 'shops/' . $page->slug) }}">Російській</a>
			@endif
		@endif
	</div>
	<div id="disqus_thread"></div>
	<script type="text/javascript">
		/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
		var disqus_shortname = 'guns-ua'; // required: replace example with your forum shortname

		/* * * DON'T EDIT BELOW THIS LINE * * */
		(function() {
			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		})();
	</script>

@endsection