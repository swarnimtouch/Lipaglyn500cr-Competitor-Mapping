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
                'sr_no'                     => $index + 1, // ✅ Sr No
                'msl_code'                  => $d->msl_code,
                'name'                      => $d->name,
                'specialization'            => $d->specialization,
//                'lipaglyn_rx_br_type'       => $d->lipaglyn_rx_br_type,
//                'avg_lipaglyn_pr_month'     => $d->avg_lipaglyn_pr_month,
//                'actual_speciality'         => $d->actual_speciality,
                'Diabetes_patients_day'     => $d->Diabetes_patients_day,
//                'kol_kbl'                   => $d->kol_kbl,
//                'inst_dr'                   => $d->inst_dr,
//                'govt_dropdown'             => $d->govt_dropdown,
                'udca_rx_per_month'         => $d->udca_rx_per_month,
                'sema_rx_prer_month'        => $d->sema_rx_prer_month,
//                'other_saro_rm_per_month'   => $d->other_saro_rm_per_month,
//                'total_business_value'      => $d->total_business_value,
//                'planned_for_conversition'  => $d->planned_for_conversition,
//                'incremental_lipaglyn_busines' => $d->incremental_lipaglyn_busines,
                'bilypsa_rx_per_month'      => $d->bilypsa_rx_per_month,
                'linvas_rx_per_month'       => $d->linvas_rx_per_month,
                'vorxar_rx_per_month'       => $d->vorxar_rx_per_month,
                'created_at'                => $d->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Doctor Code',
            'Name',
            'Specialization',
//            'Lipaglyn Type',
//            'Avg Lipaglyn / Month',
//            'Actual Speciality',
            'Diabetes Patients / Month',
//            'KOL/KBL',
//            'Inst Dr',
//            'Institution Name',
            'UDCA Rx / Month',
            'Sema Rx / Month',
//            'Other Saro Rx',
//            'Total Business',
//            'Planned Conversion',
//            'Incremental Lipaglyn',
            'Bilypsa Rx / Month',
            'Linvas Rx / Month',
            'Vorxar Rx / Month',
            'Created At'
        ];
    }
}
