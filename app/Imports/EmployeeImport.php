<?php
namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeeImport implements ToModel
{
    protected $hashedPassword;

    public function __construct()
    {
        // ✅ hash only once (BIG FIX 🚀)
        $this->hashedPassword = Hash::make('Lipaglyn500cr');
    }

    public function model(array $row)
    {
        // ✅ Skip header
        if (trim($row[0]) == 'EMPLOYEE NAME') {
            return null;
        }

        // ✅ Skip empty row
        if (empty($row[0])) {
            return null;
        }

        return new Employee([
            'name'        => trim($row[0]),
            'zone'        => trim($row[1]),
            'employee_id' => trim($row[2]),
            'region'      => trim($row[3]),
            'hq'          => trim($row[5]),

            // ✅ reuse hashed password
            'password'    => $this->hashedPassword,
        ]);
    }
}
