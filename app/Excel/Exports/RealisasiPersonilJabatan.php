<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class RealisasiPersonilJabatan implements FromCollection, WithHeadings
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
            'cabang awal',
            'note',
            'Realisasi Jabatan Date',
            'Nama Asset',                        
            'Perhitungan',
            'QTY',
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
                  $dataAsset = DB::table('trans_h_realisasi')
                                    ->where('trans_h_realisasi.createdDtm', '>=', $this->datefrom)
                                    ->where('trans_h_realisasi.createdDtm', '<=',$this->dateto)
                                    ->where('trans_h_realisasi.id_cabang',$this->idcabang)                      
                                    ->leftjoin('trans_d_realisasi_jabatan_asset', 'trans_h_realisasi.id', 'trans_d_realisasi_jabatan_asset.id_transrealisasi')
                                    ->leftjoin('m_jabatan_asset', 'trans_d_realisasi_jabatan_asset.id_jabatan_asset', 'm_jabatan_asset.id')
                                    ->leftjoin('m_cabang', 'trans_h_realisasi.id_cabang', 'm_cabang.id')  
                                    ->leftjoin('m_users as approvedname', 'trans_h_realisasi.approvedBy', 'approvedname.id')  
                                     ->leftjoin('m_users as cancelname', 'trans_h_realisasi.cancelBy', 'cancelname.id')  
                                    ->select(                      
                                        'm_jabatan_asset.jabatan_asset_name',
                                        'trans_d_realisasi_jabatan_asset.value_aritmatika',
                                        'trans_d_realisasi_jabatan_asset.keterangan',
                                        'm_cabang.cabang_name',
                                        'trans_h_realisasi.notes',
                                        'trans_d_realisasi_jabatan_asset.qty',
                                        'trans_h_realisasi.realisasi_date',
                                        'trans_h_realisasi.notesapproval',
                                        'approvedname.name as approved_name',
                                        'trans_h_realisasi.flag_cancel',
                                        'trans_h_realisasi.flag_approved',
                                        'trans_h_realisasi.approvedDtm',
                                        'cancelname.name as cancel_name',
                                        'trans_h_realisasi.CancelDtm'
                                        )
                                    ->groupBy('trans_d_realisasi_jabatan_asset.id')
                                    ->orderBy('trans_d_realisasi_jabatan_asset.id', 'desc')
                                    ->get()->toArray();

                    // var_dump($dataAsset); die();
        } elseif ($this->idcabang == NULL) {

                      $dataAsset = DB::table('trans_h_realisasi')
                                    ->where('trans_h_realisasi.createdDtm', '>=', $this->datefrom)
                                    ->where('trans_h_realisasi.createdDtm', '<=',$this->dateto)                  
                                    ->leftjoin('trans_d_realisasi_jabatan_asset', 'trans_h_realisasi.id', 'trans_d_realisasi_jabatan_asset.id_transrealisasi')
                                    ->leftjoin('m_jabatan_asset', 'trans_d_realisasi_jabatan_asset.id_jabatan_asset', 'm_jabatan_asset.id')
                                    ->leftjoin('m_cabang', 'trans_h_realisasi.id_cabang', 'm_cabang.id')  
                                    ->leftjoin('m_users as approvedname', 'trans_h_realisasi.approvedBy', 'approvedname.id')  
                                     ->leftjoin('m_users as cancelname', 'trans_h_realisasi.cancelBy', 'cancelname.id')  
                                    ->select(                      
                                        'm_jabatan_asset.jabatan_asset_name',
                                        'trans_d_realisasi_jabatan_asset.value_aritmatika',
                                        'trans_d_realisasi_jabatan_asset.keterangan',
                                        'm_cabang.cabang_name',
                                        'trans_h_realisasi.notes',
                                        'trans_d_realisasi_jabatan_asset.qty',
                                        'trans_h_realisasi.realisasi_date',
                                        'trans_h_realisasi.notesapproval',
                                        'approvedname.name as approved_name',
                                        'trans_h_realisasi.flag_cancel',
                                        'trans_h_realisasi.flag_approved',
                                        'trans_h_realisasi.approvedDtm',
                                        'cancelname.name as cancel_name',
                                        'trans_h_realisasi.CancelDtm'
                                        )
                                    ->groupBy('trans_d_realisasi_jabatan_asset.id')
                                    ->orderBy('trans_d_realisasi_jabatan_asset.id', 'desc')
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
                $collectionData[] = (object) array('val' => $k_dataAsset->cabang_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->notes);
                $collectionData[] = (object) array('val' => $k_dataAsset->realisasi_date);
                $collectionData[] = (object) array('val' => $k_dataAsset->jabatan_asset_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->value_aritmatika);
                $collectionData[] = (object) array('val' => $k_dataAsset->qty);               
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

                $valTeam = array_column($collectionData, 'val');
                array_push($allCollections, $valTeam);
           
            }
        }
        return collect([$allCollections]);
        }
}