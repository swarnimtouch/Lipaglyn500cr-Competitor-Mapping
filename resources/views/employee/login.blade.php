<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login | Lipaglyn</title>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Google Font: DM Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Core Colors */
            --color-a:       #009ea3;
            --color-b:       #b3569f;
            --gradient-primary: linear-gradient(135deg, var(--color-a), var(--color-b));
            
            /* Surface Colors */
            --bg:            #f4f7fe;
            --card-bg:       #ffffff;
            --text:          #2b3674;
            --text-muted:    #8a8fa8;
            --border:        #e4e8f0;
            --input-bg:      #f8f9fc;
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'DM Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            /* Subtle background accent to make it eye-catching */
            background-image: radial-gradient(circle at 15% 50%, rgba(0, 158, 163, 0.05), transparent 25%),
                              radial-gradient(circle at 85% 30%, rgba(179, 86, 159, 0.05), transparent 25%);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 48px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 15px 35px rgba(0, 158, 163, 0.08);
            position: relative;
            overflow: hidden;
        }

        /* Top gradient border line for the card */
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 5px;
            background: var(--gradient-primary);
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        /* Naye logo ke liye width aur height set ki gayi hai */
        .logo .brand-logo {
            max-width: 100%;
            height: 65px; /* Size ko jarurat ke hisab se adjust kar sakte hain */
            width: auto;
            object-fit: contain;
            margin-bottom: 16px;
        }

        h1 {
            color: var(--text);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
        }

        .error-box {
            background: rgba(239, 68, 68, 0.1);
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            padding: 14px 16px;
            color: #b91c1c;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: var(--text);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 16px;
            color: var(--text);
            font-size: 15px;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: #a3aed1;
        }

        input:focus {
            border-color: var(--color-a);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(0, 158, 163, 0.1);
        }

        input.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .field-error {
            color: #ef4444;
            font-size: 13px;
            font-weight: 500;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Password Toggle UI ── */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 45px; /* Icon ke liye input me right side space */
        }

        .toggle-password-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            font-size: 16px;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .toggle-password-icon:hover {
            color: var(--color-a); /* Hover karne par Teal color aayega */
        }

        button[type="submit"] {
            width: 100%;
            background: var(--gradient-primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 158, 163, 0.2);
        }

        button[type="submit"]:hover { 
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(179, 86, 159, 0.3);
        }

        button[type="submit"]:active { 
            transform: translateY(0); 
        }

        .footer {
            text-align: center;
            margin-top: 32px;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
        }

        .footer i {
            color: var(--color-b);
            margin-right: 4px;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="logo">
        <img src="{{ URL::asset('assets/logo_2.png') }}" alt="Lipaglyn Logo" class="brand-logo">
        <h1>Employee Portal</h1>
        {{-- <p class="subtitle">Enter your credentials to login</p> --}}
    </div>

    {{-- Server-side Error Message --}}
    @if ($errors->any())
        <div class="error-box">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form id="loginForm" method="POST" action="{{ route('employee.doLogin') }}">
        @csrf

        <div class="form-group">
            <label for="employee_id">Employee ID</label>
            <input
                type="text"
                id="employee_id"
                name="employee_id"
                value="{{ old('employee_id') }}"
                placeholder="Enter employee id"
                class="{{ $errors->has('employee_id') ? 'is-invalid' : '' }}"
                autocomplete="username"
            >
            {{-- Server-side field error --}}
            @error('employee_id')
            <div class="field-error">
                <i class="fas fa-info-circle"></i> {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    autocomplete="current-password"
                >
                <i class="fas fa-eye-slash toggle-password-icon" id="togglePassword"></i>
            </div>
        </div>

        <button type="submit">
            Login <i class="fas fa-arrow-right"></i>
        </button>
    </form>

    {{-- <div class="footer">
        <i class="fas fa-headset"></i> Having trouble? Contact Admin.
    </div> --}}
</div>

{{-- jQuery & jQuery Validation Plugins --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

{{-- Form Validation Initialization --}}
<script>
    $(document).ready(function() {
        $("#loginForm").validate({
            // Validation Rules
            rules: {
                employee_id: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            // Custom Error Messages
            messages: {
                employee_id: {
                    required: "Please enter your Employee ID."
                },
                password: {
                    required: "Please enter your password."
                }
            },
            // Mapping classes to existing design
            errorElement: "div",
            errorClass: "field-error",
            
            // Add 'is-invalid' class to input when error occurs
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            
            // Remove 'is-invalid' class when corrected
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
            
            // Custom HTML for the error message to match the design
            errorPlacement: function(error, element) {
                var icon = $('<i>').addClass('fas fa-info-circle').css('margin-right', '6px');
                error.prepend(icon);
                
                // Agar input password-wrapper ke andar hai, toh error wrapper ke baad dikhao
                if (element.parent().hasClass('password-wrapper')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // ── Password Hide/Show Logic ──
        $('#togglePassword').on('click', function() {
            var passwordInput = $('#password');
            var icon = $(this);

            // Agar password hide hai, toh text (show) karo aur icon change karo
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } 
            // Agar show hai, toh wapas password (hide) karo aur icon slash karo
            else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
    });
</script>

</body>
</html>