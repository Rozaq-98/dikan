<?php

namespace App\Excel\Exports\PhotoKunjungan;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

use DB;

use App\Models\trans_d_resultwo_foto;

class MncMt implements WithEvents
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
                $event->sheet->styleCells(
                    'A1:A1000',
                    [
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
                $event->sheet->styleCells(
                    'B1:B1000',
                    [
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
                $event->sheet->styleCells(
                    'C1:C1000',
                    [
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );

                // styling size column
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(50);

                // style border
                $styleBorder = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                // get input
                $datefrom = $this->datefrom;
                $dateto = $this->dateto;
                $sql = "
                        SELECT
                            a.id,
                            aa.customerid_klien,
                            f.value as username,
                            -- c.value as fatid,
                            a.no_fat as fatid,
                            e.value as ttnumber,
                            a.createdDtm
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
                            WHERE aa.id_klien = 1
                            and aa.id_tipewo = 2
                            and d.nama_kolom like 'TT Number'
                        ) as e on a.id = e.id
                        left join (
                            SELECT a.id, c.value
                            FROM trans_h_resultwo a
                            join trans_h_wo aa on a.id_wo = aa.id
                            JOIN m_users b on a.createdBy = b.id
                            JOIN trans_d_wo c on a.id_wo = c.id_transwo
                            LEFT JOIN m_kolomwo d on c.id_kolomwo = d.id
                            WHERE aa.id_klien = 1
                            and aa.id_tipewo = 2
                            and d.nama_kolom like 'Cust Name'
                        ) as f on a.id = f.id
                        WHERE aa.id_klien = 1
                        and aa.flag_active = 1
                        and aa.id_tipewo = 2
                        and d.nama_kolom like 'FAT ID'
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
                    $numStartHead = 1; $numEndHead = 0;
                    foreach ($data as $k_data) {
                        // push headers
                        $numRow = $numStartHead;
                        for ($i=0; $i < 5; $i++) {
                            if($i==0){
                                $col1 = NULL; $col2 = "Documentation"; $col3 = NULL;
                            } elseif($i==1){
                                $col1 = NULL; $col2 = $k_data->customerid_klien; $col3 = NULL;
                            } elseif($i==2){
                                $col1 = NULL; $col2 = $k_data->username; $col3 = NULL;
                            } elseif($i==3){
                                $col1 = NULL; $col2 = $k_data->ttnumber; $col3 = NULL;
                            } elseif($i==4){
                                $col1 = NULL; $col2 = $k_data->fatid; $col3 = NULL;
                            } else {
                                $col1 = NULL; $col2 = NULL; $col3 = NULL;
                            }

                            $event->sheet->setCellValue('A'.$numRow, $col1);
                            $event->sheet->setCellValue('B'.$numRow, $col2);
                            $event->sheet->setCellValue('C'.$numRow, $col3);

                            $numRow++;
                        }

                        $numEndHead = $numRow-1;
                        // merge cell in header
                        $event->sheet->mergeCells("A".$numStartHead.":A".$numEndHead);
                        $event->sheet->mergeCells("C".$numStartHead.":C".$numEndHead);

                        // set border
                        $event->sheet->getStyle("A".$numStartHead.":C".$numEndHead)->applyFromArray($styleBorder);
                        $event->sheet->getStyle("B".$numStartHead.":B".$numEndHead)->applyFromArray($styleBorder);

                        // add img headers
                        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $drawing->setName('Logo');
                        $drawing->setDescription('Logo');
                        $drawing->setPath(public_path('images/core/logo-wide.png'));
                        $drawing->setHeight(99);
                        $drawing->setResizeProportional(true);
                        $drawing->setCoordinates('A'.$numStartHead);
                        $drawing->setOffsetX(13);
                        $drawing->setWorksheet($event->sheet->getDelegate());
                        // add img headers
                        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $drawing->setName('Logo');
                        $drawing->setDescription('Logo');
                        $drawing->setPath(public_path('images/core/mnc.png'));
                        $drawing->setHeight(99);
                        $drawing->setResizeProportional(true);
                        $drawing->setCoordinates('C'.$numStartHead);
                        $drawing->setOffsetX(30);
                        $drawing->setWorksheet($event->sheet->getDelegate());

                        $dataPhoto = trans_d_resultwo_foto::where('id_resultwo',$k_data->id)->get()->toArray();

                        // data detail
                        if(count($dataPhoto) > 0){
                            $numStartDetail = $numEndHead + 2;
                            $borderStart = $numStartDetail; // set batas border

                            $posCell = 'A';
                            for ($i=0; $i < count($dataPhoto); $i++) {
                                if ($dataPhoto[$i]['path_foto']) {
                                    $event->sheet->setCellValue($posCell.$numStartDetail, $dataPhoto[$i]['foto_name']);
                                    $event->sheet->setCellValue($posCell.($numStartDetail + 1), 'Lat: '.$dataPhoto[$i]['lat_foto']);
                                    $event->sheet->setCellValue($posCell.($numStartDetail + 2), 'Lng: '.$dataPhoto[$i]['long_foto']);

                                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                                    $drawing->setName('Logo');
                                    $drawing->setDescription('Logo');
                                    $drawing->setPath(public_path($dataPhoto[$i]['path_foto']));
                                    $drawing->setHeight(234);
                                    $drawing->setResizeProportional(true);
                                    $drawing->setCoordinates($posCell.($numStartDetail + 3));
                                    $drawing->setOffsetX(85);
                                    $drawing->setWorksheet($event->sheet->getDelegate());

                                    $posCell++;
                                    if($posCell == 'D' && $i < count($dataPhoto)){
                                        $posCell = 'A';
                                        $numStartDetail = $numStartDetail + 16;
                                    }
                                }
                            }
                        } else {
                            $numStartDetail = $numEndHead + 2;
                            $borderStart = $numStartDetail; // set batas border
                        }
                        // set batas border
                        $borderEnd = $numStartDetail + 14;
                        // set border
                        $event->sheet->getStyle("A".$borderStart.":C".$borderEnd)->applyFromArray($styleBorder);

                        // set start for new trans
                        $numStartHead = $numStartDetail + 17;
                    }
                }
            },
        ];
    }
}
