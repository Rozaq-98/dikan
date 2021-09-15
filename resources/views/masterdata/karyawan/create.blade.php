@extends('app.layout')
@section('title', 'MB 86')
@section('css')
@include('app.include.select2Css')
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Create Karyawan</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @include('app.include.alert')
                <div class="box box-primary">
                    <!-- form start -->
                    <form id="form-submit">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="noKaryawan">No. Karyawan</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="noKaryawan"
                                    name="noKaryawan"
                                    placeholder="No. Karyawan"
                                    onkeypress="return isNumberKey(event)"
                                    value=""
                                >
                            </div>
                            <div class="form-group">
                                <label for="noKtp">No. KTP</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="noKtp"
                                    name="noKtp"
                                    placeholder="No. KTP"
                                    onkeypress="return isNumberKey(event)"
                                    value=""
                                >
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">Nama Lengkap</label>
                                <input
                                    type="text"
                                    class="form-control isNameSpace"
                                    id="namaLengkap"
                                    name="namaLengkap"
                                    placeholder="Nama Lengkap"
                                    value=""
                                >
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="Email"
                                    value=""
                                    required
                                >
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="username"
                                    name="username"
                                    placeholder="Username"
                                    value=""
                                >
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    name="password"
                                    id="password"
                                    placeholder="Password"
                                    value=""
                                >
                            </div>                          
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="mobile"
                                    name="mobile"
                                    placeholder="Mobile"
                                    value=""
                                    onkeypress="return isNumberKey(event)"
                                >
                            </div>                           
                            <div class="form-group">
                                <label for="role">Role Access</label>
                                <select class="form-control select2" name="role" required style="width:100%;">
                                    <option value="">Choose Role Access</option>
                                    @foreach ($role as $k_role)
                                        <option value="{{$k_role->id}}">{{$k_role->view_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cabang">Cabang</label>
                                <select class="form-control select2" name="cabang" required style="width:100%;" required>
                                    <option value="">Choose Cabang</option>
                                    @foreach ($cabang as $k_cabang)
                                        <option value="{{$k_cabang->id}}">{{$k_cabang->cabang_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Join Date</label>
                                <div class="input-group date">
                                    <input
                                        type="text"
                                        class="form-control pull-right datepicker"
                                        id="joindate"
                                        name="joindate"
                                        placeholder="Join Date"
                                        value=""
                                        style="border-radius: 0px"
                                        autocomplete="off"
                                        required
                                    >
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="notes"
                                    name="notes"
                                    placeholder="Notes"
                                    value=""
                                >
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default" onclick="window.history.go(-1); return false;"><i class="fa fa-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-primary" onclick=onSubmit('{{url('masterdata/karyawan/store')}}')><i class="fa fa-check"></i> Submit</button>
                            </div>
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
@section('js')
@include('app.include.select2Js')
@include('app.include.datepickerJs')
<script>
    function refModalAlert(){
        var code = $('#modal-alert-code').val();
        if(code == 1){
            window.location.href= "{{url('/masterdata/karyawan')}}";
        }
    }
</script>
@endsection
