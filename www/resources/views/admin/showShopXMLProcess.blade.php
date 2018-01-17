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
			
			{!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
				
				<div class="form-group">
					{!! Form::label('shop_id', 'Выберите магазин', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::select('shop_id', $shopsMas, 0, ['class' => 'form-control']) !!}
					</div>
					<div class="col-sm-2">
						<button type="submit" class="btn btn-danger btn-block">
							<i class="fa fa-history"></i> Парсить товары
						</button>
					</div>
				</div>
			
			{!! Form::close() !!}
			
			<div class="panel panel-default progress-hidden">
				<div class="panel-body">
					<h4 class="progress-title">Выполняется обработка данных (<span class="text-danger progress-persent">0%</span>)...</h4>
					<div class="progress">
						<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
					</div>
					<h5 class="processing"></h5>
				</div>
			</div>
		</div>
	</div>
	<script>
		// Token
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
		});

		// Запрос на основной метод парсинга данных
		function parserProcess(){
			
			// Запускаем функцию опроса результата прогресса каждую секунду
			var interval = setInterval(function(){ progress(); }, 1000);
 
			$.ajax({
				type: 'POST',
				url: '{{ URL::current() }}',
				data: { shop_id: $('#shop_id').val() },
				success: function(data){

					// По завершению работы скрипта эмуляции останавливаем таймер опроса прогресса
                	clearInterval( interval );
 
					// Формируем конечный результат
					$('.form-horizontal').find('#shop_id').prop('disabled', false);
					$('.form-horizontal').find('button').prop('disabled', false);
					$('.progress').hide();
					$('.progress-title').html('Процесс успешно завершен!');
					$('.processing').html('');
					
					// Список ссылок для скачки отчетов
					var linksForXML = '<div class="list-group">';
					$.each(JSON.parse(data), function(i, item){
						linksForXML += '<a href="' + item.urlToXML + '" class="list-group-item"><i class="fa fa-download" aria-hidden="true"></i> ' + item.shopName + '</a>';
					});
					linksForXML += '</div>';
					
					$('.processing').after(linksForXML);
				},
			});
 
			return false;
		}
		
		// Запрос к методу опроса результата прогресса
		function progress(){

			$.ajax({
				type: 'POST',
            	url: '{{ URL::to("master/shop/parser/process-status") }}',
            	dataType: 'JSON',
				success: function(data){
					
					// Выводим в информационный блок количество выполненных %
					$('.progress-persent').html(data.progress + '%');
								
					// Растягиваем полосу загрузки
					$('.progress-bar.progress-bar-striped').width(data.progress + '%');
					
					// Процесс обработки
					$('.processing').html(data.processing);
				},
			});

			return false;
		}
		
		// Запускаем парсер
		$('.form-horizontal').on('submit', function(event){
			
			event.preventDefault();
			
			$('.progress-hidden').show();
			$('.progress').show();
			$('.progress-title').html('Выполняется обработка данных (<span class="text-danger progress-persent">0%</span>)...');

			$(this).find('#shop_id').attr('disabled', 'disabled');
			$(this).find('button').attr('disabled', 'disabled');
			
			if( $('.list-group').length )
				$('.list-group').remove();

			parserProcess();
		});
	</script>
	
@stop