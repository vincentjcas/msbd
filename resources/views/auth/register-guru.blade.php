<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Guru - SIMAK SMK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <style>
        /* Tom Select Custom Styling */
        .ts-wrapper.form-control,
        .ts-wrapper.form-select {
            padding: 0 !important;
        }
        .ts-control {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1rem !important;
            min-height: 48px !important;
            font-size: 1rem !important;
        }
        .ts-control:focus-within {
            outline: none !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
        /* Placeholder styling - abu-abu seperti input biasa */
        .ts-control input::placeholder {
            color: #9ca3af !important;
            opacity: 1 !important;
        }
        /* Input text size */
        .ts-control input {
            font-size: 1rem !important;
        }
        .ts-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            margin-top: 0.25rem !important;
        }
        .ts-dropdown .option {
            padding: 0.75rem 1rem !important;
            font-size: 1rem !important;
            color: #374151 !important;
        }
        .ts-dropdown .option:hover {
            background-color: #eff6ff !important;
            color: #1e40af !important;
        }
        /* Selected item in dropdown - warna biru dengan text putih */
        .ts-dropdown .active {
            background-color: #3b82f6 !important;
            color: white !important;
        }
        /* Chip/badge yang sudah dipilih */
        .item {
            background-color: #3b82f6 !important;
            color: white !important;
            border-radius: 0.375rem !important;
            padding: 0.375rem 0.75rem !important;
            margin: 0.125rem !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
        }
        .remove {
            border-left: 1px solid rgba(255, 255, 255, 0.3) !important;
            padding-left: 0.5rem !important;
            margin-left: 0.25rem !important;
            color: white !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-white text-center">
            <div class="flex justify-center mb-4">
                @if(file_exists(public_path('images/yapim.png')))
                    <img src="{{ asset('images/yapim.png') }}" alt="Logo YAPIM" class="h-20 w-20 object-contain">
                @else
                    <i class="fas fa-chalkboard-teacher text-6xl"></i>
                @endif
            </div>
            <h1 class="text-3xl font-bold mb-2">Registrasi Guru</h1>
            <p class="text-blue-100">Sistem Informasi Manajemen Akademik SMK</p>
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

            <form action="{{ route('register.guru.submit') }}" method="POST" id="registerForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- NIP -->
                    <div class="md:col-span-2">
                        <label for="nip" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-id-card text-blue-600 mr-2"></i>NIP (Nomor Induk Pegawai)
                        </label>
                        <input type="text" 
                               id="nip" 
                               name="nip" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                               placeholder="Masukkan NIP"
                               value="{{ old('nip') }}">
                        @error('nip')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-envelope text-blue-600 mr-2"></i>Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                               placeholder="contoh@email.com"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div class="md:col-span-2">
                        <label for="no_hp" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-phone text-blue-600 mr-2"></i>Nomor HP
                        </label>
                        <input type="text" 
                               id="no_hp" 
                               name="no_hp" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                               placeholder="08xxxxxxxxxx"
                               value="{{ old('no_hp') }}"
                               pattern="[0-9]{10,15}"
                               title="Nomor HP harus berisi 10-15 digit angka">
                        @error('no_hp')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-venus-mars text-blue-600 mr-2"></i>Jenis Kelamin
                        </label>
                        <select id="jenis_kelamin" 
                                name="jenis_kelamin" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
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
                            <i class="fas fa-praying-hands text-blue-600 mr-2"></i>Agama
                        </label>
                        <select id="agama" 
                                name="agama" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
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

                    <!-- Mata Pelajaran (Multiple Selection) -->
                    <div class="md:col-span-2">
                        <label for="mata_pelajaran" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-book text-blue-600 mr-2"></i>Mata Pelajaran yang Diampu
                        </label>
                        <select id="mata_pelajaran" 
                                name="mata_pelajaran[]" 
                                multiple 
                                required 
                                placeholder="Pilih mata pelajaran..."
                                class="w-full">
                            <option value="Matematika">Matematika</option>
                            <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                            <option value="Bahasa Inggris">Bahasa Inggris</option>
                            <option value="Fisika">Fisika</option>
                            <option value="Kimia">Kimia</option>
                            <option value="Biologi">Biologi</option>
                            <option value="Sejarah">Sejarah</option>
                            <option value="Geografi">Geografi</option>
                            <option value="Ekonomi">Ekonomi</option>
                            <option value="Sosiologi">Sosiologi</option>
                            <option value="Seni Budaya">Seni Budaya</option>
                            <option value="Pendidikan Jasmani">Pendidikan Jasmani</option>
                            <option value="Pendidikan Agama">Pendidikan Agama</option>
                            <option value="PKn">Pendidikan Kewarganegaraan (PKn)</option>
                            <option value="Teknik Komputer dan Jaringan">Teknik Komputer dan Jaringan</option>
                            <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                            <option value="Multimedia">Multimedia</option>
                            <option value="Akuntansi">Akuntansi</option>
                            <option value="Administrasi Perkantoran">Administrasi Perkantoran</option>
                            <option value="Pemasaran">Pemasaran</option>
                        </select>
                        @error('mata_pelajaran')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="md:col-span-2">
                        <label for="password" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-lock text-blue-600 mr-2"></i>Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   minlength="6"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition pr-12"
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
                            <i class="fas fa-lock text-blue-600 mr-2"></i>Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   minlength="6"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition pr-12"
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
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-300 transform hover:scale-[1.02] shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Sebagai Guru
                    </button>
                </div>

                <!-- Link ke Login & Register Siswa -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            Login di sini
                        </a>
                    </p>
                    <p class="text-gray-600 mt-2">
                        Daftar sebagai siswa? 
                        <a href="{{ route('register.siswa') }}" class="text-green-600 hover:text-green-800 font-semibold">
                            Registrasi Siswa
                        </a>
                    </p>
                </div>

            </form>
        </div>
    </div>

    <script>
        // Initialize Tom Select for Mata Pelajaran
        new TomSelect('#mata_pelajaran', {
            plugins: ['remove_button'],
            placeholder: 'Pilih mata pelajaran...',
            maxItems: null,
            create: false,
            onInitialize: function() {
                this.control_input.setAttribute('required', 'required');
            }
        });

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
            const mataPelajaran = document.querySelector('#mata_pelajaran').tomselect.items;

            // Check if passwords match
            if (password !== passwordConfirmation) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Cocok',
                    text: 'Password dan konfirmasi password harus sama!',
                    confirmButtonColor: '#2563eb'
                });
                return false;
            }

            // Check if at least one mata pelajaran selected
            if (mataPelajaran.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Mata Pelajaran Belum Dipilih',
                    text: 'Pilih minimal satu mata pelajaran yang diampu!',
                    confirmButtonColor: '#2563eb'
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
                    confirmButtonColor: '#2563eb'
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
                confirmButtonColor: '#2563eb'
            });
        @endif
    </script>

</body>
</html>
