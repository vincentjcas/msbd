<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SMK Yapim Biru-Biru</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .register-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .register-header h1 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .register-header p {
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
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus, .form-group select:focus {
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
        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
        .login-link a {
            color: #0369a1;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Register</h1>
            <p>Daftar Akun Baru</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required onchange="toggleKelasField()">
                    <option value="">Pilih Role</option>
                    <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>

            <div class="form-group" id="nisn-field" style="display: none;">
                <label for="nisn">NISN (Nomor Induk Siswa Nasional)</label>
                <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" placeholder="Contoh: 0123456789">
            </div>

            <div class="form-group" id="kelas-field" style="display: none;">
                <label for="id_kelas">Kelas</label>
                <select id="id_kelas" name="id_kelas">
                    <option value="">Pilih Kelas</option>
                    @if(isset($kelas) && $kelas->count() > 0)
                        @php
                            $currentTingkat = null;
                        @endphp
                        @foreach($kelas as $item)
                            @if($currentTingkat !== $item->tingkat)
                                @if($currentTingkat !== null)
                                    </optgroup>
                                @endif
                                <optgroup label="Kelas {{ $item->tingkat }}">
                                @php
                                    $currentTingkat = $item->tingkat;
                                @endphp
                            @endif
                            <option value="{{ $item->id_kelas }}" {{ old('id_kelas') == $item->id_kelas ? 'selected' : '' }}>
                                {{ $item->nama_kelas }} - {{ $item->jurusan }}
                            </option>
                        @endforeach
                        @if($currentTingkat !== null)
                            </optgroup>
                        @endif
                    @else
                        <option value="">Tidak ada kelas tersedia</option>
                    @endif
                </select>
            </div>

            <div class="form-group" id="nip-field" style="display: none;">
                <label for="nip">NIP (Nomor Induk Pegawai)</label>
                <input type="text" id="nip" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 123456789012345678">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn">Register</button>
        </form>

        <div class="login-link">
            <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
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
                confirmButtonColor: '#0369a1'
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
                confirmButtonColor: '#0369a1',
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
    @endif

    <script>
        // Toggle field kelas, NISN, dan NIP berdasarkan role yang dipilih
        function toggleKelasField() {
            const role = document.getElementById('role').value;
            const kelasField = document.getElementById('kelas-field');
            const nisnField = document.getElementById('nisn-field');
            const nipField = document.getElementById('nip-field');
            const kelasSelect = document.getElementById('id_kelas');
            const nisnInput = document.getElementById('nisn');
            const nipInput = document.getElementById('nip');
            
            if (role === 'siswa') {
                kelasField.style.display = 'block';
                nisnField.style.display = 'block';
                nipField.style.display = 'none';
                
                kelasSelect.setAttribute('required', 'required');
                nisnInput.setAttribute('required', 'required');
                nipInput.removeAttribute('required');
                nipInput.value = ''; // Reset NIP
            } else if (role === 'guru') {
                kelasField.style.display = 'none';
                nisnField.style.display = 'none';
                nipField.style.display = 'block';
                
                kelasSelect.removeAttribute('required');
                nisnInput.removeAttribute('required');
                nipInput.setAttribute('required', 'required');
                kelasSelect.value = ''; // Reset pilihan kelas
                nisnInput.value = ''; // Reset NISN
            } else {
                kelasField.style.display = 'none';
                nisnField.style.display = 'none';
                nipField.style.display = 'none';
                
                kelasSelect.removeAttribute('required');
                nisnInput.removeAttribute('required');
                nipInput.removeAttribute('required');
                kelasSelect.value = '';
                nisnInput.value = '';
                nipInput.value = '';
            }
        }

        // Jalankan saat halaman dimuat untuk handle old() value
        document.addEventListener('DOMContentLoaded', function() {
            toggleKelasField();
        });
    </script>
</body>
</html>
