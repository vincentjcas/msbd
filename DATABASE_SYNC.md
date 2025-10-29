# Database Synchronization - SIMAK SMK

## Models Baru

### 1. BackupLog
Model untuk logging backup database
- Path: `App\Models\BackupLog`
- Table: `backup_log`

### 2. LaporanAktivitas
Model untuk laporan aktivitas guru/pembina
- Path: `App\Models\LaporanAktivitas`
- Table: `laporan_aktivitas`

### 3. EvaluasiKepsek
Model untuk evaluasi dari kepala sekolah
- Path: `App\Models\EvaluasiKepsek`
- Table: `evaluasi_kepsek`

### 4. JadwalStatus
Model untuk status jadwal pelajaran
- Path: `App\Models\JadwalStatus`
- Table: `jadwal_status`

### 5. LogAktivitas
Model untuk audit trail aktivitas user
- Path: `App\Models\LogAktivitas`
- Table: `log_aktivitas`

### 6. LogNilai
Model untuk logging perubahan nilai
- Path: `App\Models\LogNilai`
- Table: `log_nilai`

## View Models

### 1. VAdminInfo
- Path: `App\Models\Views\VAdminInfo`
- View: `v_admin_info`

### 2. VRekapPresensiGuruStaf
- Path: `App\Models\Views\VRekapPresensiGuruStaf`
- View: `v_rekap_presensi_guru_staf`

### 3. VRekapPresensiSiswa
- Path: `App\Models\Views\VRekapPresensiSiswa`
- View: `v_rekap_presensi_siswa`

### 4. VTugasStatus
- Path: `App\Models\Views\VTugasStatus`
- View: `v_tugas_status`

### 5. VGrafikKehadiranHarian
- Path: `App\Models\Views\VGrafikKehadiranHarian`
- View: `v_grafik_kehadiran_harian`

### 6. VGrafikKehadiranSiswaHarian
- Path: `App\Models\Views\VGrafikKehadiranSiswaHarian`
- View: `v_grafik_kehadiran_siswa_harian`

### 7. VStatistikKehadiranKelas
- Path: `App\Models\Views\VStatistikKehadiranKelas`
- View: `v_statistik_kehadiran_kelas`

### 8. VStatusIzinSiswa
- Path: `App\Models\Views\VStatusIzinSiswa`
- View: `v_status_izin_siswa`

## Services

### 1. DatabaseProcedureService
Service untuk memanggil stored procedures

**Methods:**
```php
// Approve/reject izin
approveIzin(int $idIzin, int $idApprover, string $status, ?string $catatan)

// Hapus siswa
hapusSiswa(int $idSiswa)

// Input presensi harian
inputPresensiHarian(int $idUser, string $tanggal, string $jamMasuk, string $status, ?string $keterangan)

// Rekap presensi bulanan
rekapPresensiBulanan(int $bulan, int $tahun, ?string $role)

// Rekap tugas kelas
rekapTugasKelas(int $idKelas)
```

### 2. DatabaseFunctionService
Service untuk memanggil database functions

**Methods:**
```php
// Cek keterlambatan
cekKeterlambatan(string $waktuSubmit, string $deadline): string

// Hitung persentase kehadiran
hitungPersentaseKehadiran(int $idUser, int $bulan, int $tahun): float

// Hitung rata-rata nilai
hitungRataNilai(int $idSiswa, int $idKelas): float

// Check materi compliance
checkMateriCompliance(int $idMateri): string
```

### 3. LogActivityService
Service untuk logging aktivitas user

**Methods:**
```php
// Log generic
log(string $tipeAktivitas, ?int $idUser, string $deskripsi, ?string $ipAddress, ?string $userAgent)

// Log login
logLogin(int $idUser)

// Log logout
logLogout(int $idUser)

// Log approval izin
logApprovalIzin(int $idUser, int $idIzin, string $status)

// Log CRUD
logCrud(string $action, int $idUser, string $tableName, $recordId)
```

## Controllers

### 1. IzinController
Controller untuk mengelola izin

**Routes:**
```php
GET  /izin                 - index()
GET  /izin/siswa           - statusIzinSiswa()
POST /izin/{id}/approve    - approve()
GET  /izin/pending         - pending()
```

### 2. PresensiController
Controller untuk mengelola presensi

**Routes:**
```php
POST /presensi/input              - inputPresensi()
GET  /presensi/rekap-bulanan      - rekapBulanan()
GET  /presensi/grafik             - grafikKehadiran()
GET  /presensi/guru-staf          - rekapGuruStaf()
GET  /presensi/siswa              - rekapSiswa()
GET  /presensi/kelas/{id}         - statistikKelas()
GET  /presensi/persentase/{id}    - persentaseKehadiran()
```

### 3. LaporanAktivitasController
Controller untuk mengelola laporan aktivitas

**Routes:**
```php
GET  /laporan                    - index()
POST /laporan                    - store()
POST /laporan/{id}/review        - review()
POST /laporan/{id}/evaluasi      - addEvaluasi()
```

## Cara Penggunaan

### 1. Menggunakan Stored Procedure
```php
use App\Services\DatabaseProcedureService;

$dbProcedure = new DatabaseProcedureService();

// Approve izin
$dbProcedure->approveIzin(1, 5, 'approved', 'Disetujui');

// Rekap presensi
$rekap = $dbProcedure->rekapPresensiBulanan(10, 2025, 'guru');
```

### 2. Menggunakan Database Function
```php
use App\Services\DatabaseFunctionService;

$dbFunction = new DatabaseFunctionService();

// Cek keterlambatan
$status = $dbFunction->cekKeterlambatan('2025-10-30 10:00:00', '2025-10-29 23:59:59');

// Hitung persentase
$persentase = $dbFunction->hitungPersentaseKehadiran(1, 10, 2025);
```

### 3. Menggunakan View
```php
use App\Models\Views\VRekapPresensiSiswa;

// Get rekap presensi siswa
$rekap = VRekapPresensiSiswa::where('bulan', 10)
    ->where('tahun', 2025)
    ->get();
```

### 4. Logging Aktivitas
```php
use App\Services\LogActivityService;

$logActivity = new LogActivityService();

// Log login
$logActivity->logLogin(auth()->user()->id_user);

// Log CRUD
$logActivity->logCrud('create', auth()->user()->id_user, 'siswa', $siswa->id_siswa);
```

## Migrations

Jalankan migrations untuk membuat tabel baru:
```bash
php artisan migrate
```

Migration files yang ditambahkan:
- `2025_10_29_000001_create_backup_log_table.php`
- `2025_10_29_000002_create_laporan_aktivitas_table.php`
- `2025_10_29_000003_create_evaluasi_kepsek_table.php`
- `2025_10_29_000004_create_jadwal_status_table.php`
- `2025_10_29_000005_create_log_aktivitas_table.php`
- `2025_10_29_000006_update_izin_table.php`

## Catatan Penting

1. **Database Views** sudah dibuat langsung di database melalui SQL query
2. **Stored Procedures** dan **Functions** sudah ada di database
3. **Triggers** sudah aktif di database
4. Pastikan koneksi database di `.env` sudah benar
5. Model-model sudah include relationships yang diperlukan

## Testing

Test koneksi dan fungsi dasar:
```bash
php artisan tinker
```

```php
// Test view
App\Models\Views\VRekapPresensiSiswa::count();

// Test service
$service = new App\Services\DatabaseFunctionService();
$service->hitungPersentaseKehadiran(1, 10, 2025);

// Test relationship
$user = App\Models\User::find(1);
$user->presensi;
```
