@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li><a href="/master/shop/products">Товары</a></li>
				<li><a href="/master/shop/products/prices/{{ $id }}">Цены в магазинах для товара: "{{ $productName }}"</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="languages-container">
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
				
					<input type="hidden" name="product_id" value="{{ $id }}" />
					
					<div class="form-group">
						<div class="col-sm-12">
							{!! Form::select('shop_id', $shops, $post->shop_id, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('not_delete', 'Удаление товара', ['class' => 'col-sm-3 control-label']) !!}
						<div class="col-sm-9">
							{!! Form::select('not_delete', [ 0 => 'Удалять товар перед обновлением XML прайсов', 1 => 'НЕ удалять товар перед обновлением XML прайсов'], $post->not_delete, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('shop_product_name', 'Название товара в магазине', ['class' => 'col-sm-3 control-label']) !!}
						<div class="col-sm-9">
							{!! Form::text('shop_product_name',  $post->shop_product_name, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('shop_product_link', 'Ссылка на товар в магазине', ['class' => 'col-sm-3 control-label']) !!}
						<div class="col-sm-9">
							{!! Form::text('shop_product_link',  $post->shop_product_link, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('shop_product_price', 'Цена на товар в магазине', ['class' => 'col-sm-3 control-label']) !!}
						<div class="col-sm-9">
							{!! Form::text('shop_product_price',  $post->shop_product_price, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<button type="submit" class="btn btn-success">Сохранить</button>
						</div>
					</div>
				
				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
	
@stop