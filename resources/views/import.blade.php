<div style="max-width:500px;margin:50px auto;padding:20px;border:1px solid #ddd;border-radius:10px;box-shadow:0 0 10px #eee;">

    <h3 style="text-align:center;margin-bottom:20px;">Import Employees</h3>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div style="background:#d4edda;color:#155724;padding:10px;border-radius:5px;margin-bottom:15px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ Error Message --}}
    @if($errors->any())
        <div style="background:#f8d7da;color:#721c24;padding:10px;border-radius:5px;margin-bottom:15px;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ✅ Upload Form --}}
    <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:15px;">
            <label><b>Select Excel File</b></label><br>
            <input type="file" name="file" required style="margin-top:5px;">
        </div>

        <button type="submit" style="width:100%;padding:10px;background:#007bff;color:#fff;border:none;border-radius:5px;">
            Upload Excel
        </button>
    </form>



</div>
