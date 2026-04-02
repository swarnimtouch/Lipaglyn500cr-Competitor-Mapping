<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login()
    {
        return view('employee.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'password'    => 'required|string',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)
            ->where('status', 'active')
            ->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return back()->withErrors([
                'employee_id' => 'Employee ID or Password Wrong.',
            ])->withInput();
        }

        // Session mein employee store karo
        session([
            'employee_id'   => $employee->id,
            'employee_name' => $employee->name,
            'employee_eid'  => $employee->employee_id,
        ]);

        return redirect()->route('portal.dashboard');
    }

    public function dashboard()
    {
        if (!session('employee_id')) {
            return redirect()->route('employee.login');
        }

        $doctorCount = \App\Models\MrAllocatedDoctors::where('mr_id', session('employee_eid'))->count();

        return view('employee.dashboard', compact('doctorCount'));
    }


    public function logout()
    {
        session()->flush();
        return redirect()->route('employee.login');
    }


    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
    public function import()
    {
        return view('import');
    }
    public function importEmployees(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new EmployeeImport, $request->file('file'));

        return back()->with('success', 'Employees Imported Successfully!');
    }
}
