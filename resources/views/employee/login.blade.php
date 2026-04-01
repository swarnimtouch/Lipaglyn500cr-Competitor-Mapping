<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: #0f1117;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            background-image: radial-gradient(ellipse at 20% 50%, #1a1f35 0%, transparent 60%),
            radial-gradient(ellipse at 80% 20%, #0d1f2d 0%, transparent 50%);
        }

        .card {
            background: #16181f;
            border: 1px solid #2a2d3a;
            border-radius: 16px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 14px;
        }

        h1 {
            color: #f1f5f9;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }

        .subtitle {
            color: #64748b;
            font-size: 13px;
            margin-top: 4px;
        }

        .error-box {
            background: #2a1215;
            border: 1px solid #7f1d1d;
            border-radius: 8px;
            padding: 12px 14px;
            color: #fca5a5;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 7px;
            letter-spacing: 0.2px;
        }

        input {
            width: 100%;
            background: #0f1117;
            border: 1px solid #2a2d3a;
            border-radius: 8px;
            padding: 11px 14px;
            color: #f1f5f9;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        input.is-invalid {
            border-color: #ef4444;
        }

        .field-error {
            color: #f87171;
            font-size: 12px;
            margin-top: 5px;
        }

        button[type="submit"] {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: opacity 0.2s, transform 0.1s;
            letter-spacing: 0.2px;
        }

        button[type="submit"]:hover  { opacity: 0.9; }
        button[type="submit"]:active { transform: scale(0.99); }

        .footer {
            text-align: center;
            margin-top: 24px;
            color: #475569;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="logo">
        <div class="logo-icon">👤</div>
        <h1>Employee Portal</h1>
        <p class="subtitle">Apne credentials se login karein</p>
    </div>

    {{-- Error Message --}}
    @if ($errors->any())
        <div class="error-box">
            ⚠️ {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('employee.doLogin') }}">
        @csrf

        <div class="form-group">
            <label for="employee_id">Employee ID</label>
            <input
                type="text"
                id="employee_id"
                name="employee_id"
                value="{{ old('employee_id') }}"
                placeholder="e.g. EMP-1001"
                class="{{ $errors->has('employee_id') ? 'is-invalid' : '' }}"
                autocomplete="username"
                required
            >
            @error('employee_id')
            <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••"
                autocomplete="current-password"
                required
            >
        </div>

        <button type="submit">Login &rarr;</button>
    </form>

    <div class="footer">
        Problems? Admin se contact karein.
    </div>
</div>

</body>
</html>
