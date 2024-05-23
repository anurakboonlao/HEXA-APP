<!DOCTYPE html>
<html lang="en">
<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<?php 
		include"include/function.php";
		css2_function();
	?>
	
</head>
<body class="bg-login1">

<div class="container pd-login5">
	<div class="ct-login1">
		<div class="tab-content">
			<div class="font-login1 tab-pane active" role="tabpanel" id="orders1">
				<div class="row bg-login2">
					<div class="col-lg-7 col-12 pd-login4">
						<div class="heroSlider-fixed">
							<div class="overlay"></div>
							<!-- Slider -->
							<div class="slider responsive">
								<div><img src="images/login/login1.png" alt="" class=""></div>
								<div><img src="images/login/login1.png" alt="" class=""></div>
								<div><img src="images/login/login1.png" alt="" class=""></div>
							</div>
							<!-- control arrows -->
							<div class="prev"><span class="icon-chevron-left"></span></div>
							<div class="next"><span class="icon-chevron-right"></span></div>
						</div>
					</div>
					<div class="col-lg-5 col-12 pd-login4">
						<div class="pd-login1">
							<form>
								<div class="form-group">
									<div class="input-group">
										<span class="icon-user input-group-addon bg-login3"></span>	
										<input  name="first_name" placeholder="ชื่อเข้าใช้ระบบ" class="form-control bor-login1"  type="text">
									</div>
								</div>
								<div class="form-group"> 
									<div class="input-group">
										<span class="icon-lock input-group-addon bg-login3"></span>	
										<input  name="first_name" placeholder="รหัสผ่าน" class="form-control bor-login1"  type="password">
									</div>
								</div>	

								<a href="mailto: "><span class="font-login2">ลืมรหัสผ่าน</span></a>
								<a href="index.php"><button class="btn-login1">ล็อกอิน</button></a>
								<div class="line-lo2"><span>หรือ</span></div><hr class="line-login1">
								<button class="btn-login1 btn-face"><img src="images/login/facebook.svg" class="left-log">เข้าสู่ระบบด้วย Facebook</button>
								<button class="btn-login1 btn-google"><img src="images/login/google.png" width="20px" class="left-log">เข้าสู่ระบบด้วย Google</button>
							</form>
							<p>By signing in you agree with the Terms of Service<br>and Privacy policy</p>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	
</div>

<?php script2_function(); ?>	
</body>
</html>