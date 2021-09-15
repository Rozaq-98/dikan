@extends('app.layout')
@section('title', 'MB 86')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Detail Karyawan</h1>
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
                                <label for="noKaryawan">No. Karyawan</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="noKaryawan"
                                    name="noKaryawan"
                                    placeholder="No. Karyawan"
                                    value="{{$karyawan->no_karyawan}}"
                                    readonly
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
                                    readonly
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
                                    readonly
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
                                    readonly
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
                            {{-- <div class="form-group">
                                <label for="password">Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    name="password"
                                    id="password"
                                    placeholder="Password"
                                    value=""
                                >
                            </div> --}}
                                                              
                            <div class="form-group">
                                <label for="role">Role Access</label>
                                <select class="form-control" name="role" disabled>
                                    {{-- <option value="">Choose Role Access</option> --}}
                                    @foreach ($role as $k_role)
                                        @if($k_role->id == $user->id_role)
                                            <option value="{{$k_role->id}}">{{$k_role->view_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cabang">Cabang</label>
                                <select class="form-control" name="cabang" disabled>
                                    {{-- <option value="">Choose cabang Access</option> --}}
                                    @foreach ($cabang as $k_cabang)
                                        @if($cabangUser != null && $k_cabang->id == $cabangUser->id_cabang)
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
                                        disabled
                                    >
                                    <div class="input-group-addon" style="background-color:#eee">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="notes">notes</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="notes"
                                    name="notes"
                                    placeholder="notes"
                                    value="{{$karyawan->notes}}"
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
