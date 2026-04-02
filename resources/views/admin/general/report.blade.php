@extends('layouts.master')

@section('title')
    Region Report
@endsection

@section('css')
    <link href="{{ URL::asset('/assets/admin/vendors/general/datatable/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />

    <style>
        /* DataTable Fixed Columns Tweaks */
        table.dataTable tbody .dtfc-fixed-left { background-color: #fff; }
        table.dataTable thead .dtfc-fixed-left { background-color: #f8f9fc; }
        table.dataTable tbody tr:hover .dtfc-fixed-left { background-color: rgba(0, 158, 163, 0.03); }
        table.dataTable tfoot .dtfc-fixed-left { background-color: #f8f9fc; font-weight: bold; }
        tfoot tr td { font-weight: 700; background: #f8f9fc; }

        /* ── Select2 Premium Theme Sync ── */
        .select2-container { width: 100% !important; text-align: left; }
        /* Multi-select box fix */
        .select2-container--default .select2-selection--multiple {
            border-radius: 8px !important;
            border: 1px solid var(--border) !important;
            min-height: 42px !important;
            height: auto;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            transition: all 0.3s ease;
            background-color: var(--input-bg);
            padding: 2px 8px;
            cursor: pointer;
        }
        /* Search bar size ko control karne ke liye */
        .select2-container .select2-search--inline .select2-search__field {
            margin-top: 0 !important;
            height: 30px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--color-a) !important;
            box-shadow: 0 0 0 3px rgba(0, 158, 163, 0.1) !important;
            background-color: #fff;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            color: #ffffff !important; 
            background: var(--gradient-primary) !important; 
            border: none;
            border-radius: 6px;
            margin: 4px 4px 2px 0 !important;
            padding: 3px 8px;
            font-size: 13px;
            font-weight: 500;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove { 
            color: #ffffff !important; 
            margin-right: 6px; 
            border-right: 1px solid rgba(255,255,255,0.3); 
            padding-right: 6px; 
        }
        .select2-search--inline { padding: 5px !important; }
        .select2-selection__rendered { padding: 2px 8px !important; }

        /* Dropdown Box Styling */
        .select2-dropdown {
            border-radius: 8px !important;
            border: 1px solid var(--color-a) !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
            overflow: hidden;
        }
        .select2-results__option {
            font-size: 14px;
            font-weight: 500;
            padding: 8px 12px;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--color-a) !important;
            color: white !important;
        }

        /* ── Premium Buttons ── */
        .btn-filter {
            background: var(--gradient-primary);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            height: 42px;
        }
        .btn-filter:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(0, 158, 163, 0.3);
        }

        .btn-clear {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            height: 42px;
        }
        .btn-clear:hover {
            background: #f1f5f9;
            color: var(--text-main);
            border-color: #cbd5e1;
            text-decoration: none;
        }

        .export-btn {
            background: rgba(179, 86, 159, 0.1);
            color: var(--color-b);
            border: 1px solid rgba(179, 86, 159, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            transition: all 0.3s ease;
            height: 42px;
        }
        .export-btn:hover { 
            background: var(--color-b); 
            color: #fff; 
            text-decoration: none; 
            box-shadow: 0 6px 15px rgba(179, 86, 159, 0.3);
            transform: translateY(-1px);
        }

        /* Responsive Wrapper */
        .filter-wrapper {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            flex: 1;
            justify-content: flex-end;
        }
        @media (max-width: 768px) {
            .filter-wrapper { justify-content: flex-start; }
            .filter-wrapper > div { width: 100%; max-width: 100% !important; }
            .btn-filter, .btn-clear, .export-btn { flex: 1; justify-content: center; }
        }
    </style>
@endsection

@section('content')

    <div class="card">
        <div class="card-header d-flex flex-column flex-xl-row justify-content-between align-items-start align-items-xl-center gap-3" style="padding: 20px 24px;">
            
            <div style="font-size: 18px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-chart-line" style="color: var(--color-a);"></i> Region Business Report
            </div>

            <form method="GET" action="{{ route('admin.report') }}" id="zoneForm" class="filter-wrapper w-100">
                
                {{-- Dropdown Container --}}
                <div style="min-width:200px; max-width:300px; flex-grow: 1;" class="select2-container-wrap">
                    <select name="zone" id="zoneFilter" class="form-control">
    <option value="" disabled {{ empty(request('zone')) ? 'selected' : '' }}>Select Filter</option>
    <option value="all" {{ request('zone') == 'all' ? 'selected' : '' }}>All Zones</option>
    
    @foreach($zones as $zone)
        <option value="{{ $zone }}" {{ request('zone') == $zone ? 'selected' : '' }}>
            {{ $zone }}
        </option>
    @endforeach
</select>
                </div>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>

                @if(request('zone') && !in_array('all', (array) request('zone')))
                    <a href="{{ route('admin.report') }}" class="btn-clear">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif

                <a id="exportBtn"
                   href="{{ route('admin.report.export', ['zone' => request('zone')]) }}"
                   class="export-btn">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="listResults" class="table dt-responsive mb-4 nowrap w-100">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Region</th>
                        <th>No. of <br> BO's</th>
                        <th>No. of <br> Active BO's</th>
                        <th>Lipaglyn Business <br> as per RCPA</th>
                        <th>Lipaglyn Current <br> No. of Rxbers</th>
                        <th>Lipaglyn + UDCA <br> No. of Rxbers</th>
                        <th>Diabetes Patients <br> in a Month</th>
                        <th>New Doctor Conversions <br> Planned</th>
                        <th>Potential Business of Lipaglyn <br> From New Dr Conversions</th>
                        <th>Incremental Business from <br> Existing Rxbers Planned</th>
                        <th>Potential Incremental <br> Business from Existing Rxbers</th>
                        <th>Total Potential <br> New Business Planned</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($regions as $key => $region)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $region->region  }}</td>
                            <td>{{ $region->user_count }}</td>
                            <td>{{ $region->active_user_count }}</td>
                            <td>{{ number_format($region->total_avg_lipaglyn, 2) }}</td>
                            <td>{{ $region->avg_lipaglyn_count }}</td>
                            <td>{{ $region->lipaglyn_udca_count }}</td>
                            <td>{{ number_format($region->total_diabetes_patients * 25) }}</td>
                            <td>{{ number_format($region->planned_for_conversition_count) }}</td>
                            <td>{{ number_format($region->total_business_value_sum, 2) }}</td>
                            <td>{{ number_format($region->incremental_lipaglyn_busines_count) }}</td>
                            <td>{{ number_format($region->incremental_lipaglyn_busines_sum, 2) }}</td>
                            <td>{{ number_format($region->incremental_lipaglyn_busines_sum1, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr>
                        <td>Total</td>
                        <td>{{ $totals['region_count'] }} Regions</td>
                        <td>{{ $totals['user_count'] }}</td>
                        <td>{{ $totals['active_user_count'] }}</td>
                        <td>{{ number_format($totals['total_avg_lipaglyn'], 2) }}</td>
                        <td>{{ $totals['avg_lipaglyn_count'] }}</td>
                        <td>{{ $totals['lipaglyn_udca_count'] }}</td>
                        <td>{{ number_format($totals['total_diabetes_patients'] * 25) }}</td>
                        <td>{{ $totals['planned_for_conversition_count'] }}</td>
                        <td>{{ number_format($totals['total_business_value_sum'], 2) }}</td>
                        <td>{{ $totals['incremental_lipaglyn_busines_count'] }}</td>
                        <td>{{ number_format($totals['incremental_lipaglyn_busines_sum'], 2) }}</td>
                        <td>{{ number_format($totals['incremental_lipaglyn_busines_sum1'], 2) }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/assets/admin/vendors/general/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {

            // ── Sidebar Active Highlight Fix ──
            $('.sidebar-nav .nav-link-item').removeClass('active');
            $('.sidebar-nav .nav-link-item[href*="report"]').addClass('active');

            // ── DataTable init ──
            $('#listResults').DataTable({
                dom: 'Bfrtip',
                buttons: [],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
                pageLength: 100,
                scrollX: true,
                fixedColumns: { left: 2 },
                columnDefs: [
                    { targets: [0, 1], className: 'dtfc-fixed-left' }
                ],
                paging: false
            });

            // ── Select2 init ──
            // ── Select2 init ──
$('#zoneFilter').select2({
    allowClear: true,
    width: '100%',
    placeholder: 'Select Filter',
    dropdownAutoWidth: true
});

// ── Handle Select2 Changes ──
$('#zoneFilter').on('change', function () {
    var selected = $(this).val(); // Ab ye string hoga, array nahi
    updateExportUrl(selected);
});

// ── Export URL dynamically update karo ──
function updateExportUrl(zone) {
    var base = "{{ route('admin.report.export') }}";
    
    // Agar kuch select nahi kiya ya 'all' select kiya hai
    if (!zone || zone === 'all') {
        $('#exportBtn').attr('href', base);
    } else {
        // Single value pass karni hai
        $('#exportBtn').attr('href', base + '?zone=' + encodeURIComponent(zone));
    }
}

// Page load pe export URL set karo (agar filter already laga ho)
(function () {
    var selected = $('#zoneFilter').val();
    updateExportUrl(selected);
})();
        });
    </script>
@endsection