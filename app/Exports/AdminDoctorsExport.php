<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminDoctorsExport implements FromCollection, WithHeadings
{
    protected $zone;
    protected $search;
    public function __construct($zone = null, $search = null)
    {
        $this->zone = $zone;
        $this->search = $search;
    }
    public function collection()
    {
        $query = DB::table('mr_allocated_doctors as d')
            ->leftJoin('employees as e', function ($join) {
                $join->on('e.employee_id', '=', 'd.mr_id')
                    ->orOn('e.chair_id', '=', 'd.mr_id');
            })
            ->whereNull('d.deleted_at');

        // ✅ ZONE FILTER (MAIN FIX 🔥)
        if (!empty($this->zone)) {
            $query->whereRaw('LOWER(e.zone) = ?', [strtolower($this->zone)]);
        }

        // ✅ SEARCH FILTER
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('d.name', 'like', "%{$this->search}%")
                    ->orWhere('d.msl_code', 'like', "%{$this->search}%")
                    ->orWhere('d.specialization', 'like', "%{$this->search}%")
                    ->orWhere('e.name', 'like', "%{$this->search}%")
                    ->orWhere('e.region', 'like', "%{$this->search}%")
                ->orWhere('e.employee_id', 'like', "%{$this->search}%");
            });
        }

        $data = $query->select(
            'e.region as emp_region',
            'e.name as emp_name',
            'e.employee_id as emp_id',
            'd.msl_code',
            'd.name',
            'd.specialization',
//            'd.lipaglyn_rx_br_type',
//            'd.avg_lipaglyn_pr_month',
//            'd.actual_speciality',
            'd.Diabetes_patients_day',
//            'd.kol_kbl',
//            'd.inst_dr',
//            'd.govt_dropdown',
            'd.udca_rx_per_month',
            'd.sema_rx_prer_month',
//            'd.other_saro_rm_per_month',
//            'd.total_business_value',
//            'd.planned_for_conversition',
//            'd.incremental_lipaglyn_busines',
            'd.bilypsa_rx_per_month',
            'd.linvas_rx_per_month',
            'd.vorxar_rx_per_month',
            'd.created_at'
        )->get();

        return $data->map(function ($d, $index) {
            return [
                'sr_no' => $index + 1,
                'emp_region' => $d->emp_region,
                'emp_name' => $d->emp_name,
                'emp_id' => $d->emp_id,
                'msl_code' => $d->msl_code ?? '-',
                'name' => $d->name,
                'specialization' => $d->specialization,
//                'lipaglyn_rx_br_type' => $d->lipaglyn_rx_br_type,
//                'avg_lipaglyn_pr_month' => $d->avg_lipaglyn_pr_month,
//                'actual_speciality' => $d->actual_speciality,
                'Diabetes_patients_day' => $d->Diabetes_patients_day,
//                'kol_kbl' => $d->kol_kbl,
//                'inst_dr' => $d->inst_dr,
//                'govt_dropdown' => $d->govt_dropdown,
                'udca_rx_per_month' => $d->udca_rx_per_month,
                'sema_rx_prer_month' => $d->sema_rx_prer_month,
//                'other_saro_rm_per_month' => $d->other_saro_rm_per_month,
//                'total_business_value' => $d->total_business_value,
//                'planned_for_conversition' => $d->planned_for_conversition,
//                'incremental_lipaglyn_busines' => $d->incremental_lipaglyn_busines,
                'bilypsa_rx_per_month' => $d->bilypsa_rx_per_month,
                'linvas_rx_per_month' => $d->linvas_rx_per_month,
                'vorxar_rx_per_month' => $d->vorxar_rx_per_month,
                'created_at' => $d->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sr No',
            'MR Region',
            'MR Name',
            'MR Employee ID',
            'Doctor Code',
            'Doctor Name',
            'Specialization',
//            'Lipaglyn Type',
//            'Avg Lipaglyn',
//            'Actual Speciality',
            'Diabetes Patients / Month',
//            'KOL/KBL',
//            'Inst Dr',
//            'Institution Name',
            'UDCA Rx / Month',
            'Sema Rx / Month',
//            'Other Saro',
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
