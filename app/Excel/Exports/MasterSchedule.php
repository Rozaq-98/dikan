<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\m_slot_schedule;

class MasterSchedule implements FromQuery, WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'ID',
            'Slot Name',
            'Time 1',
            'Time 2',
            'Notes'
        ];
    }

    public function query()
    {
        return m_slot_schedule::query()
                ->where('flag_Active', '1')
                ->select('id','slot_name', 'time_1', 'time_2', 'notes');
    }
}