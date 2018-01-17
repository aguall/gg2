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
			<a href="/master/seo/add" class="thumbnail text-center">
				<i class="fa fa-plus-circle fa-5x"></i>
				<div class="title">Добавить запись</div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@if( count($posts) > 0 )
				{!! Form::open() !!}
					<table class="table">
						<thead>
							<tr>
								<th width="70%">URL адрес</th>
								<th class="text-right" width="30%">Управление</th>
							</tr>
						</thead>
						<tbody>
							@foreach($posts as $post)
								<tr>
									<td>
										<input type="checkbox" name="check[]" value="{{ $post->id }}" />
										&ensp;{{ $post->slug }}
									</td>
									<td class="text-right">
										<div class="btn-group" role="group">
											<a href="/master/seo/edit/{{ $post->id }}" class="btn btn-warning" title="Редактировать запись"><i class="fa fa-pencil"></i></a>
											<a href="{{ $post->slug }}" class="btn btn-primary" title="Открыть в новом окне" target="_blank"><i class="fa fa-share"></i></a>
											<button type="button" class="btn btn-danger delete" title="Удалить запись" data-id="{{ $post->id }}"><i class="fa fa-times"></i></button>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					
					{!! $posts->render() !!}
					
					<div class="select_form">
						<label id="check_all" class="link">Выбрать все</label>
						<select name="action" class="form-control">
							<option value="delete">Удалить</option>
						</select>
						<button type="submit" class="btn btn-success delete-all" disabled>Применить</button>
					</div>

				{!! Form::close() !!}
			@else
				<div class="alert alert-warning">Записи еще не созданы</div>
			@endif
		</div>
	</div>
	<script>
		$(function(){
			
			// Удаление записи
			$('.delete').click(function(){
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest('tr').find('input[type="checkbox"][name*="check"]').prop('checked', true);
				$(this).closest('form').find('select[name="action"] option[value=delete]').prop('selected', true);
				$(this).closest('form').submit();
			})

			// Удаление записей
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