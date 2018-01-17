@extends('admin.layout')

@section('main')
	
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li><a href="/master/shop/filters">Параметры фильтрации</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<a href="/master/shop/filters/features/add" class="thumbnail text-center">
				<i class="fa fa-plus-circle fa-5x"></i>
				<div class="title">Добавить параметр</div>
			</a>
		</div>
	</div>
	
	@if( count($groups) > 1 )
		<div class="row">
			<div class="col-md-12">
				<form class="form-horizontal" method="GET">
					<div class="form-group">
						<div class="col-sm-10">
							{!! Form::select('group', $groups, !empty((int)Request::get('group')) ? (int)Request::get('group') : 0, ['class' => 'form-control']); !!}
						</div>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-block btn-success">Сортировать</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	@endif
	
	<div class="row">
		<div class="col-md-12">
			@if( count($features) > 0 )
				{!! Form::open(['class' => 'posts']) !!}
					<table class="table">
						<thead>
							<tr>
								<th width="40%">Название</th>
								<th width="30%">Группа</th>
								<th class="text-right" width="30%">Управление</th>
							</tr>
						</thead>
						<tbody>
							@foreach($features as $feature)
								<tr>
									<td>
										<input type="checkbox" name="check[]" value="{{ $feature->id }}" />&ensp;
										<a href="/master/shop/filters/features-options/{{ $feature->id }}">
											{{ $feature->name }}
										</a>
									</td>
									<td>{{ $feature->features_group_name }}</td>
									<td class="text-right">
										<div class="btn-group" role="group">
											<a href="/master/shop/filters/features/edit/{{ $feature->id }}" class="btn btn-warning" title="Редактировать параметр">
												<i class="fa fa-pencil"></i>
											</a>
											<a href="/master/shop/filters/features-options/{{ $feature->id }}" class="btn btn-primary" title="Опции параметра">
												<i class="fa fa-cog"></i>
											</a>
											<button type="button" class="btn btn-danger delete" title="Удалить параметр" data-id="{{ $feature->id }}">
												<i class="fa fa-times"></i>
											</button>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					
					{!! $features->appends(['group' => (int)Request::get('group')])->render() !!}
					
					<div class="select_form">
						<label id="check_all" class="link">Выбрать все</label>
						<select name="action" class="form-control">
							<option value="delete">Удалить</option>
						</select>
						<button type="submit" class="btn btn-success delete-all" disabled>Применить</button>
					</div>

				{!! Form::close() !!}
			@else
				<div class="alert alert-warning">Параметры еще не созданы</div>
			@endif
		</div>
	</div>
	<script>
		$(function(){
			
			// Удаление параметра
			$('.delete').click(function(){
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest('tr').find('input[type="checkbox"][name*="check"]').prop('checked', true);
				$(this).closest('form').find('select[name="action"] option[value=delete]').prop('selected', true);
				$(this).closest('form').submit();
			})

			// Удаление параметров
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