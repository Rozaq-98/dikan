<?php
/*
    # add new pekerjaan or add new client
        * header
        - lengthColFirst
        - function arrayHeadingFirst
        - function arrayHeadingLast
        - function arrayStartEndColumnKlien

        * data gaji
        - startCellNumber
        - colTotalGaji
        - colAllTotalBonus
        - colAllTotalPresentaseBonus
        - function arrayColTotalBonusKlien
        - function arrayColPresentaseBonus
        - function arrayColTotalPresentaseBonus

        * summary data
        - startCell
        - lastPosNumberCell
        - function arrHeadingFirstSummary
        - colTotalGajiKotor
*/

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

use DB;
use App\Helpers\GlobalHelper;

use App\Models\m_tipe_pekerjaan;

class GajiKaryawan implements FromCollection, WithEvents
{
    use Exportable;

    public function __construct($filterdate, $datefrom, $dateto, $cabang)
    {
        // GET INPUT
        $this->filterdate = $filterdate == 1 ? "b.date_wo" : "c.result_date";
        $this->datefrom   = $datefrom;
        $this->dateto     = $dateto;
        $this->cabang     = $cabang;

        $arrKlien = ['mnc', 'link', 'myrep', 'bali', 'oxy', 'other', 'fiber'];

        $this->lengthColAlacarteKlien = 3; // length col alacarte per klien (total bonus + presentasi + total presentase)
        $this->lengthColTipePekerjaan = 3; // length col per tipe pekerjaan (point + jumlah bonus + point wo)

        // total tipe pekerjaan klien
        $this->totTipePekerjaanMnc   = $this->countTipePekerjaanKlien(1);
        $this->totTipePekerjaanLink  = $this->countTipePekerjaanKlien(2);
        $this->totTipePekerjaanMyrep = $this->countTipePekerjaanKlien(3);
        $this->totTipePekerjaanBali  = $this->countTipePekerjaanKlien(4);
        $this->totTipePekerjaanOxy   = $this->countTipePekerjaanKlien(5);
        $this->totTipePekerjaanOther = $this->countTipePekerjaanKlien(14);
        $this->totTipePekerjaanFiber = $this->countTipePekerjaanKlien(16);

        $this->firstCol                      = 1;  // first column page (A)
        $this->lengthColPrimerFirstUntilLast = 12; // Cabang - Total Gaji
        $this->firstColMnc                   = 13; // first column mnc (M)
        $this->colTotalGaji                  = 12; // L
        $this->colAllTotalBonus              = $this->firstColMnc
                                               + ($this->lengthColAlacarteKlien * count($arrKlien))
                                               + ($this->totTipePekerjaanMnc * $this->lengthColTipePekerjaan)
                                               + ($this->totTipePekerjaanLink * $this->lengthColTipePekerjaan)
                                               + ($this->totTipePekerjaanMyrep * $this->lengthColTipePekerjaan)
                                               + ($this->totTipePekerjaanBali * $this->lengthColTipePekerjaan)
                                               + ($this->totTipePekerjaanOxy * $this->lengthColTipePekerjaan)
                                               + ($this->totTipePekerjaanOther * $this->lengthColTipePekerjaan)
                                               + ($this->totTipePekerjaanFiber * $this->lengthColTipePekerjaan);
        $this->colTotalGajiKotor             = $this->colAllTotalBonus + 1;
        $this->colTotalGajiTanpaBonus        = $this->colTotalGajiKotor + 1;
    }

    public function registerEvents(): array
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        return [
            AfterSheet::class => function(AfterSheet $event) {
                $posCell        = GlobalHelper::numToAlpha($this->firstCol); // A
                $lengthColFirst = $this->lengthColPrimerFirstUntilLast; // Cabang - Total Gaji

                $arrCellKlien      = $this->arrayStartEndColumnKlien();
                $dataTipePekerjaan = $this->getDataTipePekerjaan();
                $dataKlien         = $this->getDatataKlien();

                // ********************************************** ---- MERGE HEADER ---- **********************************************
                // merger column first header (Cabang - Total Gaji)
                for ($i=0; $i < $lengthColFirst; $i++) {
                    $event->sheet->mergeCells($posCell."1:".$posCell."3"); // merge 3 row vertical
                    $posCell++;
                }

                // addNewKlien(add new line for define new klien)
                // merge klien name (all tipe pekerjaan klien + 3 kolom (total bonus, presentase, total presentase))
                $event->sheet->mergeCells($arrCellKlien['mnc'][0].'1'.':'.$arrCellKlien['mnc'][1].'1');
                $event->sheet->mergeCells($arrCellKlien['link'][0].'1'.':'.$arrCellKlien['link'][1].'1');
                $event->sheet->mergeCells($arrCellKlien['myrep'][0].'1'.':'.$arrCellKlien['myrep'][1].'1');
                $event->sheet->mergeCells($arrCellKlien['bali'][0].'1'.':'.$arrCellKlien['bali'][1].'1');
                $event->sheet->mergeCells($arrCellKlien['oxy'][0].'1'.':'.$arrCellKlien['oxy'][1].'1');
                $event->sheet->mergeCells($arrCellKlien['other'][0].'1'.':'.$arrCellKlien['other'][1].'1');
                $event->sheet->mergeCells($arrCellKlien['fiber'][0].'1'.':'.$arrCellKlien['fiber'][1].'1');

                // merge tipe pekerjaan + 3 kolom (total bonus, presentase, total presentase)
                for($i=0; $i<count($dataKlien); $i++){
                    // merge tipe pekerjaan
                    foreach($dataTipePekerjaan as $k_dataTipePekerjaan){
                        if($k_dataTipePekerjaan->id_klien == $dataKlien[$i]->id_klien){
                            $posCellLast = $posCell;
                            // looping for titik terakhir kolom tipe pekerjaan
                            for($i_posCellLast_0 = 0; $i_posCellLast_0 < ($this->lengthColTipePekerjaan - 1); $i_posCellLast_0++) {
                                $posCellLast++;
                            }

                            // merge colom tipe pekerjaan in all row 2
                            $event->sheet->mergeCells($posCell.'2'.':'.$posCellLast.'2');

                            // looping for titik merge 3 kolom (point + jumlah bonus + point wo) in all row 3
                            for($i_posCell_0 = 0; $i_posCell_0 < $this->lengthColTipePekerjaan; $i_posCell_0++) {
                                $posCell++;
                            }
                        }
                    }
                    // merge column total bonus
                    $event->sheet->mergeCells($posCell.'2'.':'.$posCell.'3');
                    $posCell++;
                    // merge column presentasi
                    $event->sheet->mergeCells($posCell.'2'.':'.$posCell.'3');
                    $posCell++;
                    // merge column total presentasi
                    $event->sheet->mergeCells($posCell.'2'.':'.$posCell.'3');
                    $posCell++;
                }
                // merge total bonus
                $event->sheet->mergeCells($posCell.'1'.':'.$posCell.'3');
                $posCell++;
                // merge total gaji kotor
                $event->sheet->mergeCells($posCell.'1'.':'.$posCell.'3');
                $posCell++;
                // merge total gaji tanpa bonus
                $event->sheet->mergeCells($posCell.'1'.':'.$posCell.'3');
                $posCell++;
                // ********************************************** ---- END MERGE HEADER ---- **********************************************

                // set style header
                for($i=1; $i<=3; $i++){
                    $event->sheet->styleCells(
                        GlobalHelper::numToAlpha($this->firstCol).$i.':'.$posCell.$i,
                        [
                            'alignment' => [
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]
                    );
                }
            },
        ];
    }

    public function collection()
    {
        $allCollections  = array();
        $startCellNumber = 4; // first row for data gaji

        // ********************************************** ---- HEADER ---- **********************************************
        $arrHeading        = $this->arrayHeadingFirst();
        $arrHeadingLast    = $this->arrayHeadingLast();
        $dataKlien         = $this->getDatataKlien();
        $dataTipePekerjaan = $this->getDataTipePekerjaan();
        $arrIdTipeKerjaan  = $this->getArrayIdTipePekerjaan($dataKlien, $dataTipePekerjaan);

        // header for column 1
        $collectionData = $this->headerColumnOne($arrHeading, $arrHeadingLast, $dataKlien, $dataTipePekerjaan);
        $valHeader      = array_column($collectionData, 'val');
        array_push($allCollections, $valHeader);

        // header for column 2
        $collectionData = $this->headerColumnTwo($arrHeading, $arrHeadingLast, $dataKlien, $dataTipePekerjaan);
        $valHeader      = array_column($collectionData, 'val');
        array_push($allCollections, $valHeader);

        // header for column 3
        $collectionData = $this->headerColumnThree($arrHeading, $arrHeadingLast, $dataKlien, $dataTipePekerjaan);
        $valHeader      = array_column($collectionData, 'val');
        array_push($allCollections, $valHeader);
        // ********************************************** ---- END HEADER ---- **********************************************

        // #################################### data gaji ####################################
        $colAllTotalBonus             = GlobalHelper::numToAlpha($this->colAllTotalBonus);
        $colAllTotalPresentaseBonus   = GlobalHelper::numToAlpha($this->colTotalGaji);
        $dataGaji                     = $this->getDataGaji();
        $arrayColTotalBonusKlien      = $this->arrayColTotalBonusKlien();
        $arrayColPresentaseBonus      = $this->arrayColPresentaseBonus();
        $arrayColTotalPresentaseBonus = $this->arrayColTotalPresentaseBonus();

        // addNewKlien(add new line for define new klien)
        // total tipe pekerjaan klien
        $totTipePekerjaanMnc   = $this->totTipePekerjaanMnc;
        $totTipePekerjaanLink  = $this->totTipePekerjaanLink;
        $totTipePekerjaanMyrep = $this->totTipePekerjaanMyrep;
        $totTipePekerjaanBali  = $this->totTipePekerjaanBali;
        $totTipePekerjaanOxy   = $this->totTipePekerjaanOxy;
        $totTipePekerjaanOther = $this->totTipePekerjaanOther;
        $totTipePekerjaanFiber = $this->totTipePekerjaanFiber;

        // push data gaji
        $startPosNumberCell = $startCellNumber;
        foreach($dataGaji as $k_dataGaji){
            $dataPoint      = $this->getDataPoint($k_dataGaji->id, $this->datefrom, $this->dateto);
            $collectionData = $this->pushDataInformasiKaryawan($k_dataGaji);

            // set default value
            $fixTotBonus = 0;

            // checking data point null
            if($dataPoint != null){
                // looping data id tipe pekerjaan
                for ($i=0; $i < count($arrIdTipeKerjaan); $i++) {
                    $breakPoint = 0;

                    // looping data point
                    foreach($dataPoint as $k_dataPoint){
                        // checking where arr id tipe pekerjaan = dataPoint->id_tipe_pekerjaan
                        if($k_dataPoint->id_tipe_pekerjaan != null && $arrIdTipeKerjaan[$i] == $k_dataPoint->id_tipe_pekerjaan){
                            // checking value total point != null
                            if($k_dataPoint->tot_point != null){
                                // push value total point
                                $collectionData[] = (object) array('val' => $k_dataPoint->tot_point);
                                // push value total bonus
                                $collectionData[] = (object) array('val' => $k_dataPoint->tot_bonus);
                                // push value total point wo
                                $collectionData[] = (object) array('val' => $k_dataPoint->tot_point_wo);

                                // get val bonus
                                // $valBonus = $k_dataPoint->tot_point * $k_dataPoint->tot_bonus;
                                $valBonus    = $k_dataPoint->tot_bonus;
                                $fixTotBonus = $fixTotBonus + $valBonus;
                            } else {
                                // if null == push value 0
                                $collectionData[] = (object) array('val' => '0');
                                $collectionData[] = (object) array('val' => '0');
                                $collectionData[] = (object) array('val' => '0');
                            }
                            $breakPoint = 1;
                        }
                    }

                    // if $k_dataPoint->id_tipe_pekerjaan not found in arrIdTipeKerjaan
                    if($breakPoint == 0){
                        $collectionData[] = (object) array('val' => '0');
                        $collectionData[] = (object) array('val' => '0');
                        $collectionData[] = (object) array('val' => '0');
                    }

                    // addNewKlien(add new line for define new klien)
                    // data total bonus klien
                    $totBonusKlienMnc   = '='.$arrayColTotalBonusKlien['mnc'][0].$startPosNumberCell;
                    $totBonusKlienLink  = '='.$arrayColTotalBonusKlien['link'][0].$startPosNumberCell;
                    $totBonusKlienMyrep = '='.$arrayColTotalBonusKlien['myrep'][0].$startPosNumberCell;
                    $totBonusKlienBali  = '='.$arrayColTotalBonusKlien['bali'][0].$startPosNumberCell;
                    $totBonusKlienOxy   = '='.$arrayColTotalBonusKlien['oxy'][0].$startPosNumberCell;
                    $totBonusKlienOther = '='.$arrayColTotalBonusKlien['other'][0].$startPosNumberCell;
                    $totBonusKlienFiber = '='.$arrayColTotalBonusKlien['fiber'][0].$startPosNumberCell;

                    // looping all column (tipe pekerjaan->totalBonus)
                    for($j=1; $j<count($arrayColTotalBonusKlien['mnc']); $j++){
                        $totBonusKlienMnc .= '+'.$arrayColTotalBonusKlien['mnc'][$j].$startPosNumberCell;
                    }
                    for($j=1; $j<count($arrayColTotalBonusKlien['link']); $j++){
                        $totBonusKlienLink .= '+'.$arrayColTotalBonusKlien['link'][$j].$startPosNumberCell;
                    }
                    for($j=1; $j<count($arrayColTotalBonusKlien['myrep']); $j++){
                        $totBonusKlienMyrep .= '+'.$arrayColTotalBonusKlien['myrep'][$j].$startPosNumberCell;
                    }
                    for($j=1; $j<count($arrayColTotalBonusKlien['bali']); $j++){
                        $totBonusKlienBali .= '+'.$arrayColTotalBonusKlien['bali'][$j].$startPosNumberCell;
                    }
                    for($j=1; $j<count($arrayColTotalBonusKlien['oxy']); $j++){
                        $totBonusKlienOxy .= '+'.$arrayColTotalBonusKlien['oxy'][$j].$startPosNumberCell;
                    }
                    for($j=1; $j<count($arrayColTotalBonusKlien['other']); $j++){
                        $totBonusKlienOther .= '+'.$arrayColTotalBonusKlien['other'][$j].$startPosNumberCell;
                    }
                    for($j=1; $j<count($arrayColTotalBonusKlien['fiber']); $j++){
                        $totBonusKlienFiber .= '+'.$arrayColTotalBonusKlien['fiber'][$j].$startPosNumberCell;
                    }

                    // addNewKlien(add new line for define new klien)
                    // presentase bonus klien
                    $presentaseBonusMnc   = '=IF($'.$colAllTotalBonus.'$'.$startPosNumberCell.'>0,'.$arrayColPresentaseBonus['mnc'].$startPosNumberCell.'/'.'$'.$colAllTotalBonus.'$'.$startPosNumberCell.',0)';
                    $presentaseBonusLink  = '=IF($'.$colAllTotalBonus.'$'.$startPosNumberCell.'>0,'.$arrayColPresentaseBonus['link'].$startPosNumberCell.'/'.'$'.$colAllTotalBonus.'$'.$startPosNumberCell.',0)';
                    $presentaseBonusMyrep = '=IF($'.$colAllTotalBonus.'$'.$startPosNumberCell.'>0,'.$arrayColPresentaseBonus['myrep'].$startPosNumberCell.'/'.'$'.$colAllTotalBonus.'$'.$startPosNumberCell.',0)';
                    $presentaseBonusBali  = '=IF($'.$colAllTotalBonus.'$'.$startPosNumberCell.'>0,'.$arrayColPresentaseBonus['bali'].$startPosNumberCell.'/'.'$'.$colAllTotalBonus.'$'.$startPosNumberCell.',0)';
                    $presentaseBonusOxy   = '=IF($'.$colAllTotalBonus.'$'.$startPosNumberCell.'>0,'.$arrayColPresentaseBonus['oxy'].$startPosNumberCell.'/'.'$'.$colAllTotalBonus.'$'.$startPosNumberCell.',0)';
                    $presentaseBonusOther = '=IF($'.$colAllTotalBonus.'$'.$startPosNumberCell.'>0,'.$arrayColPresentaseBonus['other'].$startPosNumberCell.'/'.'$'.$colAllTotalBonus.'$'.$startPosNumberCell.',0)';
                    $presentaseBonusFiber = '=IF($'.$colAllTotalBonus.'$'.$startPosNumberCell.'>0,'.$arrayColPresentaseBonus['fiber'].$startPosNumberCell.'/'.'$'.$colAllTotalBonus.'$'.$startPosNumberCell.',0)';

                    // addNewKlien(add new line for define new klien)
                    // total presentase bonus klien
                    $totalPresentaseBonusMnc   = '=$'.$colAllTotalPresentaseBonus.'$'.$startPosNumberCell.'*'.$arrayColTotalPresentaseBonus['mnc'][0].$startPosNumberCell.'+'.$arrayColTotalPresentaseBonus['mnc'][1].$startPosNumberCell;
                    $totalPresentaseBonusLink  = '=$'.$colAllTotalPresentaseBonus.'$'.$startPosNumberCell.'*'.$arrayColTotalPresentaseBonus['link'][0].$startPosNumberCell.'+'.$arrayColTotalPresentaseBonus['link'][1].$startPosNumberCell;
                    $totalPresentaseBonusMyrep = '=$'.$colAllTotalPresentaseBonus.'$'.$startPosNumberCell.'*'.$arrayColTotalPresentaseBonus['myrep'][0].$startPosNumberCell.'+'.$arrayColTotalPresentaseBonus['myrep'][1].$startPosNumberCell;
                    $totalPresentaseBonusBali  = '=$'.$colAllTotalPresentaseBonus.'$'.$startPosNumberCell.'*'.$arrayColTotalPresentaseBonus['bali'][0].$startPosNumberCell.'+'.$arrayColTotalPresentaseBonus['bali'][1].$startPosNumberCell;
                    $totalPresentaseBonusOxy   = '=$'.$colAllTotalPresentaseBonus.'$'.$startPosNumberCell.'*'.$arrayColTotalPresentaseBonus['oxy'][0].$startPosNumberCell.'+'.$arrayColTotalPresentaseBonus['oxy'][1].$startPosNumberCell;
                    $totalPresentaseBonusOther = '=$'.$colAllTotalPresentaseBonus.'$'.$startPosNumberCell.'*'.$arrayColTotalPresentaseBonus['other'][0].$startPosNumberCell.'+'.$arrayColTotalPresentaseBonus['other'][1].$startPosNumberCell;
                    $totalPresentaseBonusFiber = '=$'.$colAllTotalPresentaseBonus.'$'.$startPosNumberCell.'*'.$arrayColTotalPresentaseBonus['fiber'][0].$startPosNumberCell.'+'.$arrayColTotalPresentaseBonus['fiber'][1].$startPosNumberCell;

                    // addNewKlien(add new line for define new klien)
                    // looping for variable $i
                    // push (total bonus, presentase bonus, total presentase bonus) - mnc
                    if($i == $totTipePekerjaanMnc - 1)
                    {
                        $collectionData[] = (object) array('val' => $totBonusKlienMnc);
                        $collectionData[] = (object) array('val' => $presentaseBonusMnc);
                        $collectionData[] = (object) array('val' => $totalPresentaseBonusMnc);
                    }
                    // push (total bonus, presentase bonus, total presentase bonus) - link
                    elseif($i == $totTipePekerjaanMnc + $totTipePekerjaanLink - 1)
                    {
                        $collectionData[] = (object) array('val' => $totBonusKlienLink);
                        $collectionData[] = (object) array('val' => $presentaseBonusLink);
                        $collectionData[] = (object) array('val' => $totalPresentaseBonusLink);
                    }
                    // push (total bonus, presentase bonus, total presentase bonus) - myrep
                    elseif($i == $totTipePekerjaanMnc + $totTipePekerjaanLink + $totTipePekerjaanMyrep -1)
                    {
                        $collectionData[] = (object) array('val' => $totBonusKlienMyrep);
                        $collectionData[] = (object) array('val' => $presentaseBonusMyrep);
                        $collectionData[] = (object) array('val' => $totalPresentaseBonusMyrep);
                    }
                    // push (total bonus, presentase bonus, total presentase bonus) - bali
                    elseif($i == $totTipePekerjaanMnc + $totTipePekerjaanLink + $totTipePekerjaanMyrep + $totTipePekerjaanBali - 1)
                    {
                        $collectionData[] = (object) array('val' => $totBonusKlienBali);
                        $collectionData[] = (object) array('val' => $presentaseBonusBali);
                        $collectionData[] = (object) array('val' => $totalPresentaseBonusBali);
                    }
                    // push (total bonus, presentase bonus, total presentase bonus) - oxy
                    elseif($i == $totTipePekerjaanMnc + $totTipePekerjaanLink + $totTipePekerjaanMyrep + $totTipePekerjaanBali + $totTipePekerjaanOxy - 1)
                    {
                        $collectionData[] = (object) array('val' => $totBonusKlienOxy);
                        $collectionData[] = (object) array('val' => $presentaseBonusOxy);
                        $collectionData[] = (object) array('val' => $totalPresentaseBonusOxy);
                    }
                    // push (total bonus, presentase bonus, total presentase bonus) - other
                    elseif($i == $totTipePekerjaanMnc + $totTipePekerjaanLink + $totTipePekerjaanMyrep + $totTipePekerjaanBali + $totTipePekerjaanOxy + $totTipePekerjaanOther - 1)
                    {
                        $collectionData[] = (object) array('val' => $totBonusKlienOther);
                        $collectionData[] = (object) array('val' => $presentaseBonusOther);
                        $collectionData[] = (object) array('val' => $totalPresentaseBonusOther);
                    }
                    // push (total bonus, presentase bonus, total presentase bonus) - fiber
                    elseif($i == $totTipePekerjaanMnc + $totTipePekerjaanLink + $totTipePekerjaanMyrep + $totTipePekerjaanBali + $totTipePekerjaanOxy + $totTipePekerjaanOther + $totTipePekerjaanFiber - 1)
                    {
                        $collectionData[] = (object) array('val' => $totBonusKlienFiber);
                        $collectionData[] = (object) array('val' => $presentaseBonusFiber);
                        $collectionData[] = (object) array('val' => $totalPresentaseBonusFiber);
                    }
                }

                // set total gaji kotor
                $fixTotGajiKotor = $k_dataGaji->tot_gaji + $fixTotBonus;

                // push data total bonus && total gaji kotor
                $collectionData[] = (object) array('val' => $fixTotBonus);
                $collectionData[] = (object) array('val' => $fixTotGajiKotor);
            } else {
                // push data if dataPoint == null
                for ($i=0; $i < count($arrIdTipeKerjaan); $i++) {
                    $collectionData[] = (object) array('val' => '0');
                    $collectionData[] = (object) array('val' => '0');
                    $collectionData[] = (object) array('val' => '0');
                }
                for($i=0; $i<count($dataKlien); $i++){
                    // total bonus, presentasi, total presentasi
                    $collectionData[] = (object) array('val' => '0');
                    $collectionData[] = (object) array('val' => '0');
                    $collectionData[] = (object) array('val' => '0');
                }

                // set total gaji kotor
                $fixTotGajiKotor = $k_dataGaji->tot_gaji + $fixTotBonus;

                // push data total bonus && total gaji kotor
                $collectionData[] = (object) array('val' => "{$fixTotBonus}");
                $collectionData[] = (object) array('val' => "{$fixTotGajiKotor}");
            }

            // push data total gaji tanpa bonus
            $colTotalGaji = GlobalHelper::numToAlpha($this->colTotalGaji);
            $collectionData[] = (object) array('val' => "=IF({$colAllTotalBonus}{$startPosNumberCell}>0,0,{$colTotalGaji}{$startPosNumberCell})");

            $valData = array_column($collectionData, 'val');
            array_push($allCollections, $valData);

            $startPosNumberCell++;
        }
        // #################################### end of data gaji ####################################

        // #################################### summary data ####################################
        $collectionData         = array();
        $arrHeadingFirstSummary = $this->arrHeadingFirstSummary();
        $startCell              = GlobalHelper::numToAlpha($this->firstColMnc);
        $colTotalGajiKotor      = GlobalHelper::numToAlpha($this->colTotalGajiKotor);
        $lastPosNumberCell      = $startPosNumberCell;
        $lastPosNumberCell--;

        // push headers
        for ($i=0; $i < count($arrHeading); $i++) {
            $collectionData[] = (object) array('val' => $arrHeadingFirstSummary[$i]);
        }

        for($i=0; $i<count($dataKlien); $i++){
            foreach($dataTipePekerjaan as $k_dataTipePekerjaan){
                if($k_dataTipePekerjaan->id_klien == $dataKlien[$i]->id_klien){
                    // push data
                    $collectionData[] = (object) array('val' => '=SUM('.$startCell.$startCellNumber.':'.$startCell.$lastPosNumberCell.')');
                    $startCell++;
                    $collectionData[] = (object) array('val' => '=SUM('.$startCell.$startCellNumber.':'.$startCell.$lastPosNumberCell.')');
                    $startCell++;
                    $collectionData[] = (object) array('val' => '=SUM('.$startCell.$startCellNumber.':'.$startCell.$lastPosNumberCell.')');
                    $startCell++;
                }
            }
            // Total Bonus
            $collectionData[] = (object) array('val' => '=SUM('.$startCell.$startCellNumber.':'.$startCell.$lastPosNumberCell.')');
            $startCell++;

            // Presentase
            $startCell++;
            $posRowPresentase = $lastPosNumberCell + 1;
            $collectionData[] = (object) array('val' => '=('.$startCell.$posRowPresentase.'/'.$colTotalGajiKotor.$posRowPresentase.')');

            // Total Pengeluaran Per Operator
            $collectionData[] = (object) array('val' => '=SUM('.$startCell.$startCellNumber.':'.$startCell.$lastPosNumberCell.')');
            $startCell++;
        }

        // sum total bonus
        $collectionData[] = (object) array('val' => '=SUM('.$startCell.$startCellNumber.':'.$startCell.$lastPosNumberCell.')');
        $startCell++;

        // sum total gaji kotor
        $collectionData[] = (object) array('val' => '=SUM('.$startCell.$startCellNumber.':'.$startCell.$lastPosNumberCell.')');
        $startCell++;

        // sum total gaji tanpa bonus
        $collectionData[] = (object) array('val' => '=SUM('.$startCell.$startCellNumber.':'.$startCell.$lastPosNumberCell.')');

        $valData = array_column($collectionData, 'val');
        array_push($allCollections, $valData);
        // #################################### end of summary data ####################################

        // #################################### push presentasi gaji tanpa bonus dan gaji bonus ####################################
        $posRowSum = $posRowPresentase;
        array_push($allCollections, []);

        // push presentase total gaji tanpa bonus
        $collectionData         = array();
        $colTotalGajiKotor      = GlobalHelper::numToAlpha($this->colTotalGajiKotor);
        $colTotalGajiTanpaBonus = GlobalHelper::numToAlpha($this->colTotalGajiTanpaBonus);

        for($i=1; $i<$this->colTotalGajiKotor; $i++) $collectionData[] = (object) array('val' => "");
        $collectionData[] = (object) array('val' => "Total Presentase Gaji Tanpa Bonus");
        $collectionData[] = (object) array('val' => "={$colTotalGajiTanpaBonus}{$posRowSum}/{$colTotalGajiKotor}{$posRowSum}");
        $valData          = array_column($collectionData, 'val');
        array_push($allCollections, $valData);

        // presentase total gaji bonus
        $colPresentaseMnc   = $arrayColTotalPresentaseBonus['mnc'][0].$posRowSum;
        $colPresentaseLink  = $arrayColTotalPresentaseBonus['link'][0].$posRowSum;
        $colPresentaseBali  = $arrayColTotalPresentaseBonus['bali'][0].$posRowSum;
        $colPresentaseMyrep = $arrayColTotalPresentaseBonus['myrep'][0].$posRowSum;
        $colPresentaseOxy   = $arrayColTotalPresentaseBonus['oxy'][0].$posRowSum;
        $colPresentaseOther = $arrayColTotalPresentaseBonus['other'][0].$posRowSum;
        $colPresentaseFiber = $arrayColTotalPresentaseBonus['fiber'][0].$posRowSum;
        $presetaseTotalGajiTanpaBonus = "={$colPresentaseMnc}+{$colPresentaseLink}+{$colPresentaseBali}+{$colPresentaseMyrep}+{$colPresentaseOxy}+{$colPresentaseOther}+{$colPresentaseFiber}";

        // push presentase total gaji bonus
        $collectionData   = array();
        for($i=1; $i<$this->colTotalGajiKotor; $i++) $collectionData[] = (object) array('val' => "");
        $collectionData[] = (object) array('val' => "Total Presentase Gaji Bonus");
        $collectionData[] = (object) array('val' => $presetaseTotalGajiTanpaBonus);
        $valData          = array_column($collectionData, 'val');
        array_push($allCollections, $valData);

        return collect([$allCollections]);
    }

    function arrayStartEndColumnKlien(){
        $lengthColAlacarteKlien = $this->lengthColAlacarteKlien; // total bonus + presentasi + total presentase
        $lengthColTipePekerjaan = $this->lengthColTipePekerjaan; // point + jumlah bonus + point wo

        // total tipe pekerjaan klien
        $totTipePekerjaanMnc   = $this->totTipePekerjaanMnc;
        $totTipePekerjaanLink  = $this->totTipePekerjaanLink;
        $totTipePekerjaanMyrep = $this->totTipePekerjaanMyrep;
        $totTipePekerjaanBali  = $this->totTipePekerjaanBali;
        $totTipePekerjaanOxy   = $this->totTipePekerjaanOxy;
        $totTipePekerjaanOther = $this->totTipePekerjaanOther;
        $totTipePekerjaanFiber = $this->totTipePekerjaanFiber;

        // row 1 = titik awal; row 2 = titik akhir
        /* rumus
            titik awal
            + (total tipe pekerjaan * lengthColTipePekerjaan)
            + 3 (kolom total bonus + presentasi + total presentase)
            - 1 (jika tidak di -1 => titik awal klien selanjutnya)
        */
        $mncRow1   = $this->firstColMnc;
        $mncRow2   = $mncRow1   + ($totTipePekerjaanMnc * $lengthColTipePekerjaan)   + $lengthColAlacarteKlien - 1;
        $linkRow1  = $mncRow2   + 1;
        $linkRow2  = $linkRow1  + ($totTipePekerjaanLink * $lengthColTipePekerjaan)  + $lengthColAlacarteKlien - 1;
        $myrepRow1 = $linkRow2  + 1;
        $myrepRow2 = $myrepRow1 + ($totTipePekerjaanMyrep * $lengthColTipePekerjaan) + $lengthColAlacarteKlien - 1;
        $baliRow1  = $myrepRow2 + 1;
        $baliRow2  = $baliRow1  + ($totTipePekerjaanBali * $lengthColTipePekerjaan)  + $lengthColAlacarteKlien - 1;
        $oxyRow1   = $baliRow2  + 1;
        $oxyRow2   = $oxyRow1   + ($totTipePekerjaanOxy * $lengthColTipePekerjaan)   + $lengthColAlacarteKlien - 1;
        $otherRow1 = $oxyRow2   + 1;
        $otherRow2 = $otherRow1 + ($totTipePekerjaanOther * $lengthColTipePekerjaan) + $lengthColAlacarteKlien - 1;
        $fiberRow1 = $otherRow2 + 1;
        $fiberRow2 = $fiberRow1 + ($totTipePekerjaanFiber * $lengthColTipePekerjaan) + $lengthColAlacarteKlien - 1;

        $array = array(
            'mnc'   => [GlobalHelper::numToAlpha($mncRow1), GlobalHelper::numToAlpha($mncRow2)],
            'link'  => [GlobalHelper::numToAlpha($linkRow1), GlobalHelper::numToAlpha($linkRow2)],
            'myrep' => [GlobalHelper::numToAlpha($myrepRow1), GlobalHelper::numToAlpha($myrepRow2)],
            'bali'  => [GlobalHelper::numToAlpha($baliRow1), GlobalHelper::numToAlpha($baliRow2)],
            'oxy'   => [GlobalHelper::numToAlpha($oxyRow1), GlobalHelper::numToAlpha($oxyRow2)],
            'other' => [GlobalHelper::numToAlpha($otherRow1), GlobalHelper::numToAlpha($otherRow2)],
            'fiber' => [GlobalHelper::numToAlpha($fiberRow1), GlobalHelper::numToAlpha($fiberRow2)],
        );

        return $array;
    }

    function arrayColTotalBonusKlien(){
        $lengthColAlacarteKlien = $this->lengthColAlacarteKlien; // total bonus + presentasi + total presentase
        $lengthColTipePekerjaan = $this->lengthColTipePekerjaan; // point + jumlah bonus + point wo

        // total tipe pekerjaan klien
        $totTipePekerjaanMnc   = $this->totTipePekerjaanMnc;
        $totTipePekerjaanLink  = $this->totTipePekerjaanLink;
        $totTipePekerjaanMyrep = $this->totTipePekerjaanMyrep;
        $totTipePekerjaanBali  = $this->totTipePekerjaanBali;
        $totTipePekerjaanOxy   = $this->totTipePekerjaanOxy;
        $totTipePekerjaanOther = $this->totTipePekerjaanOther;
        $totTipePekerjaanFiber = $this->totTipePekerjaanFiber;

        $firstColMnc      = $this->firstColMnc;
        $ColMncJumlahBonus = $this->firstColMnc + 1;
        $arrColMncJumlahBonus = [GlobalHelper::numToAlpha($ColMncJumlahBonus)];
        for($i=1; $i<$totTipePekerjaanMnc; $i++){
            $ColMncJumlahBonus += $lengthColTipePekerjaan;
            array_push($arrColMncJumlahBonus, GlobalHelper::numToAlpha($ColMncJumlahBonus));
        }

        $ColLinkJumlahBonus = $ColMncJumlahBonus + ($lengthColAlacarteKlien + $lengthColTipePekerjaan);
        $arrColLinkJumlahBonus = [GlobalHelper::numToAlpha($ColLinkJumlahBonus)];
        for($i=1; $i<$totTipePekerjaanLink; $i++){
            $ColLinkJumlahBonus += $lengthColTipePekerjaan;
            array_push($arrColLinkJumlahBonus, GlobalHelper::numToAlpha($ColLinkJumlahBonus));
        }

        $ColMyrepJumlahBonus = $ColLinkJumlahBonus + ($lengthColAlacarteKlien + $lengthColTipePekerjaan);
        $arrColMyrepJumlahBonus = [GlobalHelper::numToAlpha($ColMyrepJumlahBonus)];
        for($i=1; $i<$totTipePekerjaanMyrep; $i++){
            $ColMyrepJumlahBonus += $lengthColTipePekerjaan;
            array_push($arrColMyrepJumlahBonus, GlobalHelper::numToAlpha($ColMyrepJumlahBonus));
        }

        $ColBaliJumlahBonus = $ColMyrepJumlahBonus + ($lengthColAlacarteKlien + $lengthColTipePekerjaan);
        $arrColBaliJumlahBonus = [GlobalHelper::numToAlpha($ColBaliJumlahBonus)];
        for($i=1; $i<$totTipePekerjaanBali; $i++){
            $ColBaliJumlahBonus += $lengthColTipePekerjaan;
            array_push($arrColBaliJumlahBonus, GlobalHelper::numToAlpha($ColBaliJumlahBonus));
        }

        $ColOxyJumlahBonus = $ColBaliJumlahBonus + ($lengthColAlacarteKlien + $lengthColTipePekerjaan);
        $arrColOxyJumlahBonus = [GlobalHelper::numToAlpha($ColOxyJumlahBonus)];
        for($i=1; $i<$totTipePekerjaanOxy; $i++){
            $ColOxyJumlahBonus += $lengthColTipePekerjaan;
            array_push($arrColOxyJumlahBonus, GlobalHelper::numToAlpha($ColOxyJumlahBonus));
        }

        $ColOtherJumlahBonus = $ColOxyJumlahBonus + ($lengthColAlacarteKlien + $lengthColTipePekerjaan);
        $arrColOtherJumlahBonus = [GlobalHelper::numToAlpha($ColOtherJumlahBonus)];
        for($i=1; $i<$totTipePekerjaanOther; $i++){
            $ColOtherJumlahBonus += $lengthColTipePekerjaan;
            array_push($arrColOtherJumlahBonus, GlobalHelper::numToAlpha($ColOtherJumlahBonus));
        }

        $ColFiberJumlahBonus = $ColOtherJumlahBonus + ($lengthColAlacarteKlien + $lengthColTipePekerjaan);
        $arrColFiberJumlahBonus = [GlobalHelper::numToAlpha($ColFiberJumlahBonus)];
        for($i=1; $i<$totTipePekerjaanFiber; $i++){
            $ColFiberJumlahBonus += $lengthColTipePekerjaan;
            array_push($arrColFiberJumlahBonus, GlobalHelper::numToAlpha($ColFiberJumlahBonus));
        }

        $array = array(
            'mnc'   => $arrColMncJumlahBonus,
            'link'  => $arrColLinkJumlahBonus,
            'myrep' => $arrColMyrepJumlahBonus,
            'bali'  => $arrColBaliJumlahBonus,
            'oxy'   => $arrColOxyJumlahBonus,
            'other' => $arrColOtherJumlahBonus,
            'fiber' => $arrColFiberJumlahBonus,
        );

        return $array;
    }

    function arrayColPresentaseBonus(){
        $lengthColTipePekerjaan = $this->lengthColTipePekerjaan; // point + jumlah bonus + point wo
        $lengthColAlacarteKlien = $this->lengthColAlacarteKlien; // total bonus + presentasi + total presentase

        // total tipe pekerjaan klien
        $totTipePekerjaanMnc   = $this->totTipePekerjaanMnc;
        $totTipePekerjaanLink  = $this->totTipePekerjaanLink;
        $totTipePekerjaanMyrep = $this->totTipePekerjaanMyrep;
        $totTipePekerjaanBali  = $this->totTipePekerjaanBali;
        $totTipePekerjaanOxy   = $this->totTipePekerjaanOxy;
        $totTipePekerjaanOther = $this->totTipePekerjaanOther;
        $totTipePekerjaanFiber = $this->totTipePekerjaanFiber;

        $mnc   = $this->firstColMnc + ($totTipePekerjaanMnc * $lengthColTipePekerjaan);
        $link  = $mnc + $lengthColAlacarteKlien + ($totTipePekerjaanLink * $lengthColTipePekerjaan);
        $myrep = $link + $lengthColAlacarteKlien + ($totTipePekerjaanMyrep * $lengthColTipePekerjaan);
        $bali  = $myrep + $lengthColAlacarteKlien + ($totTipePekerjaanBali * $lengthColTipePekerjaan);
        $oxy   = $bali + $lengthColAlacarteKlien + ($totTipePekerjaanOxy * $lengthColTipePekerjaan);
        $other = $oxy + $lengthColAlacarteKlien + ($totTipePekerjaanOther * $lengthColTipePekerjaan);
        $fiber = $other + $lengthColAlacarteKlien + ($totTipePekerjaanFiber * $lengthColTipePekerjaan);

        $array = array(
            'mnc'   => GlobalHelper::numToAlpha($mnc),
            'link'  => GlobalHelper::numToAlpha($link),
            'myrep' => GlobalHelper::numToAlpha($myrep),
            'bali'  => GlobalHelper::numToAlpha($bali),
            'oxy'   => GlobalHelper::numToAlpha($oxy),
            'other' => GlobalHelper::numToAlpha($other),
            'fiber' => GlobalHelper::numToAlpha($fiber),
        );

        return $array;
    }

    function arrayColTotalPresentaseBonus(){
        $lengthColTipePekerjaan = $this->lengthColTipePekerjaan; // point + jumlah bonus + point wo
        $lengthColAlacarteKlien = $this->lengthColAlacarteKlien; // total bonus + presentasi + total presentase

        // total tipe pekerjaan klien
        $totTipePekerjaanMnc   = $this->totTipePekerjaanMnc;
        $totTipePekerjaanLink  = $this->totTipePekerjaanLink;
        $totTipePekerjaanMyrep = $this->totTipePekerjaanMyrep;
        $totTipePekerjaanBali  = $this->totTipePekerjaanBali;
        $totTipePekerjaanOxy   = $this->totTipePekerjaanOxy;
        $totTipePekerjaanOther = $this->totTipePekerjaanOther;
        $totTipePekerjaanFiber = $this->totTipePekerjaanFiber;

        $mnc_2   = $this->firstColMnc + ($totTipePekerjaanMnc * $lengthColTipePekerjaan);
        $mnc_1   = $mnc_2 + 1;
        $link_2  = $mnc_2 + $lengthColAlacarteKlien + ($totTipePekerjaanLink * $lengthColTipePekerjaan);
        $link_1  = $link_2 + 1;
        $myrep_2 = $link_2 + $lengthColAlacarteKlien + ($totTipePekerjaanMyrep * $lengthColTipePekerjaan);
        $myrep_1 = $myrep_2 + 1;
        $bali_2  = $myrep_2 + $lengthColAlacarteKlien + ($totTipePekerjaanBali * $lengthColTipePekerjaan);
        $bali_1  = $bali_2 + 1;
        $oxy_2   = $bali_2 + $lengthColAlacarteKlien + ($totTipePekerjaanOxy * $lengthColTipePekerjaan);
        $oxy_1   = $oxy_2 + 1;
        $other_2 = $oxy_2 + $lengthColAlacarteKlien + ($totTipePekerjaanOther * $lengthColTipePekerjaan);
        $other_1 = $other_2 + 1;
        $fiber_2 = $other_2 + $lengthColAlacarteKlien + ($totTipePekerjaanFiber * $lengthColTipePekerjaan);
        $fiber_1 = $fiber_2 + 1;

        $array = array(
            'mnc'   => [GlobalHelper::numToAlpha($mnc_1), GlobalHelper::numToAlpha($mnc_2)],
            'link'  => [GlobalHelper::numToAlpha($link_1), GlobalHelper::numToAlpha($link_2)],
            'myrep' => [GlobalHelper::numToAlpha($myrep_1), GlobalHelper::numToAlpha($myrep_2)],
            'bali'  => [GlobalHelper::numToAlpha($bali_1), GlobalHelper::numToAlpha($bali_2)],
            'oxy'   => [GlobalHelper::numToAlpha($oxy_1), GlobalHelper::numToAlpha($oxy_2)],
            'other' => [GlobalHelper::numToAlpha($other_1), GlobalHelper::numToAlpha($other_2)],
            'fiber' => [GlobalHelper::numToAlpha($fiber_1), GlobalHelper::numToAlpha($fiber_2)],
        );

        return $array;
    }

    function arrayHeadingFirst(){
        $array = array(
            'Cabang',
            'Jabatan',
            'Nama Karyawan',
            'Bank',
            'No Rekening Bank',
            'Gaji Pokok',
            'Tunj. Jabatan',
            'Kerajinan',
            'Disiplin',
            'GAJI HARIAN',
            'Absensi Auto Count',
            'TOTAL GAJI',
        );

        return $array;
    }

    function arrayHeadingLast(){
        $array = array(
            'TOTAL BONUS',
            'TOTAL GAJI KOTOR',
            'TOTAL GAJI TANPA BONUS',
        );

        return $array;
    }

    function arrHeadingFirstSummary(){
        // (A - K => Cabang - Absensi Auto Count) - (l => TOTAL GAJI change to Grand Total)
        $array = array('', '', '', '', '', '', '', '', '', '', '', 'Grand Total');
        return $array;
    }

    function countTipePekerjaanKlien($id_klien){
        $count = m_tipe_pekerjaan::where('id_klien', $id_klien)->count();
        return $count;
    }

    function headerColumnOne($arrHeading, $arrHeadingLast, $dataKlien, $dataTipePekerjaan){
        $collectionData = array();

        // push headers
        for ($i=0; $i < count($arrHeading); $i++) {
            $collectionData[] = (object) array('val' => $arrHeading[$i]);
        }

        // push klien name
        for($i=0; $i<count($dataKlien); $i++){
            foreach($dataTipePekerjaan as $k_dataTipePekerjaan){
                if($k_dataTipePekerjaan->id_klien == $dataKlien[$i]->id_klien){
                    // push data -> looping for 3 kolom (point + jumlah bonus + point wo) in all row 3
                    for($i_posCell_1 = 0; $i_posCell_1 < $this->lengthColTipePekerjaan; $i_posCell_1++) {
                        $collectionData[] = (object) array('val' => $k_dataTipePekerjaan->klien_name);
                    }
                }
            }
            $collectionData[] = (object) array('val' => 'TotaL Bonus');
            $collectionData[] = (object) array('val' => 'Presentase');
            $collectionData[] = (object) array('val' => 'Total Presentase');
        }

        // push arrHeadingLast
        for ($i=0; $i < count($arrHeadingLast); $i++) {
            $collectionData[] = (object) array('val' => $arrHeadingLast[$i]);
        }

        return $collectionData;
    }

    function headerColumnTwo($arrHeading, $arrHeadingLast, $dataKlien, $dataTipePekerjaan){
        $collectionData = array();

        // push headers
        for ($i=0; $i < count($arrHeading); $i++) {
            $collectionData[] = (object) array('val' => $arrHeading[$i]);
        }

        // push tipe pekerjaan -> bonus
        // tmp array id tipe pekerjaan
        $arrIdTipeKerjaan = array();
        for($i=0; $i<count($dataKlien); $i++){
            foreach($dataTipePekerjaan as $k_dataTipePekerjaan){
                if($k_dataTipePekerjaan->id_klien == $dataKlien[$i]->id_klien){
                    // push data -> looping for 3 kolom (point + jumlah bonus + point wo) in all row 3
                    for($i_posCell_2 = 0; $i_posCell_2 < $this->lengthColTipePekerjaan; $i_posCell_2++) {
                        $collectionData[] = (object) array('val' => $k_dataTipePekerjaan->type_name);
                    }

                    // push to arr id tipe pekerjaan
                    array_push($arrIdTipeKerjaan, $k_dataTipePekerjaan->id);
                }
            }
            $collectionData[] = (object) array('val' => 'TotaL Bonus');
            $collectionData[] = (object) array('val' => 'Presentase');
            $collectionData[] = (object) array('val' => 'Total Presentase');
        }

        // push arrHeadingLast
        for ($i=0; $i < count($arrHeadingLast); $i++) {
            $collectionData[] = (object) array('val' => $arrHeadingLast[$i]);
        }

        return $collectionData;
    }

    function headerColumnThree($arrHeading, $arrHeadingLast, $dataKlien, $dataTipePekerjaan){
        $collectionData = array();

        // push headers
        for ($i=0; $i < count($arrHeading); $i++) {
            $collectionData[] = (object) array('val' => $arrHeading[$i]);
        }

        // tmp array id tipe pekerjaan
        for($i=0; $i<count($dataKlien); $i++){
            foreach($dataTipePekerjaan as $k_dataTipePekerjaan){
                if($k_dataTipePekerjaan->id_klien == $dataKlien[$i]->id_klien){
                    // push data
                    $collectionData[] = (object) array('val' => 'Point');
                    $collectionData[] = (object) array('val' => 'Jumlah Bonus');
                    $collectionData[] = (object) array('val' => 'Point WO');
                }
            }
            $collectionData[] = (object) array('val' => 'TotaL Bonus');
            $collectionData[] = (object) array('val' => 'Presentase');
            $collectionData[] = (object) array('val' => 'Total Presentase');
        }

        // push arrHeadingLast
        for ($i=0; $i < count($arrHeadingLast); $i++) {
            $collectionData[] = (object) array('val' => $arrHeadingLast[$i]);
        }

        return $collectionData;
    }

    function getDataTipePekerjaan(){
        $data = DB::table('m_tipe_pekerjaan')
                        ->leftjoin('m_klien', 'm_tipe_pekerjaan.id_klien', 'm_klien.id')
                        ->where('m_tipe_pekerjaan.flag_active', 1)
                        ->select('m_tipe_pekerjaan.*', 'm_klien.klien_name')
                        ->orderBy('m_tipe_pekerjaan.id_klien', 'asc')
                        ->get();
        return $data;
    }

    function getArrayIdTipePekerjaan($dataKlien, $dataTipePekerjaan){
        $arrIdTipeKerjaan = array();

        for($i=0; $i<count($dataKlien); $i++){
            foreach($dataTipePekerjaan as $k_dataTipePekerjaan){
                if($k_dataTipePekerjaan->id_klien == $dataKlien[$i]->id_klien){
                    // push to arr id tipe pekerjaan
                    array_push($arrIdTipeKerjaan, $k_dataTipePekerjaan->id);
                }
            }
        }

        return $arrIdTipeKerjaan;
    }

    function getDatataKlien(){
        $data = DB::table('m_tipe_pekerjaan')
                        ->leftjoin('m_klien', 'm_tipe_pekerjaan.id_klien', 'm_klien.id')
                        ->where('m_tipe_pekerjaan.flag_active', 1)
                        ->select('m_tipe_pekerjaan.*', 'm_klien.klien_name')
                        ->orderBy('m_tipe_pekerjaan.id_klien', 'asc')
                        ->groupBy('m_tipe_pekerjaan.id_klien')
                        ->get()
                        ->toArray();
        return $data;
    }

    function getDataGaji(){
        $qrySelectGaji = "
            SELECT
            tbl_a.*,
            tbl_b.tot_absen,
            (tbl_a.tot_gajiharian * tbl_b.tot_absen) as tot_gaji
        ";
        $qryFromTbl_a = "
            select
                b.id,
                e.id_cabang,
                f.cabang_name,
                a.createdDtm,
                c.jabatan,
                c.name,
                d.name as name_bank,
                c.rekening_bank,
                c.gaji_harian,
                c.tunjangan_jabatan,
                c.gaji_kerajinan,
                c.gaji_disiplin,
                (c.gaji_harian + c.tunjangan_jabatan + c.gaji_kerajinan + c.gaji_disiplin) as tot_gajiharian,
                b.flag_active
            from m_users b
            left join report_gaji prm1 on prm1.id_user = b.id
            left join (
                select * from trans_h_wo where flag_active = 1
            ) prm2 on prm1.id_trans_h_wo =  prm2.id
            left join trans_h_resultwo a on a.id_wo = prm2.id and a.flag_active = 1
            left join trans_h_wo aa on a.id_wo = aa.id
            left join m_karyawan c on b.id_karyawan = c.id
            left join m_bank d on c.bank = d.id
            left join rel_cabangkaryawan e on c.id = e.id_karyawan
            left join m_cabang f on f.id = e.id_cabang
            where b.flag_active = 1
            and a.flag_active = 1
        ";
        if($this->cabang != null || $this->cabang != ''){
            $qryFromTbl_a .= " and e.id_cabang = {$this->cabang}";
        }
        $qryFromTbl_a .= " GROUP BY c.id";

        $qryFromTbl_b = "
            select temp_b.id_user,count(temp_b.tot_absen) as tot_absen from
            (
                select a.id_user, count(a.id) as tot_absen
                from report_gaji a
                left join trans_h_wo b on a.id_trans_h_wo = b.id
                left join trans_h_resultwo c on b.id = c.id_wo
                where {$this->filterdate} BETWEEN '{$this->datefrom} 00:00:00' and '{$this->dateto} 23:59:59'
                and b.flag_active = 1
                and c.flag_approved = 1
                and c.flag_active = 1
                GROUP BY a.id_user, DATE({$this->filterdate})
            ) temp_b
            GROUP BY temp_b.id_user
        ";
        $qryGaji = "
            $qrySelectGaji
            from
            ($qryFromTbl_a) as tbl_a
            left join ($qryFromTbl_b) tbl_b on tbl_a.id = tbl_b.id_user
        ";

        $dataGaji = DB::select($qryGaji);

        return $dataGaji;
    }

    function getDataPoint($iduser, $datefrom, $dateto){
        $dataPoint = DB::select("
                SELECT
                    a.id_user,
                    a.id_tipe_pekerjaan,
                    sum(a.point) as tot_point,
                    (sum(a.point) * a.bonus) as tot_bonus,
                    sum(a.point_wo) as tot_point_wo
                FROM report_gaji a
                left JOIN trans_h_wo b on a.id_trans_h_wo = b.id
                left join trans_h_resultwo c on b.id = c.id_wo
                where c.flag_approved = 1
                and c.flag_active = 1
                and b.flag_active = 1
                and c.id_statuswo = 2
                and a.id_user = ?
                and {$this->filterdate} BETWEEN ? and ?
                -- and a.bonus > 0
                GROUP BY a.id_user, a.id_tipe_pekerjaan;
            ", [$iduser, "$datefrom 00:00:00", "$dateto 23:59:59"]);

        return $dataPoint;
    }

    function pushDataInformasiKaryawan($k_dataGaji){
        $collectionData = array();
        // push data
        $collectionData[] = (object) array('val' => $k_dataGaji->cabang_name);
        $collectionData[] = (object) array('val' => $k_dataGaji->jabatan);
        $collectionData[] = (object) array('val' => $k_dataGaji->name);
        $collectionData[] = (object) array('val' => $k_dataGaji->name_bank);
        $collectionData[] = (object) array('val' => $k_dataGaji->rekening_bank);
        $collectionData[] = (object) array('val' => $k_dataGaji->gaji_harian);
        $collectionData[] = (object) array('val' => $k_dataGaji->tunjangan_jabatan);
        $collectionData[] = (object) array('val' => $k_dataGaji->gaji_kerajinan);
        $collectionData[] = (object) array('val' => $k_dataGaji->gaji_disiplin);
        $collectionData[] = (object) array('val' => $k_dataGaji->tot_gajiharian);
        $collectionData[] = (object) array('val' => $k_dataGaji->tot_absen);
        $collectionData[] = (object) array('val' => $k_dataGaji->tot_gaji);

        return $collectionData;
    }

    function pushTotalBonusKlien($totBonusKlien, $presentaseBonus, $totalPresentaseBonus){
        $collectionData[] = (object) array('val' => $totBonusKlien);
        $collectionData[] = (object) array('val' => $presentaseBonus);
        $collectionData[] = (object) array('val' => $totalPresentaseBonus);

        return $collectionData;
    }
}
