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
        <h1>Create Role Access</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @include('app.include.alert')
                <div class="box">
                    <form id="form-submit">
                        {{csrf_field()}}
                        <div class="box-header">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label for="rolename">Role Name</label>
                                    <input type="text" name="rolename" class="form-control" placeholder="Role Name" value="" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label for="level">Level</label>
                                    <select class="form-control select2" name="rolelevel" required style="width:100%">
                                        <option value="">Choose Level</option>
                                        @foreach ($roleLevel as $k_roleLevel)
                                            <option value="{{$k_roleLevel->levelId}}">{{$k_roleLevel->levelId}}</option>
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
                                        <th width="40%">Master Page</th>
                                        <th>Detail Page</th>
                                    </tr>
                                    @foreach ($menuMaster as $k_menuMaster)
                                        <tr>
                                            <td>{{$k_menuMaster->name}}</td>
                                            <td style="vertical-align: bottom">
                                                @foreach ($menuDetail as $k_menuDetail)
                                                    @if ($k_menuMaster->id == $k_menuDetail->id_menu_master)
                                                        <div class="checkbox">
                                                            <label><input type="checkbox" name="id_menudetail[]" value="{{$k_menuDetail->id}}">{{$k_menuDetail->detail_name}}</label>
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
                                <button type="button" class="btn btn-primary" onclick=onSubmit('{{url('masterdata/roleaccess/store')}}')><i class="fa fa-check"></i> Submit</button>
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
