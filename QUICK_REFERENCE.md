# Quick Reference - Query & Usage

## 🔍 Query Sesuai Gambar Requirement

### III.5 Daftar Tabel, Constraint, View, dan Relasi Antar Tabel

#### 1. Daftar semua tabel & tipe
```sql
SET @db := 'db_simak_smk';

SELECT TABLE_NAME, TABLE_TYPE, ENGINE, CREATE_TIME
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = @db
ORDER BY TABLE_TYPE DESC, TABLE_NAME;
```

#### 2. Daftar field per tabel
```sql
SELECT
  TABLE_NAME,
  ORDINAL_POSITION AS no_urut,
  COLUMN_NAME      AS nama_field,
  UCASE(DATA_TYPE) AS jenis_field,
  COALESCE(CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION) AS ukuran,
  COLUMN_TYPE      AS tipe_lengkap,
  IS_NULLABLE,
  COLUMN_DEFAULT,
  COLUMN_COMMENT   AS keterangan
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = @db
ORDER BY TABLE_NAME, ORDINAL_POSITION;
```

#### 3. Daftar constraint (PK/UK/FK)
```sql
SELECT
  tc.TABLE_NAME,
  tc.CONSTRAINT_NAME,
  tc.CONSTRAINT_TYPE,
  kcu.COLUMN_NAME,
  kcu.REFERENCED_TABLE_NAME,
  kcu.REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
  ON  tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
  AND tc.TABLE_SCHEMA    = kcu.TABLE_SCHEMA
  AND tc.TABLE_NAME      = kcu.TABLE_NAME
WHERE tc.TABLE_SCHEMA = @db
ORDER BY tc.TABLE_NAME, tc.CONSTRAINT_TYPE, tc.CONSTRAINT_NAME;
```

#### 4. Relasi antar tabel (Foreign Key)
```sql
SELECT
  kcu.TABLE_NAME,
  kcu.COLUMN_NAME,
  kcu.REFERENCED_TABLE_NAME,
  kcu.REFERENCED_COLUMN_NAME,
  rc.UPDATE_RULE,
  rc.DELETE_RULE,
  kcu.CONSTRAINT_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
  ON rc.CONSTRAINT_SCHEMA = kcu.TABLE_SCHEMA
 AND rc.CONSTRAINT_NAME   = kcu.CONSTRAINT_NAME
WHERE kcu.TABLE_SCHEMA = @db
  AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
ORDER BY kcu.TABLE_NAME, kcu.COLUMN_NAME;
```

#### 5. Daftar view
```sql
SELECT TABLE_NAME AS view_name
FROM INFORMATION_SCHEMA.VIEWS
WHERE TABLE_SCHEMA = @db
ORDER BY TABLE_NAME;
```

### III.6 Trigger, Fungsi, dan Prosedur Tersimpan

#### 1. Daftar trigger
```sql
SELECT
  TRIGGER_NAME,
  EVENT_MANIPULATION AS event,
  ACTION_TIMING      AS timing,
  EVENT_OBJECT_TABLE AS tabel,
  ACTION_STATEMENT   AS definisi
FROM INFORMATION_SCHEMA.TRIGGERS
WHERE TRIGGER_SCHEMA = @db
ORDER BY EVENT_OBJECT_TABLE, TRIGGER_NAME;
```

#### 2. Daftar fungsi (FUNCTION)
```sql
SELECT
  ROUTINE_NAME,
  DTD_IDENTIFIER AS returns,
  ROUTINE_DEFINITION AS definisi
FROM INFORMATION_SCHEMA.ROUTINES
WHERE ROUTINE_SCHEMA = @db
  AND ROUTINE_TYPE = 'FUNCTION'
ORDER BY ROUTINE_NAME;
```

#### 3. Daftar prosedur (PROCEDURE)
```sql
SELECT
  ROUTINE_NAME,
  ROUTINE_DEFINITION AS definisi
FROM INFORMATION_SCHEMA.ROUTINES
WHERE ROUTINE_SCHEMA = @db
  AND ROUTINE_TYPE = 'PROCEDURE'
ORDER BY ROUTINE_NAME;
```

## 💻 Contoh Penggunaan di Laravel

### Stored Procedures

#### 1. Approve Izin
```php
// Di Controller
use App\Services\DatabaseProcedureService;

public function approveIzin($idIzin, Request $request)
{
    db_procedure()->approveIzin(
        $idIzin,
        auth()->user()->id_user,
        $request->status, // 'approved' atau 'rejected'
        $request->catatan
    );
    
    quick_log('approval_izin', "Approve izin ID {$idIzin}");
    
    return redirect()->back()->with('success', 'Izin berhasil diproses');
}
```

#### 2. Input Presensi Harian
```php
db_procedure()->inputPresensiHarian(
    $idUser,
    date('Y-m-d'),
    date('H:i:s'),
    'hadir',
    null
);
```

#### 3. Rekap Presensi Bulanan
```php
$rekap = db_procedure()->rekapPresensiBulanan(
    10,    // bulan
    2025,  // tahun
    'guru' // role (optional)
);

return view('rekap', compact('rekap'));
```

#### 4. Rekap Tugas Kelas
```php
$rekap = db_procedure()->rekapTugasKelas($idKelas);
```

#### 5. Hapus Siswa
```php
db_procedure()->hapusSiswa($idSiswa);
quick_log('delete', "Hapus siswa ID {$idSiswa}");
```

### Database Functions

#### 1. Cek Keterlambatan
```php
$status = db_function()->cekKeterlambatan(
    '2025-10-30 10:00:00',
    '2025-10-29 23:59:59'
);
// Return: 'terlambat' atau 'tepat_waktu'
```

#### 2. Hitung Persentase Kehadiran
```php
$persentase = db_function()->hitungPersentaseKehadiran(
    $idUser,
    10,   // bulan
    2025  // tahun
);
// Return: float (0-100)
```

#### 3. Hitung Rata-rata Nilai
```php
$rataNilai = db_function()->hitungRataNilai($idSiswa, $idKelas);
```

#### 4. Check Materi Compliance
```php
$status = db_function()->checkMateriCompliance($idMateri);
// Return: 'compliant' atau 'not_compliant'
```

### Views

#### 1. Rekap Presensi Siswa
```php
use App\Models\Views\VRekapPresensiSiswa;

$rekap = VRekapPresensiSiswa::where('bulan', 10)
    ->where('tahun', 2025)
    ->where('nama_kelas', 'X-TKJ-1')
    ->get();
```

#### 2. Rekap Presensi Guru/Staf
```php
use App\Models\Views\VRekapPresensiGuruStaf;

$rekap = VRekapPresensiGuruStaf::where('role', 'guru')
    ->where('bulan', 10)
    ->where('tahun', 2025)
    ->get();
```

#### 3. Status Tugas
```php
use App\Models\Views\VTugasStatus;

$tugasList = VTugasStatus::where('id_kelas', $idKelas)
    ->orderBy('deadline', 'desc')
    ->get();
```

#### 4. Grafik Kehadiran Harian
```php
use App\Models\Views\VGrafikKehadiranHarian;

$grafik = VGrafikKehadiranHarian::whereBetween('tanggal', [$startDate, $endDate])
    ->where('role', 'guru')
    ->get();
```

#### 5. Status Izin Siswa
```php
use App\Models\Views\VStatusIzinSiswa;

$izinSiswa = VStatusIzinSiswa::where('id_siswa', $idSiswa)
    ->orderBy('tanggal_pengajuan', 'desc')
    ->get();
```

### Logging Aktivitas

#### 1. Log Login/Logout
```php
log_activity()->logLogin(auth()->user()->id_user);
log_activity()->logLogout(auth()->user()->id_user);
```

#### 2. Log CRUD
```php
log_activity()->logCrud('create', auth()->user()->id_user, 'siswa', $siswa->id_siswa);
log_activity()->logCrud('update', auth()->user()->id_user, 'guru', $guru->id_guru);
log_activity()->logCrud('delete', auth()->user()->id_user, 'materi', $materi->id_materi);
```

#### 3. Quick Log
```php
quick_log('upload_materi', "Upload materi: {$materi->judul}");
quick_log('approve_izin', "Approve izin siswa: {$siswa->nama_lengkap}");
```

### Laporan Aktivitas

#### 1. Submit Laporan (Guru/Pembina)
```php
use App\Models\LaporanAktivitas;

$laporan = LaporanAktivitas::create([
    'id_guru' => auth()->user()->guru->id_guru,
    'periode_bulan' => 10,
    'periode_tahun' => 2025,
    'judul_laporan' => 'Laporan Bulanan Oktober 2025',
    'isi_laporan' => $request->isi_laporan,
    'file_pdf' => $pdfPath,
    'status' => 'submitted',
]);

quick_log('submit_laporan', "Submit laporan ID {$laporan->id_laporan}");
```

#### 2. Review Laporan (Kepala Sekolah)
```php
$laporan = LaporanAktivitas::findOrFail($id);
$laporan->update([
    'status' => 'approved',
    'catatan_kepsek' => $request->catatan,
    'reviewed_at' => now(),
]);
```

#### 3. Tambah Evaluasi
```php
use App\Models\EvaluasiKepsek;

$evaluasi = EvaluasiKepsek::create([
    'id_laporan' => $idLaporan,
    'id_target_user' => $idGuru,
    'tipe' => 'evaluasi',
    'isi_evaluasi' => $request->isi,
    'created_by' => auth()->user()->id_user,
]);
```

## 🎯 Helper Functions

```php
// Service shortcuts
db_procedure()  // DatabaseProcedureService
db_function()   // DatabaseFunctionService
log_activity()  // LogActivityService

// Quick functions
quick_log('type', 'description')
format_file_size(1024) // '1 KB'

// Role checks
is_admin()
is_kepala_sekolah()
is_pembina()
is_guru()
is_siswa()
get_current_role()
is_role('admin')
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter DatabaseSyncTest

# Test dengan tinker
php artisan tinker
```

```php
// Di tinker
App\Models\Views\VRekapPresensiSiswa::count();
db_procedure()->rekapPresensiBulanan(10, 2025, null);
db_function()->hitungPersentaseKehadiran(1, 10, 2025);
quick_log('test', 'Testing log');
```
