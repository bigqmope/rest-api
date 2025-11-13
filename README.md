# Rest API
Kelas PBO 2025, pertemuan ke-13 (7 Nov)

## Clone repo
```bash
git clone https://github.com/leo42night/rest-api
```

## Config
1. Install PHP & Database
2. Run Database & PHP Server `php -S localhost:3000` (port dapat disesuaikan)

## Rute Di Akses
```bash
GET / → { "message": "Koneksi success" }
GET /mahasiswa
GET /mahasiswa/1
POST /mahasiswa (body JSON)
PUT /mahasiswa/1 (body JSON)
DELETE /mahasiswa/1
```

## Test API (sesuaikan path)

### Menggunakan Terminal (pakai terminal yang basis Unix: Git Bash)

```bash
curl -X POST http://localhost:3000/mahasiswa \
-H "Authorization: Bearer 12345ABCDEF" \
-H "Content-Type: application/json" \
-d '{
  "nama": "Andi Saputra",
  "jurusan": "Teknik Informatika"
}'
```
respon Berhasil:
```json
{
  "message": "Data mahasiswa berhasil ditambahkan"
}
```
Lainnya:
```bash
curl -X GET http://localhost:3000/
```
```bash
curl -X GET http://localhost:3000/mahasiswa \
-H "Authorization: Bearer 12345ABCDEF"
```
```bash
curl -X GET http://localhost:3000/mahasiswa/1 \
-H "Authorization: Bearer 12345ABCDEF"
```
```bash
curl -X PUT http://localhost:3000/mahasiswa/1 \
-H "Authorization: Bearer 12345ABCDEF" \
-H "Content-Type: application/json" \
-d '{
  "nama": "Ahmad Syahroni",
  "jurusan": "Tata Boga"
}'
```
```bash
curl -X DELETE http://localhost:3000/mahasiswa/1 \
-H "Authorization: Bearer 12345ABCDEF"
```
### Alternatif
- Postman (Aplikasi)
- Thunder Client (Ekstensi VSCode)
- EchoAPI for VS Code (Ekstensi VSCode) **[🌟 Disarankan]**

## Deployment

### Vercel
Vercel bisa menjalankan PHP lewat custom runtime open-source bernama [vercel-php](https://github.com/vercel-community/php)
1. **Tambahkan file konfigurasi Vecel:**
```
/api
  ├── index.php
vercel.json
```
- **api/index.php**
```php
<?php  

require __DIR__ . "/../index.php";
```

- **vercel.json**
```json
{
  "functions": {
    "api/*.php": {
      "runtime": "vercel-php@0.7.4"
    }
  },
  "routes": [
    { "src": "/(.*)",  "dest": "/api/index.php" }
  ]
}
```
2. Install Vercel CLI  
Anda perlu [install NPM](https://www.google.com/search?q=install+node+package+manager+di+windows) (Node Package Manager) lebih dulu
```bash
npm install -g vercel
```
3. Login ke akunmu (pakai Github)
```bash
vercel login
```
4. Deploy proyek
```bash
vercel
```
Akan ada konfigurasi (tekan saja enter):
```bash
? Set up and deploy “c:\oop\rest-api”? yes
? Which scope do you want to deploy to? leo42night's projects
? Link to existing project? no
? What’s your project’s name? rest-api
? In which directory is your code located? ./
? Want to modify these settings? no
```

5. Vercel akan otomatis mendeteksi file **vercel.json** dan menggunakan runtime @vercel/php.
Setelah selesai, kamu akan dapat URL publik seperti:
```
https://rest-api.vercel.app
```

6. **Database Postgres**

Di halaman proyek vercel kalian, buka stores (contoh: **vercel.com/_nama-teams_/_nama-proyek_/stores**)
- [Create Database]
  - Region: `Singapore`
  - Public Env Variabel Prefix: `PG_`
  - Instalation Plans: Free
- [Continue]
  - Database Name: `supabase-rest-api`
- [Create]
- Salin environment variabel ke `.env.local`
- [Open In Supabase]
- [New table]
  - name: mahasiswa (id, nama [varchar], jurusan [varchar], created_at [timestamps now()])

konfigurasi seperti ini (pakai `POSTGRES_URL_NON_POOLING`):
```bash
DB_TYPE: pgsql
DB_HOST: aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT: 5432 # pakai port NON_POOLING
DB_USER: postgres.itzxopxshpvcjotxxxxx
DB_PASS: ETnWsKT1Q9xxxxx
DB_SSLMODE: require
```
**(dalam proyek vercel) Buka Settings > Environment Variables:** Tambahkan ke environment variabel proyek (letak di **vercel.com/_nama-teams_/_nama-proyek_/settings/environment-variables**) 

- (opsional) Akses di HeidiSQL:
  - Network Type: **PostgreSQL (TCP/IP)**
  - Library: **libpq-12.dll**

7. Test Deployment
```
curl -X GET https://<url-deployment>.vercel.app/

curl -X GET http://<url-deployment>.vercel.app/mahasiswa \
-H "Authorization: Bearer 12345ABCDEF"
```

## Push Repo
**Buat Repository di GitHub:** Tempat simpan proyek
```bash
git add remote repoku https://github.com/<username>/<repo>
git add .
git commit -m "persiapan sebelum deploy"
gut push repoku main --force
```