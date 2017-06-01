<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>AIMSS | {{ $title or '' }}</title>
		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">
		<link href="/css/datetimepicker.css" rel="stylesheet">
	</head>
	<body>

		<div id="wrapper">

			<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav metismenu" id="side-menu">
						<li class="nav-header">
							<div class="dropdown profile-element">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</strong>
										</span> <span class="text-muted text-xs block">Settings<b class="caret"></b></span> </span> </a>
										<ul class="dropdown-menu animated fadeInRight m-t-xs">
											<li><a href="#" class="logout-btn">Logout</a></li>
										</ul>
							</div>
							<div class="logo-element">
								BD
							</div>
						</li>
						<li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
							<a href="/dashboard"><i class="fa fa-dashboard"></i> <span class="nav-label">Dashboard</span></a>
						</li>
						<li class="{{ Request::is('users*') ? 'active' : '' }}">
							<a href="/users"><i class="fa fa-users"></i> <span class="nav-label">Users</span></a>
						</li>
						<li class="{{ Request::is('events*') ? 'active' : '' }}">
							<a href="#"><i class="fa fa-calendar"></i> <span class="nav-label">Events</span><span class="fa-arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								<li class="{{ Request::is('events*') && !Request::is('events/pending*') ? 'active' : '' }}"><a href="/events">Accepted Events</a></li>
								<li class="{{ Request::is('events/pending*') ? 'active' : '' }}"><a href="/events/pending">Pending Events</a></li>
							</ul>
						</li>
						<li class="{{ Request::is('review*') ? 'active' : '' }}">
							<a href="/review"><i class="fa fa-search"></i> <span class="nav-label">Review</span></a>
						</li>
						<li class="{{ Request::is( 'formbuilder*') ? 'active' : '' }}">
							<a href="/formbuilder"><i class="fa fa-wrench"></i> <span class="nav-label">Form Builder</span></a>
						</li>
					</ul>

				</div>
			</nav>

			<div id="page-wrapper" class="gray-bg">
				<div class="row border-bottom">
					<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
						<div class="navbar-header">
							<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
							<form role="search" class="navbar-form-custom" method="post" action="#">
								<div class="form-group">
									<input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
								</div>
							</form>
						</div>
						<ul class="nav navbar-top-links navbar-right">
							<li>
								<a href="#" class="logout-btn">
									<i class="fa fa-sign-out"></i> Log out
								</a>
							</li>
						</ul>

					</nav>
				</div>

				<div class="wrapper wrapper-content">
					@yield('content')
				</div>

				<div class="footer">
					<div class="pull-right">

					</div>
					<div>
						<strong>Copyright</strong> Australian Institute of Motor Sport Safety &copy; {{ date("Y") }}
					</div>
				</div>

			</div>
		</div>

		<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
		<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<script src="/js/inspinia.js"></script>
		<script src="/js/plugins/pace/pace.min.js"></script>
		<script src="/js/bootbox.min.js"></script>
		<script src="/js/modalform.js"></script>
		<script src="/js/moment.min.js"></script>
		<script src="/js/datetimepicker.js"></script>
		<script src="/js/common.js"></script>
		@yield('js')

		<script>
			$(document).ready(function(){
				$('.logout-btn').click(function(event){
					modalform.dialog({
						bootbox : {
							title: 'Are you sure you want to logout?',
							message: ''+
								'<form action="/logout" method="post">'+
								'{{ csrf_field() }}'+
								'</form>',
							buttons: {
								cancel: {
									label: 'Cancel',
									className: 'btn-default'
								},
								submit: {
									label: 'Logout',
									className: 'btn-danger'
								}
							}
						},
						autofocus : false,
					});
					event.preventDefault();
				});
			})
		</script>
	</body>
</html>
