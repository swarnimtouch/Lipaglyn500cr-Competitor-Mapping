<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mr_allocated_doctors')->insert([
            [
                'mr_id'                       => '1001',   // Rahul Sharma ka employee_id
                'name'                         => 'Dr. Ankit Mehta',
                'msl_code'                     => 'MSL-2201',
                'specialization'               => 'Diabetologist',
                'actual_speciality'            => 'Endocrinology',
                'lipaglyn_rx_br_type'          => 'Brand A',
                'avg_lipaglyn_pr_month'        => 45.00,
                'Diabetes_patients_day'        => 30,
                'kol_kbl'                      => 'KOL',
                'inst_dr'                      => 'Yes',
                'govt_dropdown'                => 'Private',
                'udca_rx_per_month'            => 12.50,
                'sema_rx_prer_month'           => 8.00,
                'other_saro_rm_per_month'      => 5.00,
                'total_business_value'         => 75000.00,
                'planned_for_conversition'     => 20.00,
                'incremental_lipaglyn_busines' => 15000.00,
                'status'                       => 'active',
                'created_at'                   => now(),
                'updated_at'                   => now(),
            ],
            [
                'mr_id'                       => '1001',   // Rahul Sharma ka doosra doctor
                'name'                         => 'Dr. Priya Sharma',
                'msl_code'                     => 'MSL-2202',
                'specialization'               => 'General Physician',
                'actual_speciality'            => 'Internal Medicine',
                'lipaglyn_rx_br_type'          => 'Brand B',
                'avg_lipaglyn_pr_month'        => 20.00,
                'Diabetes_patients_day'        => 15,
                'kol_kbl'                      => 'KBL',
                'inst_dr'                      => 'No',
                'govt_dropdown'                => 'Govt',
                'udca_rx_per_month'            => 6.00,
                'sema_rx_prer_month'           => 3.50,
                'other_saro_rm_per_month'      => 2.00,
                'total_business_value'         => 35000.00,
                'planned_for_conversition'     => 10.00,
                'incremental_lipaglyn_busines' => 8000.00,
                'status'                       => 'active',
                'created_at'                   => now(),
                'updated_at'                   => now(),
            ],
            [
                'mr_id'                       => '1002',   // Amit Patel ka doctor
                'name'                         => 'Dr. Suresh Verma',
                'msl_code'                     => 'MSL-3301',
                'specialization'               => 'Cardiologist',
                'actual_speciality'            => 'Cardiology',
                'lipaglyn_rx_br_type'          => 'Brand C',
                'avg_lipaglyn_pr_month'        => 60.00,
                'Diabetes_patients_day'        => 40,
                'kol_kbl'                      => 'KOL',
                'inst_dr'                      => 'Yes',
                'govt_dropdown'                => 'Private',
                'udca_rx_per_month'            => 18.00,
                'sema_rx_prer_month'           => 12.00,
                'other_saro_rm_per_month'      => 7.50,
                'total_business_value'         => 120000.00,
                'planned_for_conversition'     => 35.00,
                'incremental_lipaglyn_busines' => 25000.00,
                'status'                       => 'active',
                'created_at'                   => now(),
                'updated_at'                   => now(),
            ],
        ]);
    }
}
