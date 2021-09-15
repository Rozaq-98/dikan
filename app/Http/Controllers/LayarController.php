<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Session;
use Image;
use App\Helpers\CheckingSession;
use App\Helpers\GlobalHelper;
use App\Excel\Exports\Asset;


use App\Models\mk_product;
use App\Models\m_menu_master;
use App\Models\m_menu_detail;
use App\Models\mk_m_categoryproduct;

class LayarController extends Controller
{
     public function index()
    {


    	 $product = DB::table('mk_product')  
                     ->leftjoin('mk_m_categoryproduct', 'mk_product.idcategoryproduct', 'mk_m_categoryproduct.id')
                     ->where('flag_active', '1')                 
                    ->select(
                        'mk_product.id',
                        'mk_m_categoryproduct.categoryname',
                        'mk_product.nama',
                        'mk_product.description',
                        'mk_product.nominal',
                        'mk_product.flag_active',
                        'mk_product.foto_name',
                        'mk_product.path_foto'
                    )->get();

          $cabang = DB::table('m_cabang')       
                    ->where('status_cabang', '1')              
                    ->select(
                        'm_cabang.id',
                        'm_cabang.cabang_no',                      
                        'm_cabang.cabang_name',
                        'm_cabang.address',
                        'm_cabang.phone',
                        'm_cabang.lokasi',
                        'm_cabang.flag_active'
                    )->get();

            // dd($cabang);

        return view('layar.index', compact(
            'product',
            'cabang'
        ));
    }
}
