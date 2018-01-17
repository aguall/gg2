@extends('admin.layout')

@section('main')

	<link rel="stylesheet" href="/admin/chosen/chosen.min.css">

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/advertising">Реклама</a></li>
				<li><a href="/master/advertising/banners">Баннеры</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
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
		
				@if( isset($rotation) && count($rotation) )
					<div class="alert alert-info">
						Есть возможность выбора баннеров для ротации (случайный вывод из определенного набора рекламных баннеров) для зоны в шаблоне <strong>{{ mb_strtolower($zones[$post->zone], 'UTF-8') }}</strong>.
					</div>
					<div class="form-group">
						{!! Form::label('rotation', 'Баннеры для ротации', ['class' => 'col-sm-2 control-label']) !!}
						<div class="col-sm-5">
							<select multiple="multiple" name="rotation[]" id="rotation" class="form-control rotation">
								<option value=""></option>
								@foreach( $rotation as $key => $value )
									<option value="{{ $key }}" @if( is_array(unserialize($post->rotation)) && in_array($key, unserialize($post->rotation)) ) selected @endif>{{ $value }}</option>
								@endforeach
							</select>
						</div>
					</div>
				@endif

				<div class="form-group">
					{!! Form::label('zone', 'Показывать баннер', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						{!! Form::select('visible', [ 0 => 'Нет', 1 => 'Да'], $post->visible, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('zone', 'Зона отображения', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						{!! Form::select('zone', $zones, $post->zone, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('description', 'Краткое описание', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						{!! Form::text('description',  $post->description, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('include_urls', 'Шаблоны URL', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						<textarea name="include_urls" id="include_urls" class="form-control" rows="4" placeholder="Каждый шаблон на новой строке">{{ $post->include_urls }}</textarea>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('exclude_urls', 'Шаблоны исключений URL', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						<textarea name="exclude_urls" id="exclude_urls" class="form-control" rows="4" placeholder="Каждый шаблон на новой строке">{{ $post->exclude_urls }}</textarea>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('code', 'HTML-код баннера', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-10">
						<textarea name="code" id="code" class="form-control" rows="4">{{ $post->code }}</textarea>
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
	<script src="/admin/chosen/chosen.jquery.min.js"></script>
	<script>
		$(function(){
			
			// Multiselect
			$('.rotation').chosen({
				placeholder_text_multiple: 'Выберите баннеры для ротации...'
			});
		});
	</script>
	
@stop