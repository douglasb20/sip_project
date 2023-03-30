
<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="{{route()->link('home')}}" class="logo d-flex align-items-center">
	<img src="/assets/images/logo-ltc.jpg" alt="">
	<span class="d-none d-lg-block">LTCFibra</span>
  </a>
  <i class="fa-regular fa-bars toggle-sidebar-btn"></i>
</div><!-- End Logo -->



<nav class="header-nav ms-auto">
	<ul class="d-flex align-items-center">

		<!-- TODO: arrumar as partes de notificação -->
		<li class="nav-item dropdown " hidden>

		<a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
			<i class="fa-regular fa-bell"></i>
			<span class="badge bg-primary badge-number">4</span>
		</a><!-- End Notification Icon -->

		<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
			<li class="dropdown-header">
			You have 4 new notifications
			<a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
			</li>
			<li>
			<hr class="dropdown-divider">
			</li>

			<li class="notification-item">
			<i class="fa-regular fa-exclamation-circle text-warning"></i>
			<div>
				<h4>Lorem Ipsum</h4>
				<p>Quae dolorem earum veritatis oditseno</p>
				<p>30 min. ago</p>
			</div>
			</li>

			<li>
			<hr class="dropdown-divider">
			</li>

			<li class="notification-item">
			<i class="fa-regular fa-x-circle text-danger"></i>
			<div>
				<h4>Atque rerum nesciunt</h4>
				<p>Quae dolorem earum veritatis oditseno</p>
				<p>1 hr. ago</p>
			</div>
			</li>

			<li>
			<hr class="dropdown-divider">
			</li>

			<li class="notification-item">
			<i class="bi bi-check-circle text-success"></i>
			<div>
				<h4>Sit rerum fuga</h4>
				<p>Quae dolorem earum veritatis oditseno</p>
				<p>2 hrs. ago</p>
			</div>
			</li>

			<li>
			<hr class="dropdown-divider">
			</li>

			<li class="notification-item">
			<i class="bi bi-info-circle text-primary"></i>
			<div>
				<h4>Dicta reprehenderit</h4>
				<p>Quae dolorem earum veritatis oditseno</p>
				<p>4 hrs. ago</p>
			</div>
			</li>

			<li>
			<hr class="dropdown-divider">
			</li>
			<li class="dropdown-footer">
			<a href="#">Show all notifications</a>
			</li>

		</ul><!-- End Notification Dropdown Items -->

		</li><!-- End Notification Nav -->

		<!-- TODO: arrumar as partes de mensagens -->
		<li class="nav-item dropdown" hidden>

		<a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
			<i class="fa-regular fa-message-lines"></i>
			<span class="badge bg-success badge-number">3</span>
		</a><!-- End Messages Icon -->

		<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
			<li class="dropdown-header">
			You have 3 new messages
			<a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
			</li>
			<li>
			<hr class="dropdown-divider">
			</li>

			<li class="message-item">
			<a href="#">
				<img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
				<div>
				<h4>Maria Hudson</h4>
				<p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
				<p>4 hrs. ago</p>
				</div>
			</a>
			</li>
			<li>
			<hr class="dropdown-divider">
			</li>

			<li class="message-item">
			<a href="#">
				<img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
				<div>
				<h4>Anna Nelson</h4>
				<p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
				<p>6 hrs. ago</p>
				</div>
			</a>
			</li>
			<li>
			<hr class="dropdown-divider">
			</li>

			<li class="message-item">
			<a href="#">
				<img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
				<div>
				<h4>David Muldon</h4>
				<p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
				<p>8 hrs. ago</p>
				</div>
			</a>
			</li>
			<li>
			<hr class="dropdown-divider">
			</li>

			<li class="dropdown-footer">
			<a href="#">Show all messages</a>
			</li>

		</ul><!-- End Messages Dropdown Items -->

		</li><!-- End Messages Nav -->

		<li class="nav-item dropdown pe-3">

			<a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
				<img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle" hidden>
				<span class="d-none d-md-block dropdown-toggle ps-2">{{ucwords(strtolower(getSessao('nome_usuario')))}}</span>
			</a><!-- End Profile Iamge Icon -->

			<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
				<li class="dropdown-header">
					<h6>{{ucwords(strtolower(getSessao('nome_usuario')))}}</h6>
					<!-- <span>Web Designer</span> -->
				</li>
				<li>
					<hr class="dropdown-divider">
				</li>

				<li>
					<a class="dropdown-item d-flex align-items-center" href="users-profile.html">
						<i class="bi bi-gear"></i>
						<span>Alterar Senha</span>
					</a>
				</li>

				<li>
					<hr class="dropdown-divider">
				</li>

				<li>
					<a class="dropdown-item d-flex align-items-center" href="{{route()->link('logout')}}">
						<i class="bi bi-box-arrow-right"></i>
						<span>Sair</span>
					</a>
				</li>

			</ul><!-- End Profile Dropdown Items -->
		</li><!-- End Profile Nav -->

	</ul>
</nav><!-- End Icons Navigation -->

</header><!-- End Header -->		

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

	<!-- Start Dashboard Nav -->
	<li class="nav-item">
		<a class="nav-link " href="{{route()->link('home')}}">
		<i class="fa-regular fa-grid-2"></i>
		<span>Dashboard</span>
		</a>
	</li><!-- End Dashboard Nav -->

	<!-- Start Settings Nav -->
	<li class="nav-item">
		<a class="nav-link collapsed" href="/settings">
		<i class="fa-regular fa-cog"></i>
		<span>Configurações</span>
		</a>
	</li><!-- End Settings Nav -->



</ul>

</aside><!-- End Sidebar-->
