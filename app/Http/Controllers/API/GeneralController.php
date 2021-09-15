<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\m_users;
// use App\Models\m_loc_kota;
use App\Models\m_klien;
// use App\Models\m_material;
// use App\Models\m_typeqty;
// use App\Models\m_category_reason;
// use App\Models\m_cabang;
use App\Models\m_role;
use App\Models\m_karyawan;

use App\Models\m_loc_kota;
use App\Models\m_material;
use App\Models\m_typeqty;
use App\Models\m_category_reason;
use App\Models\m_subtipe_asset;
use App\Models\m_team_asset;
use App\Models\m_asset;
use App\Models\m_cabang;
use App\Models\his_asset;
use App\Models\his_jabatan_asset;
use App\Models\m_jabatan_asset;
use App\Models\mk_product;

class GeneralController extends Controller
{
    function locationkota(Request $req){
        $kota = m_loc_kota::where('id_provinsi', $req->id_prov)->get()->toArray();

        return response()->json([
            'kota'    => $kota,
        ]);
    }

    function subtipe(Request $req){
            // dd($req->tipe); die(); 
        $subtipe = m_subtipe_asset::where('id_tipe_asset', $req->id_tipe_asset)->get()->toArray();


        return response()->json([
            'subtipe_asset_name'    => $subtipe,
        ]);
    }

    function asset(Request $req){
            // dd($req->subtipe); die(); 
        $asset = m_asset::leftjoin('his_asset', 'his_asset.id_asset', 'm_asset.id')
        ->where('m_asset.id_subtipe_asset', $req->subtipe)
        ->where('his_asset.id_cabang_akhir', '=', $req->cabangawal)
        ->where('m_asset.id_cat_asset', '=', $req->category)
        ->where('his_asset.flag_active', '=', 1)
        ->select(
            'm_asset.id',
            'm_asset.no_asset',
            'm_asset.name', 
            'm_asset.merk', 
            'm_asset.no_id_asset', 
            'his_asset.id_cabang_akhir',
            'his_asset.id_lokasi_akhir',
            'his_asset.id_kondisi_akhir',
            'his_asset.flag_active' )
        ->get()->toArray();       
        // dd($req->subtipe.' '.$req->cabangawal.' '.$req->category);

        return response()->json([
            'no_id_asset'    => $asset,
        ]);
    }

    function assetqty(Request $req){
            // dd($req->idcabang); die(); 
        $asset = m_team_asset::leftjoin('his_team_asset', 'm_team_asset.id', 'his_team_asset.id_team_asset')
        ->where('m_team_asset.id', $req->asset) 
        ->where('his_team_asset.id_cabang', $req->idcabang)     
        ->where('m_team_asset.flag_active', '=', 1)
        ->select(
          'm_team_asset.kebutuhan' ,
           (DB::raw( 'IFNULL(his_team_asset.last_qty, 0) as last_qty'))
           
       )
        ->get()->toArray();       
        // dd($asset);

        if ($asset<>NULL) {
                $hasil = $asset;
                // $hasilkebutuhan = $asset;
                // dd($hasilkebutuhan);
            } else
                $hasil = 0;

        return response()->json([
            'last_qty'    => $hasil,
            'kebutuhan'    => $hasil
        ]);
    }

    function jabatanqty(Request $req){
            // dd($req->subtipe); die(); 
        $jabatanqty = m_jabatan_asset::leftjoin('his_jabatan_asset', 'm_jabatan_asset.id', 'his_jabatan_asset.id_jabatan_asset')
        ->where('his_jabatan_asset.id_jabatan_asset', $req->jabatanqty)     
        ->where('his_jabatan_asset.flag_active', '=', 1)
        ->select(          
            'his_jabatan_asset.last_qty'
       )
       
        ->get()->toArray();       
        // dd($jabatanqty);

            if ($jabatanqty<>NULL) {
                $hasil = $jabatanqty;
            } else
                $hasil = 0;
            // dd($hasil);


        return response()->json([
            'last_qty'    => $hasil,
        ]);
    }

    function assetHis(Request $req){
            dd($req->subtipe); die(); 
        $asset = his_asset::leftjoin('m_condition', 'his_asset.id_kondisi_akhir', 'm_condition.id')
                        ->leftjoin('m_cabang', 'his_asset.id_cabang_akhir', 'm_cabang.id')
                        ->leftjoin('m_lokasi', 'his_asset.id_lokasi_akhir', 'm_lokasi.id')
                        ->where('id_asset', $req->idasset)
                        ->where('his_asset.flag_active', '=', 1)
                        ->get()->toArray();       
        // dd($req->subtipe.' '.$req->cabangawal.' '.$req->category);
        
        return response()->json([
            'data_his_asset'    => $asset,
        ]);
    }
    
    function getproduct(Request $req){
            
        $getproduct = mk_product::where('id', $req->idproduct)
                         ->select('nominal')
                        ->get()->toArray();
        // dd($getproduct); die(); 

        return response()->json([
            'nominal' => $getproduct,
        ]);
    }
    

    function team(Request $req){
            // dd($req->tipe); die(); 
        $team = m_team_asset::where('id_cabang', $req->id_cabang)->get()->toArray();


        return response()->json([
            'team_name'    => $team,
        ]);
    }
    function teamasset(Request $req){
        // dd($req->tipe); die(); 
        $team = m_team_asset::get()->toArray();


        return response()->json([
            'team_name'    => $team,
        ]);
    }
    function jabatan(Request $req){
        // dd($req->tipe); die(); 
        $jabatan_asset = m_jabatan_asset::get()->toArray();


        return response()->json([
            'jabatan_asset_name'    => $jabatan_asset,
        ]);
    }

    function tipeqty(Request $req){
        $material = m_material::where('id', $req->id_material)->first();
        $tipeqty  = m_typeqty::where('id', $material->type_qty)->get()->toArray();

        return response()->json([
            'tipeqty'    => $tipeqty,
        ]);
    }

    function getCategoryReason(Request $req){
        $data  = m_category_reason::where('id_m_statuswo', $req->idstatus)->get();
        return response()->json(['data' => $data]);
    }

    //update made 

    //get data cabang

    function getcabangUpdTime(Request $req){
    
        $data  = m_cabang::where('updatedDtm','>', $req->lastupd)->get()->toArray();
       
        return response()->json($data);
    }

    function getCabangNew(Request $req){
    
        $data  = m_cabang::where('id','>', $req->lastid)->get()->toArray();
       
        return response()->json($data);
    }



      //get data karyawan

    function getKaryawanNew(Request $req){
    
        $data  = m_karyawan::where('id','>', $req->lastid)->get()->toArray();
       
        return response()->json($data);
    }

    
      function getKaryawanUpdTime(Request $req){
    
        $data  = m_karyawan::where('updatedDtm','>', $req->lastupd)->get()->toArray();
       
        return response()->json($data);
    }

      //get data klien

      function getKlienUpdTime(Request $req){
    
        $data  = m_klien::where('updatedDtm','>', $req->lastupd)->get()->toArray();
       
        return response()->json($data);
    }

    function getKlienNew(Request $req){
    
        $data  = m_klien::where('id','>', $req->lastid)->get()->toArray();
       
        return response()->json($data);
    }

     //get data users

     function getusersNew(Request $req){
    
        $data  = m_users::where('id','>', $req->lastid)->get()->toArray();
       
        return response()->json($data);
    }

    
      function getusersUpdTime(Request $req){
    
        $data  = m_users::where('updatedDtm','>', $req->lastupd)->get()->toArray();
       
        return response()->json($data);
    }
    
    //get data role

    function getroleNew(Request $req){
    
        $data  = m_role::where('id','>', $req->lastid)->get()->toArray();
       
        return response()->json($data);
    }

    
      function getroleUpdTime(Request $req){
    
        $data  = m_role::where('updatedDtm','>', $req->lastupd)->get()->toArray();
       
        return response()->json($data);
    }

    //get data Cabang karyawan

    function getCabangKaryawanNew(Request $req){

      //  $clientIP = request()->getClientIp();
       // $request->getClientIp();
        // if($req->_token == csrf_token() ){

       
            $data  = rel_cabangkaryawan::where('id','>', $req->lastid)->get()->toArray();
       
        return response()->json($data);
        //  }
    }

    
      function getCabangKaryawanUpdTime(Request $req){
    
        $data  = rel_cabangkaryawan::where('updatedDtm','>', $req->lastupd)->get()->toArray();
       
        return response()->json($data);
    }
}
