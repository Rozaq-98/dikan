@extends('app.layout')
@section('title', 'MB 86')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Edit Users</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-xs-12">
                @include('app.include.alert')
                <div class="box box-primary">
                    <!-- form start -->
                    <form role="form" action="{{url('/masterdata/users/update')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$detailusers->id}}">
                        <div class="box-body">                           
                            <div class="form-group">
                               <div class="form-group">
                                <label for="notes">Username</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Username"
                                    value="{{$detailusers->username}}"
                                    disabled
                                    
                                >
                            </div>
                             
                            <div class="form-group">
                                <label for="notes">Nama Lengkap</label>
                                <input
                                    type="text"
                                    name="nama_lengkap"
                                    class="form-control"
                                    placeholder="Phone"
                                    value="{{$detailusers->name}}"

                                    
                                >
                            </div>

                            <div class="form-group">
                                <label for="notes">NIS/NIK</label>
                                <input
                                    type="text"
                                    name="nik"
                                    class="form-control"
                                    placeholder="Phone"
                                    value="{{$detailusers->nik}}"
                                    
                                >
                            </div>

                            <div class="form-group">
                                <label for="idcabang">Jenis Kelamin</label>
                                <select class="form-control select2" name="jenis_kelamin" id="jenis_kelamin" style="width:100%;" required>
                                   
                                        @if($detailusers->jenis_kelamin == 1)
                                            <option value="1">Pria</option>
                                            <option value="2">Perempuan</option>
                                        @endif
                                   
                                        @if($detailusers->id != 1)
                                            <option value="2">Perempuan</option>
                                            <option value="1">Pria</option>
                                        @endif
                                    
                                </select>
                            </div>
                          
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default" onclick="window.history.go(-1); return false;"><i class="fa fa-arrow-left"></i> Back</button>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                            </div>
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
@include('app.include.datepickerJs_ddmmyyyy')
<script type="text/javascript" src="{{asset('js/metaimg/exif.min.js')}}"></script>
<script type="text/javascript">
            $(document).ready(function(){

                // Format mata uang.
                $( '.uang' ).mask('000.000.000', {reverse: true});

            })
        </script>

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

    // function onClickPicture(id){

    //     var base_url = window.location.origin;
    //     var urlPath  = base_url+"/"+url;
    //     console.log(url);
    //     // set path
    //     $("#my_image").attr("src",base_url+"/"+url);
    //     $("#container1").append("<img src='"+urlPath+"' style='width: 100%; height: 100%;'>");

    //     // show modal
    //     $('#modal-picture').modal('show');
    // }

    
</script>