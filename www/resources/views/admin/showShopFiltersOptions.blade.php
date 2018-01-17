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
			<a href="/master/shop/filters/features-groups" class="thumbnail text-center">
				<i class="fa fa-cogs fa-5x"></i>
				
				@if( !empty($featuresGroupsCount) )
					<span class="counter">{{ $featuresGroupsCount }}</span>
				@endif
				
				<div class="title">Группы параметров</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/shop/filters/features" class="thumbnail text-center">
				<i class="fa fa-cog fa-5x"></i>
				
				@if( !empty($featuresCount) )
					<span class="counter">{{ $featuresCount }}</span>
				@endif
				
				<div class="title">Параметры</div>
			</a>
		</div>
	</div>

@stop