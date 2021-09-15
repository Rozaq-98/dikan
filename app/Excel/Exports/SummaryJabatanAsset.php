<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

use DB;
use App\Helpers\GlobalHelper;

use App\Models\m_jabatan_asset;
// use App\Models\m_tipe_asset;
use App\Models\m_klien;
use App\Models\rel_jabatanasset_klien;
use App\Models\m_tipe_jabatan;

class SummaryJabatanAsset implements WithEvents
{
    use Exportable;

    public function __construct( $datefrom, $dateto,  $cabang,    $tipe_jabatan)
    {
        // $this->filterdate = $filterdate == 1 ? "aa.date_wo" : "a.result_date";
        $this->datefrom   = $datefrom;
        $this->dateto     = $dateto;
        $this->cabang     = $cabang;
       
        $this->tipe_jabatan     = $tipe_jabatan;
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
                $event->sheet->setCellValue('B1', 'Jabatan');
                $event->sheet->setCellValue('C1', 'Tipe');
                $event->sheet->setCellValue('D1', 'Cabang');
                $event->sheet->setCellValue('E1', 'Status');
                $event->sheet->setCellValue('F1', 'Realisasi');
               
                
                 $sql = "
                 SELECT
                 m_jabatan_asset.jabatan_asset_name,
                 m_jabatan_asset.flag_active,
                 his_jabatan_asset.last_qty,           
                   
                     m_cabang.cabang_name,
                     m_tipe_jabatan.tipe_name
                 FROM 
                    m_jabatan_asset 
                LEFT JOIN 
                his_jabatan_asset ON m_jabatan_asset.id = his_jabatan_asset.id_jabatan_asset
                 left join m_cabang on his_jabatan_asset.id_cabang = m_cabang.id
                 
                 left join m_tipe_jabatan on m_jabatan_asset.id_tipe_jabatan = m_tipe_jabatan.id
                 WHERE
                 m_jabatan_asset.flag_active in (0,1) 
                                    
             ";

             if($this->cabang) $sql .= " and m_jabatan_asset.id_cabang = {$this->cabang}";
             
             if($this->tipe_jabatan) $sql .= " and m_tipe_jabatan.id = {$this->tipe_jabatan}";

             $sql .= " order by m_cabang.cabang_name asc " ;
             
             $data = DB::select($sql);
                
         
                 
                  $posMergeCell = 0;                             
                  $posRow = 2 ;
                  $no =  1;
              

                  if($data != null){
                              
                   
                    foreach($data as $k_dataJabatan){
                        $posCell = "A" ;
                       
                        $event->sheet->setCellValue($posCell.$posRow, $no);$posCell++;
                        $event->sheet->setCellValue($posCell.$posRow, $k_dataJabatan->jabatan_asset_name);$posCell++;
                        $event->sheet->setCellValue($posCell.$posRow, $k_dataJabatan->tipe_name);$posCell++;
                        
                        $event->sheet->setCellValue($posCell.$posRow, $k_dataJabatan->cabang_name);$posCell++;
                      
                        if( $k_dataJabatan->flag_active == 1){
                            $event->sheet->setCellValue($posCell.$posRow, 'Active');$posCell++;
                        }else{
                            $event->sheet->setCellValue($posCell.$posRow, 'Not Active');$posCell++;
                        }
                        
                        $event->sheet->setCellValue($posCell.$posRow, $k_dataJabatan->last_qty);$posCell++;
                        $no++;
                        $posRow++;
                    
                    }   
                    
                }
              
            },
        ];
    }
}
