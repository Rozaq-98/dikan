<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Session;
use App\Helpers\CheckingSession;

use App\Models\m_role;
use App\Models\m_role_level;
use App\Models\m_menu_master;
use App\Models\m_menu_detail;
use App\Models\m_menu_relasi;

class RoleAccessController extends Controller
{
    function index(){
        if(CheckingSession::checking_session(1) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        $role = m_role::all();

        return view('masterdata.roleaccess.index', compact(
            'allMenuMaster',
            'allMenuDetail',
            'role'
        ));
    }

    function create(){
        if(CheckingSession::checking_session(2) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        $roleLevel  = m_role_level::all();
        $menuMaster = m_menu_master::where('flag_Active',1)->orderBy('sorting', 'asc')->get();
        $menuDetail = m_menu_detail::where('flag_Active',1)->orderBy('sort', 'asc')->get()->sortBy('detail_name');

        return view('masterdata.roleaccess.create', compact(
            'allMenuMaster',
            'allMenuDetail',
            'roleLevel',
            'menuMaster',
            'menuDetail'
        ));
    }

    function detail($id){
        if(CheckingSession::checking_session(6) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        // data default
        $menuMaster = m_menu_master::where('flag_Active',1)->orderBy('sorting', 'asc')->get();
        $menuDetail = m_menu_detail::where('flag_Active',1)->orderBy('sort', 'asc')->get()->sortBy('detail_name');

        // data this role
        $menuRole   = DB::table('m_role')
                        ->join('m_role_level', 'm_role_level.levelId', 'm_role.id_role_level')
                        ->where('id', $id)
                        ->select(
                            'm_role.id',
                            'm_role.view_name',
                            'm_role_level.levelId'
                        )->get();

        $menuRelasi = DB::table('m_menu_relasi')
                        ->join('m_role', 'm_role.id', 'm_menu_relasi.id_role')
                        ->join('m_role_level', 'm_role_level.levelId', 'm_role.id_role_level')
                        ->join('m_menu_master', 'm_menu_master.id', 'm_menu_relasi.id_menu_master')
                        ->join('m_menu_detail', 'm_menu_detail.id', 'm_menu_relasi.id_menu_detail')
                        ->where('id_role', $id)
                        ->select(
                            'm_role.id',
                            'm_role.view_name',
                            'm_role_level.levelId',
                            'm_menu_master.id as id_master',
                            'm_menu_master.name',
                            'm_menu_detail.id as id_detail',
                            'm_menu_detail.detail_name'
                        )->get();

        $thisRoleName  = null;
        $thisRoleLevel = null;
        foreach ($menuRole as $k_menuRole){
            $thisRoleName   = $k_menuRole->view_name;
            $thisRoleLevel  = $k_menuRole->levelId;
            break;
        }

        return view('masterdata.roleaccess.detail', compact(
            'allMenuMaster',
            'allMenuDetail',
            'menuMaster',
            'menuDetail',
            'menuRelasi',
            'thisRoleName',
            'thisRoleLevel'
        ));
    }

    function edit($id){
        if(CheckingSession::checking_session(3) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        // data default
        $roleLevel  = m_role_level::all();
        $menuMaster = m_menu_master::where('flag_Active',1)->orderBy('sorting', 'asc')->get();
        $menuDetail = m_menu_detail::where('flag_Active',1)->orderBy('sort', 'asc')->get()->sortBy('detail_name');

        // data this role
        $id_role    = $id;
        $menuRole   = DB::table('m_role')
                        ->join('m_role_level', 'm_role_level.levelId', 'm_role.id_role_level')
                        ->where('id', $id)
                        ->select(
                            'm_role.id',
                            'm_role.view_name',
                            'm_role_level.levelId'
                        )->get();
        $menuRelasi = DB::table('m_menu_relasi')
                        ->join('m_role', 'm_role.id', 'm_menu_relasi.id_role')
                        ->join('m_role_level', 'm_role_level.levelId', 'm_role.id_role_level')
                        ->join('m_menu_master', 'm_menu_master.id', 'm_menu_relasi.id_menu_master')
                        ->join('m_menu_detail', 'm_menu_detail.id', 'm_menu_relasi.id_menu_detail')
                        ->where('id_role', $id_role)
                        ->select(
                            'm_role.id',
                            'm_role.view_name',
                            'm_role_level.levelId',
                            'm_menu_master.id as id_master',
                            'm_menu_master.name',
                            'm_menu_detail.id as id_detail',
                            'm_menu_detail.detail_name'
                        )->get();

        $thisRoleName  = null;
        $thisRoleLevel = null;
        foreach ($menuRole as $k_menuRole){
            $thisRoleName   = $k_menuRole->view_name;
            $thisRoleLevel  = $k_menuRole->levelId;
            break;
        }

        return view('masterdata.roleaccess.edit', compact(
            'allMenuMaster',
            'allMenuDetail',
            'roleLevel',
            'menuMaster',
            'menuDetail',
            'id_role',
            'menuRelasi',
            'thisRoleName',
            'thisRoleLevel'
        ));
    }

    function store(Request $req){
        if(CheckingSession::checking_session(2) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        // get data request
        $rolename = $req->rolename;
        $rolelevel = $req->rolelevel;
        $id_menudetail = $req->id_menudetail;

        if($id_menudetail == null){
            return response()->json(array(
                'code' => 0,
                'msg'  => 'Detail page can not be empty.'
            ));
        }

        // checking role name exist - get data role name exist
        $qry_roleNameExist = "select id from m_role where lower(view_name) = lower('$rolename')";
        $roleNameExist = DB::select($qry_roleNameExist);

        // checking role name exist - count data role name exist - if > 0 => return error
        if (count($roleNameExist) > 0) {
            return response()->json(array(
                'code' => 0,
                'msg'  => 'Role name already exist'
            ));
        }
        // checking role name exist - count data role name exist - if = 0 => save tp tbl m_role
        else {
            try {
                // save role
                $saveRole = new m_role();
                $saveRole->view_name        = $rolename;
                $saveRole->id_role_level    = $rolelevel;
                $saveRole->flag_active      = 1;
                $saveRole->createdBy        = Session::get('ss_iduser');
                $saveRole->createdDtm       = date("Y-m-d H:i:s");
                $saveRole->save();

                // last id insert => id role
                $lastId = $saveRole->id;

                // save id_detail to tbl m_menu_relasi
                for ($i=0; $i < count($id_menudetail); $i++) {
                    // get id master
                    $getIdMaster = m_menu_detail::where('id', $id_menudetail[$i])->first();
                    $idMaster    = $getIdMaster->id_menu_master;

                    // save m_menu_relasi
                    $saveRelasi = new m_menu_relasi();
                    $saveRelasi->id_role        = $lastId;
                    $saveRelasi->id_menu_master = $idMaster;
                    $saveRelasi->id_menu_detail = $id_menudetail[$i];
                    $saveRelasi->flag_active    = 1;
                    $saveRelasi->createdBy      = Session::get('ss_iduser');
                    $saveRelasi->createdDtm     = date("Y-m-d H:i:s");
                    $saveRelasi->save();
                }
                return response()->json(array(
                    'code' => 1,
                    'msg'  => 'Save Successfully'
                ));
            } catch (Exception $e) {
                return response()->json(array(
                    'code' => 0,
                    'msg'  => $e->getMessage()
                ));
            }
        }
    }

    function update(Request $req){
        if(CheckingSession::checking_session(3) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // get data request
        $id_role        = $req->id_role;
        $rolename       = $req->rolename;
        $rolelevel      = $req->rolelevel;
        $id_menudetail  = $req->id_menudetail;

        if($id_menudetail == null){
            return response()->json(array(
                'code' => 0,
                'msg'  => 'Detail page can not be empty.'
            ));        }

        // checking role name exist where id != this role - get data role name exist
        $qry_roleNameExist = "select id from m_role where id <> $id_role && lower(view_name) = lower('$rolename')";
        $roleNameExist = DB::select($qry_roleNameExist);

        // checking role name exist where id != this role
        // - count data role name exist - if > 0 => return error
        if (count($roleNameExist) > 0) {
            return response()->json(array(
                'code' => 0,
                'msg'  => 'Role name already exist'
            ));        }
        // checking role name exist where id != this role
        // - count data role name exist - if = 0 => update m_role and menu relasi
        else {
            try {
                // update role
                DB::table('m_role')
                    ->where('id', $id_role)
                    ->update([
                        'view_name'     => $rolename,
                        'id_role_level' => $rolelevel,
                        'updatedBy'     => Session::get('ss_iduser'),
                        'updatedDtm'    => date("Y-m-d H:i:s")
                    ]);

                // delete relasi
                DB::table('m_menu_relasi')->where('id_role',$id_role)->delete();

                // save id_detail to tbl m_menu_relasi
                for ($i=0; $i < count($id_menudetail); $i++) {
                    // get id master
                    $getIdMaster = m_menu_detail::where('id', $id_menudetail[$i])->first();
                    $idMaster    = $getIdMaster->id_menu_master;

                    // save m_menu_relasi
                    $saveRelasi = new m_menu_relasi();
                    $saveRelasi->id_role        = $id_role;
                    $saveRelasi->id_menu_master = $idMaster;
                    $saveRelasi->id_menu_detail = $id_menudetail[$i];
                    $saveRelasi->flag_active    = 1;
                    $saveRelasi->createdBy      = Session::get('ss_iduser');
                    $saveRelasi->createdDtm     = date("Y-m-d H:i:s");
                    $saveRelasi->save();
                }

                return response()->json(array(
                    'code' => 1,
                    'msg'  => 'Update Successfully'
                ));
            } catch (Exception $e) {
                return response()->json(array(
                    'code' => 0,
                    'msg'  => $e->getMessage()
                ));
            }
        }
    }

    function delete(Request $req){
        if(CheckingSession::checking_session(4) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        $id_role = $req->id_role;

        try {
            // update role
            DB::table('m_role')
            ->where('id', $id_role)
            ->update([
                'flag_active'   => 0,
                'updatedBy'     => Session::get('ss_iduser'),
                'updatedDtm'    => date("Y-m-d H:i:s")
            ]);

            return redirect('/masterdata/roleaccess')->with('success', 'Delete Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function active(Request $req){
        if(CheckingSession::checking_session(5) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        $id_role = $req->id_role;

        try {
            // update role
            DB::table('m_role')
            ->where('id', $id_role)
            ->update([
                'flag_active'   => 1,
                'updatedBy'     => Session::get('ss_iduser'),
                'updatedDtm'    => date("Y-m-d H:i:s")
            ]);

            return redirect('/masterdata/roleaccess')->with('success', 'Active Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
