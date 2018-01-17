@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li><a href="/master/shop/filters">Параметры фильтрации</a></li>
				<li><a href="/master/shop/filters/features">Параметры</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="languages-container">
		<div class="row">
			<div class="col-md-12">
			
				@if(Session::has('success'))
					<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
				@endif
				
				@if( $errors->any() )
					<ul class="alert alert-danger">
						@foreach( $errors->all() as $error )
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				@endif
				
				{!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
				
					<div class="form-group">
						<div class="col-sm-12">
							{!! Form::select('feature_group_id', $groups, $post->feature_group_id, ['class' => 'form-control']); !!}
						</div>
					</div>
					
					<!-- Languages controls -->
					<div class="form-group">
						<ul class="languages-caption">
							<li class="col-md-3 active">
								<a href="#ru" class="thumbnail text-center">
									<i class="fa fa-language fa-5x"></i>
									<div class="title">Русский язык</div>
								</a>
							</li>
							<li class="col-md-3">
								<a href="#ua" class="thumbnail text-center">
									<i class="fa fa-language fa-5x"></i>
									<div class="title">Українська мова</div>
								</a>
							</li>
							<li class="col-md-3">
								<a href="#en" class="thumbnail text-center">
									<i class="fa fa-language fa-5x"></i>
									<div class="title">English language</div>
								</a>
							</li>
						</ul>
					</div>
					
					<!-- RU -->
					<div class="languages-content active">
						<div class="form-group">
							<div class="col-sm-12">
								{!! Form::text('name_ru',  $post->name_ru, ['class' => 'form-control', 'placeholder' => 'Название параметра']) !!}
							</div>
						</div>
					</div>
				
					<!-- UA -->
					<div class="languages-content">
						<div class="form-group">
							<div class="col-sm-12">
								{!! Form::text('name_ua',  $post->name_ua, ['class' => 'form-control', 'placeholder' => 'Название параметра']) !!}
							</div>
						</div>
					</div>
				
					<!-- EN -->
					<div class="languages-content">
						<div class="form-group">
							<div class="col-sm-12">
								{!! Form::text('name_en',  $post->name_en, ['class' => 'form-control', 'placeholder' => 'Название параметра']) !!}
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<button type="submit" class="btn btn-success">Сохранить</button>
						</div>
					</div>
				
				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
	<script>
		// Переключение языков
		$('ul.languages-caption').on('click', 'li:not(.active)', function(){
			$(this).addClass('active').siblings().removeClass('active').closest('div.languages-container')
			.find('div.languages-content').removeClass('active').eq($(this).index()).addClass('active');
		});
	</script>
	
@stop