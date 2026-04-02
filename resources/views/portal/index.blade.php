@extends('layouts.masterMr')

@section('title')Doctors@endsection

@section('css')
    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    {{-- Select2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

    <style>
        /* ── Form Error & Validation ── */
        label.error { display: flex; align-items: center; gap: 6px; margin-top: 6px; color: #ef4444; font-size: 13px; font-weight: 500; }
        label.error::before { content: '\f05a'; font-family: 'Font Awesome 6 Free'; font-weight: 900; }
        .form-validation-error { border-color: #ef4444 !important; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important; }

        /* ── Select2 Customization to match Premium Theme ── */
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection--single {
            border-radius: 8px !important;
            border: 1px solid var(--border) !important;
            min-height: 44px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--color-a) !important;
            box-shadow: 0 0 0 4px rgba(0, 158, 163, 0.1) !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            color: #ffffff !important;
            background: var(--gradient-primary) !important;
            border: none;
            border-radius: 6px;
            margin: 0.2rem 0.4rem 0.2rem 0 !important;
            padding: 4px 8px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove { color: #ffffff !important; margin-right: 5px; border-right: 1px solid rgba(255,255,255,0.3); padding-right: 5px; }

        /* ── Modal Premium Design ── */
        .modal { z-index: 1050 !important; }
        .modal-backdrop { z-index: 1040 !important; }
        .modal-dialog { max-width: 800px; }
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .modal-header {
            background: var(--gradient-primary);
            color: #fff;
            border-bottom: none;
            padding: 20px 24px;
        }
        .modal-title { font-weight: 700; font-size: 20px; letter-spacing: 0.3px; }
        .modal-header .close { color: #fff; opacity: 0.8; text-shadow: none; font-size: 28px; transition: opacity 0.3s; margin: -1rem -1rem -1rem auto; padding: 1rem; }
        .modal-header .close:hover { opacity: 1; }

        .modal-body { padding: 30px; background-color: var(--card-bg); }
        .modal-body .row { margin-bottom: 18px; }
        label.col-form-label { font-weight: 600; color: var(--text); padding-top: 0.5rem; font-size: 14.5px; }

        /* ── Modern Form Inputs ── */
        .form-control {
            border-radius: 8px;
            border: 1px solid var(--border);
            font-size: 14.5px;
            padding: 10px 14px;
            height: auto;
            background: var(--input-bg);
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--color-a);
            box-shadow: 0 0 0 4px rgba(0, 158, 163, 0.1);
            background: #fff;
        }

        /* ── Custom Radio Buttons ── */
        .form-check-input {
            accent-color: var(--color-a);
            width: 18px;
            height: 18px;
            margin-top: 2px;
            cursor: pointer;
        }
        .form-check-label {
            cursor: pointer;
            font-weight: 600;
            font-size: 14.5px;
            padding-left: 6px;
            color: var(--text);
        }

        /* ── Modal Buttons ── */
        .wd-sl-modalbtn {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 10px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }
        .btn-outline-secondary {
            color: var(--text-muted);
            border: 2px solid var(--border);
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 24px;
            background: transparent;
            transition: all 0.3s ease;
        }
        .btn-outline-secondary:hover {
            background: #f1f5f9;
            color: var(--text);
            border-color: #cbd5e1;
        }

        @media (max-width: 768px) {
            .col-md-2, .col-md-10 { flex: 0 0 100%; max-width: 100%; margin-bottom: 5px; }
            .wd-sl-modalbtn { flex-direction: column; align-items: stretch; }
            .btn { width: 100%; margin-bottom: 8px; }
        }

        /* ── Premium Cards for Speciality & Tag as ── */
        .ref-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-top: 4px solid var(--color-a); /* Teal color */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .ref-card.ref-purple {
            border-top-color: var(--color-b); /* Purple color */
        }
        .ref-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .ref-card-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }
        .ref-card-title.teal { color: var(--color-a); }
        .ref-card-title.purple { color: var(--color-b); }

        .ref-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .ref-list li {
            position: relative;
            padding-left: 26px;
            margin-bottom: 12px;
            font-size: 14px;
            color: var(--text);
            font-weight: 500;
        }
        .ref-list li::before {
            content: '\f0da'; /* FontAwesome angle-right icon */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 2px;
        }
        .ref-card:not(.ref-purple) .ref-list li::before { color: var(--color-a); }
        .ref-card.ref-purple .ref-list li::before { color: var(--color-b); }

        /* ── Action Icon Buttons (Edit/Delete) ── */
        .btn-icon {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: none;
            font-size: 15px;
            transition: all 0.3s ease;
            margin: 0 4px;
            text-decoration: none !important;
            cursor: pointer;
        }
        .btn-edit {
            background: rgba(0, 158, 163, 0.1);
            color: var(--color-a) !important;
        }
        .btn-edit:hover {
            background: var(--color-a);
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(0, 158, 163, 0.3);
            transform: translateY(-2px);
        }
        .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444 !important;
        }
        .btn-delete:hover {
            background: #ef4444;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            transform: translateY(-2px);
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div style="background:#d4edda;color:#155724;padding:10px;border-radius:5px;margin-bottom:15px;">
                    {{ session('success') }}
                </div>
            @endif
            {{-- Speciality Reference Table (Premium UI) --}}
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="ref-card">
                        <div class="ref-card-title teal">
                            <i class="fas fa-stethoscope"></i> Speciality
                        </div>
                        <ul class="ref-list">
                            <li>MBBS, MD, DM or DNB (Endo)</li>
                            <li>MBBS, MD, + Certification in Diabetes</li>
                            <li>MBBS, MD, DM or DNB (Cardio) Performs procedures such as stenting and also consults patients</li>
                            <li>MBBS, MD, DM or DNB (Cardio) Consults only patients</li>
                            <li>MBBS, MD, DM or DNB (Nephro)</li>
                            <li>MBBS, MD, (Medicine)</li>
                            <li>MBBS (Only)</li>
                            <li>Any other Apart from above</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="ref-card ref-purple">
                        <div class="ref-card-title purple">
                            <i class="fas fa-tags"></i> Tag as
                        </div>
                        <ul class="ref-list">
                            <li>Cons. Endocrinologist</li>
                            <li>Cons. Diabetologist</li>
                            <li>Interventional Cardiologist</li>
                            <li>Cons. Cardiologist</li>
                            <li>Cons. Nephrologist</li>
                            <li>Cons. Physician</li>
                            <li>General Physician</li>
                            <li>Others</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <a href="{{ route('portal.doctors.export') }}" class="btn btn-sm btn-primary">Export Doctors Excel</a>
                    <button type="button" id="btnAddDoctor" class="btn btn-sm btn-primary">Add</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="listResults" class="table dt-responsive mb-4 nowrap w-100">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>DR. Name</th>
                                <th>DR. UID</th>
                                <th>Speciality</th>
                                <th>Lipaglyn Rx br type</th>
                                <th>Average Lipaglyn Pr Month</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         ADD / EDIT MODAL
    ══════════════════════════════════════════════ --}}
    <div class="modal fade" id="doctorModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle">Add New Doctor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="doctor_form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <span id="method_field"></span>

                    <div class="modal-body">

                        {{-- DR UID --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Dr UID</label>
                            <div class="col-md-8">
                                <input type="text" maxlength="11"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                       class="form-control" name="msl_code" placeholder="DR UID">
                            </div>
                        </div>

                        {{-- Name --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" placeholder="Dr. Name">
                            </div>
                        </div>

                        {{-- Speciality --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Speciality <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="specialization" id="specialization" placeholder="Enter Speciality">
                            </div>
                        </div>

                        {{-- Lipaglyn Rxbr type --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Lipaglyn Rxbr type <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control" name="lipaglyn_rx_br_type" id="lipaglyn_rx_br_type">
                                    <option value="">Select Lipaglyn Rxbr type</option>
                                    <option value="Emerging O.Rxber">Emerging O.Rxber</option>
                                    <option value="Occasional Rxber">Occasional Rxber</option>
                                    <option value="Rxber">Rxber</option>
                                    <option value="Non Rxber">Non Rxber</option>
                                    <option value="Regular Rxber">Regular Rxber</option>
                                    <option value="Wall Drs">Wall Drs</option>
                                </select>
                            </div>
                        </div>

                        {{-- Average Lipaglyn Business/Month --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Average Lipaglyn Business/Month <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="avg_lipaglyn_pr_month"
                                       id="avg_lipaglyn_pr_month" placeholder="Avg Lipaglyn Business/Month"
                                       min="0" max="10" step="0.01">
                            </div>
                        </div>

                        {{-- Actual Speciality --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Actual Speciality <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control" name="actual_speciality" id="actual_speciality">
                                    <option value="">Select Actual Speciality</option>
                                    <option value="Cons. Endocrinologist">Cons. Endocrinologist</option>
                                    <option value="Cons. Diabetologist">Cons. Diabetologist</option>
                                    <option value="Interventional Cardiologist">Interventional Cardiologist</option>
                                    <option value="Cons. Cardiologist">Cons. Cardiologist</option>
                                    <option value="Cons. Physician">Cons. Physician</option>
                                    <option value="General Physician">General Physician</option>
                                    <option value="Cons. Nephrologist">Cons. Nephrologist</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>

                        {{-- Diabetes Patients in a day --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Diabetes Patients in a day <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="Diabetes_patients_day" placeholder="Diabetes Patients in a day">
                            </div>
                        </div>

                        {{-- KOL / KBL --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">KOL / KBL <span class="text-danger">*</span></label>
                            <div class="col-md-8 d-flex align-items-center" style="gap:16px; flex-wrap:wrap; padding-top:8px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kol_kbl" id="kol" value="KOL">
                                    <label class="form-check-label" for="kol">KOL</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kol_kbl" id="kbl" value="KBL">
                                    <label class="form-check-label" for="kbl">KBL</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kol_kbl" id="kol_other" value="OTHER">
                                    <label class="form-check-label" for="kol_other">OTHER</label>
                                </div>
                            </div>
                        </div>

                        {{-- Inst Dr --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Inst Dr <span class="text-danger">*</span></label>
                            <div class="col-md-8 d-flex align-items-center" style="gap:12px; flex-wrap:wrap; padding-top:8px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="inst_dr" id="cghs" value="CGHS">
                                    <label class="form-check-label" for="cghs">CGHS</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="inst_dr" id="esi" value="ESI">
                                    <label class="form-check-label" for="esi">ESI</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="inst_dr" id="other_govt" value="OTHER GOVT">
                                    <label class="form-check-label" for="other_govt">OTHER GOVT</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="inst_dr" id="non_govt_dr" value="Non Govt Dr">
                                    <label class="form-check-label" for="non_govt_dr">Non Govt Dr</label>
                                </div>
                            </div>
                        </div>

                        {{-- Govt Dropdown (conditional) --}}
                        <div class="mb-3 row" id="govt_dropdown_wrapper" style="display:none;">
                            <label class="col-md-4 col-form-label">Select Inst Name</label>
                            <div class="col-md-8">
                                <select class="form-control selectpicker" id="govt_dropdown" name="govt_dropdown" data-live-search="true">
                                    <option value="">Select Inst Name</option>
                                    <option value="new">New Institution</option>
                                    <option value="North Western Railway, DELHI">North Western Railway, DELHI</option>
                                    <option value="Sir Gangaram Hospital - Delhi, DELHI">Sir Gangaram Hospital - Delhi, DELHI</option>
                                    <option value="Jhilmil ESI, DELHI">Jhilmil ESI, DELHI</option>
                                    <option value="AMRIT PHARMACY (A DIVISON OF HLL L, HOWRAH)">AMRIT PHARMACY (A DIVISON OF HLL L, HOWRAH)</option>
                                    <option value="NF Railways, GUWAHATI">NF Railways, GUWAHATI</option>
                                    <option value="CGHS Bangalore, DELHI">CGHS Bangalore, DELHI</option>
                                    <option value="NMDC BACHELI, BILASPUR">NMDC BACHELI, BILASPUR</option>
                                    <option value="HLL - Jharkhand, JUGSALAI">HLL - Jharkhand, JUGSALAI</option>
                                    <option value="East Central Railways, HAJIPUR">East Central Railways, HAJIPUR</option>
                                    <option value="BHU VARANASI, Varanasi">BHU VARANASI, Varanasi</option>
                                    <option value="In-charge (Stores),ESIC Hospital, PATNA">In-charge (Stores),ESIC Hospital, PATNA</option>
                                    <option value="CGHS Chennai, DELHI">CGHS Chennai, DELHI</option>
                                    <option value="CRPF Avadi, DELHI">CRPF Avadi, DELHI</option>
                                    <option value="Deputy Director, Department of Atomic Energy, DELHI">Deputy Director, Department of Atomic Energy, DELHI</option>
                                    <option value="Assam Rifles Multispeciality Hospital  Manipur , DELHI">Assam Rifles Multispeciality Hospital Manipur, DELHI</option>
                                    <option value="CGHS Shillong, DELHI">CGHS Shillong, DELHI</option>
                                    <option value="DIG Capfs CH Patgaon Kamrup, DELHI">DIG Capfs CH Patgaon Kamrup, DELHI</option>
                                    <option value="HQ DG Assam Rifles Shillong, DELHI">HQ DG Assam Rifles Shillong, DELHI</option>
                                    <option value="NDRF 1st bn Patgaon, DELHI">NDRF 1st bn Patgaon, DELHI</option>
                                    <option value="177 BN CRPF, DELHI">177 BN CRPF, DELHI</option>
                                    <option value="47 BN ITBP, DELHI">47 BN ITBP, DELHI</option>
                                    <option value="79 BN CRPF, DELHI">79 BN CRPF, DELHI</option>
                                    <option value="CGHS SHIMLA, DELHI">CGHS SHIMLA, DELHI</option>
                                    <option value="CGHS Srinagar, DELHI">CGHS Srinagar, DELHI</option>
                                    <option value="CGHS Wellness Center Amritsar, DELHI">CGHS Wellness Center Amritsar, DELHI</option>
                                    <option value="Composite Hospital ITBP Chandigarh, DELHI">Composite Hospital ITBP Chandigarh, DELHI</option>
                                    <option value="Frontier Hospital Leh ITBP, DELHI">Frontier Hospital Leh ITBP, DELHI</option>
                                    <option value="AIIMS Kalyani, DELHI">AIIMS Kalyani, DELHI</option>
                                    <option value="BSF Composite Hospital Kadamtala Siliguri WB, DELHI">BSF Composite Hospital Kadamtala Siliguri WB, DELHI</option>
                                    <option value="CGHS Allahabad, DELHI">CGHS Allahabad, DELHI</option>
                                    <option value="CGHS Bhubaneswar Odisha, DELHI">CGHS Bhubaneswar Odisha, DELHI</option>
                                    <option value="CGHS Kolkata, DELHI">CGHS Kolkata, DELHI</option>
                                    <option value="Central Govt Health Scheme Bhopal, DELHI">Central Govt Health Scheme Bhopal, DELHI</option>
                                    <option value="Central Govt Health Scheme Jabalpur, DELHI">Central Govt Health Scheme Jabalpur, DELHI</option>
                                    <option value="Central Govt Health Scheme Pune, DELHI">Central Govt Health Scheme Pune, DELHI</option>
                                    <option value="Base Hospital ITBP,Tigri Camp, DELHI">Base Hospital ITBP,Tigri Camp, DELHI</option>
                                    <option value="CGHS,Kanpur, DELHI">CGHS,Kanpur, DELHI</option>
                                    <option value="CGHS,Meerut, DELHI">CGHS,Meerut, DELHI</option>
                                    <option value="CGHS,New Delhi, DELHI">CGHS,New Delhi, DELHI</option>
                                    <option value="HLL - Delhi, DELHI">HLL - Delhi, DELHI</option>
                                    <option value="HLL- Rohtak, ROHTAK">HLL- Rohtak, ROHTAK</option>
                                    <option value="Central Railways, MUMBAI">Central Railways, MUMBAI</option>
                                    <option value="NE Railways, LUCKNOW">NE Railways, LUCKNOW</option>
                                    <option value="South Eastern Railways, HOWRAH">South Eastern Railways, HOWRAH</option>
                                    <option value="CLW, HOWRAH">CLW, HOWRAH</option>
                                    <option value="Eastern Railways - HOWRAH, HOWRAH">Eastern Railways - HOWRAH, HOWRAH</option>
                                    <option value="Southern Railways, CHENNAI">Southern Railways, CHENNAI</option>
                                    <option value="South Central Railways, HYDERABAD">South Central Railways, HYDERABAD</option>
                                    <option value="North Central Railways, LUCKNOW">North Central Railways, LUCKNOW</option>
                                    <option value="RDSO, LUCKNOW">RDSO, LUCKNOW</option>
                                    <option value="ESIC Hospital Lucknow, LUCKNOW">ESIC Hospital Lucknow, LUCKNOW</option>
                                    <option value="ESIC,Employees State Insurance, PUNE">ESIC,Employees State Insurance, PUNE</option>
                                    <option value="ESIC DEPUTY MEDICAL SUPRITENDENT ES, FARIDABAD">ESIC DEPUTY MEDICAL SUPRITENDENT ES, FARIDABAD</option>
                                    <option value="ESIC,The Medical Suprintendent, HYDERABAD">ESIC,The Medical Suprintendent, HYDERABAD</option>
                                    <option value="KGMU, LUCKNOW">KGMU, LUCKNOW</option>
                                    <option value="WUS - DELHI, DELHI">WUS - DELHI, DELHI</option>
                                    <option value="SBI - Mumbai, MUMBAI">SBI - Mumbai, MUMBAI</option>
                                    <option value="DHS WB, KOLKATA">DHS WB, KOLKATA</option>
                                    <option value="OIL INDIA LIMITED, TINSUKIA">OIL INDIA LIMITED, TINSUKIA</option>
                                    <option value="TSRTC, HYDERABAD">TSRTC, HYDERABAD</option>
                                    <option value="HLL - AMRIT PHARMACY Secunderabad, HYDERABAD">HLL - AMRIT PHARMACY Secunderabad, HYDERABAD</option>
                                    <option value="KARNATAKA STATE CO-OP CONSUMERS, BANGALORE">KARNATAKA STATE CO-OP CONSUMERS, BANGALORE</option>
                                    <option value="KERALA STATE COOP.CONS.LTD., TRIVANDRUM">KERALA STATE COOP.CONS.LTD., TRIVANDRUM</option>
                                    <option value="KERALA STATE CO-OP.CONS.FED. LTD., PALAKKAD">KERALA STATE CO-OP.CONS.FED. LTD., PALAKKAD</option>
                                    <option value="KERALA STATE COOP CON.FED.LTD, COCHIN">KERALA STATE COOP CON.FED.LTD, COCHIN</option>
                                    <option value="KERALA STATE CO-OPERATIVE CONSUMER', KANNUR">KERALA STATE CO-OPERATIVE CONSUMER', KANNUR</option>
                                    <option value="KERALA ST.COOP.CONS.FED.LTD., CALICUT">KERALA ST.COOP.CONS.FED.LTD., CALICUT</option>
                                    <option value="SUPPLYCO REGIONAL MEDICAL WHOLSALE, TRIVANDRUM">SUPPLYCO REGIONAL MEDICAL WHOLSALE, TRIVANDRUM</option>
                                    <option value="RAMPURHAT HEALTH DISTRICT HOSPITAL, RAMPURHAT">RAMPURHAT HEALTH DISTRICT HOSPITAL, RAMPURHAT</option>
                                    <option value="AMRIT PHARMACY- SECL MANENDRAGARH, BILASPUR">AMRIT PHARMACY- SECL MANENDRAGARH, BILASPUR</option>
                                    <option value="HLL - Bhopal, BHOPAL">HLL - Bhopal, BHOPAL</option>
                                    <option value="BPS Medical Collage, CHANDIGARH">BPS Medical Collage, CHANDIGARH</option>
                                    <option value="MH Ambala, DELHI">MH Ambala, DELHI</option>
                                    <option value="CH (SC) Pune, DELHI">CH (SC) Pune, DELHI</option>
                                    <option value="MH VARANSAI, DELHI">MH VARANSAI, DELHI</option>
                                    <option value="MH Chennai, DELHI">MH Chennai, DELHI</option>
                                    <option value="Base Hospital Delhi, DELHI">Base Hospital Delhi, DELHI</option>
                                    <option value="Mh Gaya, DELHI">Mh Gaya, DELHI</option>
                                    <option value="MH 151 base, DELHI">MH 151 base, DELHI</option>
                                    <option value="Command Hospital (Air Force,) Bangalore, DELHI">Command Hospital (Air Force,) Bangalore, DELHI</option>
                                    <option value="Military Hospital, Chennai, DELHI">Military Hospital, Chennai, DELHI</option>
                                    <option value="Military Hospital, Ranikhet, DELHI">Military Hospital, Ranikhet, DELHI</option>
                                    <option value="Military Hospital, Varanasi, DELHI">Military Hospital, Varanasi, DELHI</option>
                                    <option value="Mh ashwini, DELHI">Mh ashwini, DELHI</option>
                                    <option value="Commandant, Command Hospital, JALANDHAR">Commandant, Command Hospital, JALANDHAR</option>
                                    <option value="CGHS Sector 16 Panchkula, DELHI">CGHS Sector 16 Panchkula, DELHI</option>
                                    <option value="CGHS Sector 29 C Chandigarh, DELHI">CGHS Sector 29 C Chandigarh, DELHI</option>
                                    <option value="CGHS Wellness Center Ambala, DELHI">CGHS Wellness Center Ambala, DELHI</option>
                                    <option value="CGHS Wellness Center-1, Jammu, DELHI">CGHS Wellness Center-1, Jammu, DELHI</option>
                                    <option value="CGHS Patna, DELHI">CGHS Patna, DELHI</option>
                                    <option value="The Additional Director CGHS Seminary Hills Nagpur Maharastra, DELHI">The Additional Director CGHS Seminary Hills Nagpur Maharastra, DELHI</option>
                                    <option value="CMS ESI (MB) SCHEME, WEST BENGAL, Kolkata">CMS ESI (MB) SCHEME, WEST BENGAL, Kolkata</option>
                                    <option value="CGHS Kolkata, KOLKATA">CGHS Kolkata, KOLKATA</option>
                                    <option value="AMRIT PHARMACY (A DIVISON OF HLL L, PATNA)">AMRIT PHARMACY (A DIVISON OF HLL L, PATNA)</option>
                                    <option value="AMRIT PHARMACY (A DIVISON OF HLL L, GWALIOR)">AMRIT PHARMACY (A DIVISON OF HLL L, GWALIOR)</option>
                                    <option value="HLL - Jamshedpur, JUGSALAI">HLL - Jamshedpur, JUGSALAI</option>
                                    <option value="HLL - Orrisa, ROURKELA">HLL - Orrisa, ROURKELA</option>
                                    <option value="THE MEDICAL SUPERINTENDENT, ROURKELA">THE MEDICAL SUPERINTENDENT, ROURKELA</option>
                                    <option value="THE MEDICAL SUPERINTENDENT OF AGMC AND GBP HOSPITAL, AGARTALA">THE MEDICAL SUPERINTENDENT OF AGMC AND GBP HOSPITAL, AGARTALA</option>
                                    <option value="INDIAN INSTITUTE OF TECHNOLOGY, DELHI">INDIAN INSTITUTE OF TECHNOLOGY, DELHI</option>
                                    <option value="Aviaition Research Centre, DELHI">Aviaition Research Centre, DELHI</option>
                                    <option value="CENTRAL MEDICINE STORES-KURNOOL, GUNTUR">CENTRAL MEDICINE STORES-KURNOOL, GUNTUR</option>
                                    <option value="CENTRAL MEDICINE STORES-VISAKAPATNA, GUNTUR">CENTRAL MEDICINE STORES-VISAKAPATNA, GUNTUR</option>
                                    <option value="OFFICE OF THE ADMINISTRATIVE MEDICA, GUWAHATI">OFFICE OF THE ADMINISTRATIVE MEDICA, GUWAHATI</option>
                                    <option value="ESIC,MEDICAL OFFICER (Stores), GUWAHATI">ESIC,MEDICAL OFFICER (Stores), GUWAHATI</option>
                                    <option value="PO5120625000445, CHENNAI">PO5120625000445, CHENNAI</option>
                                    <option value="po5130625000172, TIRUNELVELI">po5130625000172, TIRUNELVELI</option>
                                    <option value="Composite Hospital CRPF Ismailgangj Allahabad, DELHI">Composite Hospital CRPF Ismailgangj Allahabad, DELHI</option>
                                </select>
                            </div>
                        </div>

                        {{-- New Institution (conditional) --}}
                        <div class="mb-3 row" id="new_institution_wrapper" style="display:none;">
                            <label class="col-md-4 col-form-label">Enter New Institution</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="new_institution" name="new_institution" placeholder="Enter new institution name">
                            </div>
                        </div>

                        {{-- UDCA Rx/Month --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">UDCA Rx/Month <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="udca_rx_per_month" placeholder="UDCA Rx/Month" min="0" max="200" step="0.01">
                            </div>
                        </div>

                        {{-- Sema Rx/Month --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Sema Rx/Month <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="sema_rx_prer_month" placeholder="Sema Rx/Month" min="0" max="200" step="0.01">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Bilypsa Rx/Month <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="bilypsa_rx_per_month"
                                       placeholder="Bilypsa Rx/Month" min="0" max="200" step="0.01">
                            </div>
                        </div>

                        {{-- Linvas Rx/Month --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Linvas Rx/Month <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="linvas_rx_per_month"
                                       placeholder="Linvas Rx/Month" min="0" max="200" step="0.01">
                            </div>
                        </div>

                        {{-- Vorxar Rx/Month --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Vorxar Rx/Month <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="vorxar_rx_per_month"
                                       placeholder="Vorxar Rx/Month" min="0" max="200" step="0.01">
                            </div>
                        </div>


                        {{-- Other Saro Rx/Month --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Other Saro Rx/Month <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="other_saro_rm_per_month" placeholder="Other Saro Rx/Month" min="0" max="200" step="0.01">
                            </div>
                        </div>

                        {{-- Total Business Value --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Total Business of above competitors in value <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="total_business_value" placeholder="Total Business in value" min="0" max="10" step="0.01">
                            </div>
                        </div>

                        {{-- Planned for Conversion (conditional - Non Rxber) --}}
                        <div class="mb-3 row" id="planned_for_conversion_wrapper" style="display:none;">
                            <label class="col-md-4 col-form-label">Planned for Conversion in the Month of</label>
                            <div class="col-md-8">
                                <select class="form-control" name="planned_for_conversition" id="planned_for_conversition">
                                    <option value="">Planned for Conversion in the Month of</option>
                                    <option value="April'26">April'26</option>
                                    <option value="May'26">May'26</option>
                                    <option value="June'26">June'26</option>
                                    <option value="July'26">July'26</option>
                                    <option value="August'26">August'26</option>
                                    <option value="September'26">September'26</option>
                                </select>
                            </div>
                        </div>

                        {{-- Incremental Lipaglyn Business Value --}}
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Incremental Lipaglyn Business Value <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="incremental_lipaglyn_busines" placeholder="Incremental Lipaglyn Business Value" min="0" max="10" step="0.01">
                            </div>
                        </div>

                        <div class="row">
                            <div class="wd-sl-modalbtn">
                                <button type="submit" class="btn btn-primary waves-effect waves-light" id="save_changes">Submit</button>
                                <button type="button" data-dismiss="modal" class="btn btn-outline-secondary waves-effect">Close</button>
                            </div>
                        </div>

                    </div>{{-- /modal-body --}}
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    {{-- ✅ All via CDN - no local asset dependency --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script>
        // ─── Modal Open / Close ────────────────────────────────────────────────────
        function openAddModal() {
            resetForm();
            $('#modalTitle').text('Add New Doctor');
            $('#doctor_form').attr('action', '{{ route("portal.doctors.store") }}');
            $('#method_field').html('');
            $('#doctorModal').modal({ backdrop: true, keyboard: true });
            $('#doctorModal').modal('show');
        }

        function openEditModal(id) {
            resetForm();
            $('#modalTitle').text('Edit Doctor');

            let editUrl = "{{ route('portal.doctors.editData', ':id') }}";
            editUrl = editUrl.replace(':id', id);

            $.get(editUrl, function (data) {
                fillForm(data);

                let updateUrl = "{{ route('portal.doctors.update', ':id') }}";
                updateUrl = updateUrl.replace(':id', id);

                $('#doctor_form').attr('action', updateUrl);
                $('#method_field').html(''); // ❌ PUT hata diya

                $('#doctorModal').modal('show');
            });
        }

        // ─── Reset Form ────────────────────────────────────────────────────────────
        function resetForm() {
            var $form = $('#doctor_form');
            $form[0].reset();
            $('#govt_dropdown_wrapper').hide();
            $('#new_institution_wrapper').hide();
            $('#planned_for_conversion_wrapper').hide();

            // Reset selectpicker safely
            if ($.fn.selectpicker) {
                try { $('#govt_dropdown').val('').selectpicker('refresh'); } catch(e) {}
            }

            // Clear radio buttons
            $('input[name="kol_kbl"]').prop('checked', false);
            $('input[name="inst_dr"]').prop('checked', false);

            // Clear jquery-validate errors
            if ($form.data('validator')) {
                $form.validate().resetForm();
            }
            $form.find('.form-control').removeClass('form-validation-error');
            $form.find('label.error').remove();
        }

        // ─── Fill Form for Edit ────────────────────────────────────────────────────
        function fillForm(d) {
            $('[name="msl_code"]').val(d.msl_code);
            $('[name="name"]').val(d.name);
            $('[name="specialization"]').val(d.specialization);
            $('[name="lipaglyn_rx_br_type"]').val(d.lipaglyn_rx_br_type).trigger('change');
            $('[name="avg_lipaglyn_pr_month"]').val(d.avg_lipaglyn_pr_month);
            $('[name="actual_speciality"]').val(d.actual_speciality);
            $('[name="Diabetes_patients_day"]').val(d.Diabetes_patients_day);
            $('[name="bilypsa_rx_per_month"]').val(d.bilypsa_rx_per_month);
            $('[name="linvas_rx_per_month"]').val(d.linvas_rx_per_month);
            $('[name="vorxar_rx_per_month"]').val(d.vorxar_rx_per_month);

            if (d.kol_kbl) {
                $('input[name="kol_kbl"][value="' + d.kol_kbl + '"]').prop('checked', true);
            }
            if (d.inst_dr) {
                $('input[name="inst_dr"][value="' + d.inst_dr + '"]').prop('checked', true).trigger('change');
            }
            if (d.govt_dropdown) {
                var optExists = $('#govt_dropdown option[value="' + d.govt_dropdown + '"]').length > 0;
                if (optExists) {
                    $('#govt_dropdown').val(d.govt_dropdown);
                    if ($.fn.selectpicker) $('#govt_dropdown').selectpicker('refresh');
                    $('#new_institution_wrapper').hide();
                } else {
                    $('#govt_dropdown').val('new');
                    if ($.fn.selectpicker) $('#govt_dropdown').selectpicker('refresh');
                    $('#new_institution_wrapper').show();
                    $('#new_institution').val(d.govt_dropdown);
                }
            }
            $('[name="udca_rx_per_month"]').val(d.udca_rx_per_month);
            $('[name="sema_rx_prer_month"]').val(d.sema_rx_prer_month);
            $('[name="other_saro_rm_per_month"]').val(d.other_saro_rm_per_month);
            $('[name="total_business_value"]').val(d.total_business_value);
            $('[name="planned_for_conversition"]').val(d.planned_for_conversition);
            $('[name="incremental_lipaglyn_busines"]').val(d.incremental_lipaglyn_busines);
        }

        // ─── Document Ready ────────────────────────────────────────────────────────
        $(document).ready(function () {

            // ✅ Add button click
            $('#btnAddDoctor').on('click', function () {
                openAddModal();
            });

            // ✅ Toggle Planned Conversion field
            function toggleConversionField() {
                if ($('#lipaglyn_rx_br_type').val() === 'Non Rxber') {
                    $('#planned_for_conversion_wrapper').show();
                } else {
                    $('#planned_for_conversion_wrapper').hide();
                    $('#planned_for_conversition').val('');
                }
            }
            $('#lipaglyn_rx_br_type').on('change', toggleConversionField);

            // ✅ Toggle Govt Dropdown based on inst_dr radio
            $(document).on('change', 'input[name="inst_dr"]', function () {
                var selected = $(this).val();
                if (selected === 'CGHS' || selected === 'ESI' || selected === 'OTHER GOVT') {
                    $('#govt_dropdown_wrapper').show();
                    if (selected === 'CGHS' || selected === 'ESI') {
                        $('#govt_dropdown').attr('required', true);
                    } else {
                        $('#govt_dropdown').removeAttr('required');
                    }
                } else {
                    $('#govt_dropdown_wrapper').hide();
                    $('#govt_dropdown').removeAttr('required').val('');
                    if ($.fn.selectpicker) $('#govt_dropdown').selectpicker('refresh');
                }
            });

            // ✅ New Institution toggle
            $('#govt_dropdown').on('change', function () {
                if ($(this).val() === 'new') {
                    $('#new_institution_wrapper').show();
                } else {
                    $('#new_institution_wrapper').hide();
                    $('#new_institution').val('');
                }
            });

            // ✅ Init Selectpicker
            if ($.fn.selectpicker) {
                $('#govt_dropdown').selectpicker();
            }

            // ✅ DataTable Init
            if ($.fn.DataTable) {
                $('#listResults').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [[0, 'DESC']],
                    ajax: '{{ route("portal.doctors.listing") }}',
                    columns: [
                        { data: 'id',                        searchable: false, orderable: true },
                        { data: 'name',                      orderable: true },
                        { data: 'msl_code',                  searchable: false, orderable: true },
                        { data: 'specialization',            orderable: true },
                        { data: 'lipaglyn_rx_br_type',       orderable: true },
                        { data: 'everage_lipaglyn_pr_month', orderable: true },
                        { data: 'action',                    searchable: false, orderable: false }
                    ],
                    // Ye function har baar table load hone par run hoga
                    // Ye function har baar table load hone par run hoga
                    drawCallback: function(settings) {
                        // Edit button ko icon me convert karna
                        $('#listResults tbody tr td:last-child').find('button, a').each(function() {
                            var text = $(this).text().trim().toLowerCase();
                            // 'edit' text ya icon find karke replace karega
                            if(text === 'edit' || $(this).find('.fa-edit').length > 0) {
                                $(this).html('<i class="fas fa-edit"></i>')
                                       // Purani sabhi possible classes (jaise btn-warning yellow ke liye) hata denge
                                       .removeClass('btn btn-sm btn-primary btn-info btn-warning')
                                       .addClass('btn-icon btn-edit')
                                       .attr('title', 'Edit');
                            }
                        });

                        // Delete button ko icon me convert karna
                        $('#listResults tbody tr td:last-child').find('button, a').each(function() {
                            var text = $(this).text().trim().toLowerCase();
                            if(text === 'delete' || $(this).find('.fa-trash').length > 0 || $(this).find('.fa-trash-alt').length > 0) {
                                $(this).html('<i class="fas fa-trash-alt"></i>')
                                       .removeClass('btn btn-sm btn-danger btn-warning')
                                       .addClass('btn-icon btn-delete')
                                       .attr('title', 'Delete');
                            }
                        });
                    }
                });
            }

            // ✅ Validation Rules
            if ($.fn.validate) {
                $('#doctor_form').validate({
                    rules: {
                        name:                         { required: true },
                        specialization:               { required: true },
                        lipaglyn_rx_br_type:          { required: true },
                        avg_lipaglyn_pr_month:        { required: true, number: true, min: 0, max: 10 },
                        actual_speciality:            { required: true },
                        Diabetes_patients_day:        { required: true },
                        kol_kbl:                      { required: true },
                        inst_dr:                      { required: true },
                        udca_rx_per_month:            { required: true, min: 0, max: 200, number: true },
                        sema_rx_prer_month:           { required: true, min: 0, max: 200, number: true },
                        other_saro_rm_per_month:      { required: true, min: 0, max: 200, number: true },
                        total_business_value:         { required: true, number: true, min: 0, max: 10 },
                        incremental_lipaglyn_busines: { required: true, number: true, min: 0, max: 10 },
                        bilypsa_rx_per_month: { required: true, min: 0, max: 200, number: true },
                        linvas_rx_per_month:  { required: true, min: 0, max: 200, number: true },
                        vorxar_rx_per_month:  { required: true, min: 0, max: 200, number: true },

                    },
                    errorClass: 'error',
                    errorElement: 'label',
                    highlight: function (element) {
                        $(element).addClass('form-validation-error');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('form-validation-error');
                    },
                    errorPlacement: function (error, element) {
                        if (element.attr('type') === 'radio') {
                            error.css('width', '100%').appendTo(element.closest('.col-md-8'));
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });
            }

            // ✅ Form Submit
            $('#doctor_form').on('submit', function (e) {
                if ($.fn.validate && !$(this).valid()) {
                    e.preventDefault();
                    return false;
                }
                if (typeof addOverlay === 'function') addOverlay();
                $('input[type=submit], button[type=submit]').prop('disabled', true);
                return true;
            });

            // ✅ Re-enable buttons if modal closes without submit (e.g. close btn)
            $('#doctorModal').on('hidden.bs.modal', function () {
                $('input[type=submit], button[type=submit]').prop('disabled', false);
                if (typeof removeOverlay === 'function') removeOverlay();
            });

        });
    </script>
@endsection
