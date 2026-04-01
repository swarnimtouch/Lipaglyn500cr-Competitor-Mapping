@extends('layouts.master_portal')

@section('title', 'Add Doctor – MR Portal')
@section('page_title', 'Add New Doctor')

@section('header_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <style>
        /* ── bootstrap-select dark override ───────────── */
        .bootstrap-select .dropdown-toggle {
            background: var(--bg) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
            border-radius: 8px !important;
            padding: 10px 13px !important;
            font-size: 14px !important;
            font-family: 'DM Sans', sans-serif !important;
            box-shadow: none !important;
            width: 100% !important;
        }
        .bootstrap-select .dropdown-toggle:focus,
        .bootstrap-select.show .dropdown-toggle {
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px rgba(47,111,255,0.12) !important;
            outline: none !important;
        }
        .bootstrap-select .dropdown-menu {
            background: var(--surface) !important;
            border: 1px solid var(--border) !important;
            border-radius: 10px !important;
            margin-top: 4px !important;
        }
        .bootstrap-select .dropdown-item {
            color: var(--text) !important;
            font-size: 13.5px !important;
            padding: 8px 14px !important;
            font-family: 'DM Sans', sans-serif !important;
        }
        .bootstrap-select .dropdown-item:hover,
        .bootstrap-select .dropdown-item.active {
            background: rgba(47,111,255,0.12) !important;
            color: var(--accent) !important;
        }
        .bootstrap-select .bs-searchbox input {
            background: var(--bg) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
            border-radius: 6px !important;
            font-size: 13px !important;
        }
        .bootstrap-select > select { display: none !important; }
        .filter-option-inner-inner { color: var(--text) !important; }

        /* ── Section headings ─────────────────────────── */
        .form-section { margin-bottom: 30px; }
        .form-section-title {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 1.3px;
            text-transform: uppercase;
            color: var(--accent);
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
        }

        /* ── Radio pills ──────────────────────────────── */
        .radio-group { display: flex; flex-wrap: wrap; gap: 10px; padding-top: 2px; }
        .radio-pill { position: relative; }
        .radio-pill input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .radio-pill label {
            display: inline-flex;
            align-items: center;
            padding: 7px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid var(--border);
            color: var(--muted);
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
            background: var(--bg);
        }
        .radio-pill input[type="radio"]:checked + label {
            background: rgba(47,111,255,0.15);
            border-color: var(--accent);
            color: var(--accent);
        }
        .radio-pill label:hover { border-color: var(--accent); color: var(--text); }

        /* ── 3-col grid ───────────────────────────────── */
        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        /* ── Validation ───────────────────────────────── */
        span.form-validation-error-text {
            display: block;
            color: #ff7a93;
            font-size: 12px;
            margin-top: 5px;
        }
        .form-validation-error { border-color: var(--danger) !important; }

        /* ── Collapsible wrappers ─────────────────────── */
        #govt_dropdown_wrapper,
        #new_institution_wrapper,
        #planned_for_conversion_wrapper { display: none; }

        /* ── Actions bar ──────────────────────────────── */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 22px;
            border-top: 1px solid var(--border);
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .form-row-3 { grid-template-columns: 1fr; }
            .form-row   { grid-template-columns: 1fr; }
        }
    </style>
@endsection

@section('content')

    <div style="max-width:820px;">

        {{-- Page header --}}
        <div class="section-header" style="margin-bottom:22px;">
            <div>
                <h3 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;">Add New Doctor</h3>
                <p style="color:var(--muted);font-size:13px;margin-top:4px;">Fill in the details below to add a doctor to your list.</p>
            </div>
            <a href="{{ route('portal.doctors.index') }}" class="btn btn-outline">← Back</a>
        </div>

        <div class="card">
            <form name="doctor_form" id="doctor_form" method="POST" action="{{ route('portal.doctors.store') }}">
                @csrf

                {{-- ══════════════════════════════════════
                     1. BASIC INFO
                ══════════════════════════════════════ --}}
                <div class="form-section">
                    <div class="form-section-title">Basic Information</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><span class="req">*</span> Doctor Name</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}" placeholder="Dr. Full Name" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Dr. UID
                                <span style="color:var(--muted);font-weight:400;font-size:11px;">(optional)</span>
                            </label>
                            <input type="text" name="msl_code" class="form-control"
                                   value="{{ old('msl_code') }}" maxlength="11"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                   placeholder="Numeric UID">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><span class="req">*</span> Speciality</label>
                            <input type="text" name="specialization" class="form-control"
                                   value="{{ old('specialization') }}" placeholder="e.g. MBBS, MD (Endo)">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><span class="req">*</span> Tag As (Actual Speciality)</label>
                            <select name="actual_speciality" class="form-control">
                                <option value="">— Select —</option>
                                @foreach([
                                    'Cons. Endocrinologist','Cons. Diabetologist',
                                    'Interventional Cardiologist','Cons. Cardiologist',
                                    'Cons. Nephrologist','Cons. Physician',
                                    'General Physician','Others',
                                ] as $opt)
                                    <option value="{{ $opt }}" {{ old('actual_speciality')==$opt?'selected':'' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><span class="req">*</span> Diabetes Patients / Day</label>
                            <input type="number" name="Diabetes_patients_day" class="form-control"
                                   value="{{ old('Diabetes_patients_day') }}" placeholder="e.g. 20" min="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><span class="req">*</span> KOL / KBL</label>
                            <div class="radio-group">
                                @foreach(['KOL','KBL','OTHER'] as $opt)
                                    <div class="radio-pill">
                                        <input type="radio" name="kol_kbl" id="kk_{{ $opt }}" value="{{ $opt }}"
                                            {{ old('kol_kbl')==$opt?'checked':'' }}>
                                        <label for="kk_{{ $opt }}">{{ $opt }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════════
                     2. INSTITUTION
                ══════════════════════════════════════ --}}
                <div class="form-section">
                    <div class="form-section-title">Institution Details</div>

                    <div class="form-group">
                        <label class="form-label"><span class="req">*</span> Institution Type</label>
                        <div class="radio-group">
                            @foreach(['CGHS','ESI','OTHER GOVT','Non Govt Dr'] as $opt)
                                <div class="radio-pill">
                                    <input type="radio" name="inst_dr" id="id_{{ Str::slug($opt) }}" value="{{ $opt }}"
                                        {{ old('inst_dr')==$opt?'checked':'' }}>
                                    <label for="id_{{ Str::slug($opt) }}">{{ $opt }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group" id="govt_dropdown_wrapper">
                        <label class="form-label">Select Institution Name</label>
                        <select class="form-control selectpicker" id="govt_dropdown"
                                name="govt_dropdown" data-live-search="true">
                            <option value="">— Search / Select —</option>
                            <option value="new">➕ New Institution (type below)</option>
                            @foreach([
                                'North Western Railway, DELHI',
                                'Sir Gangaram Hospital - Delhi, DELHI',
                                'Jhilmil ESI, DELHI',
                                'AMRIT PHARMACY (A DIVISON OF HLL L, HOWRAH)',
                                'NF Railways, GUWAHATI',
                                'CGHS Bangalore, DELHI',
                                'NMDC BACHELI, BILASPUR',
                                'HLL - Jharkhand, JUGSALAI',
                                'East Central Railways, HAJIPUR',
                                'BHU VARANASI, Varanasi',
                                'In-charge (Stores),ESIC Hospital, PATNA',
                                'CGHS Chennai, DELHI',
                                'CRPF Avadi, DELHI',
                                'Deputy Director, Department of Atomic Energy, DELHI',
                                'Assam Rifles Multispeciality Hospital  Manipur , DELHI',
                                'CGHS Shillong, DELHI',
                                'DIG Capfs CH Patgaon Kamrup, DELHI',
                                'HQ DG Assam Rifles Shillong, DELHI',
                                'NDRF 1st bn Patgaon, DELHI',
                                '177 BN CRPF, DELHI','47 BN ITBP, DELHI','79 BN CRPF, DELHI',
                                'CGHS SHIMLA , DELHI','CGHS Srinagar , DELHI',
                                'CGHS Wellness Center Amritsar, DELHI',
                                'Composite Hospital ITBP Chandigarh, DELHI',
                                'Frontier Hospital Leh ITBP, DELHI',
                                'AIIMS Kalyani, DELHI','Aviaition Research Centre, DELHI',
                                'BSF Composite Hospital Kadamtala Siliguri WB, DELHI',
                                'CGHS Allahabad, DELHI','CGHS Bhubaneswar Odisha, DELHI','CGHS Kolkata, DELHI',
                                'Composite Hospital CRPF Ismailgangj Allahabad, DELHI',
                                'Central Govt Health Scheme Bhopal, DELHI',
                                'Central Govt Health Scheme Jabalpur, DELHI',
                                'Central Govt Health Scheme Pune, DELHI',
                                'Base Hospital ITBP,Tigri Camp, DELHI',
                                'CGHS,Kanpur, DELHI','CGHS,Meerut, DELHI','CGHS,New Delhi, DELHI',
                                'HLL - Delhi, DELHI','HLL- Rohtak, ROHTAK',
                                'Central Railways, MUMBAI',
                                'CENTRAL MEDICINE STORES-KURNOOL, GUNTUR',
                                'CENTRAL MEDICINE STORES-VISAKAPATNA, GUNTUR',
                                'NE Railways, LUCKNOW',
                                'OFFICE OF THE ADMINISTRATIVE MEDICA, GUWAHATI',
                                'ESIC,MEDICAL OFFICER (Stores), GUWAHATI',
                                'HLL - Orrisa, ROURKELA',
                                'South Eastern Railways, HOWRAH','CLW, HOWRAH',
                                'SUPPLYCO REGIONAL MEDICAL WHOLSALE, TRIVANDRUM',
                                'AMRIT PHARMACY  (A DIVISON OF HLL L, GWALIOR)',
                                'HLL - Jamshedpur, JUGSALAI',
                                'THE MEDICAL SUPERINTENDENT OF AGMC AND GBP HOSPITAL, AGARTALA',
                                'INDIAN INSTITUTE OF TECHNOLOGY, DELHI',
                                'Commandant, Command Hospital, JALANDHAR',
                                'SBI - Mumbai, MUMBAI','HLL - Bhopal, BHOPAL',
                                'BPS Medical Collage, CHANDIGARH',
                                'MH Ambala, DELHI','CH (SC) Pune, DELHI','MH Chennai, DELHI',
                                'Base Hospital Delhi, DELHI','MH 151 base, DELHI',
                                'Southern Railways, CHENNAI','ESIC Hospital Lucknow, LUCKNOW',
                                'CGHS Sector 16 Panchkula , DELHI','CGHS Sector 29 C Chandigarh , DELHI',
                                'CGHS Wellness Center Ambala, DELHI','CGHS Wellness Center-1, Jammu, DELHI',
                                'CGHS Patna, DELHI',
                                'The Additional Director CGHS Seminary Hills Nagpur Maharastra, DELHI',
                                'Eastern Railways - HOWRAH, HOWRAH','DHS WB, KOLKATA',
                                'OIL INDIA LIMITED, TINSUKIA','TSRTC, HYDERABAD',
                                'HLL - AMRIT  PHARMACY Secunderabad, HYDERABAD',
                                'North Central Railways, LUCKNOW','RDSO, LUCKNOW',
                                'KERALA STATE COOP.CONS.LTD., TRIVANDRUM',
                                'THE MEDICAL SUPERINTENDENT, ROURKELA',
                                'RAMPURHAT HEALTH DISTRICT HOSPITAL, RAMPURHAT',
                                'AMRIT PHARMACY- SECL MANENDRAGARH, BILASPUR',
                                'KERALA STATE CO-OP.CONS.FED. LTD., PALAKKAD',
                                'KERALA STATE COOP CON.FED.LTD, COCHIN',
                                "KERALA STATE CO-OPERATIVE CONSUMER', KANNUR",
                                'KERALA ST.COOP.CONS.FED.LTD., CALICUT',
                                'AMRIT PHARMACY (A DIVISON OF HLL L, PATNA)',
                                'South Central Railways, HYDERABAD','CGHS Kolkata , KOLKATA',
                                'Military Hospital, Varanasi, DELHI',
                                'CMS ESI (MB) SCHEME, WEST BENGAL, Kolkata',
                                'ESIC,Employees State Insurance, PUNE',
                                'ESIC DEPUTY MEDICAL SUPRITENDENT ES, FARIDABAD',
                                'ESIC,The Medical Suprintendent, HYDERABAD',
                                'Command Hospital (Air Force,) Bangalore, DELHI',
                                'Military Hospital, Chennai, DELHI','Military Hospital, Ranikhet, DELHI',
                                'Mh ashwini, DELHI','KGMU, LUCKNOW',
                                'KARNATAKA STATE CO-OP CONSUMERS, BANGALORE','WUS - DELHI, DELHI',
                            ] as $inst)
                                <option value="{{ $inst }}" {{ old('govt_dropdown')==$inst?'selected':'' }}>{{ $inst }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="new_institution_wrapper">
                        <label class="form-label">Enter New Institution Name</label>
                        <input type="text" name="new_institution" id="new_institution" class="form-control"
                               value="{{ old('new_institution') }}" placeholder="Type institution name…">
                    </div>
                </div>

                {{-- ══════════════════════════════════════
                     3. LIPAGLYN
                ══════════════════════════════════════ --}}
                <div class="form-section">
                    <div class="form-section-title">Lipaglyn Details</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><span class="req">*</span> Lipaglyn Rxbr Type</label>
                            <select name="lipaglyn_rx_br_type" id="lipaglyn_rx_br_type" class="form-control">
                                <option value="">— Select —</option>
                                @foreach(['Emerging O.Rxber','Occasional Rxber','Rxber','Non Rxber','Regular Rxber','Wall Drs'] as $opt)
                                    <option value="{{ $opt }}" {{ old('lipaglyn_rx_br_type')==$opt?'selected':'' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><span class="req">*</span> Avg Lipaglyn Business / Month</label>
                            <input type="number" name="avg_lipaglyn_pr_month" class="form-control"
                                   value="{{ old('avg_lipaglyn_pr_month') }}"
                                   placeholder="0.00 – 10.00" min="0" max="10" step="0.01">
                        </div>
                    </div>

                    {{-- Only visible when Non Rxber --}}
                    <div class="form-group" id="planned_for_conversion_wrapper">
                        <label class="form-label">Planned for Conversion — Month</label>
                        <select name="planned_for_conversition" id="planned_for_conversition" class="form-control">
                            <option value="">— Select Month —</option>
                            @foreach(["September'25","October'25","November'25","December'25","January'26","February'26"] as $opt)
                                <option value="{{ $opt }}" {{ old('planned_for_conversition')==$opt?'selected':'' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><span class="req">*</span> Incremental Lipaglyn Business Value</label>
                        <input type="number" name="incremental_lipaglyn_busines" class="form-control"
                               value="{{ old('incremental_lipaglyn_busines') }}"
                               placeholder="0.00 – 10.00" min="0" max="10" step="0.01">
                    </div>
                </div>

                {{-- ══════════════════════════════════════
                     4. COMPETITOR Rx / MONTH
                ══════════════════════════════════════ --}}
                <div class="form-section">
                    <div class="form-section-title">Competitor Rx / Month</div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label"><span class="req">*</span> UDCA Rx / Month</label>
                            <input type="number" name="udca_rx_per_month" class="form-control"
                                   value="{{ old('udca_rx_per_month') }}"
                                   placeholder="0 – 200" min="0" max="200" step="0.01">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><span class="req">*</span> Sema Rx / Month</label>
                            <input type="number" name="sema_rx_prer_month" class="form-control"
                                   value="{{ old('sema_rx_prer_month') }}"
                                   placeholder="0 – 200" min="0" max="200" step="0.01">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><span class="req">*</span> Other Saro Rx / Month</label>
                            <input type="number" name="other_saro_rm_per_month" class="form-control"
                                   value="{{ old('other_saro_rm_per_month') }}"
                                   placeholder="0 – 200" min="0" max="200" step="0.01">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bilypsa Rx / Month</label>
                            <input type="number" name="bilypsa_rx_per_month" class="form-control"
                                   value="{{ old('bilypsa_rx_per_month') }}"
                                   placeholder="0 – 200" min="0" max="200" step="0.01">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Linvas Rx / Month</label>
                            <input type="number" name="linvas_rx_per_month" class="form-control"
                                   value="{{ old('linvas_rx_per_month') }}"
                                   placeholder="0 – 200" min="0" max="200" step="0.01">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Vorxar Rx / Month</label>
                            <input type="number" name="vorxar_rx_per_month" class="form-control"
                                   value="{{ old('vorxar_rx_per_month') }}"
                                   placeholder="0 – 200" min="0" max="200" step="0.01">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:4px;">
                        <label class="form-label"><span class="req">*</span> Total Business of Competitors (Value)</label>
                        <input type="number" name="total_business_value" class="form-control"
                               value="{{ old('total_business_value') }}"
                               placeholder="0.00 – 10.00" min="0" max="10" step="0.01">
                    </div>
                </div>

                {{-- Actions --}}
                <div class="form-actions">
                    <a href="{{ route('portal.doctors.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" id="save_changes" class="btn btn-primary">＋ Save Doctor</button>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('/assets/admin/vendors/general/validate/jquery.validate.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {

            // ── Selectpicker ────────────────────────────────
            $('#govt_dropdown').selectpicker();

            // ── Planned conversion toggle ────────────────────
            function toggleConversion() {
                $('#lipaglyn_rx_br_type').val() === 'Non Rxber'
                    ? $('#planned_for_conversion_wrapper').slideDown(200)
                    : ($('#planned_for_conversion_wrapper').slideUp(200), $('#planned_for_conversition').val(''));
            }
            $('#lipaglyn_rx_br_type').on('change', toggleConversion);
            toggleConversion();

            // ── Inst-Dr toggle ───────────────────────────────
            function toggleInst() {
                const v = $('input[name="inst_dr"]:checked').val();
                if (v === 'CGHS' || v === 'ESI' || v === 'OTHER GOVT') {
                    $('#govt_dropdown_wrapper').slideDown(200);
                    v !== 'OTHER GOVT'
                        ? $('#govt_dropdown').attr('required', true)
                        : $('#govt_dropdown').removeAttr('required');
                } else {
                    $('#govt_dropdown_wrapper').slideUp(200);
                    $('#new_institution_wrapper').slideUp(200);
                    $('#govt_dropdown').removeAttr('required').val('');
                    $('#govt_dropdown').selectpicker('refresh');
                }
            }
            $('input[name="inst_dr"]').on('change', toggleInst);
            toggleInst();

            // ── New institution toggle ───────────────────────
            $('#govt_dropdown').on('change', function () {
                $(this).val() === 'new'
                    ? $('#new_institution_wrapper').slideDown(200)
                    : ($('#new_institution_wrapper').slideUp(200), $('#new_institution').val(''));
            });

            // ── Validation ──────────────────────────────────
            $('#doctor_form').validate({
                rules: {
                    name:                         { required: true },
                    specialization:               { required: true },
                    actual_speciality:            { required: true },
                    lipaglyn_rx_br_type:          { required: true },
                    avg_lipaglyn_pr_month:        { required: true, number: true, min: 0, max: 10 },
                    incremental_lipaglyn_busines: { required: true, number: true, min: 0, max: 10 },
                    Diabetes_patients_day:        { required: true, number: true, min: 0 },
                    kol_kbl:                      { required: true },
                    inst_dr:                      { required: true },
                    udca_rx_per_month:            { required: true, number: true, min: 0, max: 200 },
                    sema_rx_prer_month:           { required: true, number: true, min: 0, max: 200 },
                    other_saro_rm_per_month:      { required: true, number: true, min: 0, max: 200 },
                    bilypsa_rx_per_month:         { number: true, min: 0, max: 200 },
                    linvas_rx_per_month:          { number: true, min: 0, max: 200 },
                    vorxar_rx_per_month:          { number: true, min: 0, max: 200 },
                    total_business_value:         { required: true, number: true, min: 0, max: 10 },
                },
                messages: {
                    name:                { required: 'Doctor name is required' },
                    specialization:      { required: 'Speciality is required' },
                    actual_speciality:   { required: 'Please select a tag' },
                    lipaglyn_rx_br_type: { required: 'Please select Rxbr type' },
                    kol_kbl:             { required: 'Please select KOL / KBL' },
                    inst_dr:             { required: 'Please select institution type' },
                },
                errorClass:   'form-validation-error-text',
                errorElement: 'span',
                highlight:    el => $(el).addClass('form-validation-error'),
                unhighlight:  el => $(el).removeClass('form-validation-error'),
                errorPlacement(error, element) {
                    element.attr('type') === 'radio'
                        ? error.insertAfter(element.closest('.radio-group'))
                        : error.insertAfter(element);
                }
            });

            // ── Submit guard ─────────────────────────────────
            $('#doctor_form').on('submit', function () {
                if ($(this).valid()) {
                    $('#save_changes').prop('disabled', true).html('Saving…');
                    return true;
                }
                return false;
            });
        });
    </script>
@endsection
