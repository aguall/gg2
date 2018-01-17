@extends('admin.layout')

@section('main')
	
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li><a href="/master/shop/parser">XML парсер</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<a href="/master/shop/parser/control/add" class="thumbnail text-center">
				<i class="fa fa-plus-circle fa-5x"></i>
				<div class="title">Добавить XML файл</div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@if( count($shops) > 0 )
				{!! Form::open(['class' => 'posts']) !!}
					<table class="table">
						<thead>
							<tr>
								<th width="50%">Название</th>
								<th width="20%">Дата обновления товаров</th>
								<th class="text-right" width="30%">Управление</th>
							</tr>
						</thead>
						<tbody>
							@foreach($shops as $shop)
								<tr>
									<td>
										<input type="checkbox" name="check[]" value="{{ $shop->id }}" />
										&ensp;{{ $shop->shop_name }}
									</td>
									<td>
										@if( $shop->last_updated_products != '0000-00-00' )
											{!! date('d.m.Y', strtotime($shop->last_updated_products)) !!}
										@else
											<span class="label label-danger">Парсинг данных этого магазина еще не выполнялся!</span>
										@endif
									</td>
									<td class="text-right">
										<div class="btn-group" role="group">
											<a href="/master/shop/parser/control/edit/{{ $shop->id }}" class="btn btn-warning" title="Редактировать">
												<i class="fa fa-pencil"></i>
											</a>
											<button type="button" class="btn btn-danger delete" title="Удалить" data-id="{{ $shop->id }}">
												<i class="fa fa-times"></i>
											</button>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					
					{!! $shops->render() !!}
					
					<div class="select_form">
						<label id="check_all" class="link">Выбрать все</label>
						<select name="action" class="form-control">
							<option value="delete">Удалить</option>
						</select>
						<button type="submit" class="btn btn-success delete-all" disabled>Применить</button>
					</div>

				{!! Form::close() !!}
			@else
				<div class="alert alert-warning">XML файлы еще не добавлены...</div>
			@endif
		</div>
	</div>
	<script>
		$(function(){
			
			// Удаление XML файла
			$('.delete').click(function(){
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest('tr').find('input[type="checkbox"][name*="check"]').prop('checked', true);
				$(this).closest('form').find('select[name="action"] option[value=delete]').prop('selected', true);
				$(this).closest('form').submit();
			})

			// Удаление XML файлов
			$('form.posts').submit(function(){
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