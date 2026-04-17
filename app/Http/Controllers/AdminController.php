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
use Illuminate\Support\Facades\Cache;
class AdminController extends Controller
{
    

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

   
    public function dashboard()
    {
        if ($r = $this->authCheck()) return $r;

        $totalEmployees  = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $totalDoctors    = MrAllocatedDoctors::count();

        return view('admin.dashboard', compact('totalEmployees', 'activeEmployees', 'totalDoctors'));
    }

    

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
                    ->orWhere('status',     'like', "%{$search}%")
                    ->orWhere('zone', 'like', "%{$search}%")   // ✅ add
                    ->orWhere('region', 'like', "%{$search}%"); // ✅ add

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
                        'zone' => $e->zone ?? '—',
                        'region' => $e->region ?? '',
                        'employee_id' => $e->employee_id ?? '—',
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
        $query = MrAllocatedDoctors::with(['employee:id,name,employee_id,zone,region,hq'])
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
                    ->orWhere(DB::raw('CAST(avg_lipaglyn_pr_month AS CHAR)'), 'like', "%{$search}%") // ✅ number search
                    ->orWhereHas('employee', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('employee_id', 'like', "%{$search}%")
                            ->orWhere('region', 'like', "%{$search}%")
                            ->orWhere('zone', 'like', "%{$search}%")
                            ->orWhere('hq', 'like', "%{$search}%"); // ✅ add
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
                'emp_region' => $d->employee->region ?? '—',
                'emp_hq' => $d->employee->hq ?? '—',
                'emp_name' => $d->employee->name ?? '—',
                'emp_id'   => $d->employee->employee_id ?? '—',

                'name' => $d->name,
                'msl_code' => $d->msl_code ?? '—',
                'specialization' => $d->specialization ?? '—',
                'Diabetes_patients_day' => $d->Diabetes_patients_day ?? '—',
                'sema_rx_prer_month' => $d->sema_rx_prer_month ?? '—',
                'udca_rx_per_month' => $d->udca_rx_per_month ?? '—',
//                'lipaglyn_rx_br_type' => $d->lipaglyn_rx_br_type ?? '—',
//
//                'avg_lipaglyn_pr_month' => $d->avg_lipaglyn_pr_month ?? 0,
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


    public function Admin_report(Request $request)
    {
        if ($r = $this->authCheck()) return $r;

        $zone = $request->input('zone');

        $doctorStats = DB::table('mr_allocated_doctors')
            ->whereNull('deleted_at')
            ->select(
                'mr_id',

                DB::raw('MAX(is_active) as is_active'),

                DB::raw('SUM(IFNULL(Diabetes_patients_day,0)) as total_diabetes_patients'),

                DB::raw('SUM(IFNULL(udca_rx_per_month,0)) as total_udca'),
                DB::raw('SUM(CASE WHEN is_active = 1 AND IFNULL(udca_rx_per_month,0) > 0 THEN 1 ELSE 0 END) as udca_count'),

                DB::raw('SUM(IFNULL(sema_rx_prer_month,0)) as total_sema'),
                DB::raw('SUM(CASE WHEN is_active = 1 AND IFNULL(sema_rx_prer_month,0) > 0 THEN 1 ELSE 0 END) as sema_count'),

                DB::raw('SUM(IFNULL(bilypsa_rx_per_month,0)) as total_bilypsa'),
                DB::raw('SUM(CASE WHEN is_active = 1 AND IFNULL(bilypsa_rx_per_month,0) > 0 THEN 1 ELSE 0 END) as bilypsa_count'),

                DB::raw('SUM(IFNULL(linvas_rx_per_month,0)) as total_linvas'),
                DB::raw('SUM(CASE WHEN is_active = 1 AND IFNULL(linvas_rx_per_month,0) > 0 THEN 1 ELSE 0 END) as linvas_count'),

                DB::raw('SUM(IFNULL(vorxar_rx_per_month,0)) as total_vorxar'),
                DB::raw('SUM(CASE WHEN is_active = 1 AND IFNULL(vorxar_rx_per_month,0) > 0 THEN 1 ELSE 0 END) as vorxar_count')
            )
            ->groupBy('mr_id');

        $query = DB::table('employees')
            ->leftJoinSub($doctorStats, 'mad', function ($join) {
                $join->on('employees.employee_id', '=', 'mad.mr_id');
            })
            ->select(
                'employees.zone', 
                DB::raw("COALESCE(employees.region, 'No Region') as region"),

                DB::raw('COUNT(DISTINCT employees.id) as user_count'),
                DB::raw('SUM(CASE WHEN mad.is_active = 1 THEN 1 ELSE 0 END) as active_user_count'),

                DB::raw('SUM(IFNULL(mad.total_diabetes_patients,0)) as total_diabetes_patients'),

                DB::raw('SUM(IFNULL(mad.udca_count,0)) as udca_count'),
                DB::raw('SUM(IFNULL(mad.total_udca,0)) as total_udca'),

                DB::raw('SUM(IFNULL(mad.sema_count,0)) as sema_count'),
                DB::raw('SUM(IFNULL(mad.total_sema,0)) as total_sema'),

                DB::raw('SUM(IFNULL(mad.bilypsa_count,0)) as bilypsa_count'),
                DB::raw('SUM(IFNULL(mad.total_bilypsa,0)) as total_bilypsa'),

                DB::raw('SUM(IFNULL(mad.linvas_count,0)) as linvas_count'),
                DB::raw('SUM(IFNULL(mad.total_linvas,0)) as total_linvas'),

                DB::raw('SUM(IFNULL(mad.vorxar_count,0)) as vorxar_count'),
                DB::raw('SUM(IFNULL(mad.total_vorxar,0)) as total_vorxar')
            )
            ->groupBy('employees.zone', 'employees.region');

        if ($zone && $zone !== 'all') {
            $query->where('employees.zone', $zone);
        }

        $regions = $query->get();

        $totals = [
            'region_count' => $regions->count(),
            'user_count' => $regions->sum('user_count'),
            'active_user_count' => $regions->sum('active_user_count'),
            'total_diabetes_patients' => $regions->sum('total_diabetes_patients'),
            'udca_count' => $regions->sum('udca_count'),
            'total_udca' => $regions->sum('total_udca'),
            'sema_count' => $regions->sum('sema_count'),
            'total_sema' => $regions->sum('total_sema'),
            'bilypsa_count' => $regions->sum('bilypsa_count'),
            'total_bilypsa' => $regions->sum('total_bilypsa'),
            'linvas_count' => $regions->sum('linvas_count'),
            'total_linvas' => $regions->sum('total_linvas'),
            'vorxar_count' => $regions->sum('vorxar_count'),
            'total_vorxar' => $regions->sum('total_vorxar'),
        ];

        // zones simple (no cache)
        $zones = DB::table('employees')
            ->select('zone')
            ->whereNotNull('zone')
            ->distinct()
            ->orderBy('zone')
            ->pluck('zone');

        return view('admin.general.report', compact('regions', 'zones', 'totals', 'zone'));
    }


    public function exportReport(Request $request)
    {
        $query = DB::table('employees')
            ->leftJoin('mr_allocated_doctors as mad', function ($join) {
                $join->on('employees.employee_id', '=', 'mad.mr_id');
            })
            ->whereNull('mad.deleted_at')

            ->select(
                DB::raw("COALESCE(employees.region, 'No Region') as region"),

                DB::raw('COUNT(DISTINCT employees.id) as user_count'),

                DB::raw('COUNT(DISTINCT CASE WHEN mad.is_active = 1 THEN employees.id END) as active_user_count'),

                DB::raw('SUM(IFNULL(mad.Diabetes_patients_day,0)) as total_diabetes_patients'),

                DB::raw('SUM(IFNULL(mad.udca_rx_per_month,0)) as total_udca'),
                DB::raw('SUM(CASE WHEN mad.is_active = 1 AND IFNULL(mad.udca_rx_per_month,0) > 0 THEN 1 ELSE 0 END) as udca_count'),

                DB::raw('SUM(IFNULL(mad.sema_rx_prer_month,0)) as total_sema'),
                DB::raw('SUM(CASE WHEN mad.is_active = 1 AND IFNULL(mad.sema_rx_prer_month,0) > 0 THEN 1 ELSE 0 END) as sema_count'),

                DB::raw('SUM(IFNULL(mad.bilypsa_rx_per_month,0)) as total_bilypsa'),
                DB::raw('SUM(CASE WHEN mad.is_active = 1 AND IFNULL(mad.bilypsa_rx_per_month,0) > 0 THEN 1 ELSE 0 END) as bilypsa_count'),

                DB::raw('SUM(IFNULL(mad.linvas_rx_per_month,0)) as total_linvas'),
                DB::raw('SUM(CASE WHEN mad.is_active = 1 AND IFNULL(mad.linvas_rx_per_month,0) > 0 THEN 1 ELSE 0 END) as linvas_count'),

                DB::raw('SUM(IFNULL(mad.vorxar_rx_per_month,0)) as total_vorxar'),
                DB::raw('SUM(CASE WHEN mad.is_active = 1 AND IFNULL(mad.vorxar_rx_per_month,0) > 0 THEN 1 ELSE 0 END) as vorxar_count')
            )
            ->groupBy('employees.region');

        if ($request->has('zone') && $request->zone !== 'all' && $request->zone !== '') {
            $query->where('employees.zone', $request->zone);
        }

        $regions = $query->get();

        $zoneSuffix = '';
        if ($request->has('zone') && $request->zone !== 'all' && $request->zone !== '') {
            $zoneSuffix = '_' . $request->zone;
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
            'Diabetes Patients in a Month',
            'UDCA Rxbers',
            'UDCA Rx/Month',
            'Sema Rxbers',
            'Sema Rx/Month',
            'Bilypsa Rxbers',
            'Bilypsa Rx/Month',
            'Linvas Rxbers',
            'Linvas Rx/Month',
            'Vorxar Rxbers',
            'Vorxar Rx/Month',
        ];

        $callback = function () use ($regions, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            foreach ($regions as $key => $r) {
                fputcsv($file, [
                    $key + 1,
                    $r->region,
                    $r->user_count,
                    $r->active_user_count,

                    number_format($r->total_diabetes_patients * 25),

                    // UDCA
                    $r->udca_count,
                    number_format($r->total_udca, 2),

                    // Sema
                    $r->sema_count,
                    number_format($r->total_sema, 2),

                    // Bilypsa
                    $r->bilypsa_count,
                    number_format($r->total_bilypsa, 2),

                    // Linvas
                    $r->linvas_count,
                    number_format($r->total_linvas, 2),

                    // Vorxar
                    $r->vorxar_count,
                    number_format($r->total_vorxar, 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
