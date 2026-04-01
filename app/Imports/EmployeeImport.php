<?php

namespace App\Imports;
namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeeImport implements ToModel
{
    public function model(array $row)
    {
        // Skip header row (optional)
        if ($row[0] == 'name') {
            return null;
        }

        return new Employee([
            'name'         => $row[0],
            'hq'           => $row[1],
            'type'         => $row[2],
            'employee_id'  => $row[3],
            'chair_id'     => $row[4],

            // ✅ Password auto (employee_id ya default)
            'password'     => Hash::make($row[3] ?? '123456'),
        ]);
    }
}
