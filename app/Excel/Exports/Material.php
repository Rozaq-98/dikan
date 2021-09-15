<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

use DB;

use App\Models\m_material;

class Material implements WithEvents
{
    use Exportable;

    public function __construct($filterdate, $datefrom, $dateto, $client, $typewo, $cabang)
    {
        $this->filterdate = $filterdate == 1 ? "aa.date_wo" : "a.result_date";
        $this->datefrom   = $datefrom;
        $this->dateto     = $dateto;
        $this->client     = $client;
        $this->typewo     = $typewo;
        $this->cabang     = $cabang;
    }

    public function registerEvents(): array
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        return [
            AfterSheet::class    => function(AfterSheet $event) {
                // styling all cell
                $event->sheet->styleCells(
                    'A1:EW1000',
                    [
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );

                // style border
                $styleBorder = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                // header - row 1
                $event->sheet->setCellValue('A1', 'No.');
                $event->sheet->setCellValue('B1', 'Team');
                $event->sheet->setCellValue('C1', 'Team');
                $event->sheet->setCellValue('D1', 'Tanggal Pemasangan');
                $event->sheet->setCellValue('E1', 'Id Pelanggan');
                $event->sheet->setCellValue('F1', 'Nama Pelanggan');
                $event->sheet->setCellValue('G1', 'WO Type');
                $event->sheet->setCellValue('H1', 'TT');
                $event->sheet->setCellValue('I1', 'Remarks');
                $event->sheet->setCellValue('J1', 'Report Pekerjaan');
                // header - row 2
                $event->sheet->setCellValue('A2', 'No.');
                $event->sheet->setCellValue('B2', 'Teknisi');
                $event->sheet->setCellValue('C2', 'Teknisi');
                $event->sheet->setCellValue('D2', 'Tanggal Pemasangan');
                $event->sheet->setCellValue('E2', 'Id Pelanggan');
                $event->sheet->setCellValue('F2', 'Nama Pelanggan');
                $event->sheet->setCellValue('G2', 'WO Type');
                $event->sheet->setCellValue('H2', 'TT');
                $event->sheet->setCellValue('I2', 'Remarks');
                $event->sheet->setCellValue('J2', 'Report Pekerjaan');

                // merge cell in header
                $event->sheet->mergeCells("A1:A2");
                $event->sheet->mergeCells("B1:C1");
                $event->sheet->mergeCells("D1:D2");
                $event->sheet->mergeCells("E1:E2");
                $event->sheet->mergeCells("F1:F2");
                $event->sheet->mergeCells("G1:G2");
                $event->sheet->mergeCells("H1:H2");
                $event->sheet->mergeCells("I1:I2");
                $event->sheet->mergeCells("J1:J2");

                $dataMaterial = m_material::where('flag_Active', 1)->get();

                if($dataMaterial != null){
                    $posCell = 'K';
                    $posCell2 = '' ;

                    $arrIdMaterial = [];
                    foreach($dataMaterial as $k_dataMaterial){
                        array_push($arrIdMaterial, $k_dataMaterial->id);

                          $event->sheet->setCellValue($posCell."1", $k_dataMaterial->material_name);
                       	 $posCell2 =  $posCell;
                          $event->sheet->setCellValue($posCell."2","Qty");
                           $posCell++;
                          $event->sheet->setCellValue($posCell."2","SN");
                          $posCell++;

                          $event->sheet->setCellValue($posCell."2","Mac Address");
                          $event->sheet->mergeCells($posCell2."1".":".$posCell."1");


                      	 $posCell++;

                    }

                }

                // get input
                $datefrom   = $this->datefrom." 00:00:00";
                $dateto     = $this->dateto." 23:59:59";

                $sql = "
                    SELECT
                        a.id,
                        b.username,
                        a.result_date,
                        aa.customerid_klien,
                        aa.customer_name as custname,
                        c.type_name,
                        f.value as ttnumber,
                        a.typepekerjaan_name,
                        a.notes,
                        aa.date_wo,
                        a.result_date
                    FROM trans_h_resultwo a
                    JOIN trans_h_wo aa on a.id_wo = aa.id
                    JOIN m_users b on a.createdBy = b.id
                    JOIN m_tipewo c on aa.id_tipewo = c.id
                    left JOIN (
                        SELECT a.id, c.value
                        FROM trans_h_resultwo a
                        JOIN trans_h_wo aa on a.id_wo = aa.id
                        JOIN m_users b on a.createdBy = b.id
                        JOIN trans_d_wo c on a.id_wo = c.id_transwo
                        LEFT JOIN m_kolomwo d on c.id_kolomwo = d.id
                        WHERE aa.id_klien = {$this->client}
                        and aa.id_tipewo = {$this->typewo}
                        and (
                            d.nama_kolom like 'Customer Name' OR
                            d.nama_kolom like 'End-User Name' OR
                            d.nama_kolom like 'Nama'
                        )
                        and d.flag_active = 1
                        and {$this->filterdate} between '{$datefrom}' and '{$dateto}'
                    ) as e on a.id = e.id
                    left JOIN (
                        SELECT a.id, c.value
                        FROM trans_h_resultwo a
                        JOIN trans_h_wo aa on a.id_wo = aa.id
                        JOIN m_users b on a.createdBy = b.id
                        JOIN trans_d_wo c on a.id_wo = c.id_transwo
                        LEFT JOIN m_kolomwo d on c.id_kolomwo = d.id
                        WHERE aa.id_klien = {$this->client}
                        and aa.id_tipewo = {$this->typewo}
                        and (
                                d.nama_kolom like 'TT Number' OR
                                d.nama_kolom like 'No'
                            )
                        and d.flag_active = 1
                        and {$this->filterdate} between '{$datefrom}' and '{$dateto}'
                    ) as f on a.id = f.id
                    WHERE aa.id_klien = {$this->client}
                    and aa.id_tipewo = {$this->typewo}
                    and {$this->filterdate} between '{$datefrom}' and '{$dateto}'

                ";
                if($this->cabang) $sql .= "and aa.id_cabang = {$this->cabang}";
                $data = DB::select($sql);

                if($data != null){
                    $posCellDetail  = 'A';
                    $posNumCell     = 3;
                    $num            = 1;
                    foreach($data as $k_data) {
                        $tmpPosCellDetail = $posCellDetail;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $num); $tmpPosCellDetail++;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $k_data->username); $tmpPosCellDetail++;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, ''); $tmpPosCellDetail++;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $k_data->result_date); $tmpPosCellDetail++;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $k_data->customerid_klien); $tmpPosCellDetail++;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $k_data->custname); $tmpPosCellDetail++;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $k_data->type_name); $tmpPosCellDetail++;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $k_data->ttnumber); $tmpPosCellDetail++;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $k_data->typepekerjaan_name); $tmpPosCellDetail++;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $k_data->notes); $tmpPosCellDetail++;

                        $detailMaterial = DB::select('
                            SELECT id_resultwo, id_material, sum(qty) as qty,GROUP_CONCAT(serialnumber,",") as serialnumber,GROUP_CONCAT(`macaddress`,",") as macaddress FROM `trans_d_resultwo_material`
                            where id_resultwo = ?
                            GROUP BY id_material
                        ', [$k_data->id]);

                        if ($detailMaterial != null) {
                            foreach($detailMaterial as $k_detailMaterial){
                                $tmpPosMaterial = $tmpPosCellDetail;
                                for($i=0; $i<count($arrIdMaterial); $i++){
                                    if($arrIdMaterial[$i] == $k_detailMaterial->id_material){
                                        $event->sheet->setCellValue($tmpPosMaterial.$posNumCell, $k_detailMaterial->qty);
                                        $tmpPosMaterial++;
                                        $event->sheet->setCellValue($tmpPosMaterial.$posNumCell, $k_detailMaterial->serialnumber);
                                        $tmpPosMaterial++;
                                        $event->sheet->setCellValue($tmpPosMaterial.$posNumCell, $k_detailMaterial->macaddress);
                                    }
                                    else {
                                     	$tmpPosMaterial++;
                                      	$tmpPosMaterial++;
                                    }
                                    $tmpPosMaterial++;
                                }
                            }

                        }

                        $posNumCell++; $num++;
                    }
                }
            },
        ];
    }
}
