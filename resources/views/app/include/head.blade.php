<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>@yield('title')</title>

<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/AdminLTE.min.css') }}">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/skins/_all-skins.min.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/select2/dist/css/select2.min.css') }}">
{{-- my style --}}
<link rel="stylesheet" href="{{ asset('myStyle.css') }}">