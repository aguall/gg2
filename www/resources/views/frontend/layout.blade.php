<!DOCTYPE html>
<html lang="@if( $currentLocale != 'ua' ){{ $currentLocale }}@else{{ 'uk' }}@endif">
<head>
	<meta charset="utf-8" />
	
	@if( isset( $page->meta_title ) )
		<title>{{ $page->meta_title }} @if( isset( $site_offline ) && $site_offline == 1 )OFFLINE @endif</title>
	@endif
	
	@if( isset( $page->meta_keywords ) )
		<meta name="keywords" content="{{ $page->meta_keywords }}">
	@endif
	
	@if( isset( $page->meta_description ) )
		<meta name="description" content="{{ $page->meta_description }}">
	@endif
	
	<meta name="robots" content="{{ $robotsIndex }},FOLLOW">

	<link href="/frontend/css/style.css" rel="stylesheet">
	<link href="/favicon.ico" rel="icon" type="image/x-icon">
	<link href="{!! $canonicalUrl !!}" rel="canonical">
	
	<script src='https://www.google.com/recaptcha/api.js?hl={{ $currentLocale }}'></script>
	<script>
		(function(i,s,o,g,r,a,m){
			i["esSdk"] = r;
			i[r] = i[r] || function() {
				(i[r].q = i[r].q || []).push(arguments)
			}, a=s.createElement(o), m=s.getElementsByTagName(o)[0]; a.async=1; a.src=g;
			m.parentNode.insertBefore(a,m)}
		) (window, document, "script", "https://esputnik.com/scripts/v1/public/scripts?apiKey=eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI0NTI0ZWZhYTJkYzI2MGRmYTM4YTE1NDBlMWI2YTUwYzhlZTQxNDA3M2MwMGM4ODY0Y2U2MDI3YmRiOTU0NTFlMmZhZWZkMTE0OGY1Zjc3OTcyNTUyZmJjOTMzZGM3MDExNWNmZTUxMTU5Mzg3MmJkYjQyYzUyZDUzOTAwZWE1MmFiY2JhYjMzMzA3MjZlNThhMTYyYjUyNGI2ODMwZSJ9.O5RXYaqgmG7oHvN8GbANKte39DpiOdaHXWWi6XCBqKKlQPtLPPwpkNzxI5wzCdr2Cxfza3w1IdKFs9Kbp_7aAw&domain=DBEBBC8B-4203-4D10-B5AE-E49703A75E72", "es");
		es("pushOn");
		
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-85890229-1', 'auto');
		ga('send', 'pageview');
	</script>
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
	<div class="wrapper">
		<header class="header">
			<div class="header-container">
				
				<!-- Start First menu -->
				<nav class="menu">
					@if( count($firstMenu) > 0 )
						<ul>
							@foreach($firstMenu as $item)
								<li @if( HTML::getLocalizedSegment(1) == $item->slug || (HTML::getLocalizedSegment(1) . '/' . HTML::getLocalizedSegment(2)) == $item->slug ) class="active" @endif>
									<a href="{{ !empty($item->slug) ? LaravelLocalization::getLocalizedURL($currentLocale, $item->slug) : LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ $item->name }}</a>
								</li>
							@endforeach
						</ul>
					@endif
				</nav>
				<!-- End First menu -->
				
				<!-- Start Site serach -->
				{!! Form::open(['url' => LaravelLocalization::getLocalizedURL($currentLocale, 'search'), 'class' => 'search']) !!}
					<input type="search" name="search" placeholder="{{ trans('design.input_site_search') }}" minlength="3" required autocomplete="off" value="{{ !empty(Input::get('search')) ? Input::get('search') : '' }}" />
					<button type="submit"></button>
				{!! Form::close() !!}
				<!-- End Site search -->
				
				<!-- Start Languages -->
				<div class="languages">
					<a href="#" class="current">{{ $currentLocale }}</a>
					<ul>
						<li>
							@if( $currentLocale == 'ru' )
								<span>ru</span>
							@else
								<a href="{{ LaravelLocalization::getNonLocalizedURL() }}">ru</a>
							@endif
						</li>
						<li>
							@if( $currentLocale == 'ua' )
								<span>ua</span>
							@else
								<a href="{{ LaravelLocalization::getLocalizedURL('ua') }}">ua</a>
							@endif
						</li>
						<li>
							@if( $currentLocale == 'en' )
								<span>en</span>
							@else
								<a href="{{ LaravelLocalization::getLocalizedURL('en') }}">en</a>
							@endif
						</li>
					</ul>
				</div>
				<!-- End Languages -->
				
				@if( $user )
					<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'profile') }}" class="profile">{{ trans('design.profile') }}</a>
				@else
					<a href="#form-login" class="login">{{ trans('design.login_text_1') }}</a>
				@endif
				
				<div class="clear"></div>
				<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}" class="logo">
					<img src="/frontend/img/logo.png" alt="logo" />
				</a>
				
				@if( count($bannersTop) ) {!! $bannersTop[0] !!} @endif
				
				<nav class="second-menu">
					<ul>
						<li @if( HTML::getLocalizedSegment(1) == 'shops' ) class="active" @endif>
							<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'shops') }}">{{ trans('design.shops') }}</a>
						</li>
						@foreach($secondMenu as $item)
							<li @if( HTML::getLocalizedSegment(1) == 'blog' && HTML::getLocalizedSegment(2) == $item->slug ) class="active" @endif>
								<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'blog' . '/' . $item->slug) }}">{{ $item->name }}</a>
							</li>
						@endforeach
						<li><a href="http://forum.guns.ua/" target="_blank">{{ trans('design.forum') }}</a></li>
					</ul>
				</nav>
			</div>
		</header><!-- .header-->
		<div class="middle">
			<div class="container">
				<main class="content">
					
					@yield('main')
				
				</main><!-- .content -->
			</div><!-- .container-->
			<aside class="left-sidebar">
				<nav class="catalog">
					<span @if( isset($hasOptions) && $hasOptions ) class="hidden" @endif>{!! trans('design.catalog') !!}</span>
					<ul @if( isset($hasOptions) && $hasOptions ) class="hidden" @endif>
						@foreach( $productsCategories as $category )
							<li @if( HTML::getLocalizedSegment(2) == $category->slug ) class="active" @endif>
								<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'category' . '/' . $category->slug) }}">{{ $category->name }}</a>
								@if( count($category->children) )
									<ul>
										@foreach( $category->children as $subCategory )
											<li>
												<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'category' . '/' . $subCategory->slug) }}">{{ $subCategory->name }}</a>
											</li>
										@endforeach
									</ul>
								@endif
							</li>
						@endforeach
					</ul>
				</nav>
				
				@if( isset($hasOptions) && $hasOptions )
					<div class="filter-title">{{ trans('design.filters') }}</div>
					
					@if( Input::has('filter') )
						<div class="user-selection">
							<div class="title">{{ trans('design.you_choosed') }}</div>
							@foreach( $features as $feature )
								@if( isset(Input::get('filter')[$feature->id]) )
									<div class="filters-del">
										<span class="label">{{ $feature->name }}</span>
										@foreach( $feature->options as $option )
											@if( isset(Input::get('filter')[$feature->id]) && in_array($option->option_variant_id, Input::get('filter')[$feature->id]) )
												<a href="#" data-id="{{ $feature->id . $option->option_variant_id }}">
													<span class="name">{{ $option->product_option_value }}</span>
													<span class="count">({{ $option->product_count }})</span>
												</a>
											@endif
										@endforeach
									</div>
								@endif
							@endforeach
							<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, Request::path()) }}" class="filters-del-all">{{ trans('design.remove_all_filters') }}</a>
						</div>
					@endif
					
					<form method="GET">
						@foreach( $features as $feature )
							@if( count( $feature->options ) )
								<div class="filters-block">
									<div class="title">{{ $feature->name }}</div>
									<div class="scroll-pane">
										@foreach( $feature->options as $option )
											<label>
												<input type="checkbox" onChange="this.form.submit()" name="filter[{{ $feature->id }}][]" id="{{ $feature->id . $option->option_variant_id }}" value="{{ $option->option_variant_id }}" @if( isset(Input::get('filter')[$feature->id]) && in_array($option->option_variant_id, Input::get('filter')[$feature->id]) ) checked @endif/>
												<span class="checkbox"></span>
												<span class="name">{{ $option->product_option_value }}</span>
												<span class="count">({{ $option->product_count }})</span>
											</label>
										@endforeach
									</div>
								</div>
							@endif
						@endforeach
						
						@if( Input::has('sort') )
							<input type="hidden" name="sort" value="{{ Input::get('sort') }}" />
						@endif
					</form>
				@endif
				
				@if( HTML::getLocalizedSegment(1) != 'category' && HTML::getLocalizedSegment(1) != 'product' && count($shopsTop10) )
					<div class="top-10">
						<div class="title">
							<span class="title-text">{!! trans('design.top_10') !!}</span>
							<span class="rating-text">{{ trans('design.rating_shops', ['month' => trans('design.month_' . strtolower(date('F')))]) }}</span>
						</div>
						<ul class="shops">
							@foreach( $shopsTop10 as $key => $value )
								<li>
									<div class="shop-name">
										<span>{{ $key + 1 }}</span>
										<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'shops' . '/' . $value->slug) }}">
											{{ $value->name }}
										</a>
										<div class="clear"></div>
									</div>
									<div class="shop-rating">
										@if( $value->logo )
											<img src="/uploads/shops/{{ $value->logo }}" alt="{{{ $value->name }}}" />
										@else
											<img src="http://placehold.it/70x25" alt="{{{ $value->name }}}" />
										@endif
										<span>{{ $value->rating }}</span>
										<div class="clear"></div>
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				@endif
				
				@if( count($bannersLeft) )
					@foreach( $bannersLeft as $banner )
						<div class="banner-left">
							{!! $banner !!}
						</div>
					@endforeach
				@endif

			</aside><!-- .left-sidebar -->
			<aside class="right-sidebar">
				<div class="offers">{{ trans('design.offers_site') }}: <strong>{{ $offers }}</strong></div>
				<div class="banner-right">
					<div id="fb-root"></div>
					<script>
						(function(d, s, id){
							var js, fjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) return;
							js = d.createElement(s); js.id = id;
							js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.5";
							fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));
					</script>
					<div class="fb-page" data-href="https://www.facebook.com/&#x41f;&#x435;&#x440;&#x432;&#x430;&#x44f;-&#x423;&#x43a;&#x440;&#x430;&#x438;&#x43d;&#x441;&#x43a;&#x430;&#x44f;-&#x41e;&#x440;&#x443;&#x436;&#x435;&#x439;&#x43d;&#x430;&#x44f;-&#x411;&#x430;&#x437;&#x430;-GUNSUA-112298102184304/" data-tabs="timeline" data-width="230" data-height="300" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
				</div>

				@if( count($bannersRight) )
					@foreach( $bannersRight as $banner )
						<div class="banner-right">
							{!! $banner !!}
						</div>
					@endforeach
				@endif

			</aside><!-- .right-sidebar -->
		</div><!-- .middle-->
	</div><!-- .wrapper -->
	<footer class="footer">
		<div class="footer-container">
			<div class="friends">
				
				@if( count($partners) )
					<div class="title">
						{!! trans('design.friends_and_partners') !!}
					</div>
					<ul>
						@foreach( $partners as $partner )
							<li>
								@if( $partner->url )
									<a href="{!! $partner->url !!}" target="_blank" rel="nofollow">
								@endif
									@if( $partner->image )
										<img src="/uploads/partners/{{ $partner->image }}" alt="{{{ $partner->description }}}" />
									@else
										<img src="http://placehold.it/123x50" alt="{{{ $partner->description }}}" />
									@endif
								@if( $partner->url )
									</a>
								@endif
							</li>
						@endforeach
					</ul>
				@endif
				
				<div class="clear"></div>
			</div>
		</div>
		<div class="banner-bottom">
			@if( count($bannersBottom) ) {!! $bannersBottom[0] !!} @endif
		</div>
		<div class="footer-bottom">
			<div class="footer-container">
				<div class="copyright">
					Guns.ua © 2010-{{ date('Y') }}<br/>
					{{ trans('design.command') }} <a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">guns.ua</a>
				</div>
				<div class="iua">
					<!-- Yandex.Metrika counter -->
					<script type="text/javascript">
						(function (d, w, c) {
							(w[c] = w[c] || []).push(function() {
								try {
									w.yaCounter35074195 = new Ya.Metrika({
										id:35074195,
										clickmap:true,
										trackLinks:true,
										accurateTrackBounce:true,
										webvisor:true
									});
								} catch(e) { }
							});

							var n = d.getElementsByTagName("script")[0],
								s = d.createElement("script"),
								f = function () { n.parentNode.insertBefore(s, n); };
							s.type = "text/javascript";
							s.async = true;
							s.src = "https://mc.yandex.ru/metrika/watch.js";

							if (w.opera == "[object Opera]") {
								d.addEventListener("DOMContentLoaded", f, false);
							} else { f(); }
						})(document, window, "yandex_metrika_callbacks");
					</script>
					<noscript>
						<div>
							<img src="https://mc.yandex.ru/watch/35074195" style="position:absolute; left:-9999px;" alt="" />
						</div>
					</noscript>
					<!-- /Yandex.Metrika counter -->
					<!-- Yandex.Metrika informer -->
					<a href="https://metrika.yandex.ua/stat/?id=35074195&amp;from=informer" target="_blank" rel="nofollow">
						<img src="https://informer.yandex.ru/informer/35074195/1_0_FFFFFFFF_EFEFEFFF_0_pageviews" style="width:80px; height:15px; border:0;" alt="Яндекс.Метрика" />
					</a>
					<!-- /Yandex.Metrika informer -->
					<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'sitemap') }}" class="sitemap-link">{{ trans('design.sitemap') }}</a>
				</div>
				<div class="error-text">
					{!! trans('design.message_footer') !!}
					<a href="//orphus.ru" id="orphus" target="_blank" rel="nofollow">
						<img alt="Система Orphus" src="/frontend/js/orphus/orphus.gif" />
					</a>
				</div>
			</div>
		</div>
	</footer><!-- .footer -->
	
	<!-- Login Users -->
	{!! Form::open(['url' => LaravelLocalization::getLocalizedURL($currentLocale, 'login'), 'class' => 'white-popup-block mfp-hide', 'id' => 'form-login']) !!}
		<div class="title">{!! trans('design.login_text_2') !!}</div>
		<div class="group">
			<div class="message"></div>
			<input type="text" name="login" placeholder="{{ trans('design.input_login') }}" required />
			<input type="password" name="password" placeholder="{{ trans('design.input_password') }}" required />
			<button type="submit">{{ trans('design.login_text_3') }}</button>
		</div>
		<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'password/email') }}" class="link">{{ trans('design.forgot_password') }}</a>
		<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'register') }}" class="link">{{ trans('design.user_registration') }}</a>
		<div class="social">
			<span>{!! trans('design.login_text_4') !!}</span>
			<ul>
				<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'socialite/vkontakte') }}" class="vk"></a></li>
				<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'socialite/facebook') }}" class="facebook"></a></li>
				<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'socialite/google') }}" class="gplus"></a></li>
			</ul>
		</div>
	{!! Form::close() !!}
	
	<!-- Made by Fedor Semenov https://vk.com/fedor_semenov -->
	
	<link href="/frontend/css/magnific-popup.css" property="stylesheet" rel="stylesheet">
	<link href="/frontend/css/jquery.jscrollpane.css" property="stylesheet" rel="stylesheet">
	<link href="/frontend/css/jquery.treeview.css" property="stylesheet" rel="stylesheet">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="/frontend/js/jquery.magnific-popup.min.js"></script>
	<script src="/frontend/js/jquery.jscrollpane.min.js"></script>
	<script src="/frontend/js/jquery.mousewheel.js"></script>
	<script src="/frontend/js/jquery.treeview.js"></script>
	<script src="/frontend/js/orphus/orphus_{{ $currentLocale }}.js"></script>
	
	@if( HTML::getLocalizedSegment(1) == 'profile' )
		<script src="/frontend/tinymce/tinymce.min.js"></script>
	@endif
	
	<script src="/frontend/js/app.js"></script>
</body>
</html>