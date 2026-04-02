@extends('layouts.master')

@section('title') Employees @endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        /* ── Premium Badges ── */
        .badge-success { background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-danger  { background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        
        /* ── Form Error & Validation ── */
        label.error { display: flex; align-items: center; gap: 6px; margin-top: 6px; color: #ef4444; font-size: 13px; font-weight: 500; }
        label.error::before { content: '\f05a'; font-family: 'Font Awesome 6 Free'; font-weight: 900; }
        .form-validation-error { border-color: #ef4444 !important; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important; }

        /* ── Custom Toggle Switch (Premium Sync) ── */
        .switch { position:relative; display:inline-block; width:42px; height:24px; margin:0; }
        .switch input { opacity:0; width:0; height:0; }
        .slider {
            position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0;
            background:#cbd5e1; border-radius:24px; transition:.3s ease;
        }
        .slider:before {
            content:""; position:absolute; height:18px; width:18px;
            left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        input:checked + .slider { background: var(--color-a); } /* Teal Color */
        input:checked + .slider:before { transform:translateX(18px); }

        /* ── Modal Premium Design ── */
        .modal { z-index: 1050 !important; }
        .modal-backdrop { z-index: 1040 !important; }
        .modal-dialog { max-width: 700px; }
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

        /* ── Buttons ── */
        .btn-export {
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
            transition: all 0.3s ease;
        }
        .btn-export:hover { 
            background: #10b981; 
            color: #fff; 
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transform: translateY(-1px);
        }

        .btn-add {
            background: var(--gradient-primary);
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }
        .btn-add:hover { 
            color: #fff; 
            box-shadow: 0 4px 12px rgba(0, 158, 163, 0.3);
            transform: translateY(-1px);
        }

        /* Modal Save/Close Buttons */
        .btn-save { background: var(--gradient-primary); color: #fff; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; transition: all 0.3s; }
        .btn-save:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 158, 163, 0.3); }
        .btn-close-modal { background: transparent; color: var(--text-muted); border: 2px solid var(--border); padding: 9px 24px; border-radius: 8px; font-weight: 600; transition: all 0.3s; }
        .btn-close-modal:hover { background: #f1f5f9; color: var(--text-main); border-color: #cbd5e1; }

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
        .btn-edit { background: rgba(0, 158, 163, 0.1); color: var(--color-a) !important; }
        .btn-edit:hover { background: var(--color-a); color: #fff !important; box-shadow: 0 4px 12px rgba(0, 158, 163, 0.3); transform: translateY(-2px); }
        
        .btn-delete { background: rgba(239, 68, 68, 0.1); color: #ef4444 !important; }
        .btn-delete:hover { background: #ef4444; color: #fff !important; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); transform: translateY(-2px); }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header { flex-direction: column; align-items: stretch !important; gap: 12px; }
            .header-actions { display: flex; flex-direction: column; width: 100%; gap: 8px; }
            .header-actions a, .header-actions button { width: 100%; justify-content: center; }
            .d-flex.justify-content-end.gap-2 { flex-direction: column; }
            .d-flex.justify-content-end.gap-2 button { width: 100%; margin-top: 8px; }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div style="font-size: 18px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-users" style="color: var(--color-a);"></i> Manage Employees
                    </div>
                    
                    <div class="header-actions" style="display: flex; gap: 10px;">
                        <a href="{{ route('admin.export.employees') }}" class="btn-export">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
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
                                <input type="text" class="form-control" id="f_name" name="name" placeholder="Enter Full Name">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Employee ID</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="f_employee_id" name="employee_id" placeholder="Enter Employee ID">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Chair ID</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="f_chair_id" name="chair_id" placeholder="Enter Chair ID">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">HQ <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="f_hq" name="hq" placeholder="Enter HQ">
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
                                <input type="password" class="form-control" id="f_password" name="password" placeholder="Password (leave blank to keep current)">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-close-modal" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-save" id="empSaveBtn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        let isEdit = false;

        function openAddEmployee() {
            isEdit = false;
            $('#empForm')[0].reset();
            $('#emp_id_field').val('');
            $('#empModalTitle').text('Add New Employee');
            $('#pass_required').show();
            // Clear validation errors
            $('#empForm').find('.form-control').removeClass('form-validation-error');
            $('#empForm').find('label.error').remove();
            $('#empModal').modal('show');
        }

        function openEditEmployee(id) {
            isEdit = true;
            // Clear validation errors
            $('#empForm').find('.form-control').removeClass('form-validation-error');
            $('#empForm').find('label.error').remove();

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
                $('#empModalTitle').text('Edit Employee Details');
                $('#pass_required').hide();
                $('#empModal').modal('show');
            }).fail(function() {
                alert('Failed to fetch data.');
            });
        }

        // Action button exposed to global scope
        window.openEditEmployee = openEditEmployee;

        function deleteEmployee(id) {
            Swal.fire({
                title: 'Are you sure to delete?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', /* Red color tere theme ke hisab se */
                cancelButtonColor: '#cbd5e1', /* Gray color cancel ke liye */
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                // Agar user ne Yes pe click kiya
                if (result.isConfirmed) {
                    let url = "{{ route('admin.employees.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    $.post(url, {
                        _token: '{{ csrf_token() }}'
                    }, function (res) {
                        if (res.success) {
                            $('#empTable').DataTable().ajax.reload(null, false);
                            // Ghatiya alert() ki jagah SweetAlert success message
                            Swal.fire('Deleted!', res.message, 'success');
                        }
                    });
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
                    $('#empTable').DataTable().ajax.reload(null, false);
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
                    { data: 'status', orderable: false },
                    { data: 'action', orderable: false, searchable: false }
                ],
                // Change delete button to icon dynamically
                drawCallback: function() {
                    $('#empTable tbody tr td:last-child').find('button').each(function() {
                        var text = $(this).text().trim().toLowerCase();
                        if(text === 'delete') {
                            $(this).html('<i class="fas fa-trash-alt"></i>')
                                   .removeClass('btn btn-xs btn-danger')
                                   .addClass('btn-icon btn-delete')
                                   .attr('title', 'Delete');
                        }
                    });
                    
                    
                }
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
                errorClass: 'error',
                highlight: function (element) {
                    $(element).addClass('form-validation-error');
                },
                unhighlight: function (element) {
                    $(element).removeClass('form-validation-error');
                }
            });

            // Form Submit
            $('#empForm').on('submit', function (e) {
                e.preventDefault();
                if (!$(this).valid()) return;

                const id  = $('#emp_id_field').val();
                const url = isEdit
                    ? "{{ route('admin.employees.update', ':id') }}".replace(':id', id)
                    : "{{ route('admin.employees.store') }}";

                let btn = $('#empSaveBtn');
                let oldText = btn.text();
                btn.prop('disabled', true).text('Saving...');

                $.post(url, $(this).serialize() + '&_token={{ csrf_token() }}', function (res) {
                    if (res.success) {
                        $('#empModal').modal('hide');
                        $('#empTable').DataTable().ajax.reload(null, false);
                        alert(res.message);
                    }
                }).fail(function (xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let msg = Object.values(errors).flat().join('\n');
                        alert(msg);
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                }).always(function() {
                    btn.prop('disabled', false).text(oldText);
                });
            });

            // Export Button Fix
            $('a[href="{{ route('admin.export.employees') }}"]').click(function (e) {
                e.preventDefault();
                let search = $('#empTable_filter input').val();
                let url = "{{ route('admin.export.employees') }}";
                if (search) {
                    url += '?search=' + encodeURIComponent(search);
                }
                window.location.href = url;
            });

        });
    </script>
@endsection