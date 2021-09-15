@extends('app.layout')
@section('title', 'MB 86')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Create users</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @include('app.include.alert')
                <div class="box box-primary">
                    <!-- form start -->
                    <form action="{{url('masterdata/users/store')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="box-body">                            
                        <div class="form-group">
                                <label for="noKtp">NIS/NIK</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="NIK"
                                    name="niknis"
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
                                <label for="role">Jenis Kelamin</label>
                                <select class="form-control select2" name="jl_klm" required style="width:100%;">
                                    <option value="1">Pria</option>
                                    <option value="2">Wanita</option>  
                                </select>
                            </div>                          
                           
                        </div>
                            
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default" onclick="window.history.go(-1); return false;"><i class="fa fa-arrow-left"></i> Back</button>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i>Submit</button>
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
<script type="text/javascript" src="{{asset('js/metaimg/exif.min.js')}}"></script>

<script>
    function onClickAddNew(id){
        $('#modal-addnew').modal('show');
    }

    function onClickDelete(id){
        // set value id karyawan in modal
        $('#del-foto').val(id);

        $('#modal-delete').modal('show');
    }

    function onSubmitForm(id){
        // set value id wo in modal
        $('#submit-wo').val(id);

        $('#modal-submit').modal('show');
    }



 
</script>
