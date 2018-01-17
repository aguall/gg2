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
			<a href="/master/shop/products/prices/{{ $id }}/add" class="thumbnail text-center">
				<i class="fa fa-plus-circle fa-5x"></i>
				<div class="title">Добавить цену</div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@if( count($prices) > 0 )
				{!! Form::open(['class' => 'posts']) !!}
					<table class="table">
						<thead>
							<tr>
								<th width="35%">Название</th>
								<th width="35%">Магазин</th>
								<th width="15%">Цена</th>
								<th class="text-right" width="15%">Управление</th>
							</tr>
						</thead>
						<tbody>
							@foreach($prices as $price)
								<tr>
									<td>
										<input type="checkbox" name="check[]" value="{{ $price->id }}" />
										&ensp;{{ $price->shop_product_name }}
									</td>
									<td>{{ $price->shop_name }}</td>
									<td>{{ $price->shop_product_price }} грн.</td>
									<td class="text-right">
										<div class="btn-group" role="group">
											<a href="/master/shop/products/prices/{{ $id }}/edit/{{ $price->id }}" class="btn btn-warning" title="Редактировать">
												<i class="fa fa-pencil"></i>
											</a>
											<button type="button" class="btn btn-danger delete" title="Удалить" data-id="{{ $price->id }}">
												<i class="fa fa-times"></i>
											</button>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					
					{!! $prices->render() !!}
					
					<div class="select_form">
						<label id="check_all" class="link">Выбрать все</label>
						<select name="action" class="form-control">
							<option value="delete">Удалить</option>
						</select>
						<button type="submit" class="btn btn-success delete-all" disabled>Применить</button>
					</div>

				{!! Form::close() !!}
			@else
				<div class="alert alert-warning">Для данного товара цен в оружейных магазинах еще нет</div>
			@endif
		</div>
	</div>
	<script>
		$(function(){
			
			// Удаление цены
			$('.delete').click(function(){
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest('tr').find('input[type="checkbox"][name*="check"]').prop('checked', true);
				$(this).closest('form').find('select[name="action"] option[value=delete]').prop('selected', true);
				$(this).closest('form').submit();
			})

			// Удаление цен
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