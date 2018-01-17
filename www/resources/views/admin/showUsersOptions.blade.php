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
			<a href="/master/users/show" class="thumbnail text-center">
				<i class="fa fa-users fa-5x"></i>
				
				@if( !empty($usersCount) )
					<span class="counter">{{ $usersCount }}</span>
				@endif
				
				<div class="title">Пользователи</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/users/video" class="thumbnail text-center">
				<i class="fa fa-video-camera fa-5x"></i>
				
				@if( !empty($usersVideoCount) )
					<span class="counter">{{ $usersVideoCount }}</span>
				@endif
				
				<div class="title">Видео от пользователей</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/users/articles" class="thumbnail text-center">
				<i class="fa fa-file fa-5x"></i>
				
				@if( !empty($usersArticlesCount) )
					<span class="counter">{{ $usersArticlesCount }}</span>
				@endif
				
				<div class="title">Статьи от пользователей</div>
			</a>
		</div>
	</div>

@stop