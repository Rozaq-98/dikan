<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class RealisasiPerpindahanAsset implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($datefrom, $dateto, $idcabang)
    {
        $this->datefrom     = $datefrom;
        $this->dateto       = $dateto;
        $this->idcabang     = $idcabang;
    }

    public function headings(): array
    {
        // header wo
        $arrHeading = array(
            'No',
            'Cabang Awal',
            'Cabang Akhir',
            'Tanggal Perpindahan',
            'Lokasi Awal',                        
            'Lokasi Akhir',
            'Notes',
            'Nama Asset',
            'No Id Asset',
            'Keterangan',
            'Status',
            'Notes Approval',
            'Name Approved',
            'Tanggal Approved'
        );
        
        return $arrHeading;
    }

    public function collection()
    {
        if ($this->idcabang <> NULL) {
                  $dataAsset = DB::table('trans_h_moveasset')
                                    ->where('trans_h_moveasset.createdDtm', '>=', $this->datefrom)
                                    ->where('trans_h_moveasset.createdDtm', '<=',$this->dateto)
                                    ->where('trans_h_moveasset.id_cabang',$this->idcabang)                      
                                    ->leftjoin('trans_d_moveasset', 'trans_h_moveasset.id', 'trans_d_moveasset.id_transmoveasset')
                                    ->leftjoin('m_cabang as cabang_awal', 'trans_d_moveasset.id_cabang_awal', 'cabang_awal.id')
                                    ->leftjoin('m_cabang as cabang_akhir', 'trans_d_moveasset.id_cabang_akhir', 'cabang_akhir.id')
                                    ->leftjoin('m_lokasi as lokasi_awal', 'trans_d_moveasset.id_lokasi_awal', 'lokasi_awal.id')
                                    ->leftjoin('m_lokasi as lokasi_akhir', 'trans_d_moveasset.id_lokasi_akhir', 'lokasi_akhir.id')  
                                    ->leftjoin('m_asset', 'trans_d_moveasset.id_asset', 'm_asset.id')    
                                    ->leftjoin('m_users as approvedname', 'trans_h_moveasset.approvedBy', 'approvedname.id')  
                                     ->leftjoin('m_users as cancelname', 'trans_h_moveasset.cancelBy', 'cancelname.id')  
                                    ->select(                      
                                                'cabang_awal.cabang_name as cabang_awal',
                                                'cabang_akhir.cabang_name as cabang_akhir',
                                                'lokasi_awal.lokasi_name as lokasi_awal',
                                                'lokasi_akhir.lokasi_name as lokasi_akhir',
                                                'trans_d_moveasset.keterangan', 
                                                'trans_h_moveasset.notesapproval',
                                                'approvedname.name as approved_name',
                                                'cancelname.name as cancel_name',                                         
                                                'm_asset.name',
                                                'm_asset.no_id_asset',
                                                'trans_h_moveasset.*'
                                        )
                                    ->orderBy('trans_d_moveasset.id', 'desc')
                                    ->get()->toArray();

                    // var_dump($dataAsset); die();
        } elseif ($this->idcabang == NULL) {
                          $dataAsset = DB::table('trans_h_moveasset')
                                    ->where('trans_h_moveasset.createdDtm', '>=', $this->datefrom)
                                    ->where('trans_h_moveasset.createdDtm', '<=',$this->dateto)                    
                                    ->leftjoin('trans_d_moveasset', 'trans_h_moveasset.id', 'trans_d_moveasset.id_transmoveasset')
                                    ->leftjoin('m_cabang as cabang_awal', 'trans_d_moveasset.id_cabang_awal', 'cabang_awal.id')
                                    ->leftjoin('m_cabang as cabang_akhir', 'trans_d_moveasset.id_cabang_akhir', 'cabang_akhir.id')
                                    ->leftjoin('m_lokasi as lokasi_awal', 'trans_d_moveasset.id_lokasi_awal', 'lokasi_awal.id')
                                    ->leftjoin('m_lokasi as lokasi_akhir', 'trans_d_moveasset.id_lokasi_akhir', 'lokasi_akhir.id')  
                                    ->leftjoin('m_asset', 'trans_d_moveasset.id_asset', 'm_asset.id')    
                                    ->leftjoin('m_users as approvedname', 'trans_h_moveasset.approvedBy', 'approvedname.id')  
                                     ->leftjoin('m_users as cancelname', 'trans_h_moveasset.cancelBy', 'cancelname.id')  
                                    ->select(                      
                                                'cabang_awal.cabang_name as cabang_awal',
                                                'cabang_akhir.cabang_name as cabang_akhir',
                                                'lokasi_awal.lokasi_name as lokasi_awal',
                                                'lokasi_akhir.lokasi_name as lokasi_akhir',
                                                'trans_d_moveasset.keterangan',
                                                'trans_h_moveasset.notesapproval',
                                                'approvedname.name as approved_name',
                                                'cancelname.name as cancel_name',
                                                'm_asset.name',
                                                'm_asset.no_id_asset',
                                                'trans_h_moveasset.*'
                                        )
                                    ->orderBy('trans_d_moveasset.id', 'desc')
                                    ->get()->toArray();
                                }

        // var_dump($dataAsset[1]->path_foto); die(); 

              // var_dump($dataAsset); die();

                     $allCollections = array();
        if($dataAsset == null){
            $idtranswo = null;
        } else {
             $i=1;
            foreach ($dataAsset as $k_dataAsset) {        
                   

                $collectionData = [];

                // push headers
                $collectionData[] = (object) array('val' => $i++);
                $collectionData[] = (object) array('val' => $k_dataAsset->cabang_awal);
                $collectionData[] = (object) array('val' => $k_dataAsset->cabang_akhir);
                $collectionData[] = (object) array('val' => $k_dataAsset->moving_date);
                $collectionData[] = (object) array('val' => $k_dataAsset->lokasi_awal);
                $collectionData[] = (object) array('val' => $k_dataAsset->lokasi_akhir);
                $collectionData[] = (object) array('val' => $k_dataAsset->notes);
                $collectionData[] = (object) array('val' => $k_dataAsset->name);
                $collectionData[] = (object) array('val' => $k_dataAsset->no_id_asset);
                $collectionData[] = (object) array('val' => $k_dataAsset->keterangan);   
              
                if ($k_dataAsset->flag_approved == 1) {
                    $collectionData[] = (object) array('val' => 'Approved');  
                }
                else
                {
                    $collectionData[] = (object) array('val' => 'Not Approved');  
                }
                $collectionData[] = (object) array('val' => $k_dataAsset->notesapproval);
                $collectionData[] = (object) array('val' => $k_dataAsset->approved_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->approvedDtm);
                // $collectionData[] = (object) array('val' => $k_dataAsset->path_foto);

                // var_dump($collectionData[]); die();

                $valTeam = array_column($collectionData, 'val');
                array_push($allCollections, $valTeam);
           
            }
        }
        return collect([$allCollections]);
        }
}
