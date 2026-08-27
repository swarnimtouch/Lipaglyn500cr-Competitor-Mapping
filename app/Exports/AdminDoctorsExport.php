<?php
// app/Exports/AdminDoctorsExport.php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminDoctorsExport implements FromQuery, WithHeadings, WithChunkReading, WithMapping, ShouldAutoSize
{
    protected $zone;
    protected $search;
    private int $index = 0;

    public function __construct($zone = null, $search = null)
    {
        $this->zone   = $zone;
        $this->search = $search;
    }

    public function query()
    {
        $query = DB::table('mr_allocated_doctors as d')
            ->leftJoin('employees as e', function ($join) {
                $join->on('e.employee_id', '=', 'd.mr_id')
                    ->orOn('e.chair_id', '=', 'd.mr_id');
            })
            ->whereNull('d.deleted_at');

        // Zone filter
        if (!empty($this->zone)) {
            $query->whereRaw(
                'LOWER(e.zone) = ?',
                [strtolower($this->zone)]
            );
        }

        // Search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('d.name', 'like', "%{$this->search}%")
                    ->orWhere('d.msl_code', 'like', "%{$this->search}%")
                    ->orWhere('d.specialization', 'like', "%{$this->search}%")
                    ->orWhere('e.name', 'like', "%{$this->search}%")
                    ->orWhere('e.region', 'like', "%{$this->search}%")
                    ->orWhere('e.hq', 'like', "%{$this->search}%")
                    ->orWhere('e.employee_id', 'like', "%{$this->search}%");
            });
        }

        return $query->select(

        // MR Details
            'e.region as emp_region',
            'e.hq as emp_hq',
            'e.name as emp_name',
            'e.employee_id as emp_id',

            // Doctor Basic Details
            'd.msl_code',
            'd.name',
            'd.specialization',
            'd.qualification',

            // New Doctor Profile Fields
            'd.wall_doctor',
            'd.trade_govt_corporate',
            'd.national_regional_speaker_exp',
            'd.engaged_as_2026_faculty',
            'd.lipaglyn_rx_per_month',
            'd.lipaglyn_rx_trend',
            'd.lipaglyn_indication',
            'd.mobile_number',
            'd.key_dr_birthday',
            'd.hobby',

            // Existing Business Fields
            'd.Diabetes_patients_day',
            'd.udca_rx_per_month',
            'd.sema_rx_prer_month',
            'd.bilypsa_rx_per_month',
            'd.linvas_rx_per_month',
            'd.vorxar_rx_per_month',
            'd.competitor_activity',

            // Created
            'd.created_at'

        )->orderBy('d.id');
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map($d): array
    {
        return [
            // Sr No
            ++$this->index,

            // MR Details
            $d->emp_region ?? '-',
            $d->emp_hq ?? '-',
            $d->emp_name ?? '-',
            $d->emp_id ?? '-',

            // Doctor Details
            $d->msl_code ?? '-',
            $d->name ?? '-',
            $d->specialization ?? '-',
            $d->qualification ?? '-',

            // New Doctor Profile Fields
            $d->trade_govt_corporate ?? '-',
            $d->national_regional_speaker_exp ?? '-',
            $d->engaged_as_2026_faculty ?? '-',
            $d->lipaglyn_rx_per_month ?? '-',
            $d->lipaglyn_rx_trend ?? '-',
            $d->lipaglyn_indication ?? '-',
            $d->mobile_number ?? '-',
            $d->key_dr_birthday ?? '-',

            // Existing Business Fields
            $d->Diabetes_patients_day ?? '-',
            $d->udca_rx_per_month ?? '-',
            $d->sema_rx_prer_month ?? '-',
            $d->bilypsa_rx_per_month ?? '-',
            $d->linvas_rx_per_month ?? '-',
            $d->vorxar_rx_per_month ?? '-',
            $d->competitor_activity ?? '-',

            // Created At
            $d->created_at ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            // MR Details
            'Sr No',
            'MR Region',
            'MR HQ',
            'MR Name',
            'MR Employee ID',

            // Doctor Details
            'Doctor Code',
            'Doctor Name',
            'Specialization',
            'Qualification',

            // New Doctor Profile Fields
            'Trade / Govt / Corporate',
            'National / Regional Speaker with Exp',
            'Engaged as 2026 Faculty',
            'Lipaglyn Rx / Month',
            'Lipaglyn Rx Trend',
            'Lipaglyn Indication',
            'Mobile Number',
            'Key Dr Birthday',

            // Existing Business Fields
            'Diabetes Patients / Month',
            'UDCA Rx / Month',
            'Sema Rx / Month',
            'Bilypsa Rx / Month',
            'Linvas Rx / Month',
            'Vorxar Rx / Month',
            'Hobby (mention any 1 strong hobby)',

            // Created
            'Created At',
        ];
    }
}
