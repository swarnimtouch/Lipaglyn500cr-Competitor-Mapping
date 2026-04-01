<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\MrAllocatedDoctors;
use Illuminate\Support\Facades\Hash;

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

    // ─── Auth Guard ───────────────────────────────────────────────────────────
    private function authCheck()
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }
        return null;
    }

    // ─── Dashboard ────────────────────────────────────────────────────────────
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

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',        'like', "%{$search}%")
                    ->orWhere('employee_id','like', "%{$search}%")
                    ->orWhere('hq',         'like', "%{$search}%")
                    ->orWhere('type',       'like', "%{$search}%")
                    ->orWhere('status',     'like', "%{$search}%");
            });
        }

        $total    = $query->count();
        $filtered = $total;

        $cols    = ['id','name','employee_id','hq','type','status'];
        $col     = $cols[$request->input('order.0.column', 0)] ?? 'id';
        $dir     = $request->input('order.0.dir', 'desc');
        $query->orderBy($col, $dir);

        $employees = $query->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        $data = $employees->map(function ($e) {
            $badge = $e->status === 'active'
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';

            $toggle = $e->status === 'active'
                ? '<button onclick="toggleStatus('.$e->id.',\'inactive\')" class="btn btn-xs btn-warning">Deactivate</button>'
                : '<button onclick="toggleStatus('.$e->id.',\'active\')" class="btn btn-xs btn-success">Activate</button>';

            return [
                'id'          => $e->id,
                'name'        => $e->name,
                'employee_id' => $e->employee_id ?? '—',
                'hq'          => $e->hq,
                'type'        => $e->type,
                'status'      => $badge,
                'action'      => '
                    '.$toggle.'
                    <button onclick="openEditEmployee('.$e->id.')" class="btn btn-xs btn-info">Edit</button>
                    <button onclick="deleteEmployee('.$e->id.')" class="btn btn-xs btn-danger">Delete</button>
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
            'employee_id' => 'nullable|string|max:50|unique:employees,employee_id,'.$id,
        ]);

        $employee = Employee::findOrFail($id);
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
        $employee = Employee::findOrFail($id);
        $employee->status = $request->status;
        $employee->save();

        return response()->json(['success' => true, 'message' => 'Status updated!']);
    }

    public function employeeDestroy($id)
    {
        Employee::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Employee deleted!']);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  DOCTORS
    // ══════════════════════════════════════════════════════════════════════════

    public function doctorIndex()
    {
        if ($r = $this->authCheck()) return $r;
        return view('admin.doctors.index');
    }

    public function doctorListing(Request $request)
    {
        $query = MrAllocatedDoctors::with('employee'); // belongsTo Employee via mr_id

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',               'like', "%{$search}%")
                    ->orWhere('specialization',   'like', "%{$search}%")
                    ->orWhere('msl_code',         'like', "%{$search}%")
                    ->orWhere('lipaglyn_rx_br_type','like',"%{$search}%");
            });
        }

        // Filter by employee if passed
        if ($mrId = $request->input('mr_id')) {
            $query->where('mr_id', $mrId);
        }

        $total    = $query->count();
        $filtered = $total;

        $cols = ['id','name','msl_code','specialization','lipaglyn_rx_br_type','avg_lipaglyn_pr_month'];
        $col  = $cols[$request->input('order.0.column', 0)] ?? 'id';
        $dir  = $request->input('order.0.dir', 'desc');
        $query->orderBy($col, $dir);

        $doctors = $query->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        $data = $doctors->map(function ($d) {
            return [
                'id'                        => $d->id,
                'emp_name'                  => $d->employee->name    ?? '—',
                'emp_id'                    => $d->employee->employee_id ?? '—',
                'name'                      => $d->name,
                'msl_code'                  => $d->msl_code ?? '—',
                'specialization'            => $d->specialization,
                'lipaglyn_rx_br_type'       => $d->lipaglyn_rx_br_type,
                'avg_lipaglyn_pr_month'     => $d->avg_lipaglyn_pr_month,
                'bilypsa_rx_per_month'      => $d->bilypsa_rx_per_month,
                'linvas_rx_per_month'       => $d->linvas_rx_per_month,
                'vorxar_rx_per_month'       => $d->vorxar_rx_per_month,
            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);
    }
}
