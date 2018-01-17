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
			<a href="/master/advertising/banners" class="thumbnail text-center">
				<i class="fa fa-television fa-5x"></i>
				
				@if( !empty($bannersCount) )
					<span class="counter">{{ $bannersCount }}</span>
				@endif
				
				<div class="title">Баннеры</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/advertising/friends-and-partners" class="thumbnail text-center">
				<i class="fa fa-users fa-5x"></i>
				
				@if( !empty($friendsAndPartnersCount) )
					<span class="counter">{{ $friendsAndPartnersCount }}</span>
				@endif

				<div class="title">Друзья и партнеры</div>
			</a>
		</div>
	</div>

@stop