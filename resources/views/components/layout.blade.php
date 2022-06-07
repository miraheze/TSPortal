<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<title>{{ $pgname }}</title>
	<link rel="stylesheet" href="/css/bootstrap.css">
	<link rel="stylesheet" href="/css/bootstrap-custom.css">
	<link rel="stylesheet" href="/css/fontawesome6.css">
</head>
<body id="page-top">
<div id="wrapper">
	<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
		<div class="container-fluid d-flex flex-column p-0">
			<a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
				<div class="sidebar-brand-icon rotate-n-15">
					<i class="fa-solid fa-shield-halved"></i>
				</div>
				<div class="sidebar-brand-text mx-3">
					<span>TSPortal</span>
				</div>
			</a>
			<hr class="sidebar-divider my-0">
			<ul class="navbar-nav text-light" id="accordionSidebar">
				<li class="nav-item">
					<a class="nav-link {{ ( $pgname == __('home') ) ? 'active' : '' }}" href="/">
						<i class="fa-solid fa-house"></i>
						<span>{{ __('home') }}</span>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ ( $pgname == __('reports') ) ? 'active' : '' }}" href="/reports">
						<i class="fa-solid fa-triangle-exclamation"></i>
						<span>{{ __('reports') }}</span>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ ( $pgname == __('dpa') ) ? 'active' : '' }}" href="/dpa">
						<i class="fa-solid fa-user-slash"></i>
						<span>{{ __('dpa') }}</span>
					</a>
				</li>
				@can('ts')
					<li class="nav-item">
						<a class="nav-link {{ ( $pgname == __('investigations') ) ? 'active' : '' }}"
						   href="/investigations">
							<i class="fa-solid fa-magnifying-glass"></i>
							<span>{{ __('investigations') }}</span>
						</a>
					</li>
					<!-- TODO: Transparency Reports
					<li class="nav-item">
						<a class="nav-link {{ ( $pgname == 'Transparency Reports' ) ? 'active' : '' }}"
						   href="/transparency">
							<i class="fas fa-chart-bar"></i>
							<span>Transparency Report</span>
						</a>
					</li>
					-->
					<li class="nav-item">
						<a class="nav-link {{ ( $pgname == __('users') ) ? 'active' : '' }}" href="/user">
							<i class="fa-solid fa-user"></i>
							<span>{{ __('users') }}</span>
						</a>
					</li>
				@endcan
			</ul>
			<div class="text-center d-none d-md-inline">
				<button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button>
			</div>
		</div>
	</nav>
	<div class="d-flex flex-column" id="content-wrapper">
		<div id="content">
			<nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
				<div class="container-fluid">
					<button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button">
						<i class="fa-solid fa-bars"></i>
					</button>
					<!-- TODO: SEARCH
					<form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
						<div class="input-group">
							<input class="bg-light form-control border-0 small" type="text" placeholder="Search for">
							<button class="btn btn-primary py-0" type="button">
								<i class="fa-solid fa-search"></i>
							</button>
						</div>
					</form>-->
					<ul class="navbar-nav flex-nowrap ms-auto">
						<!--<li class="nav-item dropdown d-sm-none no-arrow">
							<a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown"
							   href="#">
								<i class="fas fa-search"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in"
							     aria-labelledby="searchDropdown">
								<form class="me-auto navbar-search w-100">
									<div class="input-group">
										<input class="bg-light form-control border-0 small" type="text"
										       placeholder="Search for ...">
										<div class="input-group-append">
											<button class="btn btn-primary py-0" type="button">
												<i class="fas fa-search"></i>
											</button>
										</div>
									</div>
								</form>
							</div>
						</li>-->
						<li class="nav-item dropdown no-arrow mx-1">
							<div class="nav-item dropdown no-arrow">
								<a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown"
								   href="#">
									<span class="badge bg-danger badge-counter"></span>
									<i class="fa-solid fa-plus fa-fw"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in">
									<h6 class="dropdown-header">{{ __('create-new') }}...</h6>
									<a class="dropdown-item d-flex align-items-center" href="/report/new">
										<div class="dropdown-list-image me-3" style="padding: 5px;">
											<i class="fa-solid fa-triangle-exclamation"
											   style="font-size: 30px;height: 51px;"></i>
										</div>
										<div class="fw-bold">
											<div class="text-truncate">
												<span>{{ __('report') }}</span>
											</div>
										</div>
									</a>
									@can('ts')
										<a class="dropdown-item d-flex align-items-center" href="/investigation/new">
											<div class="dropdown-list-image me-3" style="padding: 5px;">
												<i class="fa-solid fa-magnifying-glass" style="font-size: 30px;"></i>
											</div>
											<div class="fw-bold">
												<div class="text-truncate">
													<span>{{ __('investigation') }}</span>
												</div>
											</div>
										</a>
									@endcan
									<a class="dropdown-item d-flex align-items-center" href="/dpa/new">
										<div class="dropdown-list-image me-3" style="padding: 5px;"><i
												class="fa-solid fa-user-slash" style="font-size: 30px;"></i></div>
										<div class="fw-bold">
											<div class="text-truncate"><span>{{ __('dpa') }}</span></div>
										</div>
									</a>
								</div>
							</div>
							<div class="shadow dropdown-list dropdown-menu dropdown-menu-end"
							     aria-labelledby="alertsDropdown"></div>
						</li>
						<div class="d-none d-sm-block topbar-divider"></div>
						<li class="nav-item dropdown no-arrow">
							<div class="nav-item dropdown no-arrow">
								@auth('web')
									<a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown"
									   href="#">
										<span class="d-none d-lg-inline me-2 text-gray-600 small">{{ request()->user()->username }}</span>
										<span class="d-inline d-lg-none me-2 text-gray-600 small fa-solid fa-user"></span>
									</a>
									<div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
										<a class="dropdown-item" href="/user/{{ request()->user()->id }}">
											<i class="fa-solid fa-user fa-sm fa-fw me-2 text-gray-400"></i>{{ __('profile') }}</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="/logout"><i
												class="fa-solid fa-right-from-bracket fa-sm fa-fw me-2 text-gray-400"></i>{{ __('logout') }}</a>
									</div>
								@else
									<a href="/login"
									   class="dropdown-toggle nav-link">
										<span class="d-none d-lg-inline me-2 text-gray-600 small">{{ __('login') }}</span>
										<span class="d-inline d-lg-none me-2 text-gray-600 small fa-solid fa-right-to-bracket"></span>
									</a>
								@endauth
							</div>
						</li>
					</ul>
				</div>
			</nav>
			<div class="container-fluid">
				{{ $content }}
			</div>
		</div>
		<footer class="text-center">
			<div class="container text-muted py-lg-4">
				<ul class="list-inline">
					<li class="list-inline-item me-4"><a class="link-secondary"
					                                     href="https://meta.miraheze.org/wiki/Privacy_Policy">{{ __('footer-privacy') }}</a></li>
					<li class="list-inline-item me-4"><a class="link-secondary"
					                                     href="https://meta.miraheze.org/wiki/Terms_of_Use">{{ __('footer-tou') }}</a></li>
					<li class="list-inline-item"><a class="link-secondary"
					                                href="https://meta.miraheze.org/wiki/Miraheze">{{ __('footer-meta') }}</a>
					</li>
				</ul>
				<p class="mb-0">{{ __('footer-company') }}</p>
				<p class="mb-0">{{ __('version', [ 'v' => config('app.version') ] ) }}</p>
			</div>
		</footer>
	</div>
	<a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fa-solid fa-angle-up"></i></a>
</div>
<script src="/js/bootstrap.js"></script>
<script src="/js/scroll.js"></script>
{{ $scripts }}
</body>
</html>
