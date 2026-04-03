@extends('layouts.master')

@section('title') Doctors (Admin) @endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        /* ── Action Icon Buttons (Delete) ── */
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
            text-decoration: none !important;
            cursor: pointer;
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

        /* ── Header Filters & Export ── */
        .export-btn {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .export-btn:hover {
            background: #10b981;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transform: translateY(-1px);
        }

        .filter-select {
            border-radius: 8px;
            border: 1px solid var(--border);
            font-size: 14px;
            color: var(--text-main);
            padding: 8px 12px;
            outline: none;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            border-color: var(--color-a);
            box-shadow: 0 0 0 3px rgba(0, 158, 163, 0.1);
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: stretch !important;
                gap: 16px;
            }
            .header-actions {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .header-actions select, .header-actions a {
                width: 100% !important;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                {{-- HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center">

                    <div style="font-size: 18px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-user-md" style="color: var(--color-a);"></i> All Doctors
                    </div>

                    <div class="header-actions d-flex align-items-center">

                        {{-- ✅ ZONE FILTER - mr-3 class add ki gayi hai space ke liye --}}
                        <select id="zoneFilter" class="filter-select mr-3" style="width:220px;">
                            <option value="">All Zones</option>
                            @foreach($employees->pluck('zone')->unique() as $zone)
                                @if($zone)
                                    <option value="{{ $zone }}">{{ $zone }}</option>
                                @endif
                            @endforeach
                        </select>

                        {{-- ✅ EXPORT BUTTON --}}
                        <a href="#" id="exportBtn" class="export-btn">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>

                    </div>
                </div>

                {{-- TABLE --}}
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="doctorTable" class="table dt-responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>MR Name</th>
                                <th>MR  ID</th>
                                <th>Dr. Name</th>
                                <th>Dr. UID</th>
                                <th>Speciality</th>
{{--                                <th>Rx Br Type</th>--}}
{{--                                <th>Avg Lipaglyn</th>--}}
                                <th>Bilypsa Rx / Month</th>
                                <th>Linvas Rx / Month</th>
                                <th>Vorxar Rx / Month</th>
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
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {

            // Sidebar highlight fix (agar active class manage karni ho)
            $('.sidebar-nav .nav-link-item').removeClass('active');
            $('.sidebar-nav .nav-link-item[href*="doctors"]').addClass('active');

            let table = $('#doctorTable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'DESC']],

                ajax: {
                    url: '{{ route("admin.doctors.listing") }}',
                    data: function (d) {
                        d.zone = $('#zoneFilter').val(); // ✅ zone pass
                    }
                },

                columns: [
                    { data: 'index' }, // ✅ Sr No
                    { data: 'emp_name' },
                    { data: 'emp_id' },
                    { data: 'name' },
                    { data: 'msl_code' },
                    { data: 'specialization' },
                    // { data: 'lipaglyn_rx_br_type' },
                    // { data: 'avg_lipaglyn_pr_month' },
                    { data: 'bilypsa_rx_per_month' },
                    { data: 'linvas_rx_per_month' },
                    { data: 'vorxar_rx_per_month' },
                    { data: 'action', orderable: false, searchable: false }
                ],

                // Delete button ko icon me convert karna
                drawCallback: function() {
                    $('#doctorTable tbody tr td:last-child').find('button').each(function() {
                        var text = $(this).text().trim().toLowerCase();
                        if(text === 'delete') {
                            $(this).html('<i class="fas fa-trash-alt"></i>')
                                   .removeClass('btn btn-sm btn-danger')
                                   .addClass('btn-icon btn-delete')
                                   .attr('title', 'Delete');
                        }
                    });
                }
            });

            // ✅ FILTER CHANGE
            $('#zoneFilter').change(function () {
                table.ajax.reload(null, false);
            });

            // ✅ EXPORT WITH FILTER
            $('#exportBtn').click(function (e) {
                e.preventDefault();

                let zone = $('#zoneFilter').val();
                let search = $('#doctorTable_filter input').val();

                let url = "{{ route('admin.export.doctors') }}";
                let params = [];

                if (zone) params.push('zone=' + encodeURIComponent(zone));
                if (search) params.push('search=' + encodeURIComponent(search));

                if (params.length) {
                    url += '?' + params.join('&');
                }

                window.location.href = url;
            });

        });

        // ✅ DELETE FUNCTION
        function deleteDoctor(id) {
            Swal.fire({
                title: 'Are you sure to delete?',
                text: "This doctor record will be removed permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('admin.doctors.delete', ':id') }}";
                    url = url.replace(':id', id);

                    $.post(url, {
                        _token: '{{ csrf_token() }}'
                    }, function (res) {
                        if (res.success) {
                            $('#doctorTable').DataTable().ajax.reload(null, false);
                            Swal.fire('Deleted!', res.message, 'success');
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    }).fail(function() {
                        Swal.fire('Failed!', 'Something went wrong. Please try again.', 'error');
                    });
                }
            });
        }
    </script>
@endsection
