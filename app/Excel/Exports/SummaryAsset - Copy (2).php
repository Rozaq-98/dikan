<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

use DB;
use App\Helpers\GlobalHelper;

use App\Models\m_asset;
use App\Models\m_tipe_asset;
use App\Models\m_subtipe_asset;

class SummaryAsset implements WithEvents
{
    use Exportable;

    public function __construct( $datefrom, $dateto,  $cabang,  $lokasi)
    {
        // $this->filterdate = $filterdate == 1 ? "aa.date_wo" : "a.result_date";
        $this->datefrom   = $datefrom;
        $this->dateto     = $dateto;
        $this->cabang     = $cabang;
        $this->lokasi     = $lokasi;
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
                $event->sheet->setCellValue('B1', 'Cabang');
                $event->sheet->setCellValue('C1', 'Team');
                $event->sheet->setCellValue('D1', 'Personil');
                // $event->sheet->setCellValue('C1', 'Team');
                // $event->sheet->setCellValue('D1', 'Personil');
                // $event->sheet->setCellValue('E1', 'Id Pelanggan');
                // $event->sheet->setCellValue('F1', 'Nama Pelanggan');
                // $event->sheet->setCellValue('G1', 'WO Type');
                // $event->sheet->setCellValue('H1', 'TT');
                // $event->sheet->setCellValue('I1', 'Remarks');
                // $event->sheet->setCellValue('J1', 'Report Pekerjaan');
                // // header - row 2
                // $event->sheet->setCellValue('A2', 'No.');
                // $event->sheet->setCellValue('B2', 'Teknisi');
                // $event->sheet->setCellValue('C2', 'Teknisi');
                // $event->sheet->setCellValue('D2', 'Tanggal Pemasangan');
                // $event->sheet->setCellValue('E2', 'Id Pelanggan');
                // $event->sheet->setCellValue('F2', 'Nama Pelanggan');
                // $event->sheet->setCellValue('G2', 'WO Type');
                // $event->sheet->setCellValue('H2', 'TT');
                // $event->sheet->setCellValue('I2', 'Remarks');
                // $event->sheet->setCellValue('J2', 'Report Pekerjaan');

                // merge cell in header
                $event->sheet->mergeCells("A1:A2");
                $event->sheet->mergeCells("B1:B2");
                $event->sheet->mergeCells("D1:D2");
                $event->sheet->mergeCells("C1:C2");
                // $event->sheet->mergeCells("F1:F2");
                // $event->sheet->mergeCells("G1:G2");
                // $event->sheet->mergeCells("H1:H2");
                // $event->sheet->mergeCells("I1:I2");
                // $event->sheet->mergeCells("J1:J2");

                // $dataSubAsset = m_subtipe_asset::where('m_subtipe_asset.flag_Active', 1)
                //                     ->leftjoin('m_tipe_asset', 'm_subtipe_asset.id_tipe_asset', 'm_tipe_asset.id')->get();

                

                //get tipe asset
                $dataTipeAsset = m_tipe_asset::where('flag_Active', 1)
                                            //   ->where('id', 3)
                                                //  ->limit(3)
                                                ->get();                    

                if($dataTipeAsset != null){
                                    
                    $posCell  = 5 ;
                    
                    $posCell2 = 0 ;
                    // $posCell3 = 'B' ;

                    $arrIdSubAsset = [];
                    $arrIdTipeAsset = [];
                    foreach($dataTipeAsset as $k_dataTipeAsset){
                       
                        // var_dump($k_dataTipeAsset->tipe_asset_name);
                        $colCell = GlobalHelper::numToAlpha($posCell);
                        array_push($arrIdTipeAsset, $k_dataTipeAsset->id);
                        
                        $dataSubAsset = m_subtipe_asset::where('id_tipe_asset', $k_dataTipeAsset->id)
                                                        ->where('flag_Active', 1)->get();
              
                          $event->sheet->setCellValue($colCell."1", $k_dataTipeAsset->tipe_asset_name);
                          $posCell2 =  $posCell;
                          $posMergeCell = count($dataSubAsset)-1;

                          if(count($dataSubAsset) == 0){
                            $posMergeCell = 0;   
                          }
                         
                            foreach($dataSubAsset as $k_dataSubAsset){
                                // var_dump($k_dataSubAsset->subtipe_asset_name);
                                array_push($arrIdSubAsset, $k_dataSubAsset->id);
                                $colCell = GlobalHelper::numToAlpha($posCell);
                                $event->sheet->setCellValue($colCell."2",$k_dataSubAsset->subtipe_asset_name);
                                $posCell++;
                            //    $posCell3++;
                               
                                
                            }   
                            $mergeheader1 = GlobalHelper::numToAlpha($posCell2);

                            $mergeheader2 = GlobalHelper::numToAlpha($posCell2 + $posMergeCell);

                                           $event->sheet->mergeCells($mergeheader1."1".":".$mergeheader2."1");
                           
                            // var_dump($mergeheader1."1".":".$mergeheader2."1");
                            
                         
                    }
                    

                }
              
                $datefrom   = $this->datefrom." 00:00:00";
                $dateto     = $this->dateto." 23:59:59";

                $sql = "
                    SELECT
                        aa.id,aa.cabang_name
                    FROM 
                            m_cabang aa
                    WHERE
                        aa.flag_active = 1
                        
                   
                ";

                if($this->cabang) $sql .= " and aa.id_cabang = {$this->cabang}";
               
                $data = DB::select($sql);

                if($data != null){
                    $posCellDetail  = 'A';
                    $posNumCell     = 3;
                    $num            = 1;
                    foreach($data as $k_data) {
                        $tmpPosCellDetail = $posCellDetail;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $num); $tmpPosCellDetail++;
                        $event->sheet->setCellValue($tmpPosCellDetail.$posNumCell, $k_data->cabang_name); $tmpPosCellDetail++;
                        
                        //get count team
                        $tmpPosTeam = $tmpPosCellDetail;
                        $sql3 = "
                        SELECT sum(a.last_qty) as jml_data  from m_team_asset a
                        where a.id_cabang = {$k_data->id} and a.flag_active = 1 
                       ";
                        $detailTeam= DB::select($sql3);
                        $event->sheet->setCellValue($tmpPosTeam.$posNumCell, $detailTeam[0]->jml_data);
                        $tmpPosCellDetail++;

                        
                        //get data personil
                        $tmpPosPersonil = $tmpPosCellDetail;
                        $sql4 = "
                        SELECT sum(a.last_qty) as jml_data  from his_jabatan_asset a
                        where a.id_cabang = {$k_data->id} and a.flag_active = 1 
                       ";
                        $detailJabatan = DB::select($sql4);
                        $event->sheet->setCellValue($tmpPosPersonil.$posNumCell, $detailJabatan[0]->jml_data);
                        $tmpPosCellDetail++;

                        //get sub tipe asset
                        $tmpPosAsset = $tmpPosCellDetail;
                        for($i=0; $i<count($arrIdSubAsset); $i++){

                            $sql2 = "
                                        SELECT count(a.id) as jml_data  from m_asset a
                                        left join his_asset b on a.id =b.id_asset 
                                        where b.id_cabang_akhir = {$k_data->id} and a.id_subtipe_asset = {$arrIdSubAsset[$i]} 
                                       ";
                            if($this->lokasi) $sql2 .= " and b.id_lokasi_akhir= {$this->lokasi}";
                           
                            $detailAsset = DB::select($sql2);
                            
                           
                            if($detailAsset[0]->jml_data > 0 ) {
                                $event->sheet->setCellValue($tmpPosAsset.$posNumCell, $detailAsset[0]->jml_data);
                                                        
                            }
                            else{
                                $event->sheet->setCellValue($tmpPosAsset.$posNumCell, '');          

                            }

                            $tmpPosAsset++;
                        }
                       

                        $posNumCell++; $num++;
                    }
                }
            },
        ];
    }
}
