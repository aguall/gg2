@extends('admin.layout')

@section('main')
	
	<link rel="stylesheet" href="/admin/chosen/chosen.min.css">
	
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li><a href="/master/shop/products">Товары</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-offset-3 col-sm-8">
			<a href="/master/shop/products/edit/{{ Request::segment(5) }}" class="thumbnail text-center">
				<i class="fa fa-arrow-left fa-5x"></i>
				<div class="title">Назад к товару</div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			
			@if(Session::has('success'))
				<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
			@endif
			
			@if( count($features) > 0 )
				{!! Form::open(['class' => 'form-horizontal']) !!}
					
					@foreach($features as $feature)
						<div class="form-group">
							<label for="select-{{ $feature->id }}" class="col-sm-3 control-label">{{ $feature->name }}</label>
							<div class="col-sm-8">
								<select multiple="multiple" name="feature[{{ $feature->id }}][]" id="select-{{ $feature->id }}" class="form-control feature-select">
									<option value=""></option>
									@if( isset($feature->variants) && count($feature->variants) > 0 )
										@foreach( $feature->variants as $variant )
											<option value="{{ $variant->id }}" @if( isset($options[$feature->id] ) && in_array($variant->id, $options[$feature->id]) ) selected @endif>{{ $variant->name }}</option>
										@endforeach
									@endif
								</select>
							</div>
						</div>
					@endforeach
					
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-8">
							<button type="submit" class="btn btn-success">Сохранить</button>
						</div>
					</div>

				{!! Form::close() !!}
			@else
				<div class="alert alert-warning">Нет ни одного параметра для выбора значений</div>
			@endif
		</div>
	</div>
	<script src="/admin/chosen/chosen.jquery.min.js"></script>
	<script>
		$(function(){
			
			// Multiselect
			$('.feature-select').chosen({
				placeholder_text_multiple: 'Выберите вариант...'
			});
		});
	</script>
	
@stop