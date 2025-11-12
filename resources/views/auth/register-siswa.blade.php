<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa - SIMAK SMK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-sky-50 to-cyan-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-sky-500 to-cyan-500 p-8 text-white text-center">
            <div class="flex justify-center mb-4">
                @if(file_exists(public_path('images/yapim.png')))
                    <img src="{{ asset('images/yapim.png') }}" alt="Logo YAPIM" class="h-20 w-20 object-contain">
                @else
                    <i class="fas fa-user-graduate text-6xl"></i>
                @endif
            </div>
            <h1 class="text-3xl font-bold mb-2">Registrasi Siswa</h1>
            <p class="text-sky-100">Sistem Informasi Manajemen Akademik SMK</p>
        </div>

        <!-- Form -->
        <div class="p-8">
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('register.siswa.submit') }}" method="POST" id="registerForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- NIS -->
                    <div class="md:col-span-2">
                        <label for="nis" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-id-card text-sky-600 mr-2"></i>NIS (Nomor Induk Siswa)
                        </label>
                        <input type="text" 
                               id="nis" 
                               name="nis" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition"
                               placeholder="Masukkan NIS"
                               value="{{ old('nis') }}">
                        @error('nis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-envelope text-sky-600 mr-2"></i>Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition"
                               placeholder="contoh@email.com"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div class="md:col-span-2">
                        <label for="no_hp" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-phone text-sky-600 mr-2"></i>Nomor HP
                        </label>
                        <input type="text" 
                               id="no_hp" 
                               name="no_hp" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition"
                               placeholder="08xxxxxxxxxx"
                               value="{{ old('no_hp') }}"
                               pattern="[0-9]{10,15}"
                               title="Nomor HP harus berisi 10-15 digit angka">
                        @error('no_hp')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-map-marker-alt text-sky-600 mr-2"></i>Tempat Lahir
                        </label>
                        <input type="text" 
                               id="tempat_lahir" 
                               name="tempat_lahir" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition"
                               placeholder="Kota/Kabupaten"
                               value="{{ old('tempat_lahir') }}">
                        @error('tempat_lahir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-calendar-alt text-sky-600 mr-2"></i>Tanggal Lahir
                        </label>
                        <input type="date" 
                               id="tanggal_lahir" 
                               name="tanggal_lahir" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition"
                               value="{{ old('tanggal_lahir') }}">
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-venus-mars text-sky-600 mr-2"></i>Jenis Kelamin
                        </label>
                        <select id="jenis_kelamin" 
                                name="jenis_kelamin" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Agama -->
                    <div>
                        <label for="agama" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-praying-hands text-sky-600 mr-2"></i>Agama
                        </label>
                        <select id="agama" 
                                name="agama" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition">
                            <option value="" disabled selected>Pilih Agama</option>
                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                        @error('agama')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kelas -->
                    <div class="md:col-span-2">
                        <label for="id_kelas" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-school text-sky-600 mr-2"></i>Kelas
                        </label>
                        <select id="id_kelas" 
                                name="id_kelas" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition">
                            <option value="" disabled selected>Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} @if($k->jurusan) - {{ $k->jurusan }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('id_kelas')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sekolah Asal -->
                    <div class="md:col-span-2">
                        <label for="sekolah_asal" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-building text-sky-600 mr-2"></i>Sekolah Asal
                        </label>
                        <input type="text" 
                               id="sekolah_asal" 
                               name="sekolah_asal" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition"
                               placeholder="Nama SMP/MTs asal"
                               value="{{ old('sekolah_asal') }}">
                        @error('sekolah_asal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-home text-sky-600 mr-2"></i>Alamat Lengkap
                        </label>
                        <textarea id="alamat" 
                                  name="alamat" 
                                  required 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition resize-none"
                                  placeholder="Jl. Nama Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="md:col-span-2">
                        <label for="password" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-lock text-sky-600 mr-2"></i>Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   minlength="6"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition pr-12"
                                   placeholder="Minimal 6 karakter">
                            <button type="button" 
                                    onclick="togglePassword('password')" 
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="md:col-span-2">
                        <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-lock text-sky-600 mr-2"></i>Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   minlength="6"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 transition pr-12"
                                   placeholder="Ulangi password">
                            <button type="button" 
                                    onclick="togglePassword('password_confirmation')" 
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-eye" id="password_confirmation-icon"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-sky-500 to-cyan-500 text-white py-4 rounded-lg font-semibold hover:from-sky-600 hover:to-cyan-600 transition duration-300 transform hover:scale-[1.02] shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Sebagai Siswa
                    </button>
                </div>

                <!-- Link ke Login & Register Guru -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-sky-600 hover:text-sky-800 font-semibold">
                            Login di sini
                        </a>
                    </p>
                    <p class="text-gray-600 mt-2">
                        Daftar sebagai guru? 
                        <a href="{{ route('register.guru') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            Registrasi Guru
                        </a>
                    </p>
                </div>

            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;

            // Check if passwords match
            if (password !== passwordConfirmation) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Cocok',
                    text: 'Password dan konfirmasi password harus sama!',
                    confirmButtonColor: '#0ea5e9'
                });
                return false;
            }

            // Check password length
            if (password.length < 6) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Terlalu Pendek',
                    text: 'Password minimal 6 karakter!',
                    confirmButtonColor: '#0ea5e9'
                });
                return false;
            }
        });

        // Show success message if exists
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0ea5e9'
            });
        @endif

        // ===== AUTOCOMPLETE NIS =====
        const nisInput = document.getElementById('nis');
        const namaInput = document.getElementById('name');
        const tempatLahirInput = document.getElementById('tempat_lahir');
        const tanggalLahirInput = document.getElementById('tanggal_lahir');
        const jenisKelaminRadios = document.getElementsByName('jenis_kelamin');
        const agamaSelect = document.getElementById('agama');
        const kelasSelect = document.getElementById('id_kelas');
        const sekolahAsalInput = document.getElementById('sekolah_asal');
        const alamatTextarea = document.getElementById('alamat');
        
        let typingTimer;
        const doneTypingInterval = 800;
        const minNisLength = 10;

        nisInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            const nis = this.value.trim();
            
            // Reset border colors saat mengetik
            nisInput.classList.remove('border-green-500', 'border-red-500', 'border-yellow-400');
            
            if (nis.length >= minNisLength) { // Minimal 10 karakter untuk mulai search
                nisInput.classList.add('border-yellow-400'); // Show loading indicator
                typingTimer = setTimeout(() => checkNIS(nis), doneTypingInterval);
            } else if (nis.length > 0 && nis.length < minNisLength) {
                // Jangan reset form, hanya beri hint
                // User masih mengetik
            } else {
                // Reset form jika NIS kosong
                resetForm();
            }
        });

        function checkNIS(nis) {
            // NIS input sudah ada border-yellow-400 dari event listener
            
            fetch(`/api/check-nis/${nis}`)
                .then(response => response.json())
                .then(data => {
                    nisInput.classList.remove('border-yellow-400');
                    
                    if (data.found) {
                        if (data.already_registered) {
                            // NIS sudah pernah terdaftar
                            nisInput.classList.add('border-red-500');
                            resetForm();
                            
                            Swal.fire({
                                icon: 'warning',
                                title: 'NIS Sudah Terdaftar',
                                text: 'NIS ini sudah pernah digunakan untuk registrasi.',
                                confirmButtonColor: '#0ea5e9'
                            });
                        } else {
                            // NIS ditemukan, fill form
                            nisInput.classList.add('border-green-500');
                            fillForm(data.data);
                            
                            // Show success notification (tidak blocking)
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            
                            Toast.fire({
                                icon: 'success',
                                title: 'Data Ditemukan!',
                                text: `${data.data.nama_siswa} - ${data.data.nama_kelas}`
                            });
                        }
                    } else {
                        // NIS tidak ditemukan - TIDAK BLOCKING, beri info saja
                        nisInput.classList.add('border-orange-500');
                        
                        // Enable all fields untuk input manual
                        enableFormForManualInput();
                        
                        // Show info toast (bukan error popup)
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                        });
                        
                        Toast.fire({
                            icon: 'info',
                            title: 'NIS Tidak Ditemukan',
                            text: 'Silakan isi form secara manual. Data akan diverifikasi admin.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    nisInput.classList.remove('border-yellow-400');
                    nisInput.classList.add('border-red-500');
                });
        }

        function fillForm(data) {
            // Fill nama (read-only)
            namaInput.value = data.nama_siswa || '';
            namaInput.readOnly = true;
            namaInput.classList.add('bg-gray-100');
            
            // Fill tempat lahir
            tempatLahirInput.value = data.tempat_lahir || '';
            tempatLahirInput.readOnly = true;
            tempatLahirInput.classList.add('bg-gray-100');
            
            // Fill tanggal lahir
            tanggalLahirInput.value = data.tanggal_lahir || '';
            tanggalLahirInput.readOnly = true;
            tanggalLahirInput.classList.add('bg-gray-100');
            
            // Select jenis kelamin
            jenisKelaminRadios.forEach(radio => {
                if (radio.value === data.jenis_kelamin) {
                    radio.checked = true;
                }
                radio.disabled = true;
            });
            
            // Select agama
            if (data.agama) {
                agamaSelect.value = data.agama;
                agamaSelect.disabled = true;
                agamaSelect.classList.add('bg-gray-100');
            }
            
            // Select kelas
            if (data.id_kelas) {
                kelasSelect.value = data.id_kelas;
                kelasSelect.disabled = true;
                kelasSelect.classList.add('bg-gray-100');
            }
            
            // Fill sekolah asal
            sekolahAsalInput.value = data.sekolah_asal || '';
            sekolahAsalInput.readOnly = true;
            sekolahAsalInput.classList.add('bg-gray-100');
            
            // Fill alamat
            alamatTextarea.value = data.alamat || '';
            alamatTextarea.readOnly = true;
            alamatTextarea.classList.add('bg-gray-100');
        }

        function resetForm() {
            nisInput.classList.remove('border-green-500', 'border-red-500', 'border-orange-500');
            
            // Reset and enable all fields
            namaInput.value = '';
            namaInput.readOnly = false;
            namaInput.classList.remove('bg-gray-100');
            
            tempatLahirInput.value = '';
            tempatLahirInput.readOnly = false;
            tempatLahirInput.classList.remove('bg-gray-100');
            
            tanggalLahirInput.value = '';
            tanggalLahirInput.readOnly = false;
            tanggalLahirInput.classList.remove('bg-gray-100');
            
            jenisKelaminRadios.forEach(radio => {
                radio.checked = false;
                radio.disabled = false;
            });
            
            agamaSelect.value = '';
            agamaSelect.disabled = false;
            agamaSelect.classList.remove('bg-gray-100');
            
            kelasSelect.value = '';
            kelasSelect.disabled = false;
            kelasSelect.classList.remove('bg-gray-100');
            
            sekolahAsalInput.value = '';
            sekolahAsalInput.readOnly = false;
            sekolahAsalInput.classList.remove('bg-gray-100');
            
            alamatTextarea.value = '';
            alamatTextarea.readOnly = false;
            alamatTextarea.classList.remove('bg-gray-100');
        }

        function enableFormForManualInput() {
            // Fungsi ini dipanggil saat NIS tidak ditemukan
            // Form dibiarkan kosong dan editable untuk input manual
            
            namaInput.value = '';
            namaInput.readOnly = false;
            namaInput.classList.remove('bg-gray-100');
            namaInput.placeholder = 'Masukkan Nama Lengkap';
            
            tempatLahirInput.value = '';
            tempatLahirInput.readOnly = false;
            tempatLahirInput.classList.remove('bg-gray-100');
            
            tanggalLahirInput.value = '';
            tanggalLahirInput.readOnly = false;
            tanggalLahirInput.classList.remove('bg-gray-100');
            
            jenisKelaminRadios.forEach(radio => {
                radio.checked = false;
                radio.disabled = false;
            });
            
            agamaSelect.value = '';
            agamaSelect.disabled = false;
            agamaSelect.classList.remove('bg-gray-100');
            
            kelasSelect.value = '';
            kelasSelect.disabled = false;
            kelasSelect.classList.remove('bg-gray-100');
            
            sekolahAsalInput.value = '';
            sekolahAsalInput.readOnly = false;
            sekolahAsalInput.classList.remove('bg-gray-100');
            
            alamatTextarea.value = '';
            alamatTextarea.readOnly = false;
            alamatTextarea.classList.remove('bg-gray-100');
        }
    </script>

</body>
</html>
