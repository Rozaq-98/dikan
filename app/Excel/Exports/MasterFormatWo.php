<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

use App\Models\trans_d_wo;

class MasterFormatWo implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct(int $idklien, int $idtipewo)
    {
        $this->idklien  = $idklien;
        $this->idtipewo = $idtipewo;
    }

    public function headings(): array
    {
        $arrHeading = array(
            'Tanggal Wo',
            'Customer ID Klien',
            'Customer Name',
            'Alamat',
            'No Wo Klien',
            'ID Slot Schedule',
            'ID Cabang',
            'Tanggal Assign',
            'Team Name'
        );

        $dataMasterWo = DB::table('m_kolomwo')
                            ->where('id_klien', $this->idklien)
                            ->where('id_tipewo',$this->idtipewo)
                            ->get();

        foreach($dataMasterWo as $k_dataMasterWo){
            array_push($arrHeading, $k_dataMasterWo->nama_kolom);
        }

        return $arrHeading;
    }

    public function collection()
    {
        $dataTransHWo = DB::table('trans_h_wo')
                            ->leftjoin('trans_assignwo', 'trans_h_wo.id', 'trans_assignwo.id_wo')
                            ->leftjoin('m_team', 'trans_assignwo.id_team', 'm_team.id')
                            ->where('id_klien', $this->idklien)
                            ->where('id_tipewo',$this->idtipewo)
                            ->select('trans_h_wo.*', 'trans_assignwo.assign_date', 'm_team.team_name')
                            ->first();

        if($dataTransHWo == null){
            $idtranswo = null;
        } else{
            $idtranswo = $dataTransHWo->id;
        }

        $dataTransDWo = DB::table('trans_d_wo')
                            ->where('id_transwo', $idtranswo)
                            ->get();

        $collectionData = array();
        if($dataTransHWo != null){
            $collectionData[] = (object) array('val' => $dataTransHWo->date_wo);
            $collectionData[] = (object) array('val' => $dataTransHWo->customerid_klien);
            $collectionData[] = (object) array('val' => $dataTransHWo->customer_name);
            $collectionData[] = (object) array('val' => $dataTransHWo->alamat);
            $collectionData[] = (object) array('val' => $dataTransHWo->no_wo_klien);
            $collectionData[] = (object) array('val' => $dataTransHWo->id_slot);
            $collectionData[] = (object) array('val' => $dataTransHWo->id_cabang);
            $collectionData[] = (object) array('val' => $dataTransHWo->assign_date);
            $collectionData[] = (object) array('val' => $dataTransHWo->team_name);
        }
        if($dataTransDWo != null){
            foreach($dataTransDWo as $k_dataTransDWo){
                $collectionData[] = (object) array('val' => $k_dataTransDWo->value);
            }
        }

        $valWo = array_column($collectionData, 'val');

        return collect([$valWo]);
    }
}
