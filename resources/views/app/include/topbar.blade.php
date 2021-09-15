<header class="main-header">
	<!-- Logo -->
	<a href="{{ url('/home') }}" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><b>Belajar Online</b></span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><b>Belajar Online</b></span>
	</a>
	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar navbar-static-top">
		<!-- Sidebar toggle button-->
		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<!-- Messages: style can be found in dropdown.less-->
				<!-- Notifications: style can be found in dropdown.less -->
				<!-- Tasks: style can be found in dropdown.less -->

				<!-- User Account: style can be found in dropdown.less -->
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{ asset('images/mb.png') }}" class="user-image" alt="User Image">
						<span class="hidden-xs">{{Session::get('ss_username')}}</span>
					</a>
					<ul class="dropdown-menu">
						<!-- User image -->
						<li class="user-header">
							<br>
							<img src="{{ asset('images/mb.png') }}" class="img-circle" alt="User Image">
							<p>{{Session::get('ss_username')}}</p>
						</li>

						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="text-center">
								<a href="{{ url('/gantipassword/'.Session::get('ss_iduser')) }}" class="btn btn-default btn-flat">Change Password</a>
								<a href="#modal-logout" class="btn btn-default btn-flat" data-toggle="modal" data-target="#modal-logout">Sign out</a>
							</div>
						</li>
					</ul>
				</li>
				<!-- Control Sidebar Toggle Button -->
				{{-- <li>
					<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
				</li> --}}
			</ul>
		</div>
	</nav>
</header>

{{-- modal logout --}}
<div class="modal modal-default fade" id="modal-logout">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form action="{{ url('/logout') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Are You Sure To Logout ?</h4>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-danger">Submit</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{{-- modal alert --}}
<div class="modal modal-default fade" id="modal-alert">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-alert-msg"></h4>
                <input type="hidden" name="modal-alert-code" id="modal-alert-code">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="refModalAlert()">OK</button>
            </div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
