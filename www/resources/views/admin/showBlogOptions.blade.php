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
			<a href="/master/blog/categories" class="thumbnail text-center">
				<i class="fa fa-code-fork fa-5x"></i>
				
				@if( !empty($categoriesCount) )
					<span class="counter">{{ $categoriesCount }}</span>
				@endif
				
				<div class="title">Рубрики</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/blog/posts" class="thumbnail text-center">
				<i class="fa fa-file-text fa-5x"></i>
				
				@if( !empty($postsCount) )
					<span class="counter">{{ $postsCount }}</span>
				@endif
				
				<div class="title">Записи</div>
			</a>
		</div>
	</div>

@stop