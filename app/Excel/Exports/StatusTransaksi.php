<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

use Session;
use DB;
use App\Helpers\CheckingSession;

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

class StatusTransaksi implements FromCollection, WithHeadings
{
    use Exportable;

     public function __construct( $cabang, $datefrom,  $dateto)
    {
        $this->cabang  = $cabang;
        $this->datefrom = $datefrom;
        $this->dateto = $dateto;
        
    }


    public function headings(): array
    {
        return [
            'Nomor Transaksi',
            'Cabang',
            'Product',
            'Harga Satuan',
            'Total QTY',
            'Total Harga',
            'Status'
        ];


    }

    public function collection()
    {
         $hasil = Session::get('ss_idrole') == 1 || Session::get('ss_idrole') == 6;

        if($hasil == true)
        {
        if ($this->cabang <> NULL) {
            $dataStatus = DB::table('detail_trx_kasir')
                    ->where('header_trx_kasir.created_at', '<=', $this->dateto)
                    ->where('header_trx_kasir.created_at', '>=',$this->datefrom)
                    ->where('m_cabang.id',$this->cabang)                      
                    ->leftjoin('header_trx_kasir', 'detail_trx_kasir.id_header', 'header_trx_kasir.id')
                    ->leftjoin('m_cabang', 'header_trx_kasir.id_cabang', 'm_cabang.id')
                    ->leftjoin('mk_product', 'detail_trx_kasir.id_product', 'mk_product.id')                   
                   
                    ->select(                      
                        'header_trx_kasir.id_trx',
                        'm_cabang.cabang_name',
                        'mk_product.nama',
                        'detail_trx_kasir.harga',
                        'detail_trx_kasir.qty',
                        'detail_trx_kasir.total_harga',
                        'header_trx_kasir.flag_active'
                    )
                    // ->groupBy('m_asset.id')
                    ->orderBy('header_trx_kasir.id_trx', 'asc')
                    ->get()->toArray();

                    // dd($dataStatus);

        } elseif ($this->cabang == NULL) {

             $dataStatus = DB::table('detail_trx_kasir')
                    ->where('header_trx_kasir.created_at', '<=', $this->dateto)
                    ->where('header_trx_kasir.created_at', '>=',$this->datefrom)                    
                    ->leftjoin('header_trx_kasir', 'detail_trx_kasir.id_header', 'header_trx_kasir.id')
                    ->leftjoin('m_cabang', 'header_trx_kasir.id_cabang', 'm_cabang.id')
                    ->leftjoin('mk_product', 'detail_trx_kasir.id_product', 'mk_product.id')                   
                   
                    ->select(                      
                        'header_trx_kasir.id_trx',
                        'm_cabang.cabang_name',
                        'mk_product.nama',
                        'detail_trx_kasir.harga',
                        'detail_trx_kasir.qty',
                        'detail_trx_kasir.total_harga',
                        'header_trx_kasir.flag_active'
                    )
                    // ->groupBy('m_asset.id')
                    ->orderBy('header_trx_kasir.id_trx', 'asc')
                    ->get()->toArray();
        }
         

    }
   
    
    
    if($hasil == false)
    {
     $dataStatus = DB::table('detail_trx_kasir')
                    ->where('header_trx_kasir.created_at', '<=', $this->dateto)
                    ->where('header_trx_kasir.created_at', '>=',$this->datefrom)
                    ->where('m_cabang.id',Session::get('ss_cabangkaryawan'))                      
                    ->leftjoin('header_trx_kasir', 'detail_trx_kasir.id_header', 'header_trx_kasir.id')
                    ->leftjoin('m_cabang', 'header_trx_kasir.id_cabang', 'm_cabang.id')
                    ->leftjoin('mk_product', 'detail_trx_kasir.id_product', 'mk_product.id')                   
                   
                    ->select(                      
                        'header_trx_kasir.id_trx',
                        'm_cabang.cabang_name',
                        'mk_product.nama',
                        'detail_trx_kasir.harga',
                        'detail_trx_kasir.qty',
                        'detail_trx_kasir.total_harga',
                        'header_trx_kasir.flag_active'
                    )
                    // ->groupBy('m_asset.id')
                    ->orderBy('header_trx_kasir.id_trx', 'asc')
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
                $collectionData[] = (object) array('val' => $k_dataStatus->id_trx);
                $collectionData[] = (object) array('val' => $k_dataStatus->cabang_name);
                $collectionData[] = (object) array('val' => $k_dataStatus->nama);
                $collectionData[] = (object) array('val' => $k_dataStatus->harga);
                $collectionData[] = (object) array('val' => $k_dataStatus->qty);
                $collectionData[] = (object) array('val' => $k_dataStatus->total_harga);            
   

                if( $k_dataStatus->flag_active ==1){
                    $collectionData[] = (object) array('val' => 'Active');
                }else{
                    $collectionData[] = (object) array('val' => 'Canceled');
                }
              

                $valTeam = array_column($collectionData, 'val');
                array_push($allCollections, $valTeam);
            }
        }
        return collect([$allCollections]);
        }
}
