@extends('layouts.master_portal')

@section('title', 'Edit Doctor – MR Portal')
@section('page_title', 'Edit Doctor')

@section('content')

    <div style="max-width:860px;">
        <div class="section-header">
            <h3>Edit: {{ $doctor->name }}</h3>
            <a href="{{ route('portal.doctors.index') }}" class="btn btn-outline">← Back</a>
        </div>

        <div class="card">
            <form method="POST" action="{{ route('portal.doctors.update', $doctor->id) }}">
                @csrf
                @method('PUT')

                {{-- Row 1 --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label"><span class="req">*</span> Doctor Name</label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $doctor->name) }}" maxlength="100" required>
                        @error('name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">MSL Code</label>
                        <input type="text" name="msl_code" class="form-control"
                               value="{{ old('msl_code', $doctor->msl_code) }}" maxlength="50">
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Specialization</label>
                        <input type="text" name="specialization" class="form-control"
                               value="{{ old('specialization', $doctor->specialization) }}" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Actual Speciality</label>
                        <input type="text" name="actual_speciality" class="form-control"
                               value="{{ old('actual_speciality', $doctor->actual_speciality) }}" maxlength="100">
                    </div>
                </div>

                {{-- Row 3 --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Lipaglyn Rx Brand Type</label>
                        <input type="text" name="lipaglyn_rx_br_type" class="form-control"
                               value="{{ old('lipaglyn_rx_br_type', $doctor->lipaglyn_rx_br_type) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Avg Lipaglyn/Month</label>
                        <input type="number" step="0.01" name="avg_lipaglyn_pr_month" class="form-control"
                               value="{{ old('avg_lipaglyn_pr_month', $doctor->avg_lipaglyn_pr_month) }}">
                    </div>
                </div>

                {{-- Row 4 --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Diabetes Patients/Day</label>
                        <input type="number" name="Diabetes_patients_day" class="form-control"
                               value="{{ old('Diabetes_patients_day', $doctor->Diabetes_patients_day) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">KOL / KBL</label>
                        <select name="kol_kbl" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach(['KOL','KBL','None'] as $opt)
                                <option value="{{ $opt }}"
                                    {{ old('kol_kbl', $doctor->kol_kbl) == $opt ? 'selected' : '' }}>
                                    {{ $opt }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Row 5 --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Institution Dr</label>
                        <input type="text" name="inst_dr" class="form-control"
                               value="{{ old('inst_dr', $doctor->inst_dr) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Govt / Institution</label>
                        @php
                            $govtVal = old('govt_dropdown', $doctor->govt_dropdown);
                            $isCustom = !in_array($govtVal, ['Govt', 'Private', '']);
                        @endphp
                        <select name="govt_dropdown" id="govt_dropdown" class="form-control"
                                onchange="toggleNewInstitution(this.value)">
                            <option value="">-- Select --</option>
                            <option value="Govt"    {{ $govtVal == 'Govt'    ? 'selected' : '' }}>Govt</option>
                            <option value="Private" {{ $govtVal == 'Private' ? 'selected' : '' }}>Private</option>
                            <option value="new"     {{ $isCustom             ? 'selected' : '' }}>Other (Enter New)</option>
                        </select>
                    </div>
                </div>

                {{-- New Institution --}}
                <div class="form-group" id="new_institution_field" style="display:{{ $isCustom ? 'block' : 'none' }}">
                    <label class="form-label">New Institution Name</label>
                    <input type="text" name="new_institution" class="form-control"
                           value="{{ $isCustom ? old('new_institution', $doctor->govt_dropdown) : '' }}"
                           placeholder="Enter institution name">
                </div>

                {{-- Row 6 --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">UDCA Rx/Month</label>
                        <input type="number" step="0.01" name="udca_rx_per_month" class="form-control"
                               value="{{ old('udca_rx_per_month', $doctor->udca_rx_per_month) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">SEMA Rx/Month</label>
                        <input type="number" step="0.01" name="sema_rx_prer_month" class="form-control"
                               value="{{ old('sema_rx_prer_month', $doctor->sema_rx_prer_month) }}">
                    </div>
                </div>

                {{-- Row 7 --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Other Saro RM/Month</label>
                        <input type="number" step="0.01" name="other_saro_rm_per_month" class="form-control"
                               value="{{ old('other_saro_rm_per_month', $doctor->other_saro_rm_per_month) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Business Value (₹)</label>
                        <input type="number" step="0.01" name="total_business_value" class="form-control"
                               value="{{ old('total_business_value', $doctor->total_business_value) }}">
                    </div>
                </div>

                {{-- Row 8 --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Planned for Conversion</label>
                        <input type="number" step="0.01" name="planned_for_conversition" class="form-control"
                               value="{{ old('planned_for_conversition', $doctor->planned_for_conversition) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Incremental Lipaglyn Business</label>
                        <input type="number" step="0.01" name="incremental_lipaglyn_busines" class="form-control"
                               value="{{ old('incremental_lipaglyn_busines', $doctor->incremental_lipaglyn_busines) }}">
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex; gap:12px; margin-top:8px;">
                    <button type="submit" class="btn btn-primary">Update Doctor</button>
                    <a href="{{ route('portal.doctors.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function toggleNewInstitution(val) {
            document.getElementById('new_institution_field').style.display = val === 'new' ? 'block' : 'none';
        }
    </script>
@endsection
