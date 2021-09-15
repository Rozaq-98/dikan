@extends('app.layout')
@section('title', 'MB 86')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Detail Team</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <!-- form start -->
                    <form role="form">
                        <div class="box-body">
                           
                            <div class="form-group">
                                <label for="notes">Username</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Username"
                                    value="{{$detailusers->username}}"
                                    readonly
                                >
                            </div>
                             
                            <div class="form-group">
                                <label for="notes">Nama Lengkap</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Phone"
                                    value="{{$detailusers->name}}"
                                    readonly
                                >
                            </div>

                            <div class="form-group">
                                <label for="notes">NIS/NIK</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Phone"
                                    value="{{$detailusers->nik}}"
                                    readonly
                                >
                            </div>

                            <div class="form-group">
                                <label for="notes">Jenis Kelamin</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Phone"
                                    value="{{$detailusers->jenis_kelamin}}"
                                    readonly
                                >
                            </div>
                            
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-default pull-right" onclick="window.history.go(-1); return false;"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
