# Rest API
Kelas PBO 2025, pertemuan ke-13 (7 Nov)

## Config
1. Install PHP & Database
2. Run Database & PHP Server `php -S localhost:3000` (port dapat disesuaikan)

## Rute Di Akses
```bash
GET / → { "message": "Koneksi success" }
GET /mahasiswa
GET /mahasiswa/1
POST /mahasiswa (body JSON)
```

## Test (sesuaikan path)

## Menggunakan Terminal (pakai terminal yang basis Unix: Git Bash)

```bash
curl -X POST http://localhost:8000/mahasiswa \
-H "Authorization: Bearer 12345ABCDEF" \
-H "Content-Type: application/json" \
-d '{
  "nama": "Andi Saputra",
  "jurusan": "Teknik Informatika"
}'
```

respon Berhasil:
```
{
  "message": "Data mahasiswa berhasil ditambahkan"
}
```

## Alternatif
- Postman (Aplikasi)
- Thunder Client (Ekstensi VSCode)