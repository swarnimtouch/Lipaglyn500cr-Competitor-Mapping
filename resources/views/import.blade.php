<div style="max-width:500px;margin:50px auto;padding:20px;border:1px solid #ddd;border-radius:10px;box-shadow:0 0 10px #eee;">

    <h3 style="text-align:center;margin-bottom:20px;">Import Employees</h3>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div style="background:#d4edda;color:#155724;padding:10px;border-radius:5px;margin-bottom:15px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ Employee Import Errors --}}
    @if($errors->employeeImport->any())
        <div style="background:#f8d7da;color:#721c24;padding:10px;border-radius:5px;margin-bottom:15px;">
            {{ $errors->employeeImport->first() }}
        </div>
    @endif

    {{-- ✅ Employee Upload Form --}}
    <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:15px;">
            <label><b>Select Excel File</b></label><br>
            <input type="file" name="employee_file" required style="margin-top:5px;">
        </div>

        <button type="submit" style="width:100%;padding:10px;background:#007bff;color:#fff;border:none;border-radius:5px;">
            Upload Excel
        </button>
    </form>

    <hr style="margin:25px 0;">

    {{-- ✅ Doctor Import Errors --}}
    @if($errors->doctorImport->any())
        <div style="background:#f8d7da;color:#721c24;padding:10px;border-radius:5px;margin-bottom:15px;">
            {{ $errors->doctorImport->first() }}
        </div>
    @endif

    {{-- ✅ Doctor Upload Form --}}
    <form action="{{ route('admin.doctors.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label>Select Excel File</label>
            <input type="file" name="doctor_file" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Upload &amp; Import
        </button>
    </form>

</div>
