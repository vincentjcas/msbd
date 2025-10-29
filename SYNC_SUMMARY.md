# Sinkronisasi Database dengan Laravel - SIMAK SMK

## 📋 Ringkasan Perubahan

Database telah diupdate dengan fitur-fitur baru sesuai requirement. Berikut adalah perubahan yang telah dilakukan:

## ✅ Yang Sudah Ditambahkan

### 1. **Model Baru (6 models)**
- `BackupLog` - Logging backup database
- `LaporanAktivitas` - Laporan aktivitas guru/pembina
- `EvaluasiKepsek` - Evaluasi dari kepala sekolah
- `JadwalStatus` - Status jadwal pelajaran
- `LogAktivitas` - Audit trail aktivitas
- `LogNilai` - Logging perubahan nilai

### 2. **View Models (8 views)**
- `VAdminInfo`
- `VRekapPresensiGuruStaf`
- `VRekapPresensiSiswa`
- `VTugasStatus`
- `VGrafikKehadiranHarian`
- `VGrafikKehadiranSiswaHarian`
- `VStatistikKehadiranKelas`
- `VStatusIzinSiswa`

### 3. **Services (3 services)**
- `DatabaseProcedureService` - Untuk memanggil stored procedures
- `DatabaseFunctionService` - Untuk memanggil database functions
- `LogActivityService` - Untuk logging aktivitas user

### 4. **Controllers (3 controllers)**
- `IzinController` - Mengelola izin
- `PresensiController` - Mengelola presensi
- `LaporanAktivitasController` - Mengelola laporan aktivitas

### 5. **Model Updates**
Updated models dengan fillable, casts, dan relationships:
- `User`
- `Guru`
- `Pembina`
- `KepalaSekolah`
- `Izin`
- `Materi`
- `Presensi`
- `Jadwal`

### 6. **Migrations (6 migrations)**
- Backup log table
- Laporan aktivitas table
- Evaluasi kepsek table
- Jadwal status table
- Log aktivitas table
- Update izin table

## 🚀 Cara Menggunakan

### 1. Jalankan Migration (Opsional)
```bash
php artisan migrate
```
*Note: Tabel sudah dibuat manual di database, migration hanya untuk tracking.*

### 2. Test Koneksi Database
```bash
php artisan tinker
```

```php
// Test model
App\Models\User::count();

// Test view
App\Models\Views\VRekapPresensiSiswa::count();

// Test service
$service = app(\App\Services\DatabaseProcedureService::class);
$rekap = $service->rekapPresensiBulanan(10, 2025, null);
print_r($rekap);
```

### 3. Contoh Penggunaan di Controller

#### Approve Izin
```php
use App\Services\DatabaseProcedureService;

public function approveIzin($id, Request $request)
{
    $dbProcedure = app(DatabaseProcedureService::class);
    
    $dbProcedure->approveIzin(
        $id,
        auth()->user()->id_user,
        $request->status,
        $request->catatan
    );
}
```

#### Hitung Persentase Kehadiran
```php
use App\Services\DatabaseFunctionService;

public function getPersentase($idUser, $bulan, $tahun)
{
    $dbFunction = app(DatabaseFunctionService::class);
    $persentase = $dbFunction->hitungPersentaseKehadiran($idUser, $bulan, $tahun);
    
    return $persentase;
}
```

#### Logging Aktivitas
```php
use App\Services\LogActivityService;

public function someAction()
{
    $logActivity = app(LogActivityService::class);
    $logActivity->logLogin(auth()->user()->id_user);
}
```

#### Menggunakan View
```php
use App\Models\Views\VRekapPresensiSiswa;

public function rekapSiswa($bulan, $tahun)
{
    $rekap = VRekapPresensiSiswa::where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->get();
    
    return view('rekap', compact('rekap'));
}
```

## 📁 Struktur File Baru

```
app/
├── Models/
│   ├── BackupLog.php
│   ├── LaporanAktivitas.php
│   ├── EvaluasiKepsek.php
│   ├── JadwalStatus.php
│   ├── LogAktivitas.php
│   ├── LogNilai.php
│   └── Views/
│       ├── VAdminInfo.php
│       ├── VRekapPresensiGuruStaf.php
│       ├── VRekapPresensiSiswa.php
│       ├── VTugasStatus.php
│       ├── VGrafikKehadiranHarian.php
│       ├── VGrafikKehadiranSiswaHarian.php
│       ├── VStatistikKehadiranKelas.php
│       └── VStatusIzinSiswa.php
├── Services/
│   ├── DatabaseProcedureService.php
│   ├── DatabaseFunctionService.php
│   └── LogActivityService.php
└── Http/Controllers/
    ├── IzinController.php
    ├── PresensiController.php
    └── LaporanAktivitasController.php
```

## 🔗 Mapping Fitur ke Database

### Admin
- ✅ Backup database → `BackupLog` model + `backup_log` table
- ✅ Monitoring presensi → Views + Procedures
- ✅ CRUD pengguna → Updated User model
- ✅ Log aktivitas → `LogAktivitas` model

### Kepala Sekolah
- ✅ Grafik kehadiran → `VGrafikKehadiranHarian`, `VGrafikKehadiranSiswaHarian`
- ✅ Laporan presensi → `VRekapPresensiGuruStaf`, `VRekapPresensiSiswa`
- ✅ Review laporan → `LaporanAktivitas` model
- ✅ Approve izin → `sp_approve_izin` procedure
- ✅ Evaluasi guru/pembina → `EvaluasiKepsek` model

### Pembina
- ✅ Statistik kehadiran → Views
- ✅ Data presensi (read-only) → Views
- ✅ Status jadwal → `JadwalStatus` model
- ✅ Review materi → `check_materi_compliance` function
- ✅ Laporan aktivitas → `LaporanAktivitas` model

### Guru
- ✅ Presensi kehadiran → `sp_input_presensi_harian` procedure
- ✅ Rekap siswa → `VRekapPresensiSiswa`, `VStatistikKehadiranKelas`
- ✅ Approve izin siswa → `sp_approve_izin` procedure
- ✅ Upload materi → Updated `Materi` model
- ✅ Rekap kehadiran → Views + Functions

### Siswa
- ✅ Presensi harian → `Presensi`, `PresensiSiswa` models
- ✅ Jadwal & status → `Jadwal`, `JadwalStatus` models
- ✅ Submit izin → Updated `Izin` model
- ✅ Download materi → `Materi` model
- ✅ Persentase kehadiran → `hitung_persentase_kehadiran` function
- ✅ Status izin → `VStatusIzinSiswa` view

## 📝 Catatan Penting

1. **Database sudah diupdate** - Semua tabel, views, procedures, functions, dan triggers sudah ada di database
2. **Models sudah sync** - Semua model Laravel sudah disesuaikan dengan struktur database
3. **Services siap pakai** - DatabaseProcedureService dan DatabaseFunctionService sudah tersedia
4. **Controllers contoh** - IzinController, PresensiController, LaporanAktivitasController sebagai referensi

## 🔍 Next Steps

1. **Buat Routes** - Tambahkan routes di `routes/web.php` untuk controller yang sudah dibuat
2. **Buat Views** - Buat blade templates untuk menampilkan data
3. **Implement Middleware** - Tambahkan role middleware untuk authorization
4. **Testing** - Test semua fitur yang sudah dibuat

## 📚 Dokumentasi Lengkap

Lihat file `DATABASE_SYNC.md` untuk dokumentasi lengkap tentang:
- Semua models dan relationships
- Cara penggunaan services
- Contoh penggunaan di controller
- Testing dan debugging

---

**Status:** ✅ Database dan Laravel sudah tersinkronisasi
**Tanggal:** 29 Oktober 2025
