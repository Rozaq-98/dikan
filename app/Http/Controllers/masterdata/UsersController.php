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
use App\Models\tbl_gurudansiswa;

class UsersController extends Controller
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
                    ->join('tbl_gurudansiswa', 'm_users.id', 'tbl_gurudansiswa.id_users')                   
                    ->select(
                        'm_users.id',
                        'm_users.username',
                        'm_users.name',
                        'm_users.flag_active',
                        'm_role.view_name',
                        'tbl_gurudansiswa.nama_lengkap',
                        'tbl_gurudansiswa.nik',
                        'tbl_gurudansiswa.jenis_kelamin',
                    )->get();

        return view('masterdata.users.index', compact(
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

        return view('masterdata.users.create', compact(
            
            'allMenuMaster',
            'allMenuDetail',
            'role'
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

      $detailusers = DB::table('m_users')
                    ->join('m_role', 'm_role.id', 'm_users.id_role')
                    ->join('tbl_gurudansiswa', 'm_users.id', 'tbl_gurudansiswa.id_users') 
                    ->where('m_users.id',$id)
                    ->select(
                        'm_users.id',
                        'm_users.username',
                        'm_users.name',
                        'm_users.flag_active',
                        'm_role.view_name',
                        'tbl_gurudansiswa.nama_lengkap',
                        'tbl_gurudansiswa.nik',
                        DB::raw('(case when tbl_gurudansiswa.jenis_kelamin = 1 then "pria" else "perempuan" end) as jenis_kelamin ')
                    )->first();

        // dd($detailusers);
        return view('masterdata.users.detail', compact(
            'detailusers',
            'allMenuMaster',
            'allMenuDetail',
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
        
        $detailusers = DB::table('m_users')
                    ->join('m_role', 'm_role.id', 'm_users.id_role')
                    ->join('tbl_gurudansiswa', 'm_users.id', 'tbl_gurudansiswa.id_users') 
                    ->where('m_users.id',$id)
                    ->select(
                        'm_users.id',
                        'm_users.username',
                        'm_users.name',
                        'm_users.flag_active',
                        'm_role.view_name',
                        'tbl_gurudansiswa.nama_lengkap',
                        'tbl_gurudansiswa.nik',
                        'tbl_gurudansiswa.jenis_kelamin'
                    )->first();
                    // dd($detailusers);

        return view('masterdata.users.edit', compact(
           
           
            'allMenuMaster',
            'allMenuDetail',
            'detailusers'
        ));
    }

    function store(Request $req){
        if(CheckingSession::checking_session(9) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }
        
        try {


             // save to m_users
            $saveUsers = new m_users();
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

            $idusers = $saveUsers->id;

            $saveData                       = new tbl_gurudansiswa();
            $saveData->id_users                   = $idusers;
            $saveData->jenis_kelamin        = $req->jl_klm;
            $saveData->nama_lengkap         = $req->namaLengkap;
            $saveData->nik                  = $req->niknis;
            $saveData->flag_active          = 1;
            $saveData->created_by           = Session::get('ss_iduser');
            $saveData->created_at           = date("Y-m-d H:i:s");
            $saveData->save();


            DB::commit();    
           return redirect('/masterdata/users')->with('success', 'Save Successfully');
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
       
    }

    function update(Request $req){
        if(CheckingSession::checking_session(10) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        $id_user     = $req->id;

        try {
             DB::table('tbl_gurudansiswa')
            ->where('id', $id_user)
            ->update([
                'nama_lengkap'      => $req->nama_lengkap,
                'nik'               => $req->nik,
                'jenis_kelamin'     => $req->jenis_kelamin,
                'updatedBy'         => Session::get('ss_iduser'),
                'updatedDtm'        => date("Y-m-d H:i:s")
            ]);
         return redirect('/masterdata/users')->with('success', 'Update Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    function delete(Request $req){
        if(CheckingSession::checking_session(11) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        $id_user = $req->id;

        try {
            // $dataUsers = m_users::where('id', $id_user)->first();

            // update flag
            DB::table('m_users')
            ->where('id', $id_user)
            ->update([
                'flag_active'   => 0,
                'updatedBy'     => Session::get('ss_iduser'),
                'updatedDtm'    => date("Y-m-d H:i:s")
            ]);

            DB::table('tbl_gurudansiswa')
            ->where('id', $id_user)
            ->update([
                'flag_active'   => 0                
            ]);

            return redirect('/masterdata/users')->with('success', 'Delete Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function active(Request $req){
        if(CheckingSession::checking_session(12) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        $id_user = $req->id;

        try {
              // update flag
            DB::table('m_users')
            ->where('id', $id_user)
            ->update([
                'flag_active'   => 1,
                'updatedBy'     => Session::get('ss_iduser'),
                'updatedDtm'    => date("Y-m-d H:i:s")
            ]);

            DB::table('tbl_gurudansiswa')
            ->where('id', $id_user)
            ->update([
                'flag_active'   => 1                
            ]);

            return redirect('/masterdata/users')->with('success', 'Active Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
