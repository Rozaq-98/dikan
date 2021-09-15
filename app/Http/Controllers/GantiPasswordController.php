<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use DB;
use Session;

use App\Models\m_users;
use App\Models\m_menu_master;
use App\Models\m_menu_detail;

class GantiPasswordController extends Controller
{
    function index(Request $req){
        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        $users       = m_users::where('id', $req->id)->first();

        return view('profile.edit', compact(
            'allMenuMaster',
            'allMenuDetail',
            'users'
        ));
    }

    function update(Request $req){
        // get data user
        $user = m_users::where('username', Session::get('ss_username'))->first();

        // checking password
        if (!(Hash::check($req->old_password, $user->password)) ){
            return response()->json(array(
                'code' => 0,
                'msg'  => 'Current password is wrong'
            ));
        }

        // checking retype password       
         if($req->new_password != $req->re_password){
            return response()->json(array(
                'code' => 0,
                'msg'  => 'Re-type password is wrong'
            ));
        }
        
        DB::beginTransaction();
        try {
            // update password
            DB::table('m_users')
            ->where('username', Session::get('ss_username'))
            ->update([
                'password'      => Hash::make($req->new_password),
                'updatedBy'     => Session::get('ss_iduser'),
                'updatedDtm'    => date("Y-m-d H:i:s")
            ]);

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
}
