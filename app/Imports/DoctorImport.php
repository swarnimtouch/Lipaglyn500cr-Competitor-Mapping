<?php
namespace App\Imports;

use App\Models\MrAllocatedDoctors;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

class DoctorImport implements ToModel, WithChunkReading, ShouldQueue
{
    public function model(array $row)
    {
        // Skip header
        if (trim($row[0]) == 'EMPLOYEE CODE') {
            return null;
        }

        if (empty($row[2])) {
            return null;
        }

        return new MrAllocatedDoctors([
            'mr_id'         => trim($row[0]),
            'msl_code'      => trim($row[2]),
            'name'          => trim($row[3]),
            'specialization'=> trim($row[4]),
            'qualification' => trim($row[5]),
            'state'         => trim($row[6]),
            'city'          => trim($row[7]),
        ]);
    }

    // ✅ chunk size (VERY IMPORTANT)
    public function chunkSize(): int
    {
        return 5000; // 500 rows per batch
    }
}
