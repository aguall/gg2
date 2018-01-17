@extends('frontend.layout')

@section('main')

	<ol class="breadcrumb">
		<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/') }}">{{ trans('design.home') }}</a></li>
		
		@foreach( $breadcrumb as $crumb )
			<li><a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, 'category' . '/' . $crumb->slug) }}">{{ $crumb->name }}</a></li>
		@endforeach

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
	<div class="product">
		<div class="thumb">
			@if( count($page->product_images) )
				@foreach( $page->product_images as $item )
					@if( $item->active )
						<a href="/uploads/shop/products/{{ $item->image }}" class="photo">
							<img src="/uploads/shop/products/thumbs/{{ $item->image }}" alt="{{{ $page->name }}}" />
						</a>
					@else
						<a href="/uploads/shop/products/{{ $item->image }}" class="photo hidden"></a>
					@endif
				@endforeach
			@else
				<div class="photo">
					<img src="http://placehold.it/250x165" alt="{{{ $page->name }}}" />
				</div>
			@endif
			<span class="photo">{{ trans('design.all_photos') }} ({{ count($page->product_images) }})</span>
			<span class="video">{{ trans('design.video_review') }} ({{ count($page->product_video) }})</span>
		</div>
		@if( count($page->product_options) )
			<ul class="options">
				@foreach( $page->product_options as $item )
					<li><span>{{ $item->option_name }}:</span>{{ $item->option_value }}</li>
				@endforeach
			</ul>
		@endif
		<div class="clear"></div>
		<div class="tabs">
			<ul class="caption">
				<li><span>{{ trans('design.prices') }}</span></li>
				@if( $page->body )
					<li><span>{{ trans('design.description') }}</span></li>
				@endif
				@if( count($page->product_options) )
					<li><span>{{ trans('design.characteristics') }}</span></li>
				@endif
				@if( count($page->product_video) )
					<li><span>{{ trans('design.video') }}</span></li>
				@endif
				<li><span>{{ trans('design.comments') }}</span></li>
			</ul>
			<div class="tabs-content">
				@if( count($page->product_prices) )
					<ul class="stores">
						@foreach( $page->product_prices as $item )
							<li>
								<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/shops/' . $item->shop_slug) }}" class="store-logo">
									@if( $item->shop_logo )
										<img src="/uploads/shops/{{ $item->shop_logo }}" title="{{{ $item->shop_name }}}" alt="{{{ $item->shop_name }}}" />
									@else
										<img src="http://placehold.it/95x30" title="{{{ $item->shop_name }}}" alt="{{{ $item->shop_name }}}" />
									@endif
								</a>
								<div class="product-name">
									{{ $item->shop_product_name }}
								</div>
								<div class="price-block">
									@if( (int)$item->shop_product_price )
										<div class="price">{{ ceil($item->shop_product_price) }}<span> {{ trans('design.uah') }}</span></div>
									@else
										<div class="price"><span>{{ trans('design.price_is_given') }}</span></div>
									@endif
									<a href="#" data-href="{{ $item->shop_product_link }}" data-shop-id="{{ $item->shop_id }}" data-token="{{ csrf_token() }}" class="in-store">
										{{ trans('design.in_shop') }}
									</a>
								</div>
								<div class="clear"></div>
							</li>
						@endforeach
					</ul>
				@else
					<div class="no-proposals-container">
						<div>{{ trans('design.message_no_proposals') }}</div>
						<a href="{{ $currentProductCategoryURL }}">{{ trans('design.back_to_catalog') }}</a>
					</div>
				@endif
			</div>
			@if( $page->body )
				<div class="tabs-content">
					<div class="user-content">
						{!! $page->body !!}
					</div>
				</div>
			@endif
			@if( count($optionByGroups) )
				<div class="tabs-content">
					<div class="full-options">
						@foreach( $optionByGroups as $groupName => $options )
							@if( count($options) )
								<div class="title">{{ $groupName }}:</div>
								<table>
									@foreach( $options as $option )
										<tr>
											<td class="name">{{ $option->option_name }}</td>
											<td>{{ $option->option_value }}</td>
										</tr>
									@endforeach
								</table>
							@endif
						@endforeach
					</div>
				</div>
			@endif
			@if( count($page->product_video) )
				<div class="tabs-content">
					<div class="user-content">
						@foreach( $page->product_video as $item )
							@if( $item->name )
								<p><strong>{{ $item->name }}</strong></p>
							@endif
							<p><iframe src="https://www.youtube.com/embed/{{ App\Models\ProductVideo::videoCode( $item->video ) }}"></iframe></p>
							@if( $item->description )
								<p>{!! $item->description !!}</p>
							@endif
						@endforeach
					</div>
				</div>
			@endif
			<div class="tabs-content">
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
			</div>
		</div><!-- .tabs -->
	</div>

@endsection