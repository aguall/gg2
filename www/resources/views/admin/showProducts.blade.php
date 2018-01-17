@extends('admin.layout')

@section('main')
	
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<a href="/master/shop/products/add" class="thumbnail text-center">
				<i class="fa fa-plus-circle fa-5x"></i>
				<div class="title">Добавить товар</div>
			</a>
		</div>
		<div class="col-md-3">
			<a href="/master/shop/products/xml-products-not-delete" class="thumbnail text-center">
				<i class="fa fa-cart-plus fa-5x"></i>
				<div class="title">Товары добавленные вручную для XML парсера</div>
			</a>
		</div>
	</div>
	
	@if( count($categories) > 1 )
		<div class="row">
			<div class="col-md-12">
				<form class="form-horizontal" method="GET">
					<div class="form-group">
						<div class="col-sm-12">
							<input type="search" name="search" class="form-control" value="{{ !empty(Request::get('search')) ? Request::get('search') : '' }}" placeholder="Поиск по товарам..." />
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10">
							{!! Form::select('category', $categories, !empty((int)Request::get('category')) ? (int)Request::get('category') : 0, ['class' => 'form-control']); !!}
						</div>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-block btn-success">Отправить</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	@endif
	
	<div class="row">
		<div class="col-md-12">
			@if( count($products) > 0 )
					
				<div class="title-module">
					<div class="pull-left">
						<strong>Название</strong>
					</div>
					<div class="pull-right">
						<strong class="text-control">Управление</strong>
					</div>
					<div class="clear"></div>
				</div>
						
				{!! Form::open(['class' => 'products-list']) !!}
				
					<div class="list-group">
						
						@foreach($products as $product)
							<div class="list-group-item" id="{{ $product->id }}">
								<div class="pull-left">
									<span class="item-element-menu">
										<input type="checkbox" name="check[]" value="{{ $product->id }}" />
									</span>
									<span class="item-element-menu">
										{{ $product->name }}
									</span>
								</div>
								<div class="pull-right">
									<div class="btn-group" role="group">
										<button type="button" class="btn {{ !empty($product->visible) ? 'btn-success' : 'btn-default' }} visible" title="Показать/Скрыть товар" data-id="{{ $product->id }}">
											<i class="fa {{ !empty($product->visible) ? 'fa-star' : 'fa-star-o' }}"></i>
										</button>
										<button type="button" class="btn {{ !empty($product->advertising) ? 'btn-danger' : 'btn-default' }} advertising" title="Показать как рекламное предложение" data-id="{{ $product->id }}">
											<i class="fa fa-television"></i>
										</button>
										<a href="/master/shop/products/options/{{ $product->id }}" class="btn btn-primary" title="Опции">
											<i class="fa fa-cog"></i>
										</a>
										<a href="/master/shop/products/images/{{ $product->id }}" class="btn btn-warning" title="Изображения">
											<i class="fa fa-picture-o"></i>
										</a>
										<a href="/master/shop/products/prices/{{ $product->id }}" class="btn btn-danger" title="Цены">
											&nbsp;<i class="fa fa-usd"></i>&nbsp;
										</a>
										<a href="/master/shop/products/video/{{ $product->id }}" class="btn btn-success" title="Видеообзоры">
											<i class="fa fa-video-camera"></i>
										</a>
										<a href="/master/shop/products/edit/{{ $product->id }}" class="btn btn-warning" title="Редактировать">
											<i class="fa fa-pencil"></i>
										</a>
										<a href="/product/{{ $product->slug }}" class="btn btn-primary" target="_blank" title="Открыть в новом окне">
											<i class="fa fa-share"></i>
										</a>
										<button type="button" class="btn btn-danger delete" title="Удалить" data-id="{{ $product->id }}">
											<i class="fa fa-times"></i>
										</button>
									</div>
								</div>
								<div class="clear"></div>
							</div>
						@endforeach
						
					</div>
				
					{!! $products->appends(['category' => (int)Request::get('category'), 'search' => Request::get('search')])->render() !!}
					
					<div class="select_form">
						<label id="check_all" class="link">Выбрать все</label>
						<select name="action" class="form-control">
							<option value="delete">Удалить</option>
						</select>
						<button type="submit" class="btn btn-success delete-all" disabled>Применить</button>
					</div>

				{!! Form::close() !!}
			@else
				<div class="alert alert-warning">Товары еще не добавлены</div>
			@endif
		</div>
	</div>
	<script>
		$(function(){
			
			// Удаление товара
			$('.delete').click( function() {
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest('.list-group-item').find('input[type="checkbox"][name*="check"]').prop('checked', true);
				$(this).closest('form').find('select[name="action"] option[value=delete]').attr('selected', true);
				$(this).closest('form').submit();
			});

			// Удаление товаров
			$('form.products-list').submit(function(){
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
			
			// Показать/Скрыть товар
			$('.visible').on('click', function(event){
				event.preventDefault();
				if( $(this).hasClass('btn-success') )
					$(this).removeClass('btn-success').addClass('btn-default').find('i').removeClass('fa-star').addClass('fa-star-o');
				else
					$(this).removeClass('btn-default').addClass('btn-success').find('i').removeClass('fa-star-o').addClass('fa-star');
				var data = { _token: '{{ csrf_token() }}', id: $(this).data('id') };
				$.post('{{ URL::to("master/shop/products/visible") }}', data, function(data){ console.log(data.message) }, 'JSON');
			});
			
			// Показать как рекламное предложение
			$('.advertising').on('click', function(event){
				event.preventDefault();
				if( $(this).hasClass('btn-danger') )
					$(this).removeClass('btn-danger').addClass('btn-default');
				else
					$(this).removeClass('btn-default').addClass('btn-danger');
				var data = { _token: '{{ csrf_token() }}', id: $(this).data('id') };
				$.post('{{ URL::to("master/shop/products/advertising") }}', data, function(data){ console.log(data.message) }, 'JSON');
			});
		});
	</script>
	
@stop