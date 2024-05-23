<header class="main-header">
	<!-- Logo -->
	<a href="" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><b></span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><b>HEXA CERAM</b></span>
	</a>
	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar navbar-static-top">
		<!-- Sidebar toggle button-->
		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<!-- User Account: style can be found in dropdown.less -->
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="hidden-xs">{{ auth()->user()->email }}</span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-footer">
							<div class="pull-left">
								<a href="admin/profile" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
								<a href="admin/logout" class="btn btn-default btn-flat">Sign out</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>