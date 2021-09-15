<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class MasterTeam implements FromCollection, WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'ID Cabang',
            'Cabang Name',
            'ID Team',
            'Team Name'
        ];
    }

    public function collection()
    {
        $qry = "
            select a.id as id_cabang, a.cabang_name, b.id as id_team, b.team_name
            from m_cabang a
            join m_team b on a.id = b.id_cabang
        ";

        $dataTeam = DB::select($qry);

        $allCollections = array();
        if($dataTeam == null){
            $idtranswo = null;
        } else {
            foreach ($dataTeam as $k_dataTeam) {
                $collectionData = [];
                // push headers
                $collectionData[] = (object) array('val' => $k_dataTeam->id_cabang);
                $collectionData[] = (object) array('val' => $k_dataTeam->cabang_name);
                $collectionData[] = (object) array('val' => $k_dataTeam->id_team);
                $collectionData[] = (object) array('val' => $k_dataTeam->team_name);

                $valTeam = array_column($collectionData, 'val');
                array_push($allCollections, $valTeam);
            }
        }
        return collect([$allCollections]);
    }
}
