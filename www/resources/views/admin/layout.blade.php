<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }} @if( isset( $site_offline ) && $site_offline == 1 ) OFFLINE @endif</title>
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<!-- Custom Fonts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Custom CSS -->
    <link href="/admin/css/dashboard.css" rel="stylesheet">
	<link href="/admin/css/bootstrap-switch.min.css" rel="stylesheet">
	<link href="/admin/angular/angular-ui-tree.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script src="/admin/js/bootstrap-switch.min.js"></script>
</head>
<body>
    <div id="wrapper">
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/master/settings" data-toggle="tooltip" data-placement="right" title="Настройки"><i class="fa fa-cogs"></i></a>
			</div>
			<ul class="nav navbar-right top-nav">
				<li>
					<a href="/" target="_blank" class="link-site" data-toggle="tooltip" data-placement="left" title="Сайт"><i class="fa fa-arrow-circle-o-right"></i></a>
				</li>
				<li>
					<a href="/logout" class="logout"><i class="fa fa-sign-out"></i>&ensp;Выход</a>
				</li>
			</ul>
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav side-nav dash-menu">
					<li class="logo">
						<a href="/master">
							<img src="/admin/img/logo_small.png" alt="logo" />
						</a>
					</li>
					
					@include('admin.dashMenu')
					
				</ul>
			</div>
		</nav>
        <div id="page-wrapper">
            <div class="container-fluid">
				
				@yield('main')
				
            </div>
        </div>
    </div>
	<script>
		// Tooltip
		$('[data-toggle="tooltip"]').tooltip();
	</script>
</body>
</html>