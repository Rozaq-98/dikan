<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Session;
use DB;

use App\Models\m_users;
use App\Models\tbl_gurudansiswa;
use App\Models\m_role;
use App\Models\m_role_level;
use App\Models\m_menu_relasi;

class LoginController extends Controller
{
    function index(){
        return view('login.index');
    }

    function login(Request $req){
        // get data user
        $user = m_users::where('username', $req->username)->first();
            // dd(m_users::all());
        // checking username
        if (is_null($user)) {
            return redirect('/')->with('error_login', 'Username not found');
        }

        // checking password
        if (!(Hash::check($req->password, $user->password)) ){
            return redirect('/')->with('error_login', 'Wrong Password');
        }

        // checking flag active
        if ($user->flag_active == 0) {
            return redirect('/')->with('error_login', 'Account is not active');
        }

        // get data karyawan
        $gurudansiswa = tbl_gurudansiswa::where('id_users', $user->id)->first();

   

        // get data role level
        $roleLevel = DB::table('m_role')
                        ->join('m_role_level', 'm_role.id_role_level', 'm_role_level.levelId')
                        ->where('m_role.id', $user->id_role)
                        ->first();
        
        // get data menu relasi
        $menuRelasi = m_menu_relasi::where('id_role', $user->id_role)->get()->toArray();
       
        // get array menu master and menu detail
        $arrMaster = array();
        $arrDetail = array();
        foreach ($menuRelasi as $k_menuRelasi) {
            if (!in_array($k_menuRelasi['id_menu_master'],$arrMaster,true)) {
                array_push($arrMaster, $k_menuRelasi['id_menu_master']);
            }
            if (!in_array($k_menuRelasi['id_menu_detail'],$arrDetail,true)) {
                array_push($arrDetail, $k_menuRelasi['id_menu_detail']);
            }
        }
        // dd( $gurudansiswa);
        // die();
        // set session
        Session::put('ss_iduser', $user->id);
        Session::put('ss_username', $user->username);
        Session::put('ss_name', $user->name);
        Session::put('ss_status', $user->flag_active);
        Session::put('ss_idrole', $user->id_role);
        Session::put('ss_lvrole', $roleLevel->id_role_level);
        Session::put('ss_arrmenurelasi', $menuRelasi);
        Session::put('ss_arrmaster', $arrMaster);
        Session::put('ss_arrdetail', $arrDetail);
        Session::put('ss_namasiswadanguru', $gurudansiswa->nama_lengkap);
        Session::put('ss_nik', $gurudansiswa->nik);
        Session::put('ss_pathphoto', $gurudansiswa->path_photo);

        return redirect('/home');
    }

    function logout() {
        Session::flush();
        return redirect('/');
    }
}
