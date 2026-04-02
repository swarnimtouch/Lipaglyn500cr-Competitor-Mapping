@extends('layouts.master')

@section('title') Doctors (Admin) @endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                {{-- HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">All Doctors</h4>

                    <div class="d-flex gap-2">

                        {{-- ✅ ZONE FILTER (from employees table) --}}
                        <select id="zoneFilter" class="form-control form-control-sm" style="width:200px;">
                            <option value="">All Zone</option>
                            @foreach($employees->pluck('zone')->unique() as $zone)
                                <option value="{{ $zone }}">{{ $zone }}</option>
                            @endforeach
                        </select>

                        {{-- ✅ EXPORT BUTTON --}}
                        <a href="#" id="exportBtn" class="btn btn-success btn-sm">
                            Export Excel
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
                                <th>MR Emp ID</th>
                                <th>Dr. Name</th>
                                <th>Dr. UID</th>
                                <th>Speciality</th>
                                <th>Rx Br Type</th>
                                <th>Avg Lipaglyn</th>
                                <th>Bilypsa</th>
                                <th>Linvas</th>
                                <th>Vorxar</th>
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
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {

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
                    { data: 'lipaglyn_rx_br_type' },
                    { data: 'avg_lipaglyn_pr_month' },
                    { data: 'bilypsa_rx_per_month' },
                    { data: 'linvas_rx_per_month' },
                    { data: 'vorxar_rx_per_month' },
                    { data: 'action', orderable: false, searchable: false }
                ]
            });

            // ✅ FILTER CHANGE
            $('#zoneFilter').change(function () {
                table.ajax.reload();
            });

            // ✅ EXPORT WITH FILTER
            $('#exportBtn').click(function (e) {
                e.preventDefault();

                let zone = $('#zoneFilter').val();
                let search = $('#doctorTable_filter input').val(); // ✅ ye missing tha

                let url = "{{ route('admin.export.doctors') }}";

                let params = [];

                if (zone) params.push('zone=' + zone);
                if (search) params.push('search=' + search);

                if (params.length) {
                    url += '?' + params.join('&');
                }

                window.location.href = url;
            });

        });

        // ✅ DELETE FUNCTION
        function deleteDoctor(id) {
            if (!confirm('Delete this doctor?')) return;

            let url = "{{ route('admin.doctors.delete', ':id') }}";
            url = url.replace(':id', id);

            $.post(url, {
                _token: '{{ csrf_token() }}'
            }, function (res) {
                if (res.success) {
                    $('#doctorTable').DataTable().ajax.reload();
                    alert(res.message);
                }
            });
        }
    </script>
@endsection
