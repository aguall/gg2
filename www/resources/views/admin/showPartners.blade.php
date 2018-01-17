@extends('admin.layout')

@section('main')
	
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/advertising">Реклама</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<a href="/master/advertising/friends-and-partners/add" class="thumbnail text-center">
				<i class="fa fa-plus-circle fa-5x"></i>
				<div class="title">Добавить партнера</div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@if( count($posts) > 0 )
				
				<div class="title-module">
					<div class="pull-left">
						<strong>Название</strong>
					</div>
					<div class="pull-right">
						<strong class="text-control">Управление</strong>
					</div>
					<div class="clear"></div>
				</div>
				
				{!! Form::open() !!}
					
					<div class="list-group">
						
						@foreach($posts as $post)
							<div class="list-group-item" id="{{ $post->id }}">
								<div class="pull-left">
									<span class="item-element-menu menu-sortable" title="Сортировка">
										<i class="fa fa-bars"></i>
									</span>
									<span class="item-element-menu">
										<input type="checkbox" name="check[]" value="{{ $post->id }}" />
									</span>
									<span class="item-element-menu">
										{{ $post->description }}
									</span>
								</div>
								<div class="pull-right">
									<div class="btn-group" role="group">
										<button type="button" class="btn {{ !empty($post->visible) ? 'btn-success' : 'btn-default' }} visible" title="Показать/Скрыть партнера" data-id="{{ $post->id }}">
											<i class="fa {{ !empty($post->visible) ? 'fa-star' : 'fa-star-o' }}"></i>
										</button>
										<a href="/master/advertising/friends-and-partners/edit/{{ $post->id }}" class="btn btn-warning" title="Редактировать данные о партнере"><i class="fa fa-pencil"></i></a>
										<button type="button" class="btn btn-danger delete" title="Удалить партнера" data-id="{{ $post->id }}"><i class="fa fa-times"></i></button>
									</div>
								</div>
								<div class="clear"></div>
							</div>
						@endforeach
						
					</div>
					
					<div class="select_form">
						<label id="check_all" class="link">Выбрать все</label>
						<select name="action" class="form-control">
							<option value="delete">Удалить</option>
						</select>
						<button type="submit" class="btn btn-success delete-all" disabled>Применить</button>
					</div>
					
				{!! Form::close() !!}
			@else
				<div class="alert alert-warning">Данные о партнерах еще не добавлены</div>
			@endif
		</div>
	</div>
	<script>
		$(function(){
			// Удаление партнера
			$('.delete').click( function() {
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest('.list-group-item').find('input[type="checkbox"][name*="check"]').prop('checked', true);
				$(this).closest('form').find('select[name="action"] option[value=delete]').attr('selected', true);
				$(this).closest('form').submit();
			});

			// Удаление партнеров
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
			
			// Показать/Скрыть партнера
			$('.visible').on('click', function(event){
				event.preventDefault();
				if( $(this).hasClass('btn-success') )
					$(this).removeClass('btn-success').addClass('btn-default').find('i').removeClass('fa-star').addClass('fa-star-o');
				else
					$(this).removeClass('btn-default').addClass('btn-success').find('i').removeClass('fa-star-o').addClass('fa-star');
				var data = { _token: '{{ csrf_token() }}', id: $(this).data('id') };
				$.post('{{ URL::to("master/advertising/friends-and-partners/visible") }}', data, function(data){ console.log(data.message) }, 'JSON');
			});
			
			// Позиционирование записей
			$('.list-group').sortable({
				handle: '.menu-sortable',
				opacity: 0.7,
				stop: function(){
					var elementSort = $(this).sortable('toArray');
					$.post('{{ URL::to("master/advertising/friends-and-partners/sortable") }}', { _token: '{{ csrf_token() }}', position: elementSort});	
				}
			});
		});
	</script>
	
@stop