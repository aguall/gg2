@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<a href="/master/shop/parser/control" class="thumbnail text-center">
				<i class="fa fa-wrench fa-5x"></i>
				
				@if( !empty($shopsXMLCount) )
					<span class="counter">{{ $shopsXMLCount }}</span>
				@endif
				
				<div class="title">Управление</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/shop/parser/process" class="thumbnail text-center">
				<i class="fa fa-history fa-5x"></i>
				<div class="title">Парсинг данных</div>
			</a>
		</div>
	</div>

@stop