<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MrAllocatedDoctors extends Model
{
    use SoftDeletes;

    protected $table = 'mr_allocated_doctors';

    protected $fillable = [
        'mr_id',
        'msl_code',
        'name',
        'specialization',
        'lipaglyn_rx_br_type',
        'avg_lipaglyn_pr_month',
        'actual_speciality',
        'Diabetes_patients_day',
        'kol_kbl',
        'inst_dr',
        'govt_dropdown',
        'udca_rx_per_month',
        'sema_rx_prer_month',
        'other_saro_rm_per_month',
        'total_business_value',
        'planned_for_conversition',
        'incremental_lipaglyn_busines',
        'everage_lipaglyn_pr_month',
        'bilypsa_rx_per_month',
        'linvas_rx_per_month',
        'vorxar_rx_per_month',
    ];
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'mr_id', 'employee_id');
    }

}
