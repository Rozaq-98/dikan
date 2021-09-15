@extends('app.layout')
@section('title', 'MB 86')
@section('content')
@section('css')
@include('app.include.datatableCss')
@endsection
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        @if(in_array(9,Session::get('ss_arrdetail'),true))
            <a href="{{ url('/masterdata/karyawan/create') }}">
                <button class="btn btn-success pull-right">
                    <i class="fa fa-plus"></i> Create New
                </button>
            </a>
        @endif
        <h1>Karyawan</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @include('app.include.alert')
                <div class="box">
                    <div class="box-body">
                        <table id="dataTable" class="table table-striped table-bordered table-hover nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Role Access</th>
                                    <th>Cabang</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <?php $no = 0;?>
                            <tbody>
                                @foreach ($users as $k_users)
                                <?php $no++ ;?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$k_users->username}}</td>
                                        <td>{{$k_users->name}}</td>
                                        <td>{{$k_users->view_name}}</td>
                                        <td>{{$k_users->cabang_name}}</td>
                                        <td class="text-center">
                                            @if($k_users->flag_active == 1)
                                                <span class="label label-success">Active</span>
                                            @else
                                                <span class="label label-danger">Deleted</span>
                                            @endif
                                        </td>
                                        <td class="text-center no-sort">
                                            <div role="group" aria-label="Button Group">
                                                @if(in_array(12,Session::get('ss_arrdetail'),true))
                                                    <a href="{{ url('/masterdata/karyawan/detail/'.$k_users->id) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Detail">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endif
                                                @if(in_array(10,Session::get('ss_arrdetail'),true))
                                                    <a href="{{ url('/masterdata/karyawan/edit/'.$k_users->id) }}" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                @endif
                                                @if($k_users->flag_active == 1)
                                                    @if(in_array(11,Session::get('ss_arrdetail'),true))
                                                        <a href="#" class="btn btn-danger" onclick="onClickDelete({{$k_users->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(in_array(12,Session::get('ss_arrdetail'),true))
                                                        <a href="#" class="btn btn-success" onclick="onClickActive({{$k_users->id}})" data-toggle="tooltip" data-placement="top" title="Active">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

{{-- modal delete --}}
<div class="modal modal-default fade" id="modal-delete">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form action="{{ url('masterdata/karyawan/delete') }}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="id_user" value="" id="del-karyawan">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Are You Sure To Delete ?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    {{-- modal active --}}
    <div class="modal modal-default fade" id="modal-active">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form action="{{ url('masterdata/karyawan/active') }}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="id_user" value="" id="act-karyawan">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Are You Sure To Active ?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
@section('js')
@include('app.include.datatableJs')

<script>
    function onClickDelete(id){
        // set value id karyawan in modal
        $('#del-karyawan').val(id);

        // show modal
        $('#modal-delete').modal('show');
    }

    function onClickActive(id){
        // set value id karyawan in modal
        $('#act-karyawan').val(id);

        // show modal
        $('#modal-active').modal('show');
    }

    $(document).ready(function() {
        var table = $('#dataTable').DataTable( {
            responsive: true,

            // disabled column action
            "columnDefs": [{
                "orderable": false, "targets": 5
            }]
        } );

        new $.fn.dataTable.FixedHeader( table );
    } );
</script>
@endsection
