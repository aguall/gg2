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
			<a href="/master/shop/products/edit/{{ Request::segment(5) }}" class="thumbnail text-center">
				<i class="fa fa-arrow-left fa-5x"></i>
				<div class="title">Назад к товару</div>
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
	
	{!! Form::open(['files' => true, 'class' => 'images-upload']) !!}
	
		<div class="row">
			<div class="col-sm-10">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-btn">
							<span class="btn btn-primary btn-file">
								<i class="fa fa-picture-o"></i> <input type="file" name="images[]" accept="image/gif, image/jpeg, image/png" multiple />
							</span>
						</span>
						<input type="text" class="form-control" id="image" value="" placeholder="Выбирите изображения..." readonly>
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<button type="submit" class="btn btn-success btn-block">Загрузить</button>
			</div>
		</div>
	
	{!! Form::close() !!}
	
	{!! Form::open(['class' => 'images-list']) !!}
		
		@if( count($images) > 0 )
			<div class="row">
				<div class="images">
					@foreach( $images as $image )
						<div class="col-sm-3">
							<div class="panel panel-default">
								<div class="panel-body">
									<img src="/uploads/shop/products/thumbs/{{ $image->image }}" alt="" class="item-sortable" />
								</div>
								<div class="panel-footer text-center">
									<input type="checkbox" name="check[]" value="{{ $image->id }}" />
									<div class="btn-group" role="group">
										<button type="button" class="btn {{ !empty($image->active) ? 'btn-success' : 'btn-default' }} active-image" title="Сделать главным изображением товара" data-id="{{ $image->id }}">
											<i class="fa fa-tag"></i>
										</button>
										<button type="button" class="btn btn-danger delete" title="Удалить изображение" data-id="{{ $image->id }}">
											<i class="fa fa-times"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
			
			{!! $images->render() !!}
			
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
					<div class="alert alert-warning">Изображения еще не добавлены</div>
				</div>
			</div>
		@endif
	
	{!! Form::close() !!}
	
	<script>
		$(function(){
			// Удаление изображения
			$('.delete').click(function(){
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest('.panel').find('input[type="checkbox"][name*="check"]').prop('checked', true);
				$(this).closest('form').find('select[name="action"] option[value=delete]').prop('selected', true);
				$(this).closest('form').submit();
			})

			// Удаление изображений
			$('form.images-list').submit(function(){
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
			
			// Главное изображение товара
			$('.active-image').on('click', function(event){
				
				event.preventDefault();
				
				var currentImageID = $(this).data('id'),
					data = { _token: '{{ csrf_token() }}', id: currentImageID, action: 'active' };
				
				if( $(this).hasClass('btn-success') )
					$(this).removeClass('btn-success').addClass('btn-default');
				else
					$(this).removeClass('btn-default').addClass('btn-success');
				
				$('.images .btn-group').each(function(){
					if( $(this).find('.active-image').data('id') != currentImageID )
						$(this).find('.active-image').removeClass('btn-success').addClass('btn-default');
				});
			
				$.post('{{ URL::to("master/shop/products/images/" . (int)Request::segment(5)) }}', data, function(data){ console.log(data.message) }, 'JSON');
			});

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
						log = numFiles > 1 ? numFiles + ' файла(ов) выбрано' : label;
					
					if( input.length )
						input.val(log);
					else
						if( log ) 
							alert(log);
				});
			});
		});
	</script>
	
@stop