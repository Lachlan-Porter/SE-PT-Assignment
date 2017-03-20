<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../favicon.ico">
	<title>Appointment Booking System</title>
	<!-- Bootstrap core CSS -->
	<link href="css/app.css" rel="stylesheet">
	<!-- Custom styles for this template -->
	<link href="signin.css" rel="stylesheet">
</head>

<body>
	<div class="container">
		@if (Auth::check())
		Logged in as {{ Auth::user()->firstname }}
		@endif
		@if ($flash = session('message'))
			<div class="alert alert-success">
				{{ $flash }}	
			</div>
		@endif
		@if ($flash = session('error'))
			<div class="alert alert-danger">
				{{ $flash }}	
			</div>
		@endif
		<div class="header">
			<a class="header__title" href="/">
				<h1>Business Name</h1>
			</a>
			<h3 class="header__subtitle">Booking System</h3>
		</div>
	</div>
	@yield('content')
</body>

</html>
