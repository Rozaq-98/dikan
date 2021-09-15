<!DOCTYPE html>
<html>
<head>
    {{-- head --}}
	@include('app.include.head')
	{{-- end of head --}}
<body class="hold-transition login-page skin-purple ">
    <div class="login-box">
        <div class="login-logo">
            <b>Belajar Ol</b>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <h4 class="login-box-msg">Belajar OL</h4>

            <form action="{{url('/login')}}" method="post">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Username" name="username">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                @if (session('error_login'))
                    <p style="color:red; text-align:center">{{ session('error_login') }}</p>
                @endif
                <div class="row">
                    <div class="col-xs-12">
                    <button type="submit" class="btn btn-info btn-block btn-flat">Login</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
</body>
</html>
