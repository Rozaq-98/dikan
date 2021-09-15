<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

use DB;
use App\Helpers\GlobalHelper;

use App\Models\m_team_asset;
// use App\Models\m_tipe_asset;
use App\Models\m_klien;
use App\Models\rel_teamasset_klien;
use App\Models\m_tipe_team;

class SummaryTeamAsset implements WithEvents
{
    use Exportable;

    public function __construct( $datefrom, $dateto,  $cabang,  $klien,  $tipe_team)
    {
        // $this->filterdate = $filterdate == 1 ? "aa.date_wo" : "a.result_date";
        $this->datefrom   = $datefrom;
        $this->dateto     = $dateto;
        $this->cabang     = $cabang;
        $this->klien     = $klien;
        $this->tipe_team     = $tipe_team;
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
                $event->sheet->setCellValue('C1', 'Tipe');
                $event->sheet->setCellValue('D1', 'Klien');
                $event->sheet->setCellValue('E1', 'Cabang');
                $event->sheet->setCellValue('F1', 'Status');
                $event->sheet->setCellValue('G1', 'Realisasi');
                
                 $sql = "
                 SELECT
                 m_team_asset.team_asset_name,
                 m_team_asset.flag_active,
                 
                 his_team_asset.last_qty,             
                     m_klien.klien_name,
                     m_cabang.cabang_name,
                     m_tipe_team.tipe_name
                 FROM 
                    m_team_asset 
               
                LEFT JOIN 
                his_team_asset ON m_team_asset.id = his_team_asset.id_team_asset
                LEFT JOIN  rel_teamasset_klien ON m_team_asset.id = rel_teamasset_klien.id_team_asset
                 left join m_cabang on his_team_asset.id_cabang = m_cabang.id
                 
                 left join m_klien on rel_teamasset_klien.id_klien = m_klien.id
                 left join m_tipe_team on m_team_asset.id_tipe_team = m_tipe_team.id
                 WHERE
                 m_team_asset.flag_active in (0,1) 
                                    
             ";

             if($this->cabang) $sql .= " and his_team_asset.id_cabang = {$this->cabang}";
             if($this->klien) $sql .= " and m_klien.id = {$this->klien}";
             if($this->tipe_team) $sql .= " and m_tipe_team.id = {$this->tipe_team}";

             $sql .= " order by m_cabang.cabang_name asc " ;
             
             $data = DB::select($sql);
                
         
                 
                  $posMergeCell = 0;                             
                  $posRow = 2 ;
                  $no =  1;
              

                  if($data != null){
                              
                   
                    foreach($data as $k_dataTeam){
                        $posCell = "A" ;
                       
                        $event->sheet->setCellValue($posCell.$posRow, $no);$posCell++;
                        $event->sheet->setCellValue($posCell.$posRow, $k_dataTeam->team_asset_name);$posCell++;
                        $event->sheet->setCellValue($posCell.$posRow, $k_dataTeam->tipe_name);$posCell++;
                        $event->sheet->setCellValue($posCell.$posRow, $k_dataTeam->klien_name);$posCell++;
                        $event->sheet->setCellValue($posCell.$posRow, $k_dataTeam->cabang_name);$posCell++;
                      
                        if( $k_dataTeam->flag_active == 1){
                            $event->sheet->setCellValue($posCell.$posRow, 'Active');$posCell++;
                        }else{
                            $event->sheet->setCellValue($posCell.$posRow, 'Not Active');$posCell++;
                        }
                        
                        $event->sheet->setCellValue($posCell.$posRow, $k_dataTeam->last_qty);$posCell++;
                        $no++;
                        $posRow++;
                    
                    }   
                    
                }
              
            },
        ];
    }
}
