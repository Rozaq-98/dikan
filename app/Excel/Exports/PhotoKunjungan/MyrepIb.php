<?php

namespace App\Excel\Exports\PhotoKunjungan;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

use DB;
use App\Helpers\GlobalHelper;

use App\Models\trans_d_resultwo_foto;

class MyrepIb implements WithEvents
{
    use Exportable;

    public function __construct($datefrom, $dateto, $cabang)
    {
        $this->datefrom     = $datefrom;
        $this->dateto       = $dateto;
        $this->cabang       = $cabang;
    }

    public function registerEvents(): array
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        return [
            AfterSheet::class    => function(AfterSheet $event) {
                // styling all cell
                $postCellHead = 'A';
                for ($i=0; $i < 50; $i++) {
                    $event->sheet->styleCells(
                        $postCellHead.'1:'.$postCellHead.'1000',
                        [
                            'alignment' => [
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]
                    );
                    $postCellHead++;
                }


                // styling size column
                // $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(50);
                // $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(50);
                // $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(50);

                // style border
                $styleBorder = [
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'b218c9',
                        ],
                    ],
                    'font' => array(
                        'color' => ['rgb' => 'ffffff'],
                    )
                ];
                $event->sheet->getStyle('A5:F5')->applyFromArray($styleBorder);
                $event->sheet->getStyle('A6:F6')->applyFromArray($styleBorder);
                // Set background color for a specific cell
                // $event->sheet->styleCells(
                //    'A5:F5',
                //     [
                //         'fill' => [
                //             'type'  => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                //             'color' => array('rgb' => 'FF0000'),
                //         ],
                //     ]
                // );

                // header
                $event->sheet->setCellValue('A1', 'VENDOR PT. QUANTUM NUSATAMA');
                $event->sheet->setCellValue('A2', 'PERIODE '.GlobalHelper::formatDateToDmy($this->datefrom).' s.d '.GlobalHelper::formatDateToDmy($this->dateto));
                $event->sheet->setCellValue('A3', 'CUSTOMER PO: ');
                $event->sheet->setCellValue('A4', '');

                // header detail
                $event->sheet->setCellValue('A5', 'No.');
                $event->sheet->setCellValue('B5', 'CUSTOMER INFORMATION');
                $event->sheet->setCellValue('E5', 'FAT NO');
                $event->sheet->setCellValue('F5', 'DOKUMENTASI');

                $event->sheet->setCellValue('B6', 'CUSTOMER ID');
                $event->sheet->setCellValue('C6', 'CUSTOMER NAME');
                $event->sheet->setCellValue('D6', 'CUSTOMER ADDRESS');

                // merge cell in header
                $event->sheet->mergeCells("A5:A6");
                $event->sheet->mergeCells("B5:D5");
                $event->sheet->mergeCells("E5:E6");
                // $event->sheet->mergeCells("F5:F6");

                // get input
                $datefrom = $this->datefrom;
                $dateto = $this->dateto;
                $sql = "
                        SELECT
                            a.id,
                            aa.no_wo_klien as ttnumber,
                            aa.customerid_klien,
                            aa.customer_name as custname,
                            aa.alamat as address,
                            DATE_FORMAT(a.createdDtm, '%W, %M %d, %Y') as createdDtm,
                            g.type_name,
                            a.typepekerjaan_name,
                            a.no_fat
                        FROM trans_h_resultwo a
                        join trans_h_wo aa on a.id_wo = aa.id
                        JOIN m_users b on a.createdBy = b.id
                        JOIN trans_d_wo c on a.id_wo = c.id_transwo
                        LEFT JOIN m_kolomwo d on c.id_kolomwo = d.id
                        left join (
                            SELECT a.id, c.value
                            FROM trans_h_resultwo a
                            join trans_h_wo aa on a.id_wo = aa.id
                            JOIN m_users b on a.createdBy = b.id
                            JOIN trans_d_wo c on a.id_wo = c.id_transwo
                            LEFT JOIN m_kolomwo d on c.id_kolomwo = d.id
                            WHERE aa.id_klien = 3
                            and aa.id_tipewo = 1
                            and d.nama_kolom like 'TT Number'
                            and d.flag_Active = 1
                        ) as e on a.id = e.id
                        left join (
                            SELECT a.id, c.value
                            FROM trans_h_resultwo a
                            join trans_h_wo aa on a.id_wo = aa.id
                            JOIN m_users b on a.createdBy = b.id
                            JOIN trans_d_wo c on a.id_wo = c.id_transwo
                            LEFT JOIN m_kolomwo d on c.id_kolomwo = d.id
                            WHERE aa.id_klien = 3
                            and aa.id_tipewo = 1
                            and trim(d.nama_kolom) = trim('Street Nama')
                            and d.flag_Active = 1
                        ) as f on a.id = f.id
                        left join m_tipewo g on aa.id_tipewo = g.id
                        WHERE aa.id_klien = 3
                        and aa.flag_active = 1
                        and aa.id_tipewo = 1
                        and d.nama_kolom like 'End-User Name'
                        and d.flag_Active = 1
                        and aa.date_wo between ? and ?
                        -- and a.approvedBy > 0
                ";
                if($this->cabang == 0){
                    $data = DB::select("$sql", [$datefrom, $dateto]);
                }
                else {
                    $sql .= " and aa.id_cabang = ?";
                    $data = DB::select("$sql", [$datefrom, $dateto, $this->cabang]);
                }

                // checking data null
                if($data == null){
                    $idtranshwo = null;
                } else {
                    $numStartHead = 7;
                    $lastPost = null;
                    $maxLengtPhoto = 0;
                    $number = 1;
                    foreach ($data as $k_data) {
                        // push headers
                        $numRow = $numStartHead;

                        // push primary data
                        $event->sheet->setCellValue('A'.$numRow, $number);
                        // $event->sheet->setCellValue('B'.$numRow, $k_data->ttnumber);
                        $event->sheet->setCellValue('B'.$numRow, $k_data->customerid_klien);
                        $event->sheet->setCellValue('C'.$numRow, $k_data->custname);
                        $event->sheet->setCellValue('D'.$numRow, $k_data->address);
                        $event->sheet->setCellValue('E'.$numRow, $k_data->no_fat);
                        // $event->sheet->setCellValue('G'.$numRow, $k_data->type_name);
                        // $event->sheet->setCellValue('H'.$numRow, $k_data->typepekerjaan_name);

                        // get data photo
                        $dataPhoto = trans_d_resultwo_foto::where('id_resultwo',$k_data->id)->get()->toArray();

                        $posCell = 'F';
                        // data detail
                        if(count($dataPhoto) > 0){
                            for ($i=0; $i < count($dataPhoto); $i++) {
                                if ($dataPhoto[$i]['path_foto']) {
                                    // photo name
                                    $event->sheet->setCellValue($posCell.$numRow, $dataPhoto[$i]['foto_name']);
                                    $event->sheet->setCellValue($posCell.($numRow + 1), $dataPhoto[$i]['lat_foto']);
                                    $event->sheet->setCellValue($posCell.($numRow + 2), $dataPhoto[$i]['long_foto']);

                                    // photo
                                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                                    $drawing->setName('Logo');
                                    $drawing->setDescription('Logo');
                                    $drawing->setPath(public_path($dataPhoto[$i]['path_foto']));
                                    $drawing->setHeight(100);
                                    $drawing->setResizeProportional(true);
                                    $drawing->setCoordinates($posCell.($numRow+3));
                                    $drawing->setOffsetX(40);
                                    $drawing->setWorksheet($event->sheet->getDelegate());

                                    // set width col
                                    $event->sheet->getDelegate()->getColumnDimension($posCell)->setWidth(21.5);
                                    $posCell++;
                                }
                            }
                        }

                        $numStartHead = $numRow + 8;
                        $lastPost = $posCell;

                        // checking length photo
                        if($maxLengtPhoto < count($dataPhoto)){
                            $maxLengtPhoto = count($dataPhoto);
                            $lastPost--;

                            // merge header
                            $event->sheet->mergeCells("A1:".$lastPost."1");
                            $event->sheet->mergeCells("A2:".$lastPost."2");
                            $event->sheet->mergeCells("A3:".$lastPost."3");
                            $event->sheet->mergeCells("A4:".$lastPost."4");

                            // merge dokumentasi
                            $event->sheet->mergeCells("F5:".$lastPost."6");
                        }
                        $number++;
                    }
                }
            },
        ];
    }
}
