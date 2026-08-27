<?php

namespace App\Exports;

use App\Models\MrAllocatedDoctors;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DoctorsExport implements FromCollection, WithHeadings
{
    protected $mr_id;

    public function __construct($mr_id)
    {
        $this->mr_id = $mr_id;
    }

    public function collection()
    {
        $data = MrAllocatedDoctors::where('mr_id', $this->mr_id)
            ->get();

        return $data->map(function ($d, $index) {
            return [

                // Basic
                'sr_no' => $index + 1,
                'msl_code' => $d->msl_code,
                'name' => $d->name,
                'specialization' => $d->specialization,

                // New Doctor Profile Fields
                'qualification' => $d->qualification,
                'wall_doctor' => $d->wall_doctor,
                'trade_govt_corporate' => $d->trade_govt_corporate,
                'national_regional_speaker_exp' => $d->national_regional_speaker_exp,
                'engaged_as_2026_faculty' => $d->engaged_as_2026_faculty,
                'lipaglyn_rx_per_month' => $d->lipaglyn_rx_per_month,
                'lipaglyn_rx_trend' => $d->lipaglyn_rx_trend,
                'lipaglyn_indication' => $d->lipaglyn_indication,
                'mobile_number' => $d->mobile_number,
                'key_dr_birthday' => $d->key_dr_birthday,
                'hobby' => $d->hobby,

                // Existing Business Fields
                'Diabetes_patients_day' => $d->Diabetes_patients_day,
                'udca_rx_per_month' => $d->udca_rx_per_month,
                'sema_rx_prer_month' => $d->sema_rx_prer_month,
                'bilypsa_rx_per_month' => $d->bilypsa_rx_per_month,
                'linvas_rx_per_month' => $d->linvas_rx_per_month,
                'vorxar_rx_per_month' => $d->vorxar_rx_per_month,
                'competitor_activity' => $d->competitor_activity,

                'created_at' => $d->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [

            // Basic
            'ID',
            'Doctor Code',
            'Name',
            'Specialization',

            // New Doctor Profile Fields
            'Qualification',
            'Wall Doctor',
            'Trade / Govt / Corporate',
            'National / Regional Speaker with Exp',
            'Engaged as 2026 Faculty',
            'Lipaglyn Rx / Month',
            'Lipaglyn Rx Trend',
            'Lipaglyn Indication',
            'Mobile Number',
            'Key Dr Birthday',
            'Hobby',

            // Existing Business Fields
            'Diabetes Patients / Month',
            'UDCA Rx / Month',
            'Sema Rx / Month',
            'Bilypsa Rx / Month',
            'Linvas Rx / Month',
            'Vorxar Rx / Month',
            'Competitor activity',

            'Created At'
        ];
    }
}
