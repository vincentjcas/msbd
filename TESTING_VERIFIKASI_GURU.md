# 🧪 Testing Guide: Sistem Verifikasi Pendaftaran Guru

**Feature:** Admin Verification for Guru Registration  
**Date:** 10 November 2025  
**Status:** ✅ Ready for Testing

---

## 📋 Pre-Requisites

### 1. Database Migration
```bash
php artisan migrate
```

**Expected Output:**
```
✓ Berhasil mengaktifkan X guru yang sudah terdaftar.
```

### 2. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 3. Check Database Status
```bash
php artisan tinker
```

```php
// Cek total guru
User::where('role', 'guru')->count()

// Cek guru aktif
User::where('role', 'guru')->where('status_aktif', 1)->count()

// Cek guru pending
User::where('role', 'guru')->where('status_aktif', 0)->count()
```

---

## 🧪 Test Scenarios

### Test #1: Register Guru Baru (Pending State)

**Steps:**
1. Buka browser: `http://localhost/msbd/register`
2. Isi form registrasi:
   - Nama Lengkap: `Test Guru Verifikasi`
   - Email: `testguru@smk.ac.id`
   - Username: `testguru`
   - Password: `password123`
   - Konfirmasi Password: `password123`
   - No HP: `081234567890`
   - Role: **Guru**
3. Klik "Daftar"

**Expected Results:**
- ✅ Redirect ke login page
- ✅ Flash message: "Pendaftaran berhasil! Akun Anda menunggu verifikasi dari Admin."
- ✅ Database: Check `users` table → `status_aktif = 0`
- ✅ Database: Check `guru` table → NIP auto-generated (e.g., `NIPXXXXXX`)

**SQL Check:**
```sql
SELECT id_user, nama_lengkap, email, role, status_aktif, created_at 
FROM users 
WHERE email = 'testguru@smk.ac.id';
```

**Screenshot Checklist:**
- [ ] Form registrasi terisi
- [ ] Success message muncul
- [ ] Database record dengan status_aktif = 0

---

### Test #2: Login dengan Akun Pending (Should Fail)

**Steps:**
1. Buka login page: `http://localhost/msbd/login`
2. Isi form login:
   - Email: `testguru@smk.ac.id`
   - Password: `password123`
3. Klik "Login"

**Expected Results:**
- ❌ Login gagal
- ✅ Error message: "Akun Anda belum diverifikasi oleh Admin. Silakan tunggu konfirmasi."
- ✅ Tidak redirect ke dashboard
- ✅ User tetap di halaman login

**Screenshot Checklist:**
- [ ] Error message muncul (warna merah)
- [ ] User tidak masuk sistem
- [ ] Session tidak terbuat

---

### Test #3: Admin Dashboard - Notification & Badge

**Steps:**
1. Login sebagai Admin:
   - Email: `admin@smk.ac.id` (sesuaikan dengan admin Anda)
   - Password: `[admin password]`
2. Setelah login, lihat dashboard admin

**Expected Results:**
- ✅ **Orange notification banner** muncul:
  - Text: "Ada 1 Pendaftaran Guru Menunggu Verifikasi"
  - Button: "Verifikasi Sekarang"
- ✅ **Card "Verifikasi Guru"** dengan:
  - Badge merah dengan angka `1` di pojok kanan atas
  - Icon: user-check
  - Link: "Verifikasi Guru"

**Screenshot Checklist:**
- [ ] Orange banner visible
- [ ] Red badge dengan angka pending
- [ ] Card terlihat jelas

**SQL Check:**
```sql
SELECT COUNT(*) as pending_count 
FROM users 
WHERE role = 'guru' AND status_aktif = 0;
```

---

### Test #4: Halaman Verifikasi Guru

**Steps:**
1. Dari admin dashboard, klik "Verifikasi Sekarang" atau "Verifikasi Guru"
2. Route: `/admin/verifikasi-guru`

**Expected Results:**
- ✅ Halaman terbuka dengan title: "Verifikasi Pendaftaran Guru"
- ✅ Breadcrumb: Dashboard > Verifikasi Guru
- ✅ **Tabel** dengan kolom:
  - No
  - Nama Lengkap
  - Email
  - Username
  - No HP
  - Tanggal Daftar
  - Aksi (Approve & Reject buttons)
- ✅ Data guru pending muncul di tabel
- ✅ Tanggal format Indonesia: `10/11/2025 14:30`
- ✅ Tombol **Approve** (hijau) dan **Reject** (merah)

**Screenshot Checklist:**
- [ ] Tabel lengkap dengan data
- [ ] Buttons terlihat jelas
- [ ] Responsive design

---

### Test #5: Approve Guru

**Steps:**
1. Di halaman verifikasi guru, klik tombol **"Approve"** (hijau)
2. SweetAlert confirmation muncul
3. Klik "Ya, Approve"

**Expected Results - SweetAlert:**
- ✅ Title: "Approve Pendaftaran?"
- ✅ Text: "Anda akan menyetujui pendaftaran guru: **Test Guru Verifikasi**"
- ✅ Icon: question
- ✅ 2 Buttons: "Ya, Approve" (hijau) dan "Batal"

**Expected Results - After Approve:**
- ✅ SweetAlert success: "Pendaftaran guru berhasil disetujui"
- ✅ Redirect ke `/admin/verifikasi-guru`
- ✅ Guru **hilang** dari tabel pending
- ✅ Flash message: "Pendaftaran guru berhasil disetujui"
- ✅ Badge count berkurang (jika masih ada pending lain)
- ✅ Orange banner hilang (jika tidak ada pending)

**Database Check:**
```sql
-- Check users table
SELECT id_user, nama_lengkap, status_aktif 
FROM users 
WHERE email = 'testguru@smk.ac.id';
-- Expected: status_aktif = 1

-- Check guru table
SELECT * FROM guru WHERE id_user = [id_user_dari_query_atas];
-- Expected: Record exists dengan NIP
```

**Log Activity Check:**
```sql
SELECT * FROM log_aktivitas 
WHERE aktivitas = 'approve_guru' 
ORDER BY created_at DESC 
LIMIT 1;
-- Expected: Log dengan deskripsi approve
```

**Screenshot Checklist:**
- [ ] SweetAlert confirmation
- [ ] Success message
- [ ] Guru hilang dari tabel
- [ ] Database status_aktif = 1

---

### Test #6: Login Setelah Approved (Should Success)

**Steps:**
1. Logout dari admin
2. Buka login page
3. Login dengan akun guru yang sudah di-approve:
   - Email: `testguru@smk.ac.id`
   - Password: `password123`
4. Klik "Login"

**Expected Results:**
- ✅ Login berhasil
- ✅ Flash message: "Selamat datang kembali, Test Guru Verifikasi!"
- ✅ Redirect ke `/guru/dashboard`
- ✅ Dashboard guru terbuka
- ✅ Session terbuat

**Screenshot Checklist:**
- [ ] Berhasil login
- [ ] Dashboard guru visible
- [ ] Welcome message muncul

---

### Test #7: Reject Guru

**Setup:**
1. Buat akun guru baru lagi (Test #1)
   - Email: `testguru2@smk.ac.id`
2. Login sebagai admin
3. Buka halaman verifikasi guru

**Steps:**
1. Klik tombol **"Reject"** (merah)
2. SweetAlert muncul dengan input textarea
3. Isi alasan: `Tidak memiliki sertifikat pendidik`
4. Klik "Ya, Reject"

**Expected Results - SweetAlert:**
- ✅ Title: "Reject Pendaftaran?"
- ✅ Text: "Akun akan dihapus secara permanen."
- ✅ Icon: warning
- ✅ Input: Textarea untuk alasan penolakan
- ✅ Placeholder: "Alasan penolakan (opsional)"
- ✅ 2 Buttons: "Ya, Reject" (merah) dan "Batal"

**Expected Results - After Reject:**
- ✅ SweetAlert success: "Pendaftaran ditolak dan dihapus"
- ✅ Redirect ke `/admin/verifikasi-guru`
- ✅ Guru **hilang** dari tabel
- ✅ Flash message: "Pendaftaran ditolak dan dihapus"

**Database Check:**
```sql
-- User should be deleted
SELECT * FROM users WHERE email = 'testguru2@smk.ac.id';
-- Expected: 0 rows (user dihapus)

-- Check log
SELECT * FROM log_aktivitas 
WHERE aktivitas = 'reject_guru' 
ORDER BY created_at DESC 
LIMIT 1;
-- Expected: Log dengan deskripsi reject + alasan
```

**Screenshot Checklist:**
- [ ] SweetAlert dengan textarea
- [ ] Success message
- [ ] User terhapus dari database
- [ ] Log activity tercatat

---

### Test #8: Try Login After Rejected (Should Fail)

**Steps:**
1. Logout dari admin
2. Coba login dengan akun yang di-reject:
   - Email: `testguru2@smk.ac.id`
   - Password: `password123`

**Expected Results:**
- ❌ Login gagal
- ✅ Error message: "Email atau password salah."
- ✅ Tidak bisa masuk sistem

---

### Test #9: Register Role Lain (Non-Guru) - Auto Active

**Steps:**
1. Register akun baru dengan role **Siswa**:
   - Nama: `Test Siswa`
   - Email: `testsiswa@smk.ac.id`
   - Username: `testsiswa`
   - Password: `password123`
   - Role: **Siswa**
2. Klik "Daftar"

**Expected Results:**
- ✅ Success message: "Akun anda berhasil dibuat!" (bukan pesan verifikasi)
- ✅ Database: `status_aktif = 1` (auto active)
- ✅ Bisa langsung login tanpa verifikasi

**SQL Check:**
```sql
SELECT status_aktif FROM users WHERE email = 'testsiswa@smk.ac.id';
-- Expected: 1 (auto active untuk non-guru)
```

---

### Test #10: Empty State (Tidak Ada Pending)

**Setup:**
1. Approve/Reject semua guru pending
2. Pastikan tidak ada guru dengan status_aktif = 0

**Steps:**
1. Login sebagai admin
2. Buka `/admin/verifikasi-guru`

**Expected Results - Dashboard:**
- ✅ Orange notification banner **TIDAK muncul**
- ✅ Badge merah di card verifikasi **TIDAK muncul**

**Expected Results - Halaman Verifikasi:**
- ✅ Empty state message:
  - Icon: inbox (besar)
  - Text: "Tidak ada pendaftaran guru yang menunggu verifikasi"
  - Text tambahan: "Semua pendaftaran sudah diproses"
- ✅ Tabel **TIDAK tampil**

**Screenshot Checklist:**
- [ ] Dashboard tanpa notification
- [ ] Empty state visible
- [ ] Clean UI

---

### Test #11: Security Test - Non-Admin Access

**Steps:**
1. Login sebagai **Guru** (bukan admin)
2. Coba akses URL langsung: `/admin/verifikasi-guru`

**Expected Results:**
- ❌ Access denied
- ✅ Redirect ke halaman error atau unauthorized
- ✅ Middleware `role:admin` bekerja

**Alternative Test:**
- Coba dengan role siswa, kepala sekolah, pembina
- Semua harus blocked

---

### Test #12: Multiple Pending Guru

**Setup:**
1. Buat 3 akun guru pending:
   - `guru1@smk.ac.id`
   - `guru2@smk.ac.id`
   - `guru3@smk.ac.id`

**Steps:**
1. Login sebagai admin
2. Cek dashboard

**Expected Results:**
- ✅ Orange banner: "Ada **3** Pendaftaran Guru Menunggu Verifikasi"
- ✅ Badge: Angka `3`
- ✅ Tabel verifikasi: 3 rows
- ✅ Approve 1 guru → Badge jadi `2`
- ✅ Reject 1 guru → Badge jadi `1`
- ✅ Approve terakhir → Badge hilang, banner hilang

---

### Test #13: Validation & Edge Cases

#### A. Approve Guru yang Sudah Approved
**Steps:**
1. Approve guru
2. Coba approve lagi (manual POST via Postman)

**Expected:**
- ✅ Error: "User tidak valid"
- ✅ Tidak ada perubahan database

#### B. Approve User Non-Guru
**Steps:**
1. Coba approve user dengan role siswa (manual POST)

**Expected:**
- ✅ Error: "User tidak valid"

#### C. Reject dengan Alasan Kosong
**Steps:**
1. Reject guru tanpa isi alasan

**Expected:**
- ✅ Tetap bisa reject
- ✅ Log: "Alasan: Tidak memenuhi kriteria" (default)

---

## 📊 Test Results Summary

| No | Test Case | Status | Notes |
|----|-----------|--------|-------|
| 1 | Register Guru Baru (Pending) | ⬜ | |
| 2 | Login Pending (Blocked) | ⬜ | |
| 3 | Admin Dashboard Notification | ⬜ | |
| 4 | Halaman Verifikasi | ⬜ | |
| 5 | Approve Guru | ⬜ | |
| 6 | Login After Approved | ⬜ | |
| 7 | Reject Guru | ⬜ | |
| 8 | Login After Rejected | ⬜ | |
| 9 | Non-Guru Auto Active | ⬜ | |
| 10 | Empty State | ⬜ | |
| 11 | Security - Non-Admin Block | ⬜ | |
| 12 | Multiple Pending | ⬜ | |
| 13 | Validation Edge Cases | ⬜ | |

**Legend:**
- ⬜ Not Tested
- ✅ Passed
- ❌ Failed
- ⚠️ Partial

---

## 🐛 Bug Report Template

Jika menemukan bug, gunakan template ini:

```markdown
### Bug: [Judul singkat]

**Test Case:** #[nomor test]
**Environment:** 
- Laravel: 12
- PHP: 8.2
- Browser: Chrome/Firefox/Safari
- OS: Windows/Mac/Linux

**Steps to Reproduce:**
1. ...
2. ...
3. ...

**Expected Behavior:**
...

**Actual Behavior:**
...

**Screenshots:**
[Attach screenshots]

**Database State:**
```sql
-- Query untuk cek database
```

**Error Logs:**
```
[Paste error dari storage/logs/laravel.log]
```

**Priority:** High/Medium/Low
```

---

## ✅ Sign-Off Checklist

Sebelum deploy ke production:

- [ ] Semua 13 test cases passed
- [ ] Screenshots dokumentasi lengkap
- [ ] Database migration tested
- [ ] No errors di `storage/logs/laravel.log`
- [ ] Security test passed (middleware works)
- [ ] UI/UX sesuai design
- [ ] SweetAlert berfungsi semua
- [ ] Logging tercatat dengan benar
- [ ] Backward compatibility maintained (guru lama auto-active)
- [ ] Code review completed
- [ ] Documentation updated

---

**Tester:** _______________  
**Date:** _______________  
**Approved by:** _______________  

---

**Project:** SIMAK SMK  
**Feature:** Admin Verification System  
**Version:** 1.0.0
