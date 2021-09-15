<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;


use DB;


use App\Helpers\GlobalHelper;


use App\Models\m_menu_master;
use App\Models\m_menu_detail;
use App\Models\m_asset;
use App\Models\rel_asset_foto;
use App\Models\m_cabang;
use App\Models\m_cat_pengadaan;
use App\Models\m_cat_asset;
use App\Models\m_subtipe_asset;
use App\Models\m_tipe_id_asset;
use App\Models\m_tipe_asset;
use App\Models\m_tipe_massa_berlaku;

class Asset implements WithEvents
{
    use Exportable;

     public function __construct( $cabang, $datefrom,  $dateto)
    {
        $this->cabang  = $cabang;
        $this->datefrom = $datefrom;
        $this->dateto = $dateto;
        // var_dump($cabang); die();
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

                $ArrHeader = [
                    'No',
                    'No Asset',
                    'Category Asset',
                    'Category Pengadaan',
                    'Tipe Asset', 
                    'Sub Tipe',
                    'Tipe Id',
                    'No Id Asset',
                    'Merk',
                    'Nama',
                    'Tanggal Pengadaan',        
                    'No Pengadaan',
                    'Tanggal Terima',
                    'Masa Berlaku',
                    // 'Masa Berlaku',
                    'Tanggal Masa Berlaku',
                    'Notes Awal',
                    'User Input',
                    'Waktu Input',
                    'Cabang Awal',
                    'Lokasi Awal',
                    'Kondisi Awal',                    
                    'Cabang Akhir',
                    'Lokasi Akhir',
                    'Kondisi Akhir',
                    'Last Notes',
                    'User Update',
                    'Waktu Update',
                    'Foto1 Name',
                    'Foto2 Name',
                    'Foto3 Name'
                ];

                $posCell  = 1 ;
                for ($i=0; $i < count($ArrHeader); $i++) { 
                    $colCell = GlobalHelper::numToAlpha($posCell);
                    // $event->sheet->setCellValue('A1', 'No.');
                    $event->sheet->setCellValue($colCell."1", $ArrHeader[$i]);
                    $posCell++;
                    # code...
                }
                       
                 //get data asset
                 if ($this->cabang <> NULL) {
                    $dataAsset = DB::table('m_asset')
                            ->where('m_asset.createdDtm', '>=', $this->datefrom)
                            ->where('m_asset.createdDtm', '<=',$this->dateto)
                            ->where('his_asset.id_cabang_akhir',$this->cabang)                      
                            ->leftjoin('m_cat_pengadaan', 'm_asset.id_cat_pengadaan', 'm_cat_pengadaan.id')
                            ->leftjoin('m_cat_asset', 'm_asset.id_cat_asset', 'm_cat_asset.id')
                            ->leftjoin('m_subtipe_asset', 'm_asset.id_subtipe_asset', 'm_subtipe_asset.id')
                            ->leftjoin('m_tipe_id_asset', 'm_asset.id_tipeid_asset', 'm_tipe_id_asset.id')
                            ->leftjoin('m_cabang', 'm_asset.id_cabang_awal', 'm_cabang.id')
                            ->leftjoin('m_tipe_asset', 'm_subtipe_asset.id_tipe_asset', 'm_tipe_asset.id')
                            ->leftjoin('rel_asset_foto', 'm_asset.id', 'rel_asset_foto.id_asset')
                            ->leftjoin('m_condition', 'm_asset.id_kondisi_awal', 'm_condition.id')
                            ->leftjoin('m_tipe_massa_berlaku', 'm_asset.id_massaberlaku', 'm_tipe_massa_berlaku.id')
                            ->leftjoin('m_lokasi', 'm_asset.id_lokasi_awal', 'm_lokasi.id')
                            ->leftjoin('his_asset', 'his_asset.id_asset', 'm_asset.id')
                            ->leftjoin('m_cabang as a', 'his_asset.id_cabang_akhir', 'a.id')
                            ->leftjoin('m_condition as b', 'his_asset.id_kondisi_akhir', 'b.id')
                            ->leftjoin('m_lokasi as c', 'his_asset.id_lokasi_akhir', 'c.id')
                            ->leftjoin('m_users', 'm_asset.createdBy', 'm_users.id')
                            ->leftjoin('m_users as d', 'his_asset.updatedBy', 'd.id')
                   
                            ->select(                      
                                'm_asset.no_asset',                              
                                'm_cat_asset.cat_asset_name',   
                                'm_cat_pengadaan.cat_pengadaan_name',
                                'm_tipe_asset.tipe_asset_name',
                                'm_subtipe_asset.subtipe_asset_name',
                                'm_tipe_id_asset.tipe_id_asset_name',
                                'm_asset.no_id_asset', 
                                'm_asset.merk', 
                                'm_asset.name', 
                                'm_asset.pengadaan_date',
                                'm_asset.no_pengadaan', 
                                'm_asset.terima_date', 
                                'm_asset.masa_berlaku',
                                'm_asset.notes', 
                                'm_users.name as pic_input', 
                                'm_asset.createdDtm', 
                                'm_cabang.cabang_name',
                                'm_lokasi.lokasi_name',
                                'm_tipe_massa_berlaku.massa_berlaku',
                                'm_condition.condition_name',
                                'a.cabang_name as cabang_akhir',
                                'c.lokasi_name as lokasi_akhir',
                                'b.condition_name as condition_akhir',
                                'his_asset.last_notes',
                                'd.name as pic_update', 
                                'd.updatedDtm', 
                                'rel_asset_foto.path_foto', 
                                'rel_asset_foto.path_foto2', 
                                'rel_asset_foto.path_foto3'
                            )
                            ->groupBy('m_asset.id')
                            ->orderBy('m_asset.id', 'desc')
                            ->get()->toArray();
                } elseif ($this->cabang == NULL) {
                    $dataAsset = DB::table('m_asset')
                            ->where('m_asset.createdDtm', '>=', $this->datefrom)
                            ->where('m_asset.createdDtm', '<=',$this->dateto)               
                            ->leftjoin('m_cat_pengadaan', 'm_asset.id_cat_pengadaan', 'm_cat_pengadaan.id')
                            ->leftjoin('m_cat_asset', 'm_asset.id_cat_asset', 'm_cat_asset.id')
                            ->leftjoin('m_subtipe_asset', 'm_asset.id_subtipe_asset', 'm_subtipe_asset.id')
                            ->leftjoin('m_tipe_id_asset', 'm_asset.id_tipeid_asset', 'm_tipe_id_asset.id')
                            ->leftjoin('m_cabang', 'm_asset.id_cabang_awal', 'm_cabang.id')
                            ->leftjoin('m_tipe_asset', 'm_subtipe_asset.id_tipe_asset', 'm_tipe_asset.id')
                            ->leftjoin('rel_asset_foto', 'm_asset.id', 'rel_asset_foto.id_asset')
                            ->leftjoin('m_condition', 'm_asset.id_kondisi_awal', 'm_condition.id')
                            ->leftjoin('m_lokasi', 'm_asset.id_lokasi_awal', 'm_lokasi.id')
                            ->leftjoin('m_tipe_massa_berlaku', 'm_asset.id_massaberlaku', 'm_tipe_massa_berlaku.id')
                            ->leftjoin('his_asset', 'his_asset.id_asset', 'm_asset.id')
                            ->leftjoin('m_cabang as a', 'his_asset.id_cabang_akhir', 'a.id')
                            ->leftjoin('m_condition as b', 'his_asset.id_kondisi_akhir', 'b.id')
                            ->leftjoin('m_lokasi as c', 'his_asset.id_lokasi_akhir', 'c.id')
                            ->leftjoin('m_users', 'm_asset.createdBy', 'm_users.id')
                            ->leftjoin('m_users as d', 'his_asset.updatedBy', 'd.id')
                   
                            ->select(                      
                                'm_asset.no_asset',                              
                                'm_cat_asset.cat_asset_name',   
                                'm_cat_pengadaan.cat_pengadaan_name',
                                'm_tipe_asset.tipe_asset_name',
                                'm_subtipe_asset.subtipe_asset_name',
                                'm_tipe_id_asset.tipe_id_asset_name',
                                'm_asset.no_id_asset', 
                                'm_asset.merk', 
                                'm_asset.name', 
                                'm_asset.pengadaan_date',
                                'm_asset.no_pengadaan', 
                                'm_asset.terima_date', 
                                'm_asset.masa_berlaku', 
                                
                                'm_asset.notes', 
                                'm_users.name as pic_input', 
                                'm_asset.createdDtm', 
                                'm_cabang.cabang_name',
                                'm_lokasi.lokasi_name',
                                'm_condition.condition_name',
                                'm_tipe_massa_berlaku.massa_berlaku',
                                'a.cabang_name as cabang_akhir',
                                'c.lokasi_name as lokasi_akhir',
                                'b.condition_name as condition_akhir',
                                'his_asset.last_notes',
                                'd.name as pic_update', 
                                'd.updatedDtm', 
                                'rel_asset_foto.path_foto', 
                                'rel_asset_foto.path_foto2', 
                                'rel_asset_foto.path_foto3'
                            )
                            ->groupBy('m_asset.id')
                            ->orderBy('m_asset.id', 'desc')
                            ->get()->toArray();
                }
              
                // var_dump($dataAsset[0]);
                // die();
                  $no = 1;
                  $posRow  = 2 ;
                  $colCell = GlobalHelper::numToAlpha($posCell);
                //   $event->sheet->getDelegate()->getColumnDimension($colCell)->setWidth(50);$colCell++;
                //   $event->sheet->getDelegate()->getColumnDimension($colCell)->setWidth(50);$colCell++;
                //   $event->sheet->getDelegate()->getColumnDimension($colCell)->setWidth(50);$colCell++;


                  if($dataAsset != null){
                              
                    // $posMergeCell = count($dataTipeTeam)-1;
                    foreach($dataAsset as $k_dataAsset){
                        $posCell  = 1 ;
                       
                            $colCell = GlobalHelper::numToAlpha($posCell);
                            // $event->sheet->setCellValue('A1', 'No.');
                            $event->sheet->setCellValue($colCell.$posRow, $no);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->no_asset);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->cat_asset_name);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->cat_pengadaan_name);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->tipe_asset_name);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->subtipe_asset_name);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->tipe_id_asset_name);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->no_id_asset);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->merk);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->name);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->pengadaan_date);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->no_pengadaan);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->terima_date);$colCell++;
                             $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->massa_berlaku);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->masa_berlaku);$colCell++;
                           
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->notes);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->pic_input);$colCell++;
                            
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->createdDtm);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->cabang_name);$colCell++;
                            
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->lokasi_name);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->condition_name);$colCell++;
                             
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->cabang_akhir);$colCell++;
                           
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->lokasi_akhir);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->condition_akhir);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->last_notes);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->pic_update);$colCell++;
                            
                            $event->sheet->setCellValue($colCell.$posRow, $k_dataAsset->updatedDtm);$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, substr($k_dataAsset->path_foto,24));$colCell++;
                            $event->sheet->setCellValue($colCell.$posRow, substr($k_dataAsset->path_foto2,24));$colCell++; 
                            $event->sheet->setCellValue($colCell.$posRow, substr($k_dataAsset->path_foto3,24));$colCell++;
                            
                            // if($k_dataAsset->path_foto <> NULL){
                            //     $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                            //     $drawing->setName('Foto1');
                            //     $drawing->setDescription('Foto1');
                            //     $drawing->setPath(public_path($k_dataAsset->path_foto));
                            //     $drawing->setHeight(50);
                            //     $drawing->setResizeProportional(true);
                            //     $drawing->setCoordinates($colCell.$posRow);
                            //     $drawing->setOffsetX(85);
                            //     $drawing->setWorksheet($event->sheet->getDelegate());
                            // }

                            // if($k_dataAsset->path_foto2 <> NULL){
                            //     $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                            //     $drawing->setName('Foto1');
                            //     $drawing->setDescription('Foto1');
                            //     $drawing->setPath(public_path($k_dataAsset->path_foto2));
                            //     $drawing->setHeight(50);
                            //     $drawing->setResizeProportional(true);
                            //     $drawing->setCoordinates($colCell.$posRow);
                            //     $drawing->setOffsetX(85);
                            //     $drawing->setWorksheet($event->sheet->getDelegate());
                            // }
                            // if($k_dataAsset->path_foto3 <> NULL){
                            //     $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                            //     $drawing->setName('Foto1');
                            //     $drawing->setDescription('Foto1');
                            //     $drawing->setPath(public_path($k_dataAsset->path_foto3));
                            //     $drawing->setHeight(50);
                            //     $drawing->setResizeProportional(true);
                            //     $drawing->setCoordinates($colCell.$posRow);
                            //     $drawing->setOffsetX(85);
                            //     $drawing->setWorksheet($event->sheet->getDelegate());
                            // }
            
                            # code...
                            // $event->sheet->getDelegate()->getColumnDimension($posCell)->setWidth(21.5);
                                   
                       
                        $posRow++;
                        $no++;

                    }   
                    
                    
                }

                
               
            },
        ];
    }
       
}