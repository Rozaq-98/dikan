@extends('app.layout')
@section('title', 'MB 86')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Create Cabang</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @include('app.include.alert')
                <div class="box box-primary">
                    <!-- form start -->
                    <form action="{{url('masterdata/cabang/store')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="box-body">                            
                            <div class="form-group">
                                <label for="namaproduct">Kode Cabang</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="kodecabang"
                                    name="kodecabang"
                                    placeholder="Kode Cabang"
                                    value=""
                                    required
                                >
                            </div>
                            <div class="form-group">
                                <label for="namacabang">Nama Cabang</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="namacabang"
                                    name="namacabang"
                                    placeholder="Nama Cabang"
                                    value=""
                                >
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="alamat"
                                    name="alamat"
                                    placeholder="Alamat"
                                    value=""
                                >
                            </div>
                             <div class="form-group">
                                <label for="alamat">Phone</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    placeholder="Phone"
                                    value=""
                                >
                            </div>
                            <div class="form-group">
                                <label for="alamat">Lokasi Maps</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="maps"
                                    placeholder="Lokasi Maps"
                                    value=""
                                >
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
