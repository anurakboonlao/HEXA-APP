
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="{{ url('/') }}/">
	<title>@yield('title', 'Login Hexa')</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- css slide -->
	<link rel="stylesheet" href="{{ asset('template/front/css/slide/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front/css/slide/slick.min.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front/css/slide/style.css') }}">
	<!-- css templete front -->
	<link rel="stylesheet" href="{{ asset('template/front/fonts/icomoon/style.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front/css/main.css') }}">

</head>
<body class="bg-login1">

	@yield('content')

	<!---js slide--->
	<script src="{{ asset("template/front/js/slide/jquery.min.js") }}"></script>
	<script src="{{ asset("template/front/js/slide/slick.min.js") }}"></script>
	<script src="{{ asset("template/front/js/slide/script.js") }}"></script>

	<!-- js templete front -->
	<script src="{{ asset("template/front/js/jquery-3.3.1.min.js") }}"></script>
	<script src="{{ asset("template/front/js/jquery-migrate-3.0.1.min.js") }}"></script>
	<script src="{{ asset("template/front/js/jquery-ui.js") }}"></script>
	<script src="{{ asset("template/front/js/popper.min.js") }}"></script>
	<script src="{{ asset("template/front/js/bootstrap.min.js") }}"></script>
	<script src="{{ asset("template/front/js/owl.carousel.min.js") }}"></script>
	<script src="{{ asset("template/front/js/jquery.stellar.min.js") }}"></script>
	<script src="{{ asset("template/front/js/jquery.countdown.min.js") }}"></script>
	<script src="{{ asset("template/front/js/bootstrap-datepicker.min.js") }}"></script>
	<script src="{{ asset("template/front/js/jquery.easing.1.3.js") }}"></script>
	<script src="{{ asset("template/front/js/aos.js") }}"></script>
	<script src="{{ asset("template/front/js/jquery.fancybox.min.js") }}"></script>
	<script src="{{ asset("template/front/js/jquery.sticky.js") }}"></script>
	<script src="{{ asset("template/front/js/main.js") }}"></script>

	<!-- js sweet alert -->
	<script src="bower_components/sweetalert2/dist/sweetalert2.all.min.js"></script>
	@if (session('message'))
		@php
			$message = session('message');
		@endphp
		<script>
			$(function(e) {
				swal({
					type: "{{ ($message['status']) ? 'success' : 'error' }}",
					title: "{{ $message['message'] }}",
					showConfirmButton: true,
					timer: false
				})
			});
		</script>
	@endif
</body>
</html>