<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MrAllocatedDoctors;
use Maatwebsite\Excel\Facades\Excel; // optional — only if you have laravel-excel

class DoctorController extends Controller
{
    // ─── Helper: current employee_id from session ────────────────────────────
    private function mrId()
    {
        return session('employee_eid');
    }

    // ─── Auth guard helper ────────────────────────────────────────────────────
    private function authCheck()
    {
        if (!session('employee_id')) {
            return redirect()->route('employee.login');
        }
        return null;
    }

    // ─── Dashboard ───────────────────────────────────────────────────────────
    public function dashboard()
    {
        if ($r = $this->authCheck()) return $r;

        $doctorCount = MrAllocatedDoctors::where('mr_id', $this->mrId())->count();

        return view('portal.dashboard', compact('doctorCount'));
    }

    // ─── Index (blade) ───────────────────────────────────────────────────────
    public function index()
    {
        if ($r = $this->authCheck()) return $r;

        return view('portal.index');
    }

    // ─── DataTable Ajax Listing ──────────────────────────────────────────────
    public function listing(Request $request)
    {
        $query = MrAllocatedDoctors::where('mr_id', $this->mrId());

        // Search
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%")
                    ->orWhere('lipaglyn_rx_br_type', 'like', "%{$search}%");
            });
        }

        $total    = $query->count();
        $filtered = $total;

        // Order
        $orderCol  = $request->input('order.0.column', 0);
        $orderDir  = $request->input('order.0.dir', 'desc');
        $columns   = ['id', 'name', 'msl_code', 'specialization', 'lipaglyn_rx_br_type', 'everage_lipaglyn_pr_month'];
        $sortCol   = $columns[$orderCol] ?? 'id';
        $query->orderBy($sortCol, $orderDir);

        // Paginate
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $doctors = $query->skip($start)->take($length)->get();

        $data = $doctors->map(function ($d) {
            return [
                'id'                        => $d->id,
                'name'                      => $d->name,
                'msl_code'                  => $d->msl_code,
                'specialization'            => $d->specialization,
                'lipaglyn_rx_br_type'       => $d->lipaglyn_rx_br_type,
                'everage_lipaglyn_pr_month' => $d->everage_lipaglyn_pr_month ?? $d->avg_lipaglyn_pr_month,
                'action'                    => '
                    <button onclick="openEditModal(' . $d->id . ')" class="btn btn-sm btn-warning">Edit</button>
                    <form action="/portal/doctors/' . $d->id . '" method="POST" style="display:inline;"
                        onsubmit="return confirm(\'Delete this doctor?\')">
                        ' . csrf_field() . '
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
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

    // ─── Edit Data (JSON for modal) ───────────────────────────────────────────
    public function editData($id)
    {
        $doctor = MrAllocatedDoctors::where('mr_id', $this->mrId())->findOrFail($id);
        return response()->json($doctor);
    }

    // ─── Store ───────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'msl_code'     => 'nullable|string|max:50',
            'specialization' => 'nullable|string|max:100',
        ]);

        $doctor = new MrAllocatedDoctors();
        $this->fillDoctor($doctor, $request);
        $doctor->mr_id = $this->mrId();
        $doctor->save();

        return redirect()->route('portal.doctors.index')
            ->with('success', 'Doctor saved successfully!');
    }

    // ─── Update ──────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'msl_code'     => 'nullable|string|max:50',
            'specialization' => 'nullable|string|max:100',
        ]);

        $doctor = MrAllocatedDoctors::where('mr_id', $this->mrId())->findOrFail($id);
        $this->fillDoctor($doctor, $request);
        $doctor->save();

        return redirect()->route('portal.doctors.index')
            ->with('success', 'Doctor updated successfully!');
    }

    // ─── Delete ──────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        MrAllocatedDoctors::where('mr_id', $this->mrId())->findOrFail($id)->delete();

        return redirect()->route('portal.doctors.index')
            ->with('success', 'Doctor deleted.');
    }

    // ─── Export ──────────────────────────────────────────────────────────────
    public function export()
    {
        $doctors = MrAllocatedDoctors::where('mr_id', $this->mrId())->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="doctors.csv"',
        ];

        $callback = function () use ($doctors) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'ID', 'DR UID', 'Name', 'Specialization', 'Lipaglyn Rxbr Type',
                'Avg Lipaglyn/Month', 'Actual Speciality', 'Diabetes Patients/Day',
                'KOL/KBL', 'Inst Dr', 'Inst Name',
                'UDCA Rx/Month', 'Sema Rx/Month', 'Other Saro Rx/Month',
                'Total Business Value', 'Planned Conversion Month',
                'Incremental Lipaglyn Business', 'Created At','Bilypsa Rx/Month', 'Linvas Rx/Month', 'Vorxar Rx/Month',

            ]);

            foreach ($doctors as $d) {
                fputcsv($handle, [
                    $d->id, $d->msl_code, $d->name, $d->specialization,
                    $d->lipaglyn_rx_br_type, $d->avg_lipaglyn_pr_month,
                    $d->actual_speciality, $d->Diabetes_patients_day,
                    $d->kol_kbl, $d->inst_dr, $d->govt_dropdown,
                    $d->udca_rx_per_month, $d->sema_rx_prer_month,
                    $d->other_saro_rm_per_month, $d->total_business_value,
                    $d->planned_for_conversition, $d->incremental_lipaglyn_busines,
                    $d->created_at,$d->bilypsa_rx_per_month, $d->linvas_rx_per_month, $d->vorxar_rx_per_month,

                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Private: fill model fields from request ──────────────────────────────
    private function fillDoctor(MrAllocatedDoctors $doctor, Request $request)
    {
        $doctor->name                       = $request->name;
        $doctor->msl_code                   = $request->msl_code;
        $doctor->specialization             = $request->specialization;
        $doctor->lipaglyn_rx_br_type        = $request->lipaglyn_rx_br_type;
        $doctor->avg_lipaglyn_pr_month      = $request->avg_lipaglyn_pr_month;
        $doctor->actual_speciality          = $request->actual_speciality;
        $doctor->Diabetes_patients_day      = $request->Diabetes_patients_day;
        $doctor->kol_kbl                    = $request->kol_kbl;
        $doctor->inst_dr                    = $request->inst_dr;
        $doctor->govt_dropdown              = $request->govt_dropdown === 'new'
            ? $request->new_institution
            : $request->govt_dropdown;
        $doctor->udca_rx_per_month          = $request->udca_rx_per_month;
        $doctor->sema_rx_prer_month         = $request->sema_rx_prer_month;
        $doctor->other_saro_rm_per_month    = $request->other_saro_rm_per_month;
        $doctor->total_business_value       = $request->total_business_value;
        $doctor->planned_for_conversition   = $request->planned_for_conversition;
        $doctor->incremental_lipaglyn_busines = $request->incremental_lipaglyn_busines;
        $doctor->bilypsa_rx_per_month  = $request->bilypsa_rx_per_month;
        $doctor->linvas_rx_per_month   = $request->linvas_rx_per_month;
        $doctor->vorxar_rx_per_month   = $request->vorxar_rx_per_month;


    }
}
