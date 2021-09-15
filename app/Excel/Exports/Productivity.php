<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

use DB;

class Productivity implements FromCollection, WithHeadings, WithEvents
{
    use Exportable;

    public function __construct($datefrom, $dateto, $idklien, $idtipewo, $idcabang, $idteam, $idkaryawan, $typewo)
    {
        $this->datefrom     = $datefrom;
        $this->dateto       = $dateto;
        $this->idklien      = $idklien;
        $this->idtipewo     = $idtipewo;
        $this->idcabang     = $idcabang;
        $this->idteam       = $idteam;
        $this->idkaryawan   = $idkaryawan;
        $this->typewo       = $typewo;
    }

    public function headings(): array
    {
        $arrTypeWo = array(
            'All Type WO',
            'Instalasi Baru',
            'Maintenance',
            'Dismantle',
        );
        if($this->typewo == null){
            $valArrTypeWo = 0;
        } else {
            $valArrTypeWo = $this->typewo;
        }

        $labelHeading = $arrTypeWo[$valArrTypeWo];
        $arrHeading = array($labelHeading);

        return $arrHeading;
    }

    public function registerEvents(): array
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->mergeCells("A1:J1");
                $event->sheet->styleCells(
                    "A1:J1",
                    [
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
            },
        ];
    }

    public function collection()
    {
        $idcabang = $this->idcabang;
        $datefrom = $this->datefrom;
        $dateto   = $this->dateto;
        $idklien  = $this->idklien;
        $typewo   = $this->typewo;

        $qry = "
            select
                tbl_fix.*,
                tbl_fix.count_all,
                tbl_fix.count_pending,
                tbl_fix.count_close,
                tbl_fix.count_cancel,
                -- count not set
                tbl_fix.count_all-tbl_fix.count_pending-tbl_fix.count_close-tbl_fix.count_cancel as count_notset,
                (tbl_fix.count_all-tbl_fix.count_pending-tbl_fix.count_close-tbl_fix.count_cancel)/tbl_fix.count_all*100 as count_notset_persen
            from
            (
                SELECT
                    tbl_a.id_cabang,
                    m_cabang.cabang_name,
                    -- count all
                    tbl_a.count_all,

                    -- count pending
                    case when (tbl_a.count_all > 0 and tbl_b.count_all) then (tbl_b.count_all)
                    else 0
                    end as count_pending,
                    case when (tbl_a.count_all > 0 and tbl_b.count_all) then (tbl_b.count_all/tbl_a.count_all*100)
                    else 0
                    end as count_pending_persen,

                    -- count close
                    -- tbl_c.count_all as count_close,
                    case when (tbl_a.count_all > 0 and tbl_c.count_all) then (tbl_c.count_all)
                    else 0
                    end as count_close,
                    case when (tbl_a.count_all > 0 and tbl_c.count_all) then (tbl_c.count_all/tbl_a.count_all*100)
                    else 0
                    end as count_close_persen,

                    -- count cancel
                    -- tbl_d.count_all as count_cancel,
                    case when (tbl_a.count_all > 0 and tbl_d.count_all) then (tbl_d.count_all)
                    else 0
                    end as count_cancel,
                    case when (tbl_a.count_all > 0 and tbl_d.count_all) then (tbl_d.count_all/tbl_a.count_all*100)
                    else 0
                    end as count_cancel_persen
                from
                (
                    SELECT id_cabang, count(id) count_all FROM trans_h_wo
                    where date_wo between '$datefrom' and '$dateto'
                    and trans_h_wo.flag_active = 1
                    and id_klien = '$idklien'";
                    if($this->idcabang != null) $qry .= " and id_cabang = '{$idcabang}'";
                    if($this->typewo != null) $qry .= " and id_tipewo = '{$typewo}'";
                    $qry .= " GROUP BY id_cabang";
        $qry .= ") as tbl_a
                LEFT JOIN
                (
                    SELECT b.id_cabang, count(a.id) count_all
                    FROM (select * from trans_h_resultwo group by id_wo) a
                    join trans_h_wo b on a.id_wo = b.id
                    where a.id_statuswo = 1
                    and a.flag_active = 1
                    and b.flag_active = 1
                    and b.date_wo between '$datefrom' and '$dateto'
                    and b.id_klien = '$idklien'";
                    if($this->idcabang != null) $qry .= " and b.id_cabang = '{$idcabang}'";
                    if($this->typewo != null) $qry .= " and b.id_tipewo = '{$typewo}'";
                    $qry .= " GROUP BY b.id_cabang";
        $qry .= ") as tbl_b on tbl_a.id_cabang = tbl_b.id_cabang
                LEFT JOIN
                (
                    SELECT b.id_cabang, count(a.id) count_all
                    FROM (select * from trans_h_resultwo group by id_wo) a
                    join trans_h_wo b on a.id_wo = b.id
                    where a.id_statuswo = 2
                    and a.flag_active = 1
                    and b.flag_active = 1
                    and b.date_wo between '$datefrom' and '$dateto'
                    and b.id_klien = '$idklien'";
                    if($this->idcabang != null) $qry .= " and b.id_cabang = '{$idcabang}'";
                    if($this->typewo != null) $qry .= " and b.id_tipewo = '{$typewo}'";
                    $qry .= " GROUP BY b.id_cabang";
        $qry .= ") as tbl_c on tbl_a.id_cabang = tbl_c.id_cabang
                LEFT JOIN
                (
                    SELECT b.id_cabang, count(a.id) count_all
                    FROM (select * from trans_h_resultwo group by id_wo) a
                    join trans_h_wo b on a.id_wo = b.id
                    where a.id_statuswo = 3
                    and a.flag_active = 1
                    and b.flag_active = 1
                    and b.date_wo between '$datefrom' and '$dateto'
                    and b.id_klien = '$idklien'";
                    if($this->idcabang != null) $qry .= " and b.id_cabang = '{$idcabang}'";
                    if($this->typewo != null) $qry .= " and b.id_tipewo = '{$typewo}'";
                    $qry .= " GROUP BY b.id_cabang";
        $qry .= ") as tbl_d on tbl_a.id_cabang = tbl_d.id_cabang
                JOIN m_cabang on tbl_a.id_cabang = m_cabang.id
            ) as tbl_fix
        ";
        $dataTransHWo = DB::select($qry);

        // based column
        $arrHeading = array(
            'Row Labels',
            'Total Wo',
            'Not Set ID',
            'Not Set %',
            'Done ID',
            'Done %',
            'Pending ID',
            'Pending %',
            'Cancel ID',
            'Cancel %',
        );

        $allCollections = array();
        array_push($allCollections, $arrHeading);
        if($dataTransHWo == null){
            $idtranswo = null;
        } else {
            foreach ($dataTransHWo as $k_dataTransHWo) {
                $collectionData = [];
                // push headers
                $collectionData[] = (object) array('val' => $k_dataTransHWo->cabang_name);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->count_all);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->count_notset);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->count_notset_persen);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->count_close);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->count_close_persen);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->count_pending);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->count_pending_persen);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->count_cancel);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->count_cancel_persen);

                $valWo = array_column($collectionData, 'val');
                array_push($allCollections, $valWo);
            }
        }
        return collect([$allCollections]);
    }
}
