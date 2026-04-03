@extends('layouts.master')

@section('title')
    Region Report
@endsection

@section('css')
    <link href="{{ URL::asset('/assets/admin/vendors/general/datatable/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />

    <style>
        table.dataTable tbody .dtfc-fixed-left { background-color: #fff; }
        table.dataTable thead .dtfc-fixed-left { background-color: #f8f9fc; }
        table.dataTable tbody tr:hover .dtfc-fixed-left { background-color: rgba(0, 158, 163, 0.03); }
        table.dataTable tfoot .dtfc-fixed-left { background-color: #f8f9fc; font-weight: bold; }
        tfoot tr td { font-weight: 700; background: #f8f9fc; }

        .select2-container { width: 100% !important; text-align: left; }
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
        .select2-dropdown {
            border-radius: 8px !important;
            border: 1px solid var(--color-a) !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
            overflow: hidden;
        }
        .select2-results__option { font-size: 14px; font-weight: 500; padding: 8px 12px; }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--color-a) !important;
            color: white !important;
        }

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
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
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
            background: #10b981;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .top-row { flex-direction: column !important; align-items: flex-start !important; }
            .export-btn { width: 100%; justify-content: center; margin-top: 15px; margin-bottom: 10px !important; }
            #zoneForm { flex-direction: column !important; align-items: stretch !important; }
            .select2-container-wrap { max-width: 100% !important; margin-right: 0 !important; width: 100%; margin-bottom: 12px; }
            .btn-filter { width: 100%; margin-right: 0 !important; justify-content: center; margin-bottom: 12px; }
            .btn-clear { width: 100%; justify-content: center; }
        }
    </style>
@endsection

@section('content')

    <div class="card">
        <div class="card-header d-flex flex-column gap-3" style="padding: 20px 24px;">

            <div class="top-row d-flex justify-content-between align-items-center w-100">
                <div style="font-size: 18px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-chart-line" style="color: var(--color-a);"></i> Region wise Report
                </div>

                <a id="exportBtn" href="{{ route('admin.report.export', ['zone' => request('zone')]) }}" class="export-btn mb-0">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>

            <form method="GET" action="{{ route('admin.report') }}" id="zoneForm" class="d-flex align-items-center w-100 mb-0">

                <div style="min-width:200px; max-width:300px; margin-right: 15px;" class="select2-container-wrap">
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

                <button type="submit" class="btn-filter" style="margin-right: 15px;">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>

                @if(request('zone') && !in_array('all', (array) request('zone')))
                    <a href="{{ route('admin.report') }}" class="btn-clear">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
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
                        <th>Diabetes Patients <br> in a Month</th>
                        <th>UDCA <br> Rxbers</th>
                        <th>UDCA <br> Rx/Month</th>
                        <th>Sema <br> Rxbers</th>
                        <th>Sema <br> Rx/Month</th>
                        <th>Bilypsa <br> Rxbers</th>
                        <th>Bilypsa <br> Rx/Month</th>
                        <th>Linvas <br> Rxbers</th>
                        <th>Linvas <br> Rx/Month</th>
                        <th>Vorxar <br> Rxbers</th>
                        <th>Vorxar <br> Rx/Month</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($regions as $key => $region)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $region->region }}</td>

                            <td>{{ $region->user_count }}</td>
                            <td>{{ $region->active_user_count }}</td>

                            <td>{{ number_format($region->total_diabetes_patients * 25) }}</td>

                            {{-- UDCA --}}
                            <td>{{ $region->udca_count }}</td>
                            <td>{{ number_format($region->total_udca, 2) }}</td>

                            {{-- Sema --}}
                            <td>{{ $region->sema_count }}</td>
                            <td>{{ number_format($region->total_sema, 2) }}</td>

                            {{-- Bilypsa --}}
                            <td>{{ $region->bilypsa_count }}</td>
                            <td>{{ number_format($region->total_bilypsa, 2) }}</td>

                            {{-- Linvas --}}
                            <td>{{ $region->linvas_count }}</td>
                            <td>{{ number_format($region->total_linvas, 2) }}</td>

                            {{-- Vorxar --}}
                            <td>{{ $region->vorxar_count }}</td>
                            <td>{{ number_format($region->total_vorxar, 2) }}</td>

                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr>
                        <td>Total</td>
                        <td>{{ $totals['region_count'] }} Regions</td>
                        <td>{{ $totals['user_count'] }}</td>
                        <td>{{ $totals['active_user_count'] }}</td>
                        <td>{{ number_format($totals['total_diabetes_patients'] * 25) }}</td>
                        <td>{{ $totals['udca_count'] }}</td>
                        <td>{{ number_format($totals['total_udca'], 2) }}</td>
                        <td>{{ $totals['sema_count'] }}</td>
                        <td>{{ number_format($totals['total_sema'], 2) }}</td>
                        <td>{{ $totals['bilypsa_count'] }}</td>
                        <td>{{ number_format($totals['total_bilypsa'], 2) }}</td>
                        <td>{{ $totals['linvas_count'] }}</td>
                        <td>{{ number_format($totals['total_linvas'], 2) }}</td>
                        <td>{{ $totals['vorxar_count'] }}</td>
                        <td>{{ number_format($totals['total_vorxar'], 2) }}</td>
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

            $('.sidebar-nav .nav-link-item').removeClass('active');
            $('.sidebar-nav .nav-link-item[href*="report"]').addClass('active');

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

            $('#zoneFilter').select2({
                allowClear: true,
                width: '100%',
                placeholder: 'Select Filter',
                dropdownAutoWidth: true
            });

            $('#zoneFilter').on('change', function () {
                updateExportUrl($(this).val());
            });

            function updateExportUrl(zone) {
                var base = "{{ route('admin.report.export') }}";
                if (!zone || zone === 'all') {
                    $('#exportBtn').attr('href', base);
                } else {
                    $('#exportBtn').attr('href', base + '?zone=' + encodeURIComponent(zone));
                }
            }

            (function () {
                updateExportUrl($('#zoneFilter').val());
            })();
        });
    </script>
@endsection
