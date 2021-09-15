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
        <h1>Edit Karyawan</h1>
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
                        <input type="hidden" name="iduser" value="{{$user->id}}">
                        <input type="hidden" name="idkaryawan" value="{{$karyawan->id}}">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="noKaryawan">No. Karyawan</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="noKaryawan"
                                    name="noKaryawan"
                                    placeholder="No. Karyawan"
                                    value="{{$karyawan->no_karyawan}}"
                                    onkeypress="return isNumberKey(event)"
                                    required
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
                                    value="{{$karyawan->noktp}}"
                                    onkeypress="return isNumberKey(event)"
                                    required
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
                                    value="{{$karyawan->name}}"
                                    required
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
                                    value="{{$karyawan->email}}"
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
                                    value="{{$user->username}}"
                                    readonly
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
                                <span style="color:red;">Empty this field, if you do not want to change password</span>
                            </div>                            
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="mobile"
                                    name="mobile"
                                    placeholder="Mobile"
                                    onkeypress="return isNumberKey(event)"
                                    value="{{$karyawan->mobile}}"
                                    required
                                >
                            </div>                        
                            <div class="form-group">
                                <label for="role">Role Access</label>
                                <select class="form-control select2" name="role" style="width:100%;">
                                    @foreach ($role as $k_role)
                                        @if($k_role->id == $user->id_role)
                                            <option value="{{$k_role->id}}">{{$k_role->view_name}}</option>
                                        @endif
                                    @endforeach
                                    @foreach ($role as $k_role)
                                        @if($k_role->id != $user->id_role)
                                            <option value="{{$k_role->id}}">{{$k_role->view_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cabang">Cabang</label>
                                <select class="form-control select2" name="cabang" style="width:100%;" required>
                                    @foreach ($cabang as $k_cabang)
                                        @if($cabangUser != null && $k_cabang->id == $cabangUser->id_cabang)
                                            <option value="{{$k_cabang->id}}">{{$k_cabang->cabang_name}}</option>
                                        @endif
                                    @endforeach
                                    @if($cabangUser == null)
                                        <option value="">Choose Cabang</option>
                                    @endif
                                    @foreach ($cabang as $k_cabang)
                                        @if($cabangUser == null)
                                            <option value="{{$k_cabang->id}}">{{$k_cabang->cabang_name}}</option>
                                        @elseif($k_cabang->id != $cabangUser->id_cabang)
                                            <option value="{{$k_cabang->id}}">{{$k_cabang->cabang_name}}</option>
                                        @endif
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
                                        value="{{$karyawan->join_date}}"
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
                                    value="{{$karyawan->notes}}"
                                >
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-right">
                                @if($karyawan->no_karyawan == null || $karyawan->name == null || $karyawan->email == null ||  $karyawan->mobile == null || $karyawan->join_date == null || $cabangUser == null)
                                    <button type="button" class="btn btn-default" disabled><i class="fa fa-arrow-left"></i> Back</button>
                                @else
                                    <button type="button" class="btn btn-default" onclick="window.history.go(-1); return false;"><i class="fa fa-arrow-left"></i> Back</button>
                                @endif
                                <button type="button" class="btn btn-primary" onclick=onSubmit('{{url('masterdata/karyawan/update')}}')><i class="fa fa-check"></i> Submit</button>
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
