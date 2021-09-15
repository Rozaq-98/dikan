<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use DB;
use Session;

use App\Models\m_users;
use App\Models\m_menu_master;
use App\Models\m_menu_detail;

class ProfileController extends Controller
{
    function indexChangePassword(){
        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        return view('profile.changepassword', compact(
            'allMenuMaster',
            'allMenuDetail'
        ));
    }

    function storeChangePassword(Request $req){
        // get data user
        $user = m_users::where('username', Session::get('ss_username'))->first();

        // checking password
        if (!(Hash::check($req->old_password, $user->password)) ){
            return redirect()->back()->with('error', 'Current password is wrong');
        }

        // checking retype password
        if ($req->new_password != $req->re_password) {
            return redirect()->back()->with('error', 'Re-type password is wrong');
        }

        try {
            // update password
            DB::table('m_users')
            ->where('username', Session::get('ss_username'))
            ->update([
                'password'      => Hash::make($req->new_password),
                'updatedBy'     => Session::get('ss_iduser'),
                'updatedDtm'    => date("Y-m-d H:i:s")
            ]);

            return redirect('/home')->with('success', 'Change password successfully');
        } catch (Exeption $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
