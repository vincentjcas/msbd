<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMK Yapim Biru-Biru</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0369a1 0%, #06b6d4 50%, #14b8a6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #666;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0369a1;
        }
        .btn {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .register-link a {
            color: #0369a1;
            text-decoration: none;
            font-weight: 500;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .register-options {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        .register-options p {
            text-align: center;
            color: #666;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        .register-buttons {
            display: flex;
            gap: 1rem;
        }
        .register-btn {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .register-btn-guru {
            background: white;
            color: #2563eb;
            border-color: #2563eb;
        }
        .register-btn-guru:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
        }
        .register-btn-siswa {
            background: white;
            color: #0ea5e9;
            border-color: #0ea5e9;
        }
        .register-btn-siswa:hover {
            background: #0ea5e9;
            color: white;
            transform: translateY(-2px);
        }
        .icon {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Login</h1>
            <p>Sistem Multi-Role Authentication</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="identifier">NIS / NIP / Email</label>
                <input type="text" id="identifier" name="identifier" value="{{ old('identifier') }}" placeholder="Masukkan NIS, NIP, atau Email" required>
                <small style="color: #666; font-size: 0.85rem; display: block; margin-top: 0.25rem;">
                </small>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <!-- Opsi Registrasi -->
        <div class="register-options">
            <p>Belum punya akun? Daftar sebagai:</p>
            <div class="register-buttons">
                <a href="{{ route('register.guru') }}" class="register-btn register-btn-guru">
                    <i class="fas fa-chalkboard-teacher icon"></i>
                    Guru
                </a>
                <a href="{{ route('register.siswa') }}" class="register-btn register-btn-siswa">
                    <i class="fas fa-user-graduate icon"></i>
                    Siswa
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += '{{ $error }}<br>';
            @endforeach
            
            Swal.fire({
                title: 'Terjadi Kesalahan!',
                html: errorMessages,
                icon: 'error',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#667eea'
            });
        });
    </script>
    @endif

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#667eea',
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Gagal Login!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#667eea'
            });
        });
    </script>
    @endif
</body>
</html>
