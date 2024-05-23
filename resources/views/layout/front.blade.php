
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{ url('/') }}/">
	<title>@yield('title', 'HEXA CERAM - Web Application')</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- css templete front -->
	<link rel="stylesheet" href="{{ asset('template/front/fonts/icomoon/style.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front/css/main.css') }}">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css'>
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

	<!--<link rel="stylesheet" href="{{ asset('template/front/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front/css/main.css') }}">

	<link rel="stylesheet" href="{{ asset('template/front/password/style.css') }}"> -->

	<link rel="stylesheet" href="{{ asset('css/my_style.css') }}">
</head>
<body>

@yield('content')

	<div style="margin: 100px;"></div>

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

	<!---js checkbox--->
	<script src="{{ asset("template/front/js/upload_profile_image.js") }}"></script>

	<!---js checkbox--->
	<script src="{{ asset("template/front/js/checkbox/material.min.js") }}"></script>
	<script src="{{ asset("template/front/js/checkbox/script.js") }}"></script>

	<!---js dropdown--->
	<script  src="{{ asset("template/front/js/dropdown/index.js") }}"></script>

	<!---js star--->
	<script  src="{{ asset("template/front/js/star/jquery.min.js") }}"></script>
	<script  src="{{ asset("template/front/js/star/script.js") }}"></script>

	<!---js search--->
	<script  src="{{ asset("template/front/js/search/script.js") }}"></script>

	<!---js popup--->
	<script src="{{ asset("template/front/js/popup/bootstrap.min.js") }}"></script>
	<script src="{{ asset("template/front/js/popup/script.js") }}"></script>

	<!-- jQuery LoadingOverlay -->
	<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

	<!-- js sweetalert -->
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

    <!-- modal my order Add comment success -->
	@if(!empty(Session::get('modal')) && Session::get('modal') == 5)
		<script>
			$(function() {
				$('#modal-thank1').modal('show');
			});
		</script>
	@endif

	<!-- modal exchange reward success -->  
	@if(!empty(Session::get('modal')) && Session::get('modal') == 4)
		<script>
			$(function() {
				$('#modal-success1').modal('show');
			});
		</script>
	@endif

	<!-- edit profile ( modal profile in navbar ) -->
	<script>
		$('.site-navbar .my-profile').on('click', function(){
			$('.modal-profile .profile-right-area .profile-input').prop('readonly', true);
			$('.modal-profile .profile-right-area .profile-input').addClass('edit-profile-color');
			$('.modal-profile .profile-right-area button[type=submit]').prop('disabled', true);
		})

		$('.modal-profile .edit-profile').on('click', function(){
			$('.modal-profile .profile-right-area .profile-input').prop('readonly', false);
			$('.modal-profile .profile-right-area input[name=username]').prop('readonly', true);
			$('.modal-profile .profile-right-area input[name=email]').prop('readonly', true);
			$('.modal-profile .profile-right-area .profile-input').removeClass('edit-profile-color');
			$('.modal-profile .profile-right-area button[type=submit]').prop('disabled', false);
		})
	</script>

	@yield('script')

</body>
</html>