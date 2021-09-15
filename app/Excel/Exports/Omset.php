<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;


use DB;
use App\Models\m_menu_master;
use App\Models\m_menu_detail;
use App\Models\m_asset;
use App\Models\rel_asset_foto;
use App\Models\m_cabang;
use App\Models\m_cat_pengadaan;
use App\Models\m_cat_asset;
use App\Models\m_subtipe_asset;
use App\Models\m_tipe_id_asset;
use App\Models\detail_trx_kasir;
use App\Models\header_trx_kasir;
use App\Models\mk_product;

class Omset implements FromCollection, WithHeadings
{
    use Exportable;

     public function __construct( $cabang, $datefrom,  $dateto)
    {
        $this->cabang  = $cabang;
        $this->datefrom = $datefrom;
        $this->dateto = $dateto;
        // dd($this->datefrom); die();
    }


    public function headings(): array
    {
        return [            
            'Cabang',
            'Product',
            'Total Terjual',
            'Omset Penjualan'
        ];


    }

    public function collection()
    {

        if ($this->cabang <> NULL) {
            $dataStatus = DB::table('detail_trx_kasir')
                    ->where('header_trx_kasir.created_at', '<=', $this->datefrom)
                    ->where('header_trx_kasir.created_at', '>=',$this->dateto)
                    ->where('header_trx_kasir.flag_active','1')  
                    ->where('header_trx_kasir.id_cabang',$this->cabang)                      
                    ->leftjoin('header_trx_kasir', 'detail_trx_kasir.id_header', 'header_trx_kasir.id')
                    ->leftjoin('m_cabang', 'header_trx_kasir.id_cabang', 'm_cabang.id')
                    ->leftjoin('mk_product', 'detail_trx_kasir.id_product', 'mk_product.id')                   
                   
                    ->select(                      
                            'm_cabang.cabang_name',
                            'mk_product.nama',
                            DB::raw("sum(detail_trx_kasir.qty) as qty"),
                            DB::raw("sum(detail_trx_kasir.total_harga) as omset")
                            
                    )
                    // ->groupBy('m_asset.id')
                    ->groupBy('detail_trx_kasir.id_product')
                    ->groupBy('m_cabang.id')
                    ->orderBy('m_cabang.id', 'DESC')
                    ->orderBy('omset', 'DESC')
                    ->get()->toArray();

                    // dd($dataStatus);

        } elseif ($this->cabang == NULL) {

           $dataStatus = DB::table('detail_trx_kasir')
                    ->where('header_trx_kasir.created_at', '<=', $this->dateto)
                    ->where('header_trx_kasir.created_at', '>=',$this->datefrom)     
                    ->where('header_trx_kasir.flag_active','1')
                    ->leftjoin('header_trx_kasir', 'detail_trx_kasir.id_header', 'header_trx_kasir.id')
                    ->leftjoin('m_cabang', 'header_trx_kasir.id_cabang', 'm_cabang.id')
                    ->leftjoin('mk_product', 'detail_trx_kasir.id_product', 'mk_product.id')                   
                   
                    ->select(                      
                            'm_cabang.cabang_name',
                            'mk_product.nama',
                            DB::raw("sum(detail_trx_kasir.qty) as qty"),
                            DB::raw("sum(detail_trx_kasir.total_harga) as omset")
                            
                    )
                    // ->groupBy('m_asset.id')
                    ->groupBy('detail_trx_kasir.id_product')
                    ->groupBy('m_cabang.id')
                    ->orderBy('m_cabang.id', 'DESC')
                    ->orderByRaw('sum(detail_trx_kasir.qty) DESC')
                    ->get()->toArray();
        }
         // dd($dataStatus);

             
                     $allCollections = array();
        if($dataStatus == null){
            $idtranswo = null;
        } else {
            foreach ($dataStatus as $k_dataStatus) {

                $collectionData = [];

                // push headers
                $collectionData[] = (object) array('val' => $k_dataStatus->cabang_name);
                $collectionData[] = (object) array('val' => $k_dataStatus->nama);
                $collectionData[] = (object) array('val' => $k_dataStatus->qty);
                $collectionData[] = (object) array('val' => $k_dataStatus->omset);
   


                $valTeam = array_column($collectionData, 'val');
                array_push($allCollections, $valTeam);
            }
        }
        return collect([$allCollections]);
        }
}
