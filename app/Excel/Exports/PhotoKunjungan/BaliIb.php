<?php

namespace App\Excel\Exports\PhotoKunjungan;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

use DB;

use App\Models\trans_d_resultwo_foto;

class BaliIb implements WithEvents
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
                // styling size column
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);

                $event->sheet->mergeCells("A1:F1");
                $event->sheet->getRowDimension(1)->setRowHeight(60);

                // add img headers
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('images/core/balifiber.png'));
                $drawing->setHeight(80);
                $drawing->setResizeProportional(true);
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($event->sheet->getDelegate());

                $event->sheet->setCellValue('A1', 'Report Form');
                $event->sheet->styleCells(
                    'A1',
                    [
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        ],
                        'font' => array(
                            'name'      =>  'Cambria',
                            'size'      =>  27,
                        )
                    ]
                );

                // get input
                $datefrom   = $this->datefrom;
                $dateto     = $this->dateto;
                $sql = "
                        SELECT
                            a.id,
                            a.result_date,
                            aaa.time_1,
                            a.createdDtm,
                            b.username,
                            aa.no_wo_klien,
                            aa.customerid_klien,
                            e.value as custname,
                            f.value as address,
                            -- c.value as fatid,
                            a.no_fat as fatid,
                            g.value as notes
                        FROM trans_h_resultwo a
                        join trans_h_wo aa on a.id_wo = aa.id
                        join m_slot_schedule aaa on aa.id_slot = aaa.id
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
                            WHERE aa.id_klien = 4
                            and aa.id_tipewo = 1
                            and d.nama_kolom like 'Customer Name'
                            and d.flag_active = 1
                        ) as e on a.id = e.id
                        left join (
                            SELECT a.id, c.value
                            FROM trans_h_resultwo a
                            join trans_h_wo aa on a.id_wo = aa.id
                            JOIN m_users b on a.createdBy = b.id
                            JOIN trans_d_wo c on a.id_wo = c.id_transwo
                            LEFT JOIN m_kolomwo d on c.id_kolomwo = d.id
                            WHERE aa.id_klien = 4
                            and aa.id_tipewo = 1
                            and d.nama_kolom like 'Street Address'
                            and d.flag_active = 1
                        ) as f on a.id = f.id
                                                    left join (
                            SELECT a.id, c.value
                            FROM trans_h_resultwo a
                            join trans_h_wo aa on a.id_wo = aa.id
                            JOIN m_users b on a.createdBy = b.id
                            JOIN trans_d_wo c on a.id_wo = c.id_transwo
                            LEFT JOIN m_kolomwo d on c.id_kolomwo = d.id
                            WHERE aa.id_klien = 4
                            and aa.id_tipewo = 1
                            and d.nama_kolom like 'Reason'
                            and d.flag_active = 1
                        ) as g on a.id = g.id
                        WHERE aa.id_klien = 4
                        and aa.flag_active = 1
                        and aa.id_tipewo = 1
                        and trim(d.nama_kolom) like trim('FAT ID')
                        and d.flag_active = 1
                        and aa.date_wo between ? and ?
                        -- and a.approvedBy > 0
                    ";
                if($this->cabang == 0){
                    $data = DB::select("$sql", [$datefrom, $dateto]);
                } else {
                    $sql .= " and aa.id_cabang = ?";
                    $data = DB::select("$sql", [$datefrom, $dateto, $this->cabang]);
                }

                // start looping
                if($data != null){
                    $row = 3;
                    $tmpRow = $row;
                    foreach ($data as $k_data) {

                        // ###########################################
                        //  nama pt balifiber
                        $event->sheet->setCellValue('A'.$tmpRow, 'PT. Bali Towerindo Sentra, Tbk');
                        $event->sheet->mergeCells('A'.$tmpRow.':C'.$tmpRow);
                        // date
                        $event->sheet->setCellValue('E'.$tmpRow, 'Date:');
                        $event->sheet->setCellValue('F'.$tmpRow, $k_data->result_date);
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('A'.$tmpRow, 'Care Center');
                        $event->sheet->setCellValue('B'.$tmpRow, '021-2208 5800');
                        $event->sheet->setCellValue('E'.$tmpRow, 'Time Start:');
                        $event->sheet->setCellValue('F'.$tmpRow, $k_data->time_1);
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('B'.$tmpRow, 'care@balifiber.id');
                        $event->sheet->setCellValue('E'.$tmpRow, 'Time Completed:');
                        $event->sheet->setCellValue('F'.$tmpRow, $k_data->createdDtm);
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('E'.$tmpRow, 'Installer:');
                        $event->sheet->setCellValue('F'.$tmpRow, $k_data->username);
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('F'.$tmpRow, '');
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('A'.$tmpRow, 'Work Order No:');
                        $event->sheet->setCellValue('B'.$tmpRow, $k_data->no_wo_klien);
                        $event->sheet->setCellValue('E'.$tmpRow, 'Service ID:');
                        $event->sheet->setCellValue('F'.$tmpRow, '');
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('A'.$tmpRow, 'Customer Info:');
                        $event->sheet->setCellValue('B'.$tmpRow, '[Customer ID] :');
                        $event->sheet->setCellValue('C'.$tmpRow, $k_data->customerid_klien);
                        $event->sheet->setCellValue('E'.$tmpRow, 'Cluster ID:');
                        $event->sheet->setCellValue('F'.$tmpRow, '');
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('B'.$tmpRow, '[Customer Name] :');
                        $event->sheet->setCellValue('C'.$tmpRow, $k_data->custname);
                        $event->sheet->setCellValue('E'.$tmpRow, 'FDT ID:');
                        $event->sheet->setCellValue('F'.$tmpRow, '');
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('B'.$tmpRow, '[Street Address] :');
                        $event->sheet->setCellValue('C'.$tmpRow, $k_data->address);
                        $event->sheet->setCellValue('E'.$tmpRow, 'FAT ID:');
                        $event->sheet->setCellValue('F'.$tmpRow, $k_data->fatid);
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('B'.$tmpRow, '[City, Postal Code]:');
                        $event->sheet->setCellValue('C'.$tmpRow, '');
                        $event->sheet->setCellValue('E'.$tmpRow, 'Homepass ID:');
                        $event->sheet->setCellValue('F'.$tmpRow, '');
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('B'.$tmpRow, '[Phone] :');
                        $event->sheet->setCellValue('C'.$tmpRow, '');
                        $event->sheet->setCellValue('E'.$tmpRow, 'ONT ID:');
                        $event->sheet->setCellValue('F'.$tmpRow, '');
                        $tmpRow++;$tmpRow++;$tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('A'.$tmpRow, 'Installation Notes:');
                        $tmpRow++;
                        $event->sheet->setCellValue('A'.$tmpRow, $k_data->notes);
                        $event->sheet->styleCells(
                            'A'.$tmpRow,
                            [
                                'alignment' => [
                                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                                ],
                            ]
                        );
                        $event->sheet->mergeCells("A".$tmpRow.":F".$tmpRow);
                        $tmpRow++;$tmpRow++;$tmpRow++;
                        // ###########################################

                        // *********** foto ***********
                        // ###########################################
                        $event->sheet->setCellValue('A'.$tmpRow, 'PT. Bali Towerindo Sentra, Tbk');
                        $event->sheet->mergeCells("A".$tmpRow.":C".$tmpRow);
                        // date
                        $event->sheet->setCellValue('D'.$tmpRow, 'Date:');
                        $event->sheet->setCellValue('E'.$tmpRow, '');
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('A'.$tmpRow, 'Care Center');
                        $event->sheet->setCellValue('B'.$tmpRow, '021-2208 5800');
                        $event->sheet->setCellValue('D'.$tmpRow, 'Work Order No:');
                        $event->sheet->setCellValue('E'.$tmpRow, '');
                        $tmpRow++;
                        // ###########################################

                        // ###########################################
                        $event->sheet->setCellValue('B'.$tmpRow, 'care@balifiber.id');
                        $event->sheet->setCellValue('D'.$tmpRow, 'Service ID:');
                        $event->sheet->setCellValue('E'.$tmpRow, '');
                        $tmpRow++;$tmpRow++;
                        // ###########################################

                        // tittle tag photo
                        $event->sheet->setCellValue('A'.$tmpRow, 'Installation Photos:');
                        $tmpRow++;$tmpRow++;

                        // looping foto
                        $dataPhoto = trans_d_resultwo_foto::where('id_resultwo',$k_data->id)->get()->toArray();
                        // dd($dataPhoto);
                        if(count($dataPhoto) > 0){
                            for ($i=0; $i < count($dataPhoto); $i++) {
                                if($i==0 || $i%2 == 0){
                                    $posCell1 = 'A';
                                    $posCell2 = 'C';
                                } else {
                                    $posCell1 = 'D';
                                    $posCell2 = 'F';
                                }

                                // photo name
                                $event->sheet->setCellValue($posCell1.($tmpRow), $dataPhoto[$i]['foto_name']);
                                $event->sheet->styleCells(
                                    $posCell1.($tmpRow),
                                    [
                                        'alignment' => [
                                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                        ],
                                    ]
                                );
                                $event->sheet->mergeCells($posCell1.($tmpRow).':'.$posCell2.($tmpRow));
                                // lat long
                                $event->sheet->setCellValue($posCell1.($tmpRow + 1), 'Lat: '.$dataPhoto[$i]['lat_foto'].' Long: '.$dataPhoto[$i]['long_foto']);
                                $event->sheet->styleCells(
                                    $posCell1.'27',
                                    [
                                        'alignment' => [
                                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                        ],
                                    ]
                                );
                                $event->sheet->mergeCells($posCell1.($tmpRow + 1).":".$posCell2.($tmpRow + 1));

                                // img
                                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                                $drawing->setName('Logo');
                                $drawing->setDescription('Logo');
                                $drawing->setPath(public_path($dataPhoto[$i]['path_foto']));
                                $drawing->setHeight(210);
                                $drawing->setResizeProportional(true);
                                $drawing->setCoordinates($posCell1.($tmpRow+2));
                                $drawing->setOffsetX(85);
                                $drawing->setWorksheet($event->sheet->getDelegate());

                                if($i > 0 && $i%2 != 0){
                                    $tmpRow = $tmpRow+14;
                                }
                            }
                        }
                        $tmpRow = $tmpRow+15;
                    }
                } else {
                    return redirect()->back()->with('error', 'Data not found.');
                }
            },
        ];
    }
}
