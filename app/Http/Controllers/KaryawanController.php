<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Session;
use App\Helpers\CheckingSession;

use App\Models\m_menu_master;
use App\Models\m_menu_detail;
use App\Models\m_bank;
use App\Models\m_role;
use App\Models\m_loc_provinsi;
use App\Models\m_loc_kota;
use App\Models\m_karyawan;
use App\Models\m_users;
use App\Models\m_cabang;
use App\Models\rel_cabangkaryawan;

class KaryawanController extends Controller
{
    function index(){
        if(CheckingSession::checking_session(8) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        $users = DB::table('m_users')
                    ->join('m_role', 'm_role.id', 'm_users.id_role')
                    ->join('m_karyawan', 'm_users.id_karyawan', 'm_karyawan.id')
                    ->leftjoin('rel_cabangkaryawan', 'm_karyawan.id', 'rel_cabangkaryawan.id_karyawan')
                    ->leftjoin('m_cabang', 'm_cabang.id', 'rel_cabangkaryawan.id_cabang')
                    ->select(
                        'm_users.id',
                        'm_users.username',
                        'm_users.name',
                        'm_users.flag_active',
                        'm_role.view_name',
                        'm_cabang.cabang_name'
                    )->get();

        return view('masterdata.karyawan.index', compact(
            'users',
            'allMenuMaster',
            'allMenuDetail'
        ));
    }

    function create(){
        if(CheckingSession::checking_session(9) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

       
        $role       = m_role::all();
        $cabang     = m_cabang::orderBy('cabang_name')->get();

        return view('masterdata.karyawan.create', compact(           
            'role',            
            'allMenuMaster',
            'allMenuDetail',
            'cabang'
        ));
    }

    function detail($id){
        if(CheckingSession::checking_session(13) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        // data default form      
        $role       = m_role::all();   
        $cabang     = m_cabang::orderBy('cabang_name')->get();

        // data user
        $user       = m_users::where('id', $id)->first();
        $karyawan   = m_karyawan::where('id', $user->id_karyawan)->first();
        $cabangUser = rel_cabangkaryawan::where('id_karyawan',$user->id_karyawan)->first();
        // dd($karyawan);
        return view('masterdata.karyawan.detail', compact(
           
            'role',
            'user',
            'karyawan',
            'allMenuMaster',
            'allMenuDetail',
            'cabang',
            'cabangUser'
        ));
    }

    function edit($id){
        if(CheckingSession::checking_session(10) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        // data default form
        
        $role       = m_role::all();
        
        
        $cabang     = m_cabang::orderBy('cabang_name')->get();

        // data user
        $user       = m_users::where('id', $id)->first();
        $karyawan   = m_karyawan::where('id', $user->id_karyawan)->first();
        $cabangUser = rel_cabangkaryawan::where('id_karyawan',$user->id_karyawan)->first();

        return view('masterdata.karyawan.edit', compact(
            
            'role',
            'user',
            'karyawan',
            'allMenuMaster',
            'allMenuDetail',
            'cabang',
            'cabangUser'
        ));
    }

    function store(Request $req){
        if(CheckingSession::checking_session(9) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // convert uang to int
       

        $checkKaryawan = m_karyawan::where('no_karyawan', $req->noKaryawan)->first();
        if($checkKaryawan != null) {
            return response()->json(array(
                'code' => 0,
                'msg'  => 'No. Karyawan is exist.'
            ));
        }

        if($checkKaryawan != null && $checkKaryawan->noktp != null) {
            return response()->json(array(
                'code' => 0,
                'msg'  => 'No. KTP is exist.'
            ));
        }

        $checkUser = m_users::where('username', $req->username)->first();
        if($checkUser != null) {
            return response()->json(array(
                'code' => 0,
                'msg'  => 'Username is exist.'
            ));
        }

        DB::beginTransaction();
        try {
            // save to m_karyawan
            $saveKaryawan = new m_karyawan();
            $saveKaryawan->no_karyawan          = $req->noKaryawan;
            $saveKaryawan->noktp                = $req->noKtp;
            $saveKaryawan->email                = $req->email;
            $saveKaryawan->name                 = $req->namaLengkap;
            $saveKaryawan->mobile               = $req->mobile;           
            $saveKaryawan->join_date            = $req->joindate;
            $saveKaryawan->flag_active          = 1;
            $saveKaryawan->createdBy            = Session::get('ss_iduser');
            $saveKaryawan->createdDtm           = date("Y-m-d H:i:s");
            $saveKaryawan->notes                = $req->notes;
            $saveKaryawan->save();

            // last insert id karyawan
            $lastId = $saveKaryawan->id;

            // save to m_users
            $saveUsers = new m_users();
            $saveUsers->id_karyawan = $lastId;
            $saveUsers->username    = $req->username;
            $saveUsers->password    = bcrypt($req->password);
            $saveUsers->email       = $req->email;
            $saveUsers->name        = $req->namaLengkap;
            $saveUsers->mobile      = $req->mobile;
            $saveUsers->id_role     = $req->role;
            $saveUsers->flag_active = 1;
            $saveUsers->createdBy   = Session::get('ss_iduser');
            $saveUsers->createdDtm  = date("Y-m-d H:i:s");
            $saveUsers->save();

            // save to rel_cabangkaryawan
            $saveRelCabang              = new rel_cabangkaryawan();
            $saveRelCabang->id_cabang   = $req->cabang;
            $saveRelCabang->id_karyawan = $lastId;
            $saveRelCabang->flag_active = 1;
            $saveRelCabang->createdBy   = Session::get('ss_iduser');
            $saveRelCabang->createdDtm  = date("Y-m-d H:i:s");
            $saveRelCabang->save();

            DB::commit();
            return response()->json(array(
                'code' => 1,
                'msg'  => 'Save Successfully'
            ));
        } catch (Execption $e) {
            DB::rollback();
            return response()->json(array(
                'code' => 0,
                'msg'  => $e->getMessage()
            ));
        }
    }

    function update(Request $req){
        if(CheckingSession::checking_session(10) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        $id_user     = $req->iduser;
        $id_karyawan = $req->idkaryawan;
     

        $checkKaryawanEdit = DB::table('m_karyawan')
                                ->join('m_users', 'm_karyawan.id', 'm_users.id_karyawan')
                                ->where('m_users.id', $req->iduser)
                                ->select('m_karyawan.*')
                                ->first();

        $checkKaryawan = m_karyawan::where('no_karyawan', $req->noKaryawan)->first();
        if($req->noKaryawan == null) {
            return response()->json(array(
                'code' => 0,
                'msg'  => 'No. Karyawan is empty.'
            ));
        }
        if($checkKaryawan != null && $checkKaryawanEdit->id != $checkKaryawan->id) {
            return response()->json(array(
                'code' => 0,
                'msg'  => 'No. Karyawan is exist.'
            ));
        }

        if($req->noKtp == null) {
            return response()->json(array(
                'code' => 0,
                'msg'  => 'No. KTP is empty.'
            ));
        }
        $checkKaryawanKtp = m_karyawan::where('noktp', $req->noKtp)->first();
        if($checkKaryawanKtp != null && $checkKaryawanEdit != null) {
            if($checkKaryawanEdit->noktp != null && $checkKaryawanKtp->noKtp!= null){
                if($checkKaryawanEdit->noktp != $checkKaryawanKtp->noKtp){
                    return response()->json(array(
                        'code' => 0,
                        'msg'  => 'No. KTP is exist.'
                    ));
                }
            }
        }

        if($req->cabang == null){
            return response()->json(array(
                'code' => 0,
                'msg'  => 'Cabang is empty'
            ));
        }

        DB::beginTransaction();
        try {
            // insert into m_users_hisedit
            DB::insert("
                insert into m_users_hisedit
                    (
                        id,id_karyawan,username,password,email,name,mobile,id_role,flag_active,
                        isUpdated,isDeleted,createdBy,createdDtm,updatedBy,updatedDtm,editedBy,editedDtm
                    )
                select *,".Session::get('ss_iduser').",'".date("Y-m-d H:i:s")."' from m_users where id = ?
            ",[$id_user]);

            // checking password empty
            if ($req->password == null) {
                // update m_users
                DB::table('m_users')
                ->where('id', $id_user)
                ->update([
                    'email'         => $req->email,
                    'name'          => $req->namaLengkap,
                    'mobile'        => $req->mobile,
                    'id_role'       => $req->role,
                    'updatedBy'     => Session::get('ss_iduser'),
                    'updatedDtm'    => date("Y-m-d H:i:s")
                ]);
            } else {
                // update m_users
                DB::table('m_users')
                ->where('id', $id_user)
                ->update([
                    'password'      => bcrypt($req->password),
                    'email'         => $req->email,
                    'name'          => $req->namaLengkap,
                    'mobile'        => $req->mobile,
                    'id_role'       => $req->role,
                    'updatedBy'     => Session::get('ss_iduser'),
                    'updatedDtm'    => date("Y-m-d H:i:s")
                ]);
            }

            // insert into m_karyawan_hisedit
            DB::insert("
                insert into m_karyawan_hisedit
                    (
                        id,no_karyawan,email,name,mobile,flag_active,isUpdated,isDeleted,join_date,createdBy,createdDtm,updatedBy,updatedDtm,noktp,notes,editedBy,editedDtm
                    )
                select *,".Session::get('ss_iduser').",'".date("Y-m-d H:i:s")."' from m_karyawan where id = ?
            ",[$id_karyawan]);

            // update m_karyawan
            DB::table('m_karyawan')
                ->where('id', $id_karyawan)
                ->update([
                    'no_karyawan'       => $req->noKaryawan,
                    'email'             => $req->email,
                    'name'              => $req->namaLengkap,
                    'mobile'            => $req->mobile,                    
                    'join_date'         => $req->joindate,
                    'updatedBy'         => Session::get('ss_iduser'),
                    'updatedDtm'        => date("Y-m-d H:i:s"),
                    'noktp'             => $req->noKtp,
                    'notes'             => $req->notes
                ]);

            // insert into rel_cabangkaryawan_hisedit
            DB::insert("
                insert into rel_cabangkaryawan_hisedit
                    (
                        id,id_cabang,id_karyawan,notes,flag_active,isUpdated,isDeleted,
                        createdBy,createdDtm,updatedBy,updatedDtm,editedBy,editedDtm
                    )
                select *,".Session::get('ss_iduser').",'".date("Y-m-d H:i:s")."' from rel_cabangkaryawan where id_karyawan = ?
            ",[$id_karyawan]);

            // update rel_cabangkaryawan
            $checkRelCabangKar = rel_cabangkaryawan::where('id_karyawan', $id_karyawan)->first();
            if($checkRelCabangKar == null){
                // save to rel_cabangkaryawan
                $saveRelCabang              = new rel_cabangkaryawan();
                $saveRelCabang->id_cabang   = $req->cabang;
                $saveRelCabang->id_karyawan = $id_karyawan;
                $saveRelCabang->flag_active = 1;
                $saveRelCabang->createdBy   = Session::get('ss_iduser');
                $saveRelCabang->createdDtm  = date("Y-m-d H:i:s");
                $saveRelCabang->save();
            } else {
                DB::table('rel_cabangkaryawan')
                ->where('id_karyawan', $id_karyawan)
                ->update([
                    'id_cabang'     => $req->cabang,
                    'updatedBy'     => Session::get('ss_iduser'),
                    'updatedDtm'    => date("Y-m-d H:i:s")
                ]);
            }

            DB::commit();
            return response()->json(array(
                'code' => 1,
                'msg'  => 'Update Successfully'
            ));
        } catch (Execption $e) {
            DB::rollback();
            return response()->json(array(
                'code' => 0,
                'msg'  => $e->getMessage()
            ));        }
    }

    function delete(Request $req){
        if(CheckingSession::checking_session(11) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        $id_user = $req->id_user;

        try {
            $dataUsers = m_users::where('id', $id_user)->first();

            // update flag
            DB::table('m_users')
            ->where('id', $id_user)
            ->update([
                'flag_active'   => 0,
                'updatedBy'     => Session::get('ss_iduser'),
                'updatedDtm'    => date("Y-m-d H:i:s")
            ]);

            DB::table('m_karyawan')
            ->where('id', $dataUsers->id_karyawan)
            ->update([
                'flag_active'   => 0,
                'updatedBy'     => Session::get('ss_iduser'),
                'updatedDtm'    => date("Y-m-d H:i:s")
            ]);

            return redirect('/masterdata/karyawan')->with('success', 'Delete Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function active(Request $req){
        if(CheckingSession::checking_session(12) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        $id_user = $req->id_user;

        try {
            $dataUsers = m_users::where('id', $id_user)->first();

            // update role
            DB::table('m_users')
            ->where('id', $id_user)
            ->update([
                'flag_active'   => 1,
                'updatedBy'     => Session::get('ss_iduser'),
                'updatedDtm'    => date("Y-m-d H:i:s")
            ]);

            DB::table('m_karyawan')
            ->where('id', $dataUsers->id_karyawan)
            ->update([
                'flag_active'   => 1,
                'updatedBy'     => Session::get('ss_iduser'),
                'updatedDtm'    => date("Y-m-d H:i:s")
            ]);

            return redirect('/masterdata/karyawan')->with('success', 'Active Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
