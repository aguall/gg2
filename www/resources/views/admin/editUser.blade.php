@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/users">Пользователи</a></li>
				<li><a href="/master/users/show">Список пользователей</a></li>
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
					{!! Form::label('permissions', 'Роль', ['class' => 'col-sm-2 control-label'] ) !!}
					<div class="col-sm-8">
						{!! Form::select('permissions', ['admin' => 'Администратор', 'user' => 'Пользователь'], $post->permissions, ['class' => 'form-control']) !!}
					</div>
				</div>

				<div class="form-group">
					{!! Form::label('name', 'Имя', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::text('name',  $post->name, ['class' => 'form-control']) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('email', 'E-mail', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::text('email',  $post->email, ['class'=>'form-control']) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('login', 'Логин', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::text('login',  $post->login, ['class' => 'form-control']) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('city', 'Город', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::text('city',  $post->city, ['class' => 'form-control']) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('birthday', 'Дата рождения', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::date('birthday',  $post->birthday, ['class' => 'form-control']) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('rating', 'Рейтинг', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::number('rating',  $post->rating, ['class' => 'form-control']) !!}
					</div>
				</div>

				<div class="form-group">
					{!! Form::label('password', 'Пароль', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::password('password', ['class' => 'form-control']) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('about', 'О пользователе', ['class' => 'col-sm-2 control-label']) !!}
					<div class="col-sm-8">
						<textarea id="about" name="about" class="form-control" rows="4">{{ $post->about }}</textarea>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-8">
						<button type="submit" class="btn btn-success">Сохранить</button>
					</div>
				</div>
			
			{!! Form::close() !!}
		</div>
	</div>
	
@stop