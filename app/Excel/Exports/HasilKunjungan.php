<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class HasilKunjungan implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($datefrom, $dateto, $idklien, $idtipewo, $idcabang, $idteam, $idkaryawan)
    {
        $this->datefrom     = $datefrom;
        $this->dateto       = $dateto;
        $this->idklien      = $idklien;
        $this->idtipewo     = $idtipewo;
        $this->idcabang     = $idcabang;
        $this->idteam       = $idteam;
        $this->idkaryawan   = $idkaryawan;
    }

    public function headings(): array
    {
        // header wo
        $arrHeading = array(
            'Tanggal Wo',
            'No WO Quantum',
            'No WO Klien',
            'Client Name',
            'Customer ID Klien',
            'Customer Name',
            'Alamat',
            'Slot Schedule',
            'Createad Date WO',
        );

        // push detail wo
        $dataMasterWo = DB::table('m_kolomwo')
                            ->where('id_klien', $this->idklien)
                            ->where('id_tipewo',$this->idtipewo)
			                ->where('flag_active', 1)
                            ->get();

        foreach($dataMasterWo as $k_dataMasterWo){
            array_push($arrHeading, $k_dataMasterWo->nama_kolom);
        }

        // push trans_h_resultwo
        array_push(
            $arrHeading,
            'Tipe WO',
            'Nomor WO',
            'Team Name',
            'Created By',
            'Created Date',
            'Tipe pekerjaan',
            'Bonus',
            'Tipe Partner Kerja',
            'Partner Name',
            'Hitungan Point',
            'Status Approved',
            'Approved By',
            'Approved Date',
            'Hasil kunjungan',
            'Category Reason',
            'Notes',
            'SLA'
        );

        return $arrHeading;
    }

    public function collection()
    {
        $qry =
        "
            select a.*, m_klien.klien_name
            from trans_h_wo a
            left join trans_h_resultwo aa on a.id = aa.id_wo
            left join m_klien on a.id_klien = m_klien.id
            left join m_users b on aa.createdBy = b.id
            left join rel_cabangkaryawan c on b.id_karyawan = c.id_karyawan
            left join rel_teamkaryawan d on b.id_karyawan = d.id_karyawan
            where a.id_klien = {$this->idklien}
            and a.id_tipewo = {$this->idtipewo}
            and a.date_wo between '{$this->datefrom}' and '{$this->dateto}'
            and a.flag_active = 1
            and aa.id_wo is not null
        ";
        if ($this->idcabang != null) $qry .= " and a.id_cabang = {$this->idcabang}";
        if ($this->idteam != null) $qry .= " and d.id_team = {$this->idteam}";
        if ($this->idkaryawan != null) $qry .= " and b.id_karyawan = {$this->idkaryawan}";
        $qry .= " group by aa.id_wo";
        $qry .= " union";
        $qry .=
        "
            select a.*, m_klien.klien_name
            from trans_h_wo a
            left join trans_h_resultwo aa on a.id = aa.id_wo
            left join m_klien on a.id_klien = m_klien.id
            left join m_users b on aa.createdBy = b.id
            left join rel_cabangkaryawan c on b.id_karyawan = c.id_karyawan
            left join rel_teamkaryawan d on b.id_karyawan = d.id_karyawan
            where a.id_klien = {$this->idklien}
            and a.id_tipewo = {$this->idtipewo}
            and a.date_wo between '{$this->datefrom}' and '{$this->dateto}'
            and a.flag_active = 1
            and aa.id_wo is null
        ";
        if ($this->idcabang != null) $qry .= " and a.id_cabang = {$this->idcabang}";
        if ($this->idteam != null) $qry .= " and d.id_team = {$this->idteam}";
        if ($this->idkaryawan != null) $qry .= " and b.id_karyawan = {$this->idkaryawan}";
        $dataTransHWo = DB::select($qry);

        $allCollections = array();
        if($dataTransHWo == null){
            $idtranswo = null;
        } else {
            foreach ($dataTransHWo as $k_dataTransHWo) {
                $collectionData = array();
                $dataTransDWo   = DB::table('trans_d_wo as a')
                                    ->join('m_kolomwo as c', 'a.id_kolomwo','=',
                                        DB::raw('c.id AND a.id_transwo = ' . $k_dataTransHWo->id))
                                    ->where('c.flag_active', 1)
                                    ->where('c.id_klien', $this->idklien)
                                    ->where('c.id_tipewo', $this->idtipewo)
                                    ->select('c.id', 'c.nama_kolom', 'a.value')
                                    ->get();

                $TransResultWo  = DB::table('trans_h_resultwo as a')
                                    ->leftjoin('trans_h_wo as aa', 'aa.id', 'a.id_wo')
                                    ->leftjoin('m_users as b', 'a.createdBy', 'b.id')
                                    ->leftjoin('m_users as c', 'a.approvedBy', 'c.id')
                                    ->leftjoin('m_tipewo as d', 'aa.id_tipewo', 'd.id')
                                    ->leftjoin('trans_assignwo as e', 'a.id_wo', 'e.id_wo')
                                    ->leftjoin('m_team as f', 'e.id_team', 'f.id')
                                    ->leftjoin('m_statuswo as g', 'a.id_statuswo', 'g.id')
                                    ->leftjoin('m_category_reason as h', 'a.id_category_reason', 'h.id')
                                    ->leftjoin('m_users as i', 'a.partner_name', 'i.id')
                                    ->where('a.id_wo', $k_dataTransHWo->id)
                                    ->where('aa.flag_active', 1)
                                    ->select(
                                        'a.*',
                                        'b.username',
                                        DB::raw("
                                            (
                                                CASE WHEN a.flag_approved = 1 THEN 'Approved' ELSE 'Not Approved' END
                                            ) AS status_approved
                                        "),
                                        'c.username as username_approved',
                                        'a.approvedDtm',
                                        'd.type_name',
                                        'f.team_name',
                                        'g.status_name',
                                        'h.name as categoryreason_name',
                                        'i.username as username_partner',
                                        'aa.createdDtm as dateCreated'
                                    )
                                    ->first();

                // push headers
                $collectionData[] = (object) array('val' => $k_dataTransHWo->date_wo);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->no_wo);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->no_wo_klien);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->klien_name);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->customerid_klien);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->customer_name);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->alamat);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->slot_value);
                $collectionData[] = (object) array('val' => $k_dataTransHWo->createdDtm);

                // push detail
                if($dataTransDWo != null){
                    foreach($dataTransDWo as $k_dataTransDWo){
                        $collectionData[] = (object) array('val' => $k_dataTransDWo->value);
                    }
                }

                // push trans result wo
                if($TransResultWo != null){
                    // SLA
                    $dateCreated = date_create($TransResultWo->dateCreated);
                    $dateSubmitted = date_create($TransResultWo->createdDtm);
                    $dateDiff = date_diff($dateCreated, $dateSubmitted);
                    $sla = "{$dateDiff->h}:$dateDiff->i";

                    $collectionData[] = (object) array('val' => $TransResultWo->type_name);
                    $collectionData[] = (object) array('val' => $TransResultWo->no_wo);
                    $collectionData[] = (object) array('val' => $TransResultWo->team_name);
                    $collectionData[] = (object) array('val' => $TransResultWo->username);
                    $collectionData[] = (object) array('val' => $TransResultWo->createdDtm);
                    $collectionData[] = (object) array('val' => $TransResultWo->typepekerjaan_name);
                    $collectionData[] = (object) array('val' => $TransResultWo->bonus);
                    $collectionData[] = (object) array('val' => $TransResultWo->typepartnerkerja_name);
                    $collectionData[] = (object) array('val' => $TransResultWo->username_partner);
                    $collectionData[] = (object) array('val' => $TransResultWo->hitungan_point);
                    $collectionData[] = (object) array('val' => $TransResultWo->status_approved);
                    $collectionData[] = (object) array('val' => $TransResultWo->username_approved);
                    $collectionData[] = (object) array('val' => $TransResultWo->approvedDtm);
                    $collectionData[] = (object) array('val' => $TransResultWo->status_name);
                    $collectionData[] = (object) array('val' => $TransResultWo->categoryreason_name);
                    $collectionData[] = (object) array('val' => $TransResultWo->notes);
                    $collectionData[] = (object) array('val' => $sla);
                }
                $valWo = array_column($collectionData, 'val');
                array_push($allCollections, $valWo);
            }
        }

        return collect([$allCollections]);
    }
}
