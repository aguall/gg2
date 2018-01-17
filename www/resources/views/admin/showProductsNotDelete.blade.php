@extends('admin.layout')

@section('main')
	
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li><a href="/master/shop/products">Товары</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@if( count($products) > 0 )
					
				<div class="title-module">
					<div class="pull-left">
						<strong>Название</strong>
					</div>
					<div class="pull-right">
						<strong class="text-control">Управление</strong>
					</div>
					<div class="clear"></div>
				</div>
				<div class="list-group">
						
					@foreach($products as $product)
						<div class="list-group-item" id="{{ $product->id }}">
							<div class="pull-left">
								<a href="/master/shop/products/prices/{{ $product->product_id }}/edit/{{ $product->product_shops_prices_id }}" class="item-element-menu">
									{{ $product->product_name }}
								</a>
							</div>
							<div class="pull-right">
								<div class="btn-group" role="group">
									<a href="/master/shop/products/prices/{{ $product->product_id }}/edit/{{ $product->product_shops_prices_id }}" class="btn btn-warning" title="Редактировать">
										<i class="fa fa-pencil"></i>
									</a>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					@endforeach
						
				</div>
				
				{!! $products->render() !!}
			@else
				<div class="alert alert-warning">Товарных позиций нет...</div>
			@endif
		</div>
	</div>
	
@stop