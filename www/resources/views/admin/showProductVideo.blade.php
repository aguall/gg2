@extends('admin.layout')

@section('main')
	
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li><a href="/master/shop/products">Товары</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<a href="/master/shop/products/edit/{{ $id }}" class="thumbnail text-center">
				<i class="fa fa-arrow-left fa-5x"></i>
				<div class="title">Назад к товару</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/shop/products/video/{{ $id }}/add" class="thumbnail text-center">
				<i class="fa fa-plus-circle fa-5x"></i>
				<div class="title">Добавить видеообзор</div>
			</a>
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
			
		</div>
	</div>

	{!! Form::open() !!}
		
		@if( count($video) > 0 )
			<div class="row">
				<div class="video">
					@foreach( $video as $item )
						<div class="col-sm-3">
							<div class="panel panel-default">
								<div class="panel-body">
									<iframe src="https://www.youtube.com/embed/{{ \App\Models\ProductVideo::videoCode( $item->video ) }}"></iframe>
								</div>
								<div class="panel-footer text-center">
									<input type="checkbox" name="check[]" value="{{ $item->id }}" />
									<div class="btn-group" role="group">
										<a href="/master/shop/products/video/{{ $id }}/edit/{{ $item->id }}" class="btn btn-warning" title="Редактировать информацию">
											<i class="fa fa-pencil"></i>
										</a>
										<button type="button" class="btn btn-danger delete" title="Удалить видеообзор" data-id="{{ $item->id }}">
											<i class="fa fa-times"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
			
			{!! $video->render() !!}
			
			<div class="row">
				<div class="col-sm-12">
					<div class="select_form">
						<label id="check_all" class="link">Выбрать все</label>
						<select name="action" class="form-control">
							<option value="delete">Удалить</option>
						</select>
						<button type="submit" class="btn btn-success delete-all" disabled>Применить</button>
					</div>
				</div>
			</div>
		@else
			<div class="row">
				<div class="col-sm-12">
					<div class="alert alert-warning">Видеообзоры еще не добавлены</div>
				</div>
			</div>
		@endif
	
	{!! Form::close() !!}
	
	<script>
		$(function(){
			// Удаление видео
			$('.delete').click(function(){
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest('.panel').find('input[type="checkbox"][name*="check"]').prop('checked', true);
				$(this).closest('form').find('select[name="action"] option[value=delete]').prop('selected', true);
				$(this).closest('form').submit();
			})
			
			$('form').submit(function(){
				if( $('select[name="action"]').val() == 'delete' && !confirm('Подтвердите удаление') )
					return false;
			});

			// Выделить все
			$('#check_all').on('click', function(){
				$('input[type="checkbox"][name*="check"]:enabled').prop('checked', $('input[type="checkbox"][name*="check"]:enabled:not(:checked)').length > 0 );
				if( $('input[type="checkbox"][name*="check"]:checked').length )
					$('.delete-all').removeAttr('disabled');
				else
					$('.delete-all').attr('disabled', 'disabled');
			});
			
			// Активность кнопки "Удалить выбранные" 
			$('input[type="checkbox"][name*="check"]').change(function(){
				if( $('input[type="checkbox"][name*="check"]:checked').length )
					$('.delete-all').removeAttr('disabled');
				else
					$('.delete-all').attr('disabled', 'disabled');
			});
		});
	</script>
	
@stop