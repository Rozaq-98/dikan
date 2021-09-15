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
        <h1>Edit Role Access</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @include('app.include.alert')
                <div class="box">
                    <form id="form-submit">
                        {{csrf_field()}}
                        <input type="hidden" name="id_role" value="{{$id_role}}">
                        <div class="box-header">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label for="rolename">Role Name</label>
                                    <input type="text" name="rolename" class="form-control" placeholder="Role Name" value="{{$thisRoleName}}">
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label for="level">Level</label>
                                    <select class="form-control select2" name="rolelevel" style="width:100%">
                                        <option value="{{$thisRoleLevel}}">{{$thisRoleLevel}}</option>
                                        @foreach ($roleLevel as $k_roleLevel)
                                            @if($k_roleLevel->levelId != $thisRoleLevel)
                                                <option value="{{$k_roleLevel->levelId}}">{{$k_roleLevel->levelId}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th>Master Page</th>
                                        <th>Detail Page</th>
                                    </tr>
                                    @foreach ($menuMaster as $k_menuMaster)
                                        <tr>
                                            <td>{{$k_menuMaster->name}}</td>
                                            <td style="vertical-align: bottom">
                                                @foreach ($menuDetail as $k_menuDetail)
                                                    @if ($k_menuMaster->id == $k_menuDetail->id_menu_master)
                                                        <div class="checkbox">
                                                            <label>
                                                                <input
                                                                    type="checkbox"
                                                                    name="id_menudetail[]"
                                                                    id="id_detail-{{$k_menuDetail->id}}"
                                                                    value="{{$k_menuDetail->id}}"
                                                                >{{$k_menuDetail->detail_name}}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default" onclick="window.history.go(-1); return false;"><i class="fa fa-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-primary" onclick=onSubmit('{{url('masterdata/roleaccess/update')}}')><i class="fa fa-check"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('js')
<script>
$( document ).ready(function() {
    @foreach($menuRelasi as $k_menuRelasi)
        var idDetail = "#id_detail-"+{{$k_menuRelasi->id_detail}};
        $(idDetail).prop('checked', true);
    @endforeach
});
</script>
@include('app.include.select2Js')
<script>
    function refModalAlert(){
        var code = $('#modal-alert-code').val();
        if(code == 1){
            window.location.href= "{{url('/masterdata/roleaccess')}}";
        }
    }
</script>
@endsection
