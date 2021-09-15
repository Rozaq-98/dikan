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
        @if(in_array(229,Session::get('ss_arrdetail'),true))
            <a href="{{ url('/masterdata/cabang/create') }}">
                <button class="btn btn-success pull-right">
                    <i class="fa fa-plus"></i> Create New
                </button>
            </a>
        @endif
        <h1>Cabang Martabak 86</h1>
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
                                    <th>Kode Cabang</th>
                                    <th>Nama Cabang</th>
                                    <th>Alamat</th>
                                    <th>Phone</th>
                                    <th class="text-center">Buka/Tutup Cabang</th>
                                    <th class="text-center">Status Cabang</th>
                                    <th class="text-center">Action</th>
                                    <!-- <th></th> -->
                                </tr>
                            </thead>
                             <?php $no = 0;?>
                        
                            <tbody>
                                @foreach ($cabang as $k_cabang)
                                   <?php $no++ ;?>
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$k_cabang->cabang_no}}</td>
                                        <td>{{$k_cabang->cabang_name}}</td>
                                        <td>{{$k_cabang->address}}</td>
                                        <td>{{$k_cabang->phone}}</td>
                                        <td class="text-center">
                                            @if($k_cabang->flag_active == 1)
                                                <span class="label label-success">Buka</span>
                                            @else
                                                <span class="label label-danger">Tutup</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($k_cabang->status_cabang == 1)
                                                <span class="label label-success">Active</span>
                                            @else
                                                <span class="label label-danger">Delete</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div role="group" aria-label="Button Group">                                      
                                               
                                                @if(in_array(230,Session::get('ss_arrdetail'),true))
                                                    <a href="{{ url('/masterdata/cabang/edit/'.$k_cabang->id) }}" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                @endif
                                                @if($k_cabang->flag_active == 1)
                                                    @if(in_array(232,Session::get('ss_arrdetail'),true))
                                                        <a href="#" class="btn btn-danger" onclick="onClickDelete({{$k_cabang->id}})" data-toggle="tooltip" data-placement="top" title="Tutup Toko">
                                                            <i class="fa fa-close"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(in_array(233,Session::get('ss_arrdetail'),true))
                                                        <a href="#" class="btn btn-success" onclick="onClickActive({{$k_cabang->id}})" data-toggle="tooltip" data-placement="top" title="Buka Toko">
                                                            <i class="fa fa-clock-o"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                                @if($k_cabang->status_cabang == 1)
                                                    @if(in_array(246,Session::get('ss_arrdetail'),true))
                                                        <a href="#" class="btn btn-danger" onclick="onClickDeleteStatus({{$k_cabang->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(in_array(247,Session::get('ss_arrdetail'),true))
                                                        <a href="#" class="btn btn-success" onclick="onClickActivetatus({{$k_cabang->id}})" data-toggle="tooltip" data-placement="top" title="Active">
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
                <form action="{{ url('masterdata/cabang/delete') }}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="" id="del-team">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Apakah kamu ingin menutup toko ?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Tutup</button>
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
                <form action="{{ url('masterdata/cabang/active') }}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="" id="act-team">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Apakah Kamu ingin membuka toko ?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Buka</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal modal-default fade" id="modal-delete-status">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form action="{{ url('masterdata/cabang/deletestatus') }}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="" id="del-status">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Apakah kamu yakin ingin menghapus ?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

{-- modal delete Status --}}
<div class="modal modal-default fade" id="modal-active-status">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form action="{{ url('masterdata/cabang/activestatus') }}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="" id="act-status">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"  aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Apakah kamu yakin ingin mengaktifkan kembali ?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Aktifkan</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@section('js')
@include('app.include.datatableJs')

<script>
    function onClickDelete(id){
        // set value id team in modal
        $('#del-team').val(id);

        // show modal
        $('#modal-delete').modal('show');
    }

    function onClickActive(id){
        // set value id team in modal
        $('#act-team').val(id);

        // show modal
        $('#modal-active').modal('show');
    }

      function onClickDeleteStatus(id){
        // set value id team in modal
        $('#del-status').val(id);

        // show modal
        $('#modal-delete-status').modal('show');
    }

     function onClickActivetatus(id){
        // set value id team in modal
        $('#act-status').val(id);

        // show modal
        $('#modal-active-status').modal('show');
    }

    $(document).ready(function() {
        var table = $('#dataTable').DataTable( {
            responsive: true,
            "columnDefs": [{
                "orderable": false, "targets": 5
            }]
        } );

        new $.fn.dataTable.FixedHeader( table );
    } );
</script>
@endsection
