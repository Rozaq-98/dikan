<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class RealisasiKondisiAsset implements FromCollection, WithHeadings
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
            'Kondisi Awal',
            'Kondisi Akhir',
            'Tanggal Kondisi',
            'Category Asset',
            'Cabang',
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
                  $dataAsset = DB::table('trans_h_chconditionasset')
                                    ->where('trans_h_chconditionasset.createdDtm', '>=', $this->datefrom)
                                    ->where('trans_h_chconditionasset.createdDtm', '<=',$this->dateto)
                                    ->where('trans_h_chconditionasset.id_cabang',$this->idcabang)
                                    ->leftjoin('m_cabang', 'trans_h_chconditionasset.id_cabang', 'm_cabang.id')
                                    ->leftjoin('m_cat_asset', 'trans_h_chconditionasset.id_catasset', 'm_cat_asset.id')
                                    ->leftjoin('trans_d_chconditionasset', 'trans_h_chconditionasset.id', 'trans_d_chconditionasset.id_transchconditionasset')
                                    ->leftjoin('m_asset', 'trans_d_chconditionasset.id_asset', 'm_asset.id')  
                                    ->leftjoin('m_condition as kondisi_akhir', 'trans_d_chconditionasset.id_condition_akhir', 'kondisi_akhir.id') 
                                    ->leftjoin('m_condition as kondisi_awal', 'trans_d_chconditionasset.id_condition_awal', 'kondisi_awal.id')    
                                    ->leftjoin('m_users as approvedname', 'trans_h_chconditionasset.approvedBy', 'approvedname.id')  
                                     ->leftjoin('m_users as cancelname', 'trans_h_chconditionasset.cancelBy', 'cancelname.id')  
                                    ->select(                      
                                                'kondisi_awal.condition_name as kondisi_awal',
                                                'kondisi_akhir.condition_name as kondisi_akhir',
                                                'm_cabang.cabang_name',
                                                'm_cat_asset.cat_asset_name',
                                                'm_asset.name',
                                                'm_asset.no_id_asset',  
                                                'trans_d_chconditionasset.keterangan',
                                                'approvedname.name as approved_name',
                                                'cancelname.name as cancel_name',           
                                                'trans_h_chconditionasset.*'
                                        )
                                    ->orderBy('trans_d_chconditionasset.id', 'desc')
                                    ->get()->toArray();

                    // var_dump($dataAsset); die();
        } elseif ($this->idcabang == NULL) {
                          $dataAsset = DB::table('trans_h_chconditionasset')
                                    ->where('trans_h_chconditionasset.createdDtm', '>=', $this->datefrom)
                                    ->where('trans_h_chconditionasset.createdDtm', '<=',$this->dateto)
                                    ->leftjoin('m_cabang', 'trans_h_chconditionasset.id_cabang', 'm_cabang.id')
                                    ->leftjoin('m_cat_asset', 'trans_h_chconditionasset.id_catasset', 'm_cat_asset.id')
                                    ->leftjoin('trans_d_chconditionasset', 'trans_h_chconditionasset.id', 'trans_d_chconditionasset.id_transchconditionasset')
                                    ->leftjoin('m_asset', 'trans_d_chconditionasset.id_asset', 'm_asset.id')  
                                    ->leftjoin('m_condition as kondisi_akhir', 'trans_d_chconditionasset.id_condition_akhir', 'kondisi_akhir.id') 
                                    ->leftjoin('m_condition as kondisi_awal', 'trans_d_chconditionasset.id_condition_awal', 'kondisi_awal.id')    
                                    ->leftjoin('m_users as approvedname', 'trans_h_chconditionasset.approvedBy', 'approvedname.id')  
                                     ->leftjoin('m_users as cancelname', 'trans_h_chconditionasset.cancelBy', 'cancelname.id')  
                                    ->select(                      
                                                'kondisi_awal.condition_name as kondisi_awal',
                                                'kondisi_akhir.condition_name as kondisi_akhir',
                                                'm_cabang.cabang_name',
                                                'm_cat_asset.cat_asset_name',
                                                'm_asset.name',
                                                'm_asset.no_id_asset',  
                                                'trans_d_chconditionasset.keterangan',
                                                'approvedname.name as approved_name',
                                                'cancelname.name as cancel_name',           
                                                'trans_h_chconditionasset.*'
                                        )
                                    ->orderBy('trans_d_chconditionasset.id', 'desc')
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
                $collectionData[] = (object) array('val' => $k_dataAsset->kondisi_awal);
                $collectionData[] = (object) array('val' => $k_dataAsset->kondisi_akhir);
                $collectionData[] = (object) array('val' => $k_dataAsset->chcondition_date);
                $collectionData[] = (object) array('val' => $k_dataAsset->cat_asset_name);
                $collectionData[] = (object) array('val' => $k_dataAsset->cabang_name);
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


                // if ($k_dataAsset->flag_cancel == 1) {
                //     $collectionData[] = (object) array('val' => 'Cancel');  
                // }
                // else
                // {
                //     $collectionData[] = (object) array('val' => 'Not Cancel');  
                // }
                // $collectionData[] = (object) array('val' => $k_dataAsset->cancel_name);
                // $collectionData[] = (object) array('val' => $k_dataAsset->cancelDtm);


                // $collectionData[] = (object) array('val' => $k_dataAsset->path_foto);

                // var_dump($collectionData[]); die();

                $valTeam = array_column($collectionData, 'val');
                array_push($allCollections, $valTeam);
           
            }
        }
        return collect([$allCollections]);
        }
}
