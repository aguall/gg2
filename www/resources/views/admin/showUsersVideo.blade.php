@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/users">Пользователи</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@if( count($video) > 0 )
				{!! Form::open() !!}
					<table class="table">
						<thead>
							<tr>
								<th width="50%">События</th>
								<th width="20%">Дата добавления</th>
								<th class="text-right" width="30%">Управление</th>
							</tr>
						</thead>
						<tbody>
							@foreach($video as $item)
								<tr>
									<td>
										<input type="checkbox" name="check[]" value="{{ $item->id }}" />
										&ensp;Видео обзор от пользователя - <span class="label label-success">{{ $item->user_name }}</span>
									</td>
									<td>{!! date('d.m.Y H:i', strtotime($item->created_at)) !!}</td>
									<td class="text-right">
										<div class="btn-group" role="group">
											<button type="button" class="btn btn-primary look" title="Посмотреть видео обзор" data-id="{{ $item->id }}" data-toggle="modal" data-target="#video"><i class="fa fa-video-camera"></i></button>
											<a href="/master/users/edit/{{ $item->user_id }}" class="btn btn-info" title="Перейти в карточку пользователя"><i class="fa fa-arrow-right"></i></a>
											<button type="button" class="btn btn-danger delete" title="Удалить видео обзор" data-id="{{ $item->id }}"><i class="fa fa-times"></i></button>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					
					{!! $video->render() !!}
					
					<div class="select_form">
						<label id="check_all" class="link">Выбрать все</label>
						<select name="action" class="form-control">
							<option value="delete">Удалить</option>
						</select>
						<button type="submit" class="btn btn-success delete-all" disabled>Применить</button>
					</div>

				{!! Form::close() !!}
			@else
				<div class="alert alert-warning">Пока нет видео обзоров</div>
			@endif
		</div>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="video">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h5 class="text-left">Ссылка на видео: <a href="#" class="link-video" target="_blank"></a></h5>
					<h5 class="text-left">Ссылка на товар: <a href="#" class="link-product" target="_blank"></a></h5>
				</div>
				<div class="modal-body">
					<div class="embed-responsive embed-responsive-16by9">
						<iframe class="embed-responsive-item"></iframe>
					</div>
				</div>
				<div class="modal-footer">
					<div class="text-left comment"></div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
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
			
			// Подгрузка видео
			$('.look').on('click', function(){
				$.ajax({
					type: 'POST',
					dataType: 'JSON',
					url: '{{ URL::to("master/users/video-info") }}',
					data: { id: $(this).data('id'), _token: '{{ csrf_token() }}' },
					success: function(data){
						$('.embed-responsive-item').attr('src', 'https://www.youtube.com/embed/' + data.video);
						$('.link-video').attr('href', 'https://www.youtube.com/watch?v=' + data.video).text('https://www.youtube.com/watch?v=' + data.video);
						$('.link-product').attr('href', data.product_url).text(data.product_url);
						$('.comment').html(data.comment);
					},
					error: function (xhr, ajaxOptions, thrownError){
						console.log(xhr.status + ' ' + thrownError);
					}
				});
			});
			$('#video').on('hidden.bs.modal', function(){
				$('.embed-responsive-item').attr('src', $('.embed-responsive-item').attr('src'));
			});
		});
	</script>
	
@stop