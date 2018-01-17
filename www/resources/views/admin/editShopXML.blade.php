@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li><a href="/master/shop/parser">XML парсер</a></li>
				<li><a href="/master/shop/parser/control">Управление</a></li>
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
					{!! Form::label('shop_id', 'Выбирите магазин', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-7">
						{!! Form::select('shop_id', $shops, $post->shop_id, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('xml_url', 'Ссылка на XML/YML файл', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-7">
						{!! Form::text('xml_url',  $post->xml_url, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('xml_tag_wrapper', 'Оберточный элемент', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-7">
						{!! Form::text('xml_tag_wrapper',  $post->xml_tag_wrapper, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('xml_tag_name', 'Название элемента "Имя"', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-7">
						{!! Form::text('xml_tag_name',  $post->xml_tag_name, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('xml_tag_price', 'Название элемента "Цена"', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-7">
						{!! Form::text('xml_tag_price',  $post->xml_tag_price, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-7">
						<button type="submit" class="btn btn-success">Сохранить</button>
					</div>
				</div>
			
			{!! Form::close() !!}
			
			@if( isset($countProductsXML) && $countProductsXML )
				<hr/>
				{!! Form::open(['class' => 'form-horizontal', 'role' => 'form', 'url' => '/master/shop/parser/remove-price']) !!}
					<input name="shop_id" type="hidden" value="{{ $post->shop_id }}" />
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-7">
							<button type="submit" class="btn btn-danger btn-lg btn-block">
								<i class="fa fa-trash-o" aria-hidden="true"></i> Удалить все товарные позиции
							</button>
						</div>
					</div>
				{!! Form::close() !!}
			@endif
			
		</div>
	</div>
	
@stop