<?php
namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeeImport implements ToModel
{
    public $insertCount = 0;
    public $updateCount = 0;

    protected $hashedPassword;

    public function __construct()
    {
        $this->hashedPassword = Hash::make('Lipaglyn500cr');
    }

    public function model(array $row)
    {
        if (trim($row[0]) == 'EMPLOYEE NAME' || empty($row[0])) {
            return null;
        }

        $employee = Employee::where('employee_id', trim($row[2]))->first();

        if ($employee) {
            // ✅ UPDATE
            $employee->update([
                'name'   => trim($row[0]),
                'zone'   => trim($row[1]),
                'region' => trim($row[3]),
                'hq'     => trim($row[4]),
            ]);

            $this->updateCount++;
            return null; // IMPORTANT
        } else {
            // ✅ INSERT
            $this->insertCount++;

            return new Employee([
                'name'        => trim($row[0]),
                'zone'        => trim($row[1]),
                'employee_id' => trim($row[2]),
                'region'      => trim($row[3]),
                'hq'          => trim($row[4]),
                'password'    => $this->hashedPassword,
            ]);
        }
    }
}
