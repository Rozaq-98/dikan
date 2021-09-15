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

class Asset implements FromCollection, WithHeadings
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
            'Category Asset',
            'Category Pengadaan',
            'Sub Tipe',
            'Tipe Id',
            'Tipe Asset',          
            'No Pengadaan',
            'Merk',
            'Name',
            'Massa Berlaku',
            'No Id Asset',
            'Tanggal Pengadaan',
            'Tanggal Terima',
            'Notes Awal',
            'Cabang Awal',
            'Lokasi Awal',
            'Kondisi Awal',
            'Cabang Akhir',
            'Lokasi Akhir',
            'Kondisi Akhir',
            'Last Notes'
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
                    ->leftjoin('m_cabang', 'm_asset.id_cabang_awal', 'm_cabang.id')
                    ->leftjoin('m_tipe_asset', 'm_subtipe_asset.id_tipe_asset', 'm_tipe_asset.id')
                    ->leftjoin('rel_asset_foto', 'm_asset.id', 'rel_asset_foto.id_asset')
                    ->leftjoin('m_condition', 'm_asset.id_kondisi_awal', 'm_condition.id')
                    ->leftjoin('m_lokasi', 'm_asset.id_lokasi_awal', 'm_lokasi.id')
                    ->leftjoin('his_asset', 'his_asset.id_asset', 'm_asset.id')
                    ->leftjoin('m_cabang as a', 'his_asset.id_cabang_akhir', 'a.id')
                    ->leftjoin('m_condition as b', 'his_asset.id_kondisi_akhir', 'b.id')
                    ->leftjoin('m_lokasi as c', 'his_asset.id_lokasi_akhir', 'c.id')
                   
                   
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
                        'm_asset.terima_date', 
                        'm_asset.notes', 
                        'm_cabang.cabang_name',
                        'm_lokasi.lokasi_name',
                        'm_condition.condition_name',
                        'a.cabang_name as cabang_akhir',
                        'c.lokasi_name as lokasi_akhir',
                        'b.condition_name as condition_akhir',
                        'his_asset.last_notes', 
                    )
                    ->groupBy('m_asset.id')
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
                    ->leftjoin('m_cabang', 'm_asset.id_cabang_awal', 'm_cabang.id')
                    ->leftjoin('m_tipe_asset', 'm_subtipe_asset.id_tipe_asset', 'm_tipe_asset.id')
                    ->leftjoin('rel_asset_foto', 'm_asset.id', 'rel_asset_foto.id_asset')
                    ->leftjoin('m_condition', 'm_asset.id_kondisi_awal', 'm_condition.id')
                    ->leftjoin('m_lokasi', 'm_asset.id_lokasi_awal', 'm_lokasi.id')
                    ->leftjoin('his_asset', 'his_asset.id_asset', 'm_asset.id')
                    ->leftjoin('m_cabang as a', 'his_asset.id_cabang_akhir', 'a.id')
                    ->leftjoin('m_condition as b', 'his_asset.id_kondisi_akhir', 'b.id')
                    ->leftjoin('m_lokasi as c', 'his_asset.id_lokasi_akhir', 'c.id')
                   
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
                        'm_asset.terima_date', 
                        'm_asset.notes', 
                        'm_cabang.cabang_name',
                        'm_lokasi.lokasi_name',
                        'm_condition.condition_name',
                        'a.cabang_name as cabang_akhir',
                        'c.lokasi_name as lokasi_akhir',
                        'b.condition_name as condition_akhir',
                        'his_asset.last_notes',
                        // 'rel_asset_foto.path_foto', 
                    )
                    ->groupBy('m_asset.id')
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
                $collectionData[] = (object) array('val' => $k_dataAsset->cat_asset_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->cat_pengadaan_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->subtipe_asset_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->tipe_id_asset_name);
               
                $collectionData[] = (object) array('val' => $k_dataAsset->tipe_asset_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->no_pengadaan);
                $collectionData[] = (object) array('val' => $k_dataAsset->merk);
                $collectionData[] = (object) array('val' => $k_dataAsset->name);
                $collectionData[] = (object) array('val' => $k_dataAsset->masa_berlaku);
                $collectionData[] = (object) array('val' => $k_dataAsset->no_id_asset);
                $collectionData[] = (object) array('val' => $k_dataAsset->pengadaan_date);
                $collectionData[] = (object) array('val' => $k_dataAsset->terima_date);
                $collectionData[] = (object) array('val' => $k_dataAsset->notes);
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