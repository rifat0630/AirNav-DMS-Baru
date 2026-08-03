# AirNav DMS

AirNav DMS (Document Management System) adalah aplikasi berbasis Laravel yang digunakan untuk mengelola dokumen secara digital. Sistem ini mendukung manajemen dokumen, autentikasi pengguna berdasarkan role, pencatatan aktivitas, serta integrasi dengan Google Drive untuk penyimpanan dokumen.

---

## 🚀 Fitur

- Login & Authentication (Laravel Breeze)
- Dashboard
- Manajemen Dokumen (CRUD)
- Upload Dokumen
- Download Dokumen
- Preview Dokumen
- Hapus Dokumen
- Kategori Dokumen
- Role Management
  - Admin
  - Teknisi
  - Pegawai
- Activity Log
- Integrasi Google Drive API
- Penyimpanan file lokal dan Google Drive

---

## 🛠️ Teknologi

- Laravel 12
- PHP 8.2+
- MySQL
- Bootstrap 5
- Google Drive API
- Laravel Breeze
- Composer

---

## 📁 Struktur Project

```
app/
├── Http/
├── Models/
├── Services/
│   └── GoogleDriveService.php

resources/
├── views/

routes/
└── web.php

storage/
└── app/
```

---

## ⚙️ Instalasi

Clone repository

```bash
git clone https://github.com/annisacendana/AirNav-DMS.git
```

Masuk ke project

```bash
cd AirNav-DMS/backend
```

Install dependency

```bash
composer install
```

Copy file environment

```bash
cp .env.example .env
```

Generate key

```bash
php artisan key:generate
```

Konfigurasi database pada file `.env`

```env
DB_DATABASE=airnav_dms
DB_USERNAME=root
DB_PASSWORD=
```

Migrasi database

```bash
php artisan migrate
```

Jalankan server

```bash
php artisan serve
```

---

## ☁️ Konfigurasi Google Drive

Tambahkan konfigurasi berikut pada file `.env`

```env
GOOGLE_CLIENT_ID=

GOOGLE_CLIENT_SECRET=

GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/google/callback

GOOGLE_DRIVE_FOLDER_ID=
```

Letakkan file OAuth credentials pada

```
storage/app/google/credentials.json
```

Lakukan autentikasi melalui

```
http://127.0.0.1:8000/google/auth
```

---

## 👤 Role Pengguna

### Admin

- Kelola Dokumen
- Tambah Dokumen
- Edit Dokumen
- Hapus Dokumen
- Melihat Activity Log

### Teknisi

- Tambah Dokumen
- Edit Dokumen
- Upload Dokumen
- Download Dokumen

### Pegawai

- Tambah Dokumen
- Edit Dokumen
- Upload Dokumen
- Download Dokumen

---

## 📂 Fitur Dokumen

- Upload file
- Penyimpanan lokal
- Penyimpanan Google Drive
- Preview dokumen
- Download dokumen
- Hapus dokumen
- Riwayat aktivitas

---

## 🔒 Keamanan

Pastikan file berikut **tidak diunggah ke GitHub**

```
.env

storage/app/google/credentials.json

storage/app/google/token.json
```

Tambahkan pada `.gitignore`

```
.env
storage/app/google/credentials.json
storage/app/google/token.json
```

---

## 📷 Tampilan Sistem

- Login
- Dashboard
- Daftar Dokumen
- Tambah Dokumen
- Detail Dokumen
- Activity Log
- Google Drive Integration

---

## 👩‍💻 Pengembang

**An Nisa Putri Cendana**

Program Studi Teknologi Informasi

---

## 📄 Lisensi

Project ini dibuat untuk keperluan pembelajaran dan pengembangan sistem Document Management System (DMS).