@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<h2>Системная информация</h2>
			<table class="table table-striped">
				<tr>
					<td>Операционная система:</td>
					<td>{{ php_uname('s') . ' ' . php_uname('r') }}</td>
				</tr>
				<tr>
					<td>Версия MySQL:</td>
					<td>{{ $mysqlVer[0]->mysql_version }}</td>
				</tr>
				<tr>
					<td>Версия PHP:</td>
					<td>{{ phpversion() }}</td>
				</tr>	
				<tr>
					<td>Выделено оперативной памяти:</td>
					<td>{{ ini_get('memory_limit') }}</td>
				</tr>
				<tr>
					<td>Максимальный размер загружаемого файла:</td>
					<td>{{ ini_get('upload_max_filesize') }}</td>
				</tr>
				<tr>
					<td>Размер свободного места на диске:</td>
					<td>{{ $totalFreeSpace }}</td>
				</tr>
			</table>
		</div>
	</div>

@stop