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
        table.dataTable thead .dtfc-fixed-left { background-color: #f8f9fa; }
        table.dataTable tbody tr:hover .dtfc-fixed-left { background-color: #f5f5f5; }
        table.dataTable tfoot .dtfc-fixed-left { background-color: #f8f9fa; font-weight: bold; }

        .select2-container--default .select2-selection--multiple
        .select2-selection__rendered .select2-selection__choice {
            margin: 3px 1px 1px 4px !important;
        }

        tfoot tr td { font-weight: 700; background: #f8f9fa; }

        .export-btn {
            background: #1a7a4a;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }
        .export-btn:hover { background: #155d38; color: #fff; text-decoration: none; }
    </style>
@endsection

@section('content')
    @include('components.breadcum')

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.report') }}" id="zoneForm">
                <div class="d-flex align-items-center flex-wrap" style="gap:12px;">
                    <span style="font-size:15px; font-weight:600;">Region Business Report</span>

                    <div style="min-width:260px; flex:1; max-width:420px;">
                        <select name="zone[]" id="zoneFilter" class="form-control" multiple>
                            <option value="all"
                                {{ empty(request('zone')) || in_array('all', (array) request('zone')) ? 'selected' : '' }}>
                                All Zones
                            </option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone }}"
                                    {{ is_array(request('zone')) && in_array($zone, request('zone')) ? 'selected' : '' }}>
                                    {{ $zone }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter mr-1"></i> Apply Filter
                    </button>

                    @if(request('zone') && !in_array('all', (array) request('zone')))
                        <a href="{{ route('admin.report') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times mr-1"></i> Clear
                        </a>
                    @endif

                    <a id="exportBtn"
                       href="{{ route('admin.report.export', ['zone' => request('zone')]) }}"
                       class="export-btn">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
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

            // DataTable init
            $('#listResults').DataTable({
                dom: 'Bfrtip',
                buttons: [],          // DataTable export hata diya — apna Export Excel button use karo
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
                pageLength: 100,
                scrollX: true,
                fixedColumns: { left: 2 },
                columnDefs: [
                    { targets: [0, 1], className: 'dtfc-fixed-left' }
                ],
                paging: false
            });

            // Select2 init
            $('#zoneFilter').select2({
                allowClear: true,
                width: '100%',
                placeholder: 'Select Zone(s)',
                dropdownParent: $('#zoneFilter').closest('.card-header')
            });

            // Zone change pe form turant submit + export URL update
            $('#zoneFilter').on('change', function () {
                var selected = $(this).val() || [];

                // Agar "all" select hua to baaki sab hata do
                if (selected.includes('all')) {
                    $(this).val(['all']).trigger('change.select2');
                    updateExportUrl([]);
                    $('#zoneForm').submit();
                    return;
                }

                updateExportUrl(selected);
                $('#zoneForm').submit();
            });

            // Export URL dynamically update karo jab zone change ho
            function updateExportUrl(zones) {
                var base = "{{ route('admin.report.export') }}";
                if (zones.length === 0) {
                    $('#exportBtn').attr('href', base);
                } else {
                    var params = zones.map(z => 'zone[]=' + encodeURIComponent(z)).join('&');
                    $('#exportBtn').attr('href', base + '?' + params);
                }
            }

            // Page load pe bhi export URL set karo (agar filter already laga ho)
            (function () {
                var selected = $('#zoneFilter').val() || [];
                var filtered = selected.filter(z => z !== 'all');
                updateExportUrl(filtered);
            })();
        });
    </script>
@endsection
