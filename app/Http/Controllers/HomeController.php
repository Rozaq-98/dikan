<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Helpers\CheckingSession;

use App\Models\m_menu_master;
use App\Models\m_menu_detail;

class HomeController extends Controller
{
    public function index()
    {
        if(CheckingSession::checking_session(7) == false){
            return redirect('/')->with('error_login', 'Can not access this page');
        }

        // for sidebar
        $allMenuMaster = m_menu_master::where('flag_active', '1')->orderBy('sorting', 'asc')->get();
        $allMenuDetail = m_menu_detail::where('detail_name', 'not like', '%-%')
                                        ->where('flag_active', '1')
                                        ->orderBy('sort', 'asc')->get();

        return view('home.index', compact(
            'allMenuMaster',
            'allMenuDetail'
        ));
    }
}
