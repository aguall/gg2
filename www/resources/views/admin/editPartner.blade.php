@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/advertising">Реклама</a></li>
				<li><a href="/master/advertising/friends-and-partners">Друзья и партнеры</a></li>
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
				
				{!! Form::open(['class' => 'form-horizontal', 'role' => 'form', 'files' => true]) !!}
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="input-group">
								<span class="input-group-btn">
									<span class="btn btn-primary btn-file">
										<i class="fa fa-picture-o"></i> <input type="file" name="image" />
									</span>
								</span>
								<input type="text" class="form-control" id="image" value="{{ $post->image }}" placeholder="Выбирите логотип партнера..." readonly>
							</div>
						</div>
					</div>

					@if( !empty($post->image) )
						<style>
							.panel-default{ margin-bottom: 0 !important; }
						</style>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-3">
								<div class="panel panel-default image-prev">
									<div class="panel-body">
										<img src="/uploads/partners/{{ $post->image }}" alt="{{{ $post->description }}}" />
									</div>
								</div>
							</div>
						</div>
					@endif

					<div class="form-group">
						{!! Form::label('description', 'Краткое описание', ['class' => 'col-sm-2 control-label']) !!}
						<div class="col-sm-10">
							{!! Form::text('description',  $post->description, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('url', 'Ссылка на сайт партнера', ['class' => 'col-sm-2 control-label']) !!}
						<div class="col-sm-10">
							{!! Form::text('url',  $post->url, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('visible', 'Показывать партнера', ['class' => 'col-sm-2 control-label']) !!}
						<div class="col-sm-10">
							{!! Form::select('visible', [0 => 'Нет', 1 => 'Да'], $post->visible, ['class' => 'form-control']); !!}
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
		// File input
		$(document).on('change', '.btn-file :file', function(){
			var input = $(this),
			numFiles = input.get(0).files ? input.get(0).files.length : 1,
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			input.trigger('fileselect', [numFiles, label]);
		});
		$(document).ready(function(){
			$('.btn-file :file').on('fileselect', function(event, numFiles, label){
				var input = $(this).parents('.input-group').find(':text'),
					log = numFiles > 1 ? numFiles + ' files selected' : label;
				
				if( input.length )
					input.val(log);
				else
					if( log ) 
						alert(log);
			});
		});
	</script>
	
@stop