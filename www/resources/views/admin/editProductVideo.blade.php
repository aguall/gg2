@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li><a href="/master/shop/products">Товары</a></li>
				<li><a href="/master/shop/products/video/{{ $id }}">Видеообзоры для товара: "{{ $productName }}"</a></li>
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
					
					<input type="hidden" name="product_id" value="{{ $id }}" />
					
					<div class="form-group">
						<div class="col-sm-12">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-video-camera" aria-hidden="true"></i>
								</span>
								{!! Form::text('video',  $post->video, ['class' => 'form-control', 'placeholder' => 'Ссылка на видео с YouTube']) !!}
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-12">
							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
								{!! Form::select('user_id', $users, $post->user_id, ['class' => 'form-control']); !!}
							</div>
						</div>
					</div>
					
					@if( !empty($post->video) )
						<div class="form-group">
							<div class="col-sm-3">
								<div class="panel panel-default video-prev">
									<div class="panel-body">
										<iframe src="https://www.youtube.com/embed/{{ $videoCode }}"></iframe>
									</div>
								</div>
							</div>
						</div>
					@endif
				
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
							{!! Form::label('name_ru', 'Название', ['class' => 'col-sm-2 control-label']) !!}
							<div class="col-sm-10">
								{!! Form::text('name_ru',  $post->name_ru, ['class' => 'form-control']) !!}
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('description_ru', 'Краткое описание', ['class' => 'col-sm-2 control-label']) !!}
							<div class="col-sm-10">
								<textarea name="description_ru" id="description_ru" class="form-control" rows="3">{{ $post->description_ru }}</textarea>
							</div>
						</div>
					</div>
				
					<!-- UA -->
					<div class="languages-content">
						<div class="form-group">
							{!! Form::label('name_ua', 'Название', ['class' => 'col-sm-2 control-label']) !!}
							<div class="col-sm-10">
								{!! Form::text('name_ua',  $post->name_ua, ['class' => 'form-control']) !!}
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('description_ua', 'Краткое описание', ['class' => 'col-sm-2 control-label']) !!}
							<div class="col-sm-10">
								<textarea name="description_ua" id="description_ua" class="form-control" rows="3">{{ $post->description_ua }}</textarea>
							</div>
						</div>	
					</div>
				
					<!-- EN -->
					<div class="languages-content">
						<div class="form-group">
							{!! Form::label('name_en', 'Название', ['class' => 'col-sm-2 control-label']) !!}
							<div class="col-sm-10">
								{!! Form::text('name_en',  $post->name_en, ['class' => 'form-control']) !!}
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('description_en', 'Краткое описание', ['class' => 'col-sm-2 control-label']) !!}
							<div class="col-sm-10">
								<textarea name="description_en" id="description_en" class="form-control" rows="3">{{ $post->description_en }}</textarea>
							</div>
						</div>	
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
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