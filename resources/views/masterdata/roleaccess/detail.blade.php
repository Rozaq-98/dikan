@extends('app.layout')
@section('title', 'MB 86')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Detail Role Access</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @include('app.include.alert')
                <div class="box">
                    <div class="box-header">
                        <form action="">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label for="rolename">Role Name</label>
                                    <input type="text" name="rolename" class="form-control" placeholder="Role Name" value="{{$thisRoleName}}" readonly>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label for="level">Level</label>
                                    <select class="form-control" readonly>
                                        <option>{{$thisRoleLevel}}</option>
                                    </select>
                                </div>
                            </div>
                        </form>
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
                                                                disabled
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
                        <button type="submit" class="btn btn-default pull-right" onclick="window.history.go(-1); return false;"><i class="fa fa-arrow-left"></i> Back</button>
                    </div>
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
@endsection