<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\MrAllocatedDoctors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Exports\AdminDoctorsExport;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;
class AdminController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    //  AUTH
    // ──────────────────────────────────────────────────────────────────────────

    public function loginForm()
    {
        if (session('admin_id')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = User::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        session(['admin_id' => $admin->id, 'admin_name' => $admin->name]);

        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        session()->forget(['admin_id', 'admin_name']);
        return redirect()->route('admin.login');
    }

    private function authCheck()
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }
        return null;
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  DASHBOARD
    // ──────────────────────────────────────────────────────────────────────────

    public function dashboard()
    {
        if ($r = $this->authCheck()) return $r;

        $totalEmployees  = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $totalDoctors    = MrAllocatedDoctors::count();

        return view('admin.dashboard', compact('totalEmployees', 'activeEmployees', 'totalDoctors'));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  EMPLOYEES
    // ══════════════════════════════════════════════════════════════════════════

    public function employeeIndex()
    {
        if ($r = $this->authCheck()) return $r;
        return view('admin.employees.index');
    }

    public function employeeListing(Request $request)
    {
        $query = Employee::query();

        // Global search
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',         'like', "%{$search}%")
                    ->orWhere('employee_id','like', "%{$search}%")
                    ->orWhere('hq',         'like', "%{$search}%")
                    ->orWhere('type',       'like', "%{$search}%")
                    ->orWhere('status',     'like', "%{$search}%");
            });
        }

        $total    = $query->count();
        $filtered = $total;

        // Sorting
        $cols = ['id', 'name', 'employee_id', 'hq', 'type', 'status'];
        $col  = $cols[$request->input('order.0.column', 0)] ?? 'id';
        $dir  = $request->input('order.0.dir', 'desc');
        $query->orderBy($col, $dir);

        $employees = $query
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        $data = $employees->map(function ($e, $index) use ($request) {

            $isActive = $e->status === 'active';



                $nextStatus = $isActive ? 'inactive' : 'active';
                $checked    = $isActive ? 'checked' : '';

                    $toggleSwitch = '
                    <label class="switch">
                        <input type="checkbox" ' . $checked . ' onchange="toggleStatus(' . $e->id . ', this.checked ? \'active\' : \'inactive\')">
                        <span class="slider"></span>
                    </label>
                ';

                    return [
                        'id'          => $request->start + $index + 1,
                        'name'        => $e->name,
                        'employee_id' => $e->employee_id ?? '—',
                        'zone' => $e->zone ?? '—',
                        'region' => $e->region ?? '',
                        'hq'          => $e->hq,
                        'status'      => $toggleSwitch,
                        'action'      => '
                <div class="action-btns" style="display:flex;align-items:center;gap:8px;">

                    <button onclick="deleteEmployee(' . $e->id . ')" class="btn btn-xs btn-danger">Delete</button>
                </div>
            ',
            ];

        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);
    }

    public function employeeStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'hq'          => 'required|string|max:100',
            'type'        => 'required|string|max:50',
            'employee_id' => 'nullable|string|max:50|unique:employees,employee_id',
            'password'    => 'required|string|min:6',
        ]);

        Employee::create([
            'name'        => $request->name,
            'hq'          => $request->hq,
            'type'        => $request->type,
            'employee_id' => $request->employee_id,
            'chair_id'    => $request->chair_id,
            'status'      => $request->status ?? 'active',
            'password'    => bcrypt($request->password),
        ]);

        return response()->json(['success' => true, 'message' => 'Employee added successfully!']);
    }

    public function employeeEdit($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    public function employeeUpdate(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'hq'          => 'required|string|max:100',
            'type'        => 'required|string|max:50',
            'employee_id' => 'nullable|string|max:50|unique:employees,employee_id,' . $id,
        ]);

        $employee              = Employee::findOrFail($id);
        $employee->name        = $request->name;
        $employee->hq          = $request->hq;
        $employee->type        = $request->type;
        $employee->employee_id = $request->employee_id;
        $employee->chair_id    = $request->chair_id;
        $employee->status      = $request->status;

        if ($request->filled('password')) {
            $employee->password = bcrypt($request->password);
        }

        $employee->save();

        return response()->json(['success' => true, 'message' => 'Employee updated successfully!']);
    }

    public function employeeToggleStatus(Request $request, $id)
    {
        $employee         = Employee::findOrFail($id);
        $employee->status = $request->status;
        $employee->save();

        return response()->json(['success' => true, 'message' => 'Status updated!']);
    }

    public function employeeDestroy($id)
    {
        Employee::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Employee deleted!']);
    }
    public function exportEmployees(Request $request)
    {
        return Excel::download(
            new EmployeesExport($request->search),
            'employees.xlsx'
        );
    }
    // ══════════════════════════════════════════════════════════════════════════
    //  DOCTORS
    // ══════════════════════════════════════════════════════════════════════════

    public function doctorIndex()
    {
        if ($r = $this->authCheck()) return $r;

        // Pass employees list for the filter dropdown
        $employees = Employee::select('id', 'name', 'employee_id', 'zone')
            ->whereNotNull('zone')
            ->orderBy('zone')
            ->get();

        return view('admin.doctors.index', compact('employees'));
    }

    public function doctorListing(Request $request)
    {
        $query = MrAllocatedDoctors::with(['employee:id,name,employee_id,zone'])
            ->whereNull('mr_allocated_doctors.deleted_at');

        // ✅ ZONE FILTER
        if ($request->zone) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->whereRaw('LOWER(zone) = ?', [strtolower($request->zone)]);
            });
        }

        // ✅ SEARCH (doctor + employee)
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('msl_code', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%")
                    ->orWhere('lipaglyn_rx_br_type', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('employee_id', 'like', "%{$search}%");
                    });
            });
        }

        // ✅ FILTERED COUNT
        $filtered = $query->count();

        // ✅ SORTING
        $columns = [
            'id',
            'name',
            'msl_code',
            'specialization',
            'lipaglyn_rx_br_type',
            'avg_lipaglyn_pr_month'
        ];

        $colIndex = $request->input('order.0.column', 0);
        $col = $columns[$colIndex] ?? 'id';
        $dir = $request->input('order.0.dir', 'desc');

        $query->orderBy($col, $dir);

        // ✅ PAGINATION
        $doctors = $query
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        // ✅ TOTAL COUNT (WITHOUT SEARCH BUT WITH ZONE)
        $totalQuery = MrAllocatedDoctors::whereNull('deleted_at');

        if ($request->zone) {
            $totalQuery->whereHas('employee', function ($q) use ($request) {
                $q->whereRaw('LOWER(zone) = ?', [strtolower($request->zone)]);
            });
        }

        $total = $totalQuery->count();

        // ✅ FINAL DATA
        $data = $doctors->map(function ($d, $index) use ($request) {
            return [
                'index' => $request->start + $index + 1,

                'emp_name' => $d->employee->name ?? '—',
                'emp_id'   => $d->employee->employee_id ?? '—',

                'name' => $d->name,
                'msl_code' => $d->msl_code ?? '—',
                'specialization' => $d->specialization ?? '—',
                'lipaglyn_rx_br_type' => $d->lipaglyn_rx_br_type ?? '—',

                'avg_lipaglyn_pr_month' => $d->avg_lipaglyn_pr_month ?? 0,
                'bilypsa_rx_per_month'  => $d->bilypsa_rx_per_month ?? 0,
                'linvas_rx_per_month'   => $d->linvas_rx_per_month ?? 0,
                'vorxar_rx_per_month'   => $d->vorxar_rx_per_month ?? 0,

                'action' => '
                <button onclick="deleteDoctor('.$d->id.')" class="btn btn-sm btn-danger">
                    Delete
                </button>
            '
            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);
    }

    public function doctorDestroy($id)
    {
        MrAllocatedDoctors::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Doctor deleted successfully'
        ]);
    }
    public function exportDoctors(Request $request)
    {

        return Excel::download(
            new AdminDoctorsExport(
                $request->zone,
                $request->search
            ),
            'all_doctors.xlsx'
        );
    }
    // ══════════════════════════════════════════════════════════════════════════
    //  REPORT
    // ══════════════════════════════════════════════════════════════════════════

    public function Admin_report(Request $request)
    {
        $query = DB::table('employees')
            ->leftJoin('mr_allocated_doctors', function ($join) {
                $join->on('employees.chair_id', '=', 'mr_allocated_doctors.mr_id')
                    ->orOn('employees.employee_id', '=', 'mr_allocated_doctors.mr_id');
            })
            ->whereNull('mr_allocated_doctors.deleted_at')
            ->select(
                'employees.zone',
                DB::raw("COALESCE(employees.region, 'No Region') as region"),

                DB::raw('COUNT(DISTINCT employees.id) as user_count'),

                // 🔥 UPDATED ACTIVE
                DB::raw("
            COUNT(
                DISTINCT CASE
                    WHEN mr_allocated_doctors.is_active = 1
                    THEN employees.id
                END
            ) as active_user_count
        "),

                DB::raw('COUNT(CASE WHEN (IFNULL(mr_allocated_doctors.avg_lipaglyn_pr_month,0) + IFNULL(mr_allocated_doctors.udca_rx_per_month,0)) > 0 THEN 1 END) as lipaglyn_udca_count'),

                DB::raw('SUM(IFNULL(mr_allocated_doctors.avg_lipaglyn_pr_month,0)) as total_avg_lipaglyn'),

                DB::raw('COUNT(CASE WHEN IFNULL(mr_allocated_doctors.avg_lipaglyn_pr_month,0) > 0 THEN 1 END) as avg_lipaglyn_count'),

                DB::raw('SUM(IFNULL(mr_allocated_doctors.Diabetes_patients_day,0)) as total_diabetes_patients'),

                DB::raw("COUNT(CASE WHEN mr_allocated_doctors.planned_for_conversition IS NOT NULL AND TRIM(mr_allocated_doctors.planned_for_conversition) != '' THEN 1 END) as planned_for_conversition_count"),

                DB::raw('SUM(IFNULL(mr_allocated_doctors.total_business_value,0)) as total_business_value_sum'),

                DB::raw('COUNT(CASE WHEN mr_allocated_doctors.incremental_lipaglyn_busines IS NOT NULL THEN 1 END) as incremental_lipaglyn_busines_count'),

                DB::raw('SUM(IFNULL(mr_allocated_doctors.incremental_lipaglyn_busines,0)) as incremental_lipaglyn_busines_sum'),

                DB::raw('SUM(IFNULL(mr_allocated_doctors.incremental_lipaglyn_busines,0) + IFNULL(mr_allocated_doctors.total_business_value,0)) as incremental_lipaglyn_busines_sum1')
            )
            ->groupBy('employees.zone', 'employees.region');
        // Zone filter
       // Zone filter (Single Selection)
if ($request->has('zone') && $request->zone !== 'all' && $request->zone !== '') {
    $query->where('employees.zone', $request->zone);
}

$regions = $query->get();

        $totals = [
            'region_count'                       => $regions->count(),
            'user_count'                         => $regions->sum('user_count'),
            'active_user_count'                  => $regions->sum('active_user_count'),
            'lipaglyn_udca_count'                => $regions->sum('lipaglyn_udca_count'),
            'total_avg_lipaglyn'                 => $regions->sum('total_avg_lipaglyn'),
            'avg_lipaglyn_count'                 => $regions->sum('avg_lipaglyn_count'),
            'total_diabetes_patients'            => $regions->sum('total_diabetes_patients'),
            'planned_for_conversition_count'     => $regions->sum('planned_for_conversition_count'),
            'total_business_value_sum'           => $regions->sum('total_business_value_sum'),
            'incremental_lipaglyn_busines_count' => $regions->sum('incremental_lipaglyn_busines_count'),
            'incremental_lipaglyn_busines_sum'   => $regions->sum('incremental_lipaglyn_busines_sum'),
            'incremental_lipaglyn_busines_sum1'  => $regions->sum('incremental_lipaglyn_busines_sum1'),
        ];

        $zones = DB::table('employees')
            ->select('zone')
            ->whereNotNull('zone')
            ->distinct()
            ->pluck('zone');

        return view('admin.general.report', [
            'title'      => 'Region Report',
            'breadcrumb' => [],
            'regions'    => $regions,
            'zones'      => $zones,
            'totals'     => $totals,
        ]);
    }

    public function exportReport(Request $request)
    {
        $query = DB::table('employees')
            ->leftJoin('mr_allocated_doctors', function ($join) {
                $join->on('employees.chair_id', '=', 'mr_allocated_doctors.mr_id')
                    ->orOn('employees.employee_id', '=', 'mr_allocated_doctors.mr_id');
            })
            ->whereNull('mr_allocated_doctors.deleted_at')
            ->select(
                'employees.zone',
                DB::raw("COALESCE(employees.region, 'No Region') as region"),

                DB::raw('COUNT(DISTINCT employees.id) as user_count'),

                // 🔥 UPDATED ACTIVE
                DB::raw("
            COUNT(
                DISTINCT CASE
                    WHEN mr_allocated_doctors.is_active = 1
                    THEN employees.id
                END
            ) as active_user_count
        "),

                DB::raw('COUNT(CASE WHEN (IFNULL(mr_allocated_doctors.avg_lipaglyn_pr_month,0) + IFNULL(mr_allocated_doctors.udca_rx_per_month,0)) > 0 THEN 1 END) as lipaglyn_udca_count'),

                DB::raw('SUM(IFNULL(mr_allocated_doctors.avg_lipaglyn_pr_month,0)) as total_avg_lipaglyn'),

                DB::raw('COUNT(CASE WHEN IFNULL(mr_allocated_doctors.avg_lipaglyn_pr_month,0) > 0 THEN 1 END) as avg_lipaglyn_count'),

                DB::raw('SUM(IFNULL(mr_allocated_doctors.Diabetes_patients_day,0)) as total_diabetes_patients'),

                DB::raw("COUNT(CASE WHEN mr_allocated_doctors.planned_for_conversition IS NOT NULL AND TRIM(mr_allocated_doctors.planned_for_conversition) != '' THEN 1 END) as planned_for_conversition_count"),

                DB::raw('SUM(IFNULL(mr_allocated_doctors.total_business_value,0)) as total_business_value_sum'),

                DB::raw('COUNT(CASE WHEN mr_allocated_doctors.incremental_lipaglyn_busines IS NOT NULL THEN 1 END) as incremental_lipaglyn_busines_count'),

                DB::raw('SUM(IFNULL(mr_allocated_doctors.incremental_lipaglyn_busines,0)) as incremental_lipaglyn_busines_sum'),

                DB::raw('SUM(IFNULL(mr_allocated_doctors.incremental_lipaglyn_busines,0) + IFNULL(mr_allocated_doctors.total_business_value,0)) as incremental_lipaglyn_busines_sum1')
            )
            ->groupBy('employees.zone', 'employees.region');

        // Zone filter
        // Zone filter (Single Selection)
if ($request->has('zone') && $request->zone !== 'all' && $request->zone !== '') {
    $query->where('employees.zone', $request->zone);
}

        $regions = $query->get();

        $zoneSuffix = '';
if ($request->has('zone') && $request->zone !== 'all' && $request->zone !== '') {
    $zoneSuffix = '_' . $request->zone; // Sirf ek single zone ka naam append hoga
}

        $filename = 'region_report' . $zoneSuffix . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = [
            'No',
            'Region',
            'No. of BOs',
            'No. of Active BOs',
            'Lipaglyn Business as per RCPA',
            'Lipaglyn Current No. of Rxbers',
            'Lipaglyn + UDCA No. of Rxbers',
            'Diabetes Patients in a Month',
            'New Doctor Conversions Planned',
            'Potential Business of Lipaglyn From New Dr Conversions',
            'Incremental Business from Existing Rxbers Planned',
            'Potential Incremental Business from Existing Rxbers',
            'Total Potential New Business Planned',
        ];

        $callback = function () use ($regions, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            foreach ($regions as $key => $r) {
                fputcsv($file, [
                    $key + 1,
                    $r->region, // ✅ FIXED

                    $r->user_count,
                    $r->active_user_count,
                    number_format($r->total_avg_lipaglyn, 2),
                    $r->avg_lipaglyn_count,
                    $r->lipaglyn_udca_count,
                    number_format($r->total_diabetes_patients * 25),
                    number_format($r->planned_for_conversition_count),
                    number_format($r->total_business_value_sum, 2),
                    number_format($r->incremental_lipaglyn_busines_count),
                    number_format($r->incremental_lipaglyn_busines_sum, 2),
                    number_format($r->incremental_lipaglyn_busines_sum1, 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
