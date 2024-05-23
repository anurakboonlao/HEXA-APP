<div class="site-mobile-menu site-navbar-target">
	<div class="site-mobile-menu-header">
		<div class="site-mobile-menu-close mt-3">
			<span class="icon-close2 js-menu-toggle"></span>
		</div>
	</div>
	<div class="site-mobile-menu-body"></div>
</div>

<header class="site-navbar js-sticky-header site-navbar-target" role="banner">
	<div class="container-fluid pd-denti3">
		<div class="row align-items-center">
			<div class="col-6 col-xl-2">
				<a href="index.php"><img src="images/dentist/logo.png"></a>
			</div>
			<div class="col-12 col-md-10 d-none d-xl-block">
				<nav class="site-navigation position-relative text-right" role="navigation">
					<ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
						<li><a href="" class="nav-link">&nbsp;&nbsp;<i class="fas fa-list-ul mg-nav1 font-20" aria-hidden="true"></i>&nbsp;&nbsp;Pricelist</a></li>
						<li><a href="" class="nav-link">&nbsp;&nbsp;<i class="fab fa-line mg-nav1" aria-hidden="true"></i>&nbsp;@hexaceram</a></li>
						<li><a href="" class="nav-link">&nbsp;&nbsp;<i class="fab fa-facebook-square mg-nav1" aria-hidden="true"></i>&nbsp;Hexa Ceram</a></li>
						<li class="has-children">
							<a href="#" class="bg-nav1">Username
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
								<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
								<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
								</svg>
							</a>
							<ul class="dropdown">
								<li><a href="#" data-toggle="modal" data-target="#profile">My Profile</a></li>
								<li><a href="#">Log out</a></li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
			<div class="col-6 d-inline-block d-xl-none ml-md-0 pd-nav1" style="position: relative; top: 0px;"><a href="#" class="site-menu-toggle js-menu-toggle text-black float-right"><span class="icon-menu h3"></span></a></div>
		</div>
	</div>
</header>

<!--popup-->
<div class="modal" id="profile" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content radi-popup1">
			<div class="modal-header line-popup2">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body pd-popup7">
				<div class="col-12">
					<div class="row">
						<div class="col-4">
							<form>
								<h4 class="text-center">Username</h4>
								<button class="btn-popup1 bg-blue text-white" type="button" data-toggle="modal" data-target="#modal-exchange1">แก้ไขโปรไฟล์</button>
								<button type="button" class="btn-popup1" data-toggle="modal" data-target="#inviteSuccessfully">Change Password</button>
							</form>
						</div>
						<div class="col-8">
							<form>
								<input type="text" placeholder="กรุณากรอก Invite Code" class="form-modal1 text-center">
								<button type="button" class="btn-popup1" data-toggle="modal" data-target="#inviteSuccessfully">ขอรับ invite Code เพื่อยืนยันตัวตน</button>
								<button class="btn-rewa4 btn-suc1" type="button" data-toggle="modal" data-target="#modal-exchange1">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>