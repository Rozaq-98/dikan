<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\m_cabang;

class MasterCabang implements FromQuery, WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'ID',
            'Cabang Name',
            'Address',
            'Phone'
        ];
    }

    public function query()
    {
        return m_cabang::query()
                ->where('flag_Active', '1')
                ->select('id','cabang_name', 'address', 'phone');
    }
}
