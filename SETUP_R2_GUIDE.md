# Setup Cloudflare R2 untuk Laravel - Panduan Lengkap

## 📋 Daftar Isi
1. [Setup Cloudflare R2](#1-setup-cloudflare-r2)
2. [Setup Laravel](#2-setup-laravel)
3. [Upload File](#3-upload-file)
4. [Menampilkan File di View](#4-menampilkan-file-di-view)

---

## 1. Setup Cloudflare R2

### Step 1: Buat Akun Cloudflare
1. Buka https://dash.cloudflare.com/
2. Klik "Sign up" dan isi email, password
3. Verify email Anda
4. Done ✅

### Step 2: Buat R2 Bucket
1. Di dashboard Cloudflare, cari menu **"R2"** (di sidebar kiri)
2. Klik **"Create Bucket"**
3. Nama bucket: `bem2026` (atau sesuai project)
4. Pilih region: Asia (Tokyo) atau pilih yang terdekat
5. Klik **"Create Bucket"**
6. Done ✅

### Step 3: Setup API Token
1. Masih di halaman R2, klik **"Settings"** (gear icon)
2. Scroll ke bawah, cari **"R2 API Token"**
3. Klik **"Create API token"**
4. Pilih **"Edit"** (biar bisa read + write)
5. Klik **"Create Token"**
6. **SIMPAN ini di tempat aman:**
   - Access Key ID
   - Secret Access Key
   - S3 API Endpoint (format: `https://xxxx.r2.cloudflarestorage.com`)

⚠️ Secret Access Key hanya ditampilkan 1x, jadi catat baik-baik!

---

## 2. Setup Laravel

### Step 1: Install AWS SDK
```bash
cd d:\Project Laravel\bem2026
composer require aws/aws-sdk-php
```

### Step 2: Add ke .env
Buka file `.env` di project, tambahkan di akhir:

```env
# R2 Configuration
R2_KEY=YOUR_ACCESS_KEY_ID
R2_SECRET=YOUR_SECRET_ACCESS_KEY
R2_REGION=auto
R2_BUCKET=bem2026
R2_ENDPOINT=https://xxxx.r2.cloudflarestorage.com
```

Ganti:
- `YOUR_ACCESS_KEY_ID` → Access Key ID dari step sebelumnya
- `YOUR_SECRET_ACCESS_KEY` → Secret Access Key
- `xxxx.r2.cloudflarestorage.com` → S3 API Endpoint Anda

### Step 3: Setup Filesystem Config
Buka `config/filesystems.php`, cari section `'disks'` dan tambahkan:

```php
'r2' => [
    'driver' => 's3',
    'key' => env('R2_KEY'),
    'secret' => env('R2_SECRET'),
    'region' => env('R2_REGION'),
    'bucket' => env('R2_BUCKET'),
    'endpoint' => env('R2_ENDPOINT'),
    'use_path_style_endpoint' => false,
],
```

Letakkan sebelum closing bracket array `'disks'`.

---

## 3. Upload File

### Controller Example
Buat atau edit controller, misalnya di `app/Http/Controllers/FileUploadController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        // Upload ke R2
        $file = $request->file('file');
        $path = $file->store('uploads', 'r2'); // Folder 'uploads' di R2

        // Simpan path ke database
        // $model->file_path = $path;
        // $model->save();

        // Atau return langsung
        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => Storage::disk('r2')->url($path),
        ]);
    }
}
```

### Route
Tambahkan ke `routes/web.php` atau `routes/api.php`:

```php
Route::post('/upload', [FileUploadController::class, 'upload'])->middleware('auth');
```

### Form di Blade
```html
<form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>
```

---

## 4. Menampilkan File di View

### Dari URL Langsung
```blade
<img src="{{ Storage::disk('r2')->url($filePath) }}" alt="Image">
```

### Jika Disimpan di Database
```blade
{{-- Misalnya di model User ada column 'avatar' --}}
<img src="{{ Storage::disk('r2')->url(auth()->user()->avatar) }}" alt="Avatar">
```

### Download File
```blade
<a href="{{ Storage::disk('r2')->url($filePath) }}" download>
    Download File
</a>
```

---

## ✅ Checklist Setup

- [ ] Akun Cloudflare dibuat
- [ ] R2 Bucket dibuat  
- [ ] API Token dibuat & disimpan
- [ ] `composer require aws/aws-sdk-php` sudah dijalankan
- [ ] `.env` sudah diisi R2 credentials
- [ ] `config/filesystems.php` sudah diupdate
- [ ] Controller upload dibuat
- [ ] Routes sudah ditambah
- [ ] Blade form sudah dibuat
- [ ] Test upload file ✨

---

## 🐛 Troubleshooting

**Error: "Unable to connect to endpoint"**
- Cek endpoint URL di .env (harus copy paste dari Cloudflare)

**Error: "InvalidAccessKeyId"**
- Cek R2_KEY dan R2_SECRET di .env

**File tidak bisa di-access**
- Pastikan bucket sudah di-set public (di R2 Settings)
- Atau gunakan signed URLs untuk private files

---

**Udah siap? Test sekarang!** 🚀
