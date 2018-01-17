@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<a href="/master/shop/categories" class="thumbnail text-center">
				<i class="fa fa-code-fork fa-5x"></i>
				
				@if( !empty($categoriesCount) )
					<span class="counter">{{ $categoriesCount }}</span>
				@endif
				
				<div class="title">Категории товаров</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/shop/filters" class="thumbnail text-center">
				<i class="fa fa-filter fa-5x"></i>
				<span class="counter">2</span>
				<div class="title">Параметры фильтрации</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/shop/products" class="thumbnail text-center">
				<i class="fa fa-shopping-basket fa-5x"></i>
				
				@if( !empty($productsCount) )
					<span class="counter">{{ $productsCount }}</span>
				@endif
				
				<div class="title">Товары</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/shop/parser" class="thumbnail text-center">
				<i class="fa fa-file-excel-o fa-5x"></i>
				<span class="counter">2</span>
				<div class="title">XML парсер</div>
			</a>
		</div>
	</div>

@stop