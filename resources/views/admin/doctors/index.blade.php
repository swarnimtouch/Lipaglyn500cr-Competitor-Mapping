@extends('layouts.master')

@section('title') Doctors (Admin) @endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">All Doctors</h4>
                    <a href="{{ route('admin.doctors.index') }}?export=1" class="btn btn-sm btn-success">Export CSV</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="doctorTable" class="table dt-responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>MR Name</th>
                                <th>MR Emp ID</th>
                                <th>Dr. Name</th>
                                <th>Dr. UID</th>
                                <th>Speciality</th>
                                <th>Rx Br Type</th>
                                <th>Avg Lipaglyn/Month</th>
                                <th>Bilypsa Rx/Month</th>
                                <th>Linvas Rx/Month</th>
                                <th>Vorxar Rx/Month</th>
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
            $('#doctorTable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'DESC']],
                ajax: '{{ route("admin.doctors.listing") }}',
                columns: [
                    { data: 'id' },
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
                ]
            });
        });
    </script>
@endsection
