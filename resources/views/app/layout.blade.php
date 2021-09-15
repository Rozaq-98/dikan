<!DOCTYPE html>
<html>
<head>
	{{-- head --}}
	@include('app.include.head')
	{{-- end of head --}}

	{{-- yield css--}}
	@yield('css')
	{{-- end of yield css --}}
</head>
<body class="hold-transition skin-purple-light sidebar-mini">
	<div class="wrapper">
		{{-- topbar --}}
		@include('app.include.topbar')
		{{-- end of topbar --}}

		{{-- sidebar --}}
		@include('app.include.sidebar')
		{{-- end sidebar --}}

		@yield('content')

		{{-- footer --}}
		@include('app.include.footer')
		{{-- end footer --}}

		{{-- controlSidebar --}}
		{{-- @include('app.include.controlSidebar') --}}
		{{-- end controlSidebar --}}
		
		{{-- js --}}
		@include('app.include.js')
		{{-- end of js --}}

		{{-- yield js--}}
		@yield('js')
		{{-- end of yield js --}}
	</div>
	<!-- ./wrapper -->
</body>
</html>
