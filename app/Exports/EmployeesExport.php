<?php
namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = Employee::query();

        // ✅ SEARCH APPLY (same as DataTable)
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('employee_id', 'like', "%{$this->search}%")
                    ->orWhere('hq', 'like', "%{$this->search}%")
                    ->orWhere('type', 'like', "%{$this->search}%")
                    ->orWhere('status', 'like', "%{$this->search}%");
            });
        }

        return $query->select(
            'name',
            'zone',
            'hq',
            'employee_id',
            'created_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Zone',
            'HQ',
            'Employee ID',
            'Created At'
        ];
    }
}
