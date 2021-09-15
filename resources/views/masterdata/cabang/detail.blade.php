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
                                <label for="teamname">Kode Cabang</label>
                                <input
                                    type="text"
                                    class="form-control isNameSpace"                                   
                                    placeholder="Kode Cabang"
                                    value="{{$team->team_name}}"
                                    readonly
                                >
                            </div>
                            <div class="form-group">
                                <label for="notes">Nama Cabang</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Nama Cabang"
                                    value="{{$team->notes}}"
                                    readonly
                                >
                            </div>
                             <div class="form-group">
                                <label for="notes">Alamat</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Alamat"
                                    value="{{$team->notes}}"
                                    readonly
                                >
                            </div>
                            <div class="form-group">
                                <label for="notes">Phone</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Phone"
                                    value="{{$team->notes}}"
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
