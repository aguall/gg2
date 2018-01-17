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
			@if( count($articles) > 0 )
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
							@foreach($articles as $article)
								<tr>
									<td>
										<input type="checkbox" name="check[]" value="{{ $article->id }}" />
										&ensp;Статья от пользователя - <span class="label label-success">{{ $article->user_name }}</span>
									</td>
									<td>{!! date('d.m.Y H:i', strtotime($article->created_at)) !!}</td>
									<td class="text-right">
										<div class="btn-group" role="group">
											<button type="button" class="btn btn-primary look" title="Посмотреть статью" data-id="{{ $article->id }}" data-toggle="modal" data-target="#article"><i class="fa fa-file"></i></button>
											<a href="/master/users/edit/{{ $article->user_id }}" class="btn btn-info" title="Перейти в карточку пользователя"><i class="fa fa-arrow-right"></i></a>
											<button type="button" class="btn btn-danger delete" title="Удалить статью" data-id="{{ $article->id }}"><i class="fa fa-times"></i></button>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					
					{!! $articles->render() !!}
					
					<div class="select_form">
						<label id="check_all" class="link">Выбрать все</label>
						<select name="action" class="form-control">
							<option value="delete">Удалить</option>
						</select>
						<button type="submit" class="btn btn-success delete-all" disabled>Применить</button>
					</div>

				{!! Form::close() !!}
			@else
				<div class="alert alert-warning">Пока нет статей</div>
			@endif
		</div>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="article">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="text-left article-name"></h4>
				</div>
				<div class="modal-body">
					<textarea id="article-text" class="mceEditor"></textarea>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script src="/frontend/tinymce/tinymce.min.js"></script>
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
			
			// Подгрузка статьи
			tinymce.init({ 
				mode: 'specific_textareas',
				editor_selector: 'mceEditor',
				language: 'ru',
				height: 200
			});
			$('.look').on('click', function(){
				$.ajax({
					type: 'POST',
					dataType: 'JSON',
					url: '{{ URL::to("master/users/article-info") }}',
					data: { id: $(this).data('id'), _token: '{{ csrf_token() }}' },
					success: function(data){
						$('.article-name').text(data.name);
						tinymce.get('article-text').setContent(data.article);
					},
					error: function (xhr, ajaxOptions, thrownError){
						console.log(xhr.status + ' ' + thrownError);
					}
				});
			});
		});
	</script>
	
@stop