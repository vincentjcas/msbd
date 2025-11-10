# ⚡ Quick Reference: Verifikasi Guru

**Status:** ✅ READY  
**Date:** 10 Nov 2025

---

## 🚀 Quick Start

### 1️⃣ Jalankan Migration
```bash
php artisan migrate
```

### 2️⃣ Akses Admin
```
URL: http://localhost/msbd/admin/dashboard
Login: admin@smk.ac.id
```

### 3️⃣ Test Guru Register
```
URL: http://localhost/msbd/register
Role: Guru
```

---

## 📂 Files Modified

```
✅ app/Http/Controllers/AuthController.php
✅ app/Http/Controllers/AdminController.php
✅ resources/views/admin/dashboard.blade.php
✅ resources/views/admin/verifikasi-guru.blade.php (NEW)
✅ routes/web.php
✅ database/migrations/2025_11_10_000001_add_status_verifikasi_to_users.php (NEW)
```

---

## 🔗 Routes

| Method | URL | Action |
|--------|-----|--------|
| GET | `/admin/verifikasi-guru` | List pending guru |
| POST | `/admin/verifikasi-guru/{id}/approve` | Approve guru |
| POST | `/admin/verifikasi-guru/{id}/reject` | Reject guru |

---

## 🎯 Workflow

```
Register (Guru) → status_aktif=0 → Can't login
                       ↓
                  Admin review
                       ↓
           ┌───────────┴───────────┐
           ↓                       ↓
       APPROVE                  REJECT
   status_aktif=1           Delete user
   Create guru record       Log reason
   Can login now            Must re-register
```

---

## 💾 Database

**Table:** `users`
- `status_aktif = 0` → Pending
- `status_aktif = 1` → Approved

**Query:**
```sql
-- Check pending
SELECT * FROM users WHERE role='guru' AND status_aktif=0;

-- Check approved
SELECT * FROM users WHERE role='guru' AND status_aktif=1;
```

---

## 🧪 Quick Test

```bash
# 1. Register guru baru (browser)
http://localhost/msbd/register

# 2. Check database
php artisan tinker
User::where('role','guru')->where('status_aktif',0)->count()

# 3. Login as admin
http://localhost/msbd/login

# 4. Verify guru
http://localhost/msbd/admin/verifikasi-guru
```

---

## 📖 Full Documentation

| File | Description |
|------|-------------|
| `SUMMARY_VERIFIKASI_GURU.md` | **Complete summary** |
| `FITUR_VERIFIKASI_GURU.md` | Detailed feature docs |
| `TESTING_VERIFIKASI_GURU.md` | 13 test scenarios |
| `GIT_COMMIT_VERIFIKASI_GURU.md` | Commit templates |

---

## 🐛 Troubleshooting

**Problem:** Migration error
```bash
php artisan migrate:fresh
```

**Problem:** Routes not found
```bash
php artisan route:clear
php artisan cache:clear
```

**Problem:** View not found
```bash
php artisan view:clear
```

---

## ✅ Checklist

- [x] Migration run
- [x] Routes registered
- [x] Views created
- [x] Controllers updated
- [ ] Manual testing
- [ ] Git commit
- [ ] Deploy

---

**Need help?** Read `SUMMARY_VERIFIKASI_GURU.md`
