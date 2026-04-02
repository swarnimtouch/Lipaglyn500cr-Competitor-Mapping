<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Employee extends Model
{
    //
    protected $fillable = [
        'name',
        'hq',
        'zone',
        'region',
        'type',
        'status',
        'employee_id',
        'chair_id',
        'password'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->password)) {
                $employee->password = Hash::make('123456');
            }
        });
    }
    public function doctors()
    {
        return $this->hasMany(MrAllocatedDoctors::class, 'mr_id', 'employee_id');
    }

}
