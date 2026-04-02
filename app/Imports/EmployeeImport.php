<?php
namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeeImport implements ToModel
{
    public function model(array $row)
    {
        // ✅ Skip header
        if (trim($row[0]) == 'EMPLOYEE CODE') {
            return null;
        }

        return Employee::updateOrCreate(
            ['employee_id' => trim($row[0])],
            [
                'name'   => trim($row[1]),
                'zone'   => !empty($row[2]) ? trim($row[2]) : null,
                'region' => !empty($row[3]) ? trim($row[3]) : null,
                'hq'     => !empty($row[5]) ? trim($row[5]) : null,

                'type'   => 'MR', // default
                'chair_id' => null,

                'password' => Hash::make('Lipaglyn500cr'),
            ]
        );
    }
}
