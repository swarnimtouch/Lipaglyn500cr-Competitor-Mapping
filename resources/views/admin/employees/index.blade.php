@extends('layouts.master')

@section('title') Employees @endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .badge-success { background:#28a745; color:#fff; padding:4px 8px; border-radius:4px; font-size:12px; }
        .badge-danger  { background:#dc3545; color:#fff; padding:4px 8px; border-radius:4px; font-size:12px; }
        label.error    { color:#d9534f; font-size:12px; margin-top:3px; display:block; }
        .form-control:focus { border-color:#A11A20; box-shadow:0 0 0 0.2rem rgba(161,26,32,.2); }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Employees</h4>
                    <button class="btn btn-sm btn-primary" onclick="openAddEmployee()">+ Add Employee</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="empTable" class="table dt-responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Emp ID</th>
                                <th>HQ</th>
                                <th>Type</th>
                                <th>Status</th>
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

    {{-- ADD / EDIT MODAL --}}
    <div class="modal fade" id="empModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="empModalTitle">Add Employee</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="empForm">
                        <input type="hidden" id="emp_id_field">

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="f_name" name="name" placeholder="Full Name">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Employee ID</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="f_employee_id" name="employee_id" placeholder="Employee ID">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Chair ID</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="f_chair_id" name="chair_id" placeholder="Chair ID">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">HQ <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="f_hq" name="hq" placeholder="HQ">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Type <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control" id="f_type" name="type">
                                    <option value="">Select Type</option>
                                    <option value="MR">MR</option>
                                    <option value="ABM">ABM</option>
                                    <option value="ZBM">ZBM</option>
                                    <option value="RBM">RBM</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Status <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control" id="f_status" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Password <span class="text-danger" id="pass_required">*</span></label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" id="f_password" name="password" placeholder="Password (leave blank to keep)">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-primary" id="empSaveBtn">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        let isEdit = false;

        function openAddEmployee() {
            isEdit = false;
            $('#empForm')[0].reset();
            $('#emp_id_field').val('');
            $('#empModalTitle').text('Add Employee');
            $('#pass_required').show();
            $('#empModal').modal('show');
        }

        function openEditEmployee(id) {
            isEdit = true;

            let url = "{{ route('admin.employees.edit', ':id') }}";
            url = url.replace(':id', id);

            $.get(url, function (data) {
                $('#emp_id_field').val(data.id);
                $('#f_name').val(data.name);
                $('#f_employee_id').val(data.employee_id);
                $('#f_chair_id').val(data.chair_id);
                $('#f_hq').val(data.hq);
                $('#f_type').val(data.type);
                $('#f_status').val(data.status);
                $('#f_password').val('');
                $('#empModalTitle').text('Edit Employee');
                $('#pass_required').hide();
                $('#empModal').modal('show');
            });
        }

        function deleteEmployee(id) {
            if (!confirm('Are you sure you want to delete this employee?')) return;

            let url = "{{ route('admin.employees.destroy', ':id') }}";
            url = url.replace(':id', id);

            $.post(url, {
                _token: '{{ csrf_token() }}'
            }, function (res) {
                if (res.success) {
                    $('#empTable').DataTable().ajax.reload();
                    alert(res.message);
                }
            });
        }

        function toggleStatus(id, status) {

            let url = "{{ route('admin.employees.toggleStatus', ':id') }}";
            url = url.replace(':id', id);

            $.post(url, {
                _token: '{{ csrf_token() }}',
                status: status
            }, function (res) {
                if (res.success) {
                    $('#empTable').DataTable().ajax.reload();
                }
            });
        }

        $(document).ready(function () {

            // DataTable
            $('#empTable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'DESC']],
                ajax: '{{ route("admin.employees.listing") }}',
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'employee_id' },
                    { data: 'hq' },
                    { data: 'type' },
                    { data: 'status', orderable: false },
                    { data: 'action', orderable: false, searchable: false }
                ]
            });

            // Validation
            $('#empForm').validate({
                rules: {
                    name:   { required: true },
                    hq:     { required: true },
                    type:   { required: true },
                    status: { required: true },
                    password: {
                        required: function() { return !isEdit; },
                        minlength: 6
                    }
                },
                errorElement: 'label',
                errorClass: 'error'
            });

            // Form Submit
            $('#empForm').on('submit', function (e) {
                e.preventDefault();
                if (!$(this).valid()) return;

                const id  = $('#emp_id_field').val();
                const url = isEdit
                    ? "{{ route('admin.employees.update', ':id') }}".replace(':id', id)
                    : "{{ route('admin.employees.store') }}";

                $.post(url, $(this).serialize() + '&_token={{ csrf_token() }}', function (res) {
                    if (res.success) {
                        $('#empModal').modal('hide');
                        $('#empTable').DataTable().ajax.reload();
                        alert(res.message);
                    }
                }).fail(function (xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let msg = Object.values(errors).flat().join('\n');
                        alert(msg);
                    }
                });
            });
        });
    </script>
@endsection
