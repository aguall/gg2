<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Вход</title>

	@section('styles')
		 <!-- Bootstrap Core CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="/admin/css/login.css">
	@show
</head>
<body>
	<div class="wrapper">

		<a href="/" class="logo">
			<img src="/admin/img/logo.png" alt="" />
		</a>
		
		{!! Form::open(array('class' => 'form-login')) !!}
			@if( !$errors->isEmpty() )
				<div class="alert alert-danger">
					@foreach($errors->all() as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
			@endif
			<input type="email" class="form-control" name="email" placeholder="E-mail" value="{{ old('email') }}" required autofocus />
			<input type="password" class="form-control" name="password" placeholder="Пароль" required />      
			<button class="btn btn-lg btn-primary btn-block" type="submit">Войти <span class="glyphicon glyphicon-log-in"></span></button>  
		{!! Form::close() !!}
	
	</div>
</body>
</html>