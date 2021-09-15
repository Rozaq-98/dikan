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
use App\Models\m_tipe_asset;

class HistoryAsset implements FromCollection, WithHeadings
{
    use Exportable;

     public function __construct( $cabang, $datefrom,  $dateto)
    {
        $this->cabang  = $cabang;
        $this->datefrom = $datefrom;
        $this->dateto = $dateto;
        // var_dump($cabang); die();
    }


    public function headings(): array
    {
        return [
            'No Asset',
            'Cabang',
            'Category Asset',
            'Category Pengadaan',
            'Tipe Asset', 
            'Sub Tipe',
            'Tipe Id',
            'No Id Asset',
            'Merk',
            'Nama',
            'Tanggal Pengadaan',        
            'No Pengadaan',
            'Tanggal Terima',
            'Masa Berlaku',
            // 'Masa Berlaku',
            'Tanggal Masa Berlaku',
            'Status',
            'Transaksi',
            'User Transaksi',
            'Waktu Transaksi',
            'Cabang Awal',
            'Lokasi Awal',
            'Kondisi Awal',
            'Cabang Akhir',
            'Lokasi Akhir',
            'Kondisi Akhir',
            'Notes'
        ];


    }

    public function collection()
    {
        if ($this->cabang <> NULL) {
            $dataAsset = DB::table('m_asset')
                    ->where('m_asset.createdDtm', '<=', $this->datefrom)
                    ->where('m_asset.createdDtm', '>=',$this->dateto)
                    ->where('his_asset.id_cabang_akhir',$this->cabang)                      
                    ->leftjoin('m_cat_pengadaan', 'm_asset.id_cat_pengadaan', 'm_cat_pengadaan.id')
                    ->leftjoin('m_cat_asset', 'm_asset.id_cat_asset', 'm_cat_asset.id')
                    ->leftjoin('m_subtipe_asset', 'm_asset.id_subtipe_asset', 'm_subtipe_asset.id')
                    ->leftjoin('m_tipe_id_asset', 'm_asset.id_tipeid_asset', 'm_tipe_id_asset.id')
                     ->leftjoin('m_tipe_asset', 'm_subtipe_asset.id_tipe_asset', 'm_tipe_asset.id')
                    ->leftjoin('rel_asset_foto', 'm_asset.id', 'rel_asset_foto.id_asset')
                    ->leftjoin('his_asset', 'his_asset.id_asset', 'm_asset.id')
                    ->leftjoin('m_cabang as his_cabang', 'his_asset.id_cabang_akhir', 'his_cabang.id')
                    ->leftjoin('his_asset_log', 'his_asset_log.id_asset', 'm_asset.id')
                    ->leftjoin('m_condition', 'his_asset_log.id_kondisi_awal', 'm_condition.id')
                    ->leftjoin('m_lokasi', 'his_asset_log.id_lokasi_awal', 'm_lokasi.id')
                    ->leftjoin('m_cabang', 'his_asset_log.id_cabang_awal', 'm_cabang.id')
                    ->leftjoin('m_cabang as a', 'his_asset_log.id_cabang_akhir', 'a.id')
                    ->leftjoin('m_condition as b', 'his_asset_log.id_kondisi_akhir', 'b.id')
                    ->leftjoin('m_lokasi as c', 'his_asset_log.id_lokasi_akhir', 'c.id')
                    ->leftjoin('m_functiontype', 'his_asset_log.id_t_ar_m_functiontype', 'm_functiontype.id')
                    ->leftjoin('m_modul', 'm_functiontype.id_ar_m_modul', 'm_modul.id')
                    ->leftjoin('m_users', 'his_asset_log.createdBy', 'm_users.id')
                    ->leftjoin('m_tipe_massa_berlaku', 'm_asset.id_massaberlaku', 'm_tipe_massa_berlaku.id')
                           
                    // ->leftjoin('m_lokasi', 'm_asset.id_lokasi_awal', 'm_lokasi.id')
                   
                    ->select(                      
                        'm_asset.no_asset',                              
                        'm_cat_asset.cat_asset_name',   
                        'm_cat_pengadaan.cat_pengadaan_name',
                        'm_subtipe_asset.subtipe_asset_name',
                        'm_tipe_id_asset.tipe_id_asset_name',
                        'm_tipe_asset.tipe_asset_name',
                        'm_asset.no_pengadaan', 
                        'm_asset.merk', 
                        'm_asset.name', 
                        'm_asset.masa_berlaku', 
                        'm_asset.no_id_asset', 
                        'm_asset.pengadaan_date',
                        'm_asset.terima_date', 
                        'his_asset_log.flag_active',
                        'm_modul.modul_name', 
                        'm_users.name as pic_name',
                        'his_asset_log.createdDtm as createdtime',
                        'm_cabang.cabang_name',
                        'm_lokasi.lokasi_name',
                        'm_condition.condition_name',
                        'a.cabang_name as cabang_akhir',
                        'c.lokasi_name as lokasi_akhir',
                        'b.condition_name as condition_akhir',
                        'his_asset_log.notes as last_notes', 
                        'his_cabang.cabang_name as cabang_awal',
                        'm_tipe_massa_berlaku.massa_berlaku',
                        // 'rel_asset_foto.path_foto', 
                    )
                    // ->groupBy('m_asset.id')
                    ->orderBy('m_asset.id', 'desc')
                    ->get()->toArray();
        } elseif ($this->cabang == NULL) {
            $dataAsset = DB::table('m_asset')
                    ->where('m_asset.createdDtm', '<=', $this->datefrom)
                    ->where('m_asset.createdDtm', '>=',$this->dateto)                     
                    ->leftjoin('m_cat_pengadaan', 'm_asset.id_cat_pengadaan', 'm_cat_pengadaan.id')
                    ->leftjoin('m_cat_asset', 'm_asset.id_cat_asset', 'm_cat_asset.id')
                    ->leftjoin('m_subtipe_asset', 'm_asset.id_subtipe_asset', 'm_subtipe_asset.id')
                    ->leftjoin('m_tipe_id_asset', 'm_asset.id_tipeid_asset', 'm_tipe_id_asset.id')
                    
                    ->leftjoin('m_tipe_asset', 'm_subtipe_asset.id_tipe_asset', 'm_tipe_asset.id')
                    ->leftjoin('rel_asset_foto', 'm_asset.id', 'rel_asset_foto.id_asset')
                    ->leftjoin('his_asset', 'his_asset.id_asset', 'm_asset.id')
                    ->leftjoin('m_cabang as his_cabang', 'his_asset.id_cabang_akhir', 'his_cabang.id')
                    ->leftjoin('his_asset_log', 'his_asset_log.id_asset', 'm_asset.id')
                    ->leftjoin('m_condition', 'his_asset_log.id_kondisi_awal', 'm_condition.id')
                    ->leftjoin('m_lokasi', 'his_asset_log.id_lokasi_awal', 'm_lokasi.id')
                    ->leftjoin('m_cabang', 'his_asset_log.id_cabang_awal', 'm_cabang.id')
                    ->leftjoin('m_cabang as a', 'his_asset_log.id_cabang_akhir', 'a.id')
                    ->leftjoin('m_condition as b', 'his_asset_log.id_kondisi_akhir', 'b.id')
                    ->leftjoin('m_lokasi as c', 'his_asset_log.id_lokasi_akhir', 'c.id')
                    ->leftjoin('m_functiontype', 'his_asset_log.id_t_ar_m_functiontype', 'm_functiontype.id')
                    ->leftjoin('m_modul', 'm_functiontype.id_ar_m_modul', 'm_modul.id')
                    ->leftjoin('m_users', 'his_asset_log.createdBy', 'm_users.id')
                    ->leftjoin('m_tipe_massa_berlaku', 'm_asset.id_massaberlaku', 'm_tipe_massa_berlaku.id')
                           
                    // ->leftjoin('m_lokasi', 'm_asset.id_lokasi_awal', 'm_lokasi.id')
                   
                    ->select(                      
                        'm_asset.no_asset',                              
                        'm_cat_asset.cat_asset_name',   
                        'm_cat_pengadaan.cat_pengadaan_name',
                        'm_subtipe_asset.subtipe_asset_name',
                        'm_tipe_id_asset.tipe_id_asset_name',
                        'm_tipe_asset.tipe_asset_name',
                        'm_asset.no_pengadaan', 
                        'm_asset.merk', 
                        'm_asset.name', 
                        'm_asset.masa_berlaku', 
                        'm_asset.no_id_asset', 
                        'm_asset.pengadaan_date',
                        'm_asset.terima_date', 
                        'his_asset_log.flag_active',
                        'm_modul.modul_name', 
                        'm_users.name as pic_name',
                        'his_asset_log.createdDtm as createdtime',
                        'm_cabang.cabang_name',
                        'm_lokasi.lokasi_name',
                        'm_condition.condition_name',
                        'a.cabang_name as cabang_akhir',
                        'c.lokasi_name as lokasi_akhir',
                        'b.condition_name as condition_akhir',
                        'his_asset_log.notes as last_notes', 
                        'his_cabang.cabang_name as cabang_awal',
                        'm_tipe_massa_berlaku.massa_berlaku',
                         
                        // 'rel_asset_foto.path_foto', 
                    )

                    // ->groupBy('m_asset.id')
                    ->orderBy('m_asset.id', 'desc')
                    ->get()->toArray();
        }
        // var_dump($dataAsset[1]->path_foto); die(); 

             
                     $allCollections = array();
        if($dataAsset == null){
            $idtranswo = null;
        } else {
            foreach ($dataAsset as $k_dataAsset) {

                $collectionData = [];

                // push headers
                $collectionData[] = (object) array('val' => $k_dataAsset->no_asset);
                $collectionData[] = (object) array('val' => $k_dataAsset->cabang_awal);
                $collectionData[] = (object) array('val' => $k_dataAsset->cat_asset_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->cat_pengadaan_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->tipe_asset_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->subtipe_asset_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->tipe_id_asset_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->no_id_asset);
                $collectionData[] = (object) array('val' => $k_dataAsset->merk);
                $collectionData[] = (object) array('val' => $k_dataAsset->name);
                $collectionData[] = (object) array('val' => $k_dataAsset->pengadaan_date);
                $collectionData[] = (object) array('val' => $k_dataAsset->no_pengadaan);
                $collectionData[] = (object) array('val' => $k_dataAsset->terima_date);
                // $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->massa_berlaku);$colCell++;
                $collectionData[] = (object) array('val' => $k_dataAsset->massa_berlaku);
                $collectionData[] = (object) array('val' => $k_dataAsset->masa_berlaku);
               
               
              

                if( $k_dataAsset->flag_active ==1){
                    $collectionData[] = (object) array('val' => 'Active');
                }else{
                    $collectionData[] = (object) array('val' => 'InActive');
                }
                
                $collectionData[] = (object) array('val' => $k_dataAsset->modul_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->pic_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->createdtime);
                $collectionData[] = (object) array('val' => $k_dataAsset->cabang_name);
                
                $collectionData[] = (object) array('val' => $k_dataAsset->lokasi_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->condition_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->cabang_akhir);
               
                $collectionData[] = (object) array('val' => $k_dataAsset->lokasi_akhir);
                $collectionData[] = (object) array('val' => $k_dataAsset->condition_akhir);
                $collectionData[] = (object) array('val' => $k_dataAsset->last_notes);
                // $collectionData[] = (object) array('val' => $k_dataAsset->path_foto);

                $valTeam = array_column($collectionData, 'val');
                array_push($allCollections, $valTeam);
            }
        }
        return collect([$allCollections]);
        }
}
