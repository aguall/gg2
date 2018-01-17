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
		<div class="col-md-12">
			
			@if(Session::has('success'))
				<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
			@endif
			
			<div class="alert-ajax"></div>
			
			{!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
				<div class="form-group">
					{!! Form::label('site_offline', 'Сайт выключен', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-7">
						{!! Form::checkbox('site_offline', 'true', $settings->site_offline) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('email', 'E-mail для уведомлений', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-7">
						{!! Form::email('email', $settings->email, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button type="submit" class="btn btn-success">Сохранить</button>
					</div>
				</div>
			{!! Form::close() !!}
			
			<hr/>

			<div class="form-horizontal">
				<div class="form-group">
					{!! Form::label('site_cache', 'Кэширование', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-3">
						<a href="#" class="btn btn-danger cache">
							<i class="fa fa-refresh" aria-hidden="true"></i> Очистить кэш
						</a>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('sitemap', 'XML карты сайта', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-3">
						<a href="#" class="btn btn-primary sitemap">
							<i class="fa fa-code" aria-hidden="true"></i> Генерировать
						</a>
					</div>
				</div>
			</div>
			<script>
				// Включить/выключить сайт
				$('#site_offline').bootstrapSwitch({
					size: 'normal',
					onColor: 'danger',
					onText: 'Да',
					offText: 'Нет'
				});
				
				// Чистим кэш
				$('.cache').on('click', function(event){
					event.preventDefault();
					$.post('{{ URL::current() }}', { _token: '{{ csrf_token() }}' }, function(message){ 
						$('.alert-ajax').html('<div class="alert alert-success">' + message.success + '</div>');
					}, 'JSON');
				});

				// Генерируем карту сайта
				$('.sitemap').on('click', function(event){
					event.preventDefault();
					$.ajax({
						type: 'POST',
						url: '/master/settings/sitemap',
						dataType: 'JSON',
						data: { _token: '{{ csrf_token() }}' },
						beforeSend : function(){
							$('.sitemap').attr('disabled', 'disabled');
							$('.sitemap').find('.fa').removeClass('fa-code').addClass('fa-spinner').addClass('fa-spin');
						},
						success: function(message){
							$('.alert-ajax').html('<div class="alert alert-success">' + message.success + ' <a href="/sitemap_ru.xml" target="_blank">Русская версия</a>, <a href="/sitemap_ua.xml" target="_blank">Українська версія</a>, <a href="/sitemap_en.xml" target="_blank">English version</a></div>');
						},
						complete: function(){
							$('.sitemap').removeAttr('disabled');
							$('.sitemap').find('.fa').removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-code');
						},
						error: function (xhr, ajaxOptions, thrownError){
							console.log(xhr.status + ' ' + thrownError);
						}
					});
				});
			</script>
		</div>
	</div>
	
@stop