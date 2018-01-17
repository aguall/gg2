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
			<a href="/master/shops/add" class="thumbnail text-center">
				<i class="fa fa-plus-circle fa-5x"></i>
				<div class="title">Добавить магазин</div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@if( count($shops) > 0 )
				{!! Form::open() !!}
					<table class="table">
						<thead>
							<tr>
								<th width="70%">Название</th>
								<th class="text-right" width="30%">Управление</th>
							</tr>
						</thead>
						<tbody>
							@foreach($shops as $shop)
								<tr>
									<td>
										<input type="checkbox" name="check[]" value="{{ $shop->id }}" />
										&ensp;{{ $shop->name }}
									</td>
									<td class="text-right">
										<div class="btn-group" role="group">
											<button type="button" class="btn {{ !empty($shop->visible) ? 'btn-success' : 'btn-default' }} visible" title="Показать/Скрыть магазин" data-id="{{ $shop->id }}">
												<i class="fa {{ !empty($shop->visible) ? 'fa-star' : 'fa-star-o' }}"></i>
											</button>
											<a href="/master/shops/edit/{{ $shop->id }}" class="btn btn-warning" title="Редактировать магазин">
												<i class="fa fa-pencil"></i>
											</a>
											<a href="/shops/{{ $shop->slug }}" class="btn btn-primary" title="Открыть в новом окне" target="_blank">
												<i class="fa fa-share"></i>
											</a>
											<button type="button" class="btn btn-danger delete" title="Удалить магазин" data-id="{{ $shop->id }}">
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
				<div class="alert alert-warning">Магазины еще не добавлены</div>
			@endif
		</div>
	</div>
	<script>
		$(function(){
			
			// Удаление магазина
			$('.delete').click(function(){
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest('tr').find('input[type="checkbox"][name*="check"]').prop('checked', true);
				$(this).closest('form').find('select[name="action"] option[value=delete]').prop('selected', true);
				$(this).closest('form').submit();
			})

			// Удаление магазинов
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
			
			// Показать/Скрыть магазин
			$('.visible').on('click', function(event){
				event.preventDefault();
				if( $(this).hasClass('btn-success') )
					$(this).removeClass('btn-success').addClass('btn-default').find('i').removeClass('fa-star').addClass('fa-star-o');
				else
					$(this).removeClass('btn-default').addClass('btn-success').find('i').removeClass('fa-star-o').addClass('fa-star');
				var data = { _token: '{{ csrf_token() }}', id: $(this).data('id') };
				$.post('{{ URL::to("master/shops/visible") }}', data, function(data){ console.log(data.message) }, 'JSON');
			});
		});
	</script>
	
@stop