# 📋 SISTEM VERIFIKASI GURU - DOKUMENTASI

## ✅ Status: TERIMPLEMENTASI LENGKAP

Sistem verifikasi untuk registrasi guru telah berhasil diimplementasikan. Guru yang mendaftar harus menunggu approval dari admin sebelum dapat login ke sistem.

---

## 🎯 Alur Kerja

### 1. **Registrasi Guru**
- Guru mendaftar melalui halaman `/register`
- Memilih role "Guru"
- Mengisi data (nama, email, password)
- Setelah submit, akun dibuat dengan `status_aktif = 0` (pending)
- Guru mendapat pesan: "Pendaftaran berhasil! Akun Anda menunggu persetujuan admin."

### 2. **Verifikasi oleh Admin**
- Admin login dan melihat dashboard
- Jika ada guru pending, muncul **notifikasi orange** di bagian atas
- Admin klik tombol "Verifikasi Sekarang" atau menu "Verifikasi Guru"
- Admin melihat daftar guru yang menunggu persetujuan
- Admin dapat:
  - ✅ **Approve** - Mengaktifkan akun guru (status_aktif = 1)
  - ❌ **Reject** - Menolak dan menghapus akun guru

### 3. **Login Guru**
- Jika `status_aktif = 0` (belum diapprove):
  - Login GAGAL dengan pesan: "Akun Anda belum diaktifkan. Silakan tunggu persetujuan dari admin."
- Jika `status_aktif = 1` (sudah diapprove):
  - Login BERHASIL, diarahkan ke dashboard guru

---

## 📁 File yang Dimodifikasi

### 1. **AuthController.php**
- **Registrasi**: Set `status_aktif = 0` untuk guru, `1` untuk siswa
- **Login**: Cek status_aktif sebelum allow login
- **Pesan**: Berbeda untuk guru (pending) dan siswa (langsung aktif)

### 2. **AdminController.php**
- **dashboard()**: Tambah variabel `$pendingGuru` (count guru pending)
- **verifikasiGuru()**: Tampilkan daftar guru pending
- **approveGuru($id)**: Approve guru (set status_aktif = 1)
- **rejectGuru($id)**: Reject dan hapus akun guru

### 3. **routes/web.php**
```php
// Admin - Verifikasi Guru
Route::get('/admin/verifikasi-guru', [AdminController::class, 'verifikasiGuru'])
    ->name('admin.verifikasi-guru')->middleware('role:admin');
Route::post('/admin/verifikasi-guru/{id}/approve', [AdminController::class, 'approveGuru'])
    ->name('admin.approve-guru')->middleware('role:admin');
Route::post('/admin/verifikasi-guru/{id}/reject', [AdminController::class, 'rejectGuru'])
    ->name('admin.reject-guru')->middleware('role:admin');
```

### 4. **admin/dashboard.blade.php**
- Notifikasi orange jika ada guru pending
- Card "Verifikasi Guru" dengan badge jumlah pending
- Link langsung ke halaman verifikasi

### 5. **admin/verifikasi-guru.blade.php** (sudah ada sebelumnya)
- Tampilan daftar guru pending
- Tombol Approve (hijau) dan Reject (merah)
- Konfirmasi menggunakan SweetAlert2

---

## 🔐 Keamanan

1. **Middleware `role:admin`** - Hanya admin yang bisa akses verifikasi
2. **Database Transaction** - Approve/reject menggunakan DB transaction
3. **Cascade Delete** - Jika reject, data guru di tabel `guru` juga terhapus
4. **Log Activity** - Semua approve/reject dicatat di log aktivitas

---

## 📊 Database Schema

### Tabel `users`
```sql
- id_user (PK)
- username
- nama_lengkap
- email
- password
- role (enum: admin, guru, siswa, kepala_sekolah, pembina)
- status_aktif (boolean: 0 = pending, 1 = active) ⭐ PENTING
- no_hp
- email_verified_at
- remember_token
- created_at
- updated_at
```

### Tabel `guru`
```sql
- id_guru (PK)
- id_user (FK -> users.id_user) ON DELETE CASCADE
- nip
- mata_pelajaran
- jabatan
- alamat
- tanggal_lahir
- created_at
```

---

## 🧪 Cara Testing

### Test 1: Registrasi Guru
1. Buka `/register`
2. Pilih role "Guru"
3. Isi form dan submit
4. Cek pesan: "Pendaftaran berhasil! Akun Anda menunggu persetujuan admin."
5. Coba login → Harus GAGAL dengan pesan "Akun Anda belum diaktifkan..."

### Test 2: Approve Guru
1. Login sebagai admin
2. Lihat dashboard → Ada notifikasi orange
3. Klik "Verifikasi Sekarang"
4. Klik tombol hijau (Approve) pada guru
5. Konfirmasi → Sukses
6. Guru sekarang bisa login

### Test 3: Reject Guru
1. Login sebagai admin
2. Buka halaman verifikasi guru
3. Klik tombol merah (Reject)
4. Isi alasan (opsional)
5. Konfirmasi → Akun guru dihapus

---

## 🎨 UI/UX

### Dashboard Admin
- **Notifikasi Orange**: Muncul jika ada guru pending
- **Card Verifikasi Guru**: Posisi paling atas di grid fitur, warna ungu gradient
- **Badge**: Menampilkan jumlah guru pending

### Halaman Verifikasi
- **Table**: Tampilan clean dengan nama, email, no HP, tanggal daftar
- **Tombol Approve**: Hijau dengan icon check
- **Tombol Reject**: Merah dengan icon times
- **SweetAlert2**: Konfirmasi sebelum approve/reject

---

## 📝 Log Activity

Setiap approve/reject dicatat dengan format:
```
Action: approve_guru / reject_guru
User: ID admin yang melakukan
Description: "Approve pendaftaran guru: [Nama] (ID: [ID])"
            "Reject pendaftaran guru: [Nama] (ID: [ID]). Alasan: [Alasan]"
```

---

## ✨ Fitur Tambahan

1. **Auto-generate NIP**: NIP otomatis dibuat saat registrasi (`NIP000001`, dll)
2. **Email Notification** (future): Bisa ditambahkan notifikasi email ke guru saat approve/reject
3. **Bulk Approve** (future): Approve multiple guru sekaligus
4. **Filter & Search** (future): Search guru by nama/email

---

## 🚀 Deployment Notes

Tidak perlu migration baru, karena:
- Kolom `status_aktif` sudah ada di tabel `users`
- View `admin/verifikasi-guru.blade.php` sudah ada sebelumnya

Yang perlu di-deploy:
- File controller yang diupdate
- File routes yang diupdate
- File view dashboard yang diupdate

---

**Tanggal Implementasi**: 10 November 2025  
**Status**: ✅ Production Ready  
**Developer**: AI Assistant
