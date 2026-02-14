# Panduan Test Upload File ke R2

## Informasi File yang Dibuat
- **Controller**: `app/Http/Controllers/FileUploadController.php`
- **Routes**: `routes/api.php` (sudah ditambahkan 3 endpoint)
- **Config**: `config/filesystems.php` (sudah ditambahkan R2 disk)
- **.env**: Sudah diisi R2 credentials

---

## Test API Endpoints

### 1. Upload File ke R2
**POST** `http://localhost:8000/api/upload`

**Form Data:**
```
file: <pilih file>
```

**Response Success:**
```json
{
  "success": true,
  "message": "File berhasil di-upload ke R2",
  "path": "uploads/filename.ext",
  "url": "https://847e45ac4c823cc33f42371b1f4217d1.r2.cloudflarestorage.com/uploads/filename.ext"
}
```

**Tools untuk test:**
- Postman
- Insomnia  
- Thunder Client
- Atau cURL di terminal

**Contoh cURL:**
```bash
curl -X POST http://localhost:8000/api/upload \
  -F "file=@/path/to/file.jpg"
```

---

### 2. List File di R2
**GET** `http://localhost:8000/api/files`

**Response:**
```json
{
  "success": true,
  "files": [
    {
      "path": "uploads/file1.jpg",
      "name": "file1.jpg",
      "type": "file",
      "timestamp": 1707877200,
      "size": 102400
    }
  ]
}
```

---

### 3. Delete File dari R2
**POST** `http://localhost:8000/api/delete`

**JSON Body:**
```json
{
  "path": "uploads/filename.ext"
}
```

**Response:**
```json
{
  "success": true,
  "message": "File berhasil dihapus dari R2"
}
```

---

## Troubleshooting

### Error: "Unable to connect to endpoint"
- ✅ Cek .env R2_ENDPOINT sudah benar
- ✅ Cek internet connection
- ✅ Cek R2_KEY dan R2_SECRET

### Error: "InvalidAccessKeyId" atau "SignatureDoesNotMatch"
- ✅ Pastikan R2_KEY dan R2_SECRET sudah di-copy dengan benar
- ✅ Jangan ada spasi di awal/akhir value

### File tidak bisa di-access via URL
- ✅ Bucket R2 perlu di-set public (atau gunakan signed URLs)
- ✅ Cek CORS policy di R2 Settings

---

## Next Steps

Setelah test sukses, Anda bisa:

1. **Integrasikan di form upload Blade:**
   ```blade
   <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
       @csrf
       <input type="file" name="file" required>
       <button type="submit">Upload</button>
   </form>
   ```

2. **Simpan path file ke database:**
   ```php
   $model->file_path = $path;
   $model->save();
   ```

3. **Tampilkan file di view:**
   ```blade
   <img src="{{ Storage::disk('r2')->url($model->file_path) }}" alt="Image">
   ```

---

**Selamat! Setup R2 sudah complete! 🎉**
