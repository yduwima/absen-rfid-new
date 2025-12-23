# RFID Attendance System - CodeIgniter 3

Sistem Absensi Siswa dan Guru berbasis RFID menggunakan CodeIgniter 3 dan Tailwind CSS Modern.

## 🚀 Fitur Utama

- **Multi-Role System**: Admin, Guru, Wali Kelas, Guru Piket, dan BK
- **Absensi RFID**: Scan kartu RFID untuk absensi masuk/pulang
- **Absensi Per Mata Pelajaran**: Input H/S/I/A melalui jurnal guru
- **WhatsApp Notification**: Notifikasi otomatis ke orang tua dengan queue system
- **Laporan Lengkap**: Export PDF dan Excel dengan kop sekolah
- **Monitoring BK**: Auto deteksi siswa bermasalah (alpha & terlambat)
- **Real-time Display**: Halaman RFID dengan auto-refresh

## 📋 Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Apache/Nginx Web Server
- Composer (untuk instalasi dependencies)
- Node.js & NPM (untuk Tailwind CSS)

## 🛠️ Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/yduwima/absen-rfid-new.git
cd absen-rfid-new
```

### 2. Setup Database

```bash
# Import database schema
mysql -u root -p < database.sql

# Atau import melalui phpMyAdmin
```

### 3. Konfigurasi Environment

```bash
# Copy file .env.example ke .env
cp .env.example .env

# Edit file .env sesuai dengan konfigurasi server Anda
nano .env
```

### 4. Install Dependencies

```bash
# Install Composer dependencies (jika diperlukan)
composer install

# Install NPM dependencies untuk Tailwind CSS
npm install

# Build Tailwind CSS
npm run build
```

### 5. Set Permissions

```bash
# Set permission untuk folder uploads
chmod -R 777 assets/uploads/

# Set permission untuk folder cache (jika ada)
chmod -R 777 application/cache/
```

### 6. Akses Aplikasi

Buka browser dan akses:
```
http://localhost/absen-rfid-new/
```

## 👤 Default Login

### Admin
- Username: `admin`
- Password: `password`

### Guru
- Username: `guru1`
- Password: `password`

### Wali Kelas
- Username: `guru2`
- Password: `password`

### BK
- Username: `bk1`
- Password: `password`

### Guru Piket
- Username: `piket1`
- Password: `password`

**⚠️ PENTING: Segera ubah password default setelah login pertama kali!**

## 📁 Struktur Proyek

```
absen-rfid-new/
├── application/
│   ├── config/          # Konfigurasi aplikasi
│   ├── controllers/     # Controllers (Admin, Guru, BK, dll)
│   ├── models/          # Models untuk database
│   ├── views/           # Views/Templates
│   ├── libraries/       # Custom libraries (PDF, Excel, WhatsApp)
│   ├── helpers/         # Helper functions
│   └── third_party/     # Third party libraries
├── assets/
│   ├── css/             # CSS files (Tailwind)
│   ├── js/              # JavaScript files
│   ├── img/             # Images
│   └── uploads/         # Upload directory
├── system/              # CodeIgniter core files
├── database.sql         # Database schema
├── .env.example         # Environment configuration example
└── README.md            # This file
```

## 🔐 Sistem Role & Hak Akses

### 1. Admin
- Full access ke semua fitur
- Manajemen data master (siswa, guru, kelas, dll)
- Pengaturan sekolah dan jam kerja
- Laporan lengkap
- Konfigurasi WhatsApp

### 2. Guru
- Dashboard jadwal mengajar
- Input jurnal dan absensi per mapel
- Rekap jurnal pribadi
- Update profile

### 3. Wali Kelas
- Semua fitur Guru +
- Input izin/sakit siswa kelasnya
- Monitoring absensi kelas

### 4. Guru Piket
- Semua fitur Guru +
- Input izin keluar/masuk siswa saat KBM
- Rekap izin

### 5. BK (Bimbingan Konseling)
- Dashboard monitoring pelanggaran
- Auto deteksi siswa alpha ≥3x atau terlambat ≥5x
- Cetak surat panggilan dengan kop sekolah

## 📱 Fitur WhatsApp Notification

Sistem menggunakan queue system untuk mengirim notifikasi WhatsApp:

1. Saat siswa tap kartu RFID, data langsung tersimpan
2. Notifikasi masuk ke antrian (wa_queue)
3. Background process/cron job memproses antrian
4. Tidak blocking proses tap kartu

### Setup Cron Job

Tambahkan cron job di server untuk memproses antrian:

```bash
# Edit crontab
crontab -e

# Tambahkan baris berikut (proses setiap 1 menit)
* * * * * php /path/to/absen-rfid-new/index.php cron/process_wa_queue

# Notifikasi otomatis jam 09:00 untuk siswa yang belum absen
0 9 * * * php /path/to/absen-rfid-new/index.php cron/notif_siswa_alpha
```

## 📊 Template Import Excel

Template Excel untuk import data siswa dan guru tersedia di:
- Download dari menu Admin → Data Master → Template Import

Format template:
- **Siswa**: NIS, NISN, Nama, UID RFID, Kelas, JK, Tempat Lahir, Tanggal Lahir, dll
- **Guru**: NIP, NIK, Nama, UID RFID, JK, Tempat Lahir, Tanggal Lahir, dll

## 🖨️ Export Laporan

Semua laporan dapat di-export dalam format:
- **PDF**: Dengan kop sekolah (logo, nama, alamat)
- **Excel**: Format .xlsx untuk analisis lebih lanjut

## 🔧 Konfigurasi RFID Reader

Untuk integrasi dengan RFID reader:

1. RFID reader mengirim UID kartu ke endpoint:
   ```
   POST /rfid/scan/process
   Data: {uid: "RFID-XXXXX"}
   ```

2. Sistem akan:
   - Validasi UID di database
   - Simpan absensi (masuk/pulang)
   - Tambahkan ke queue WhatsApp
   - Return response JSON

## 🎨 Tailwind CSS

Aplikasi menggunakan Tailwind CSS untuk styling modern dan responsive.

### Build Tailwind

```bash
# Development (watch mode)
npm run dev

# Production (minified)
npm run build
```

## 🔒 Keamanan

Aplikasi sudah dilengkapi dengan:
- ✅ Password hashing dengan `password_hash()`
- ✅ CSRF Protection
- ✅ XSS Prevention (output sanitization)
- ✅ SQL Injection Prevention (prepared statements)
- ✅ Session management yang aman
- ✅ File upload validation
- ✅ Input validation server-side

## 📝 API Endpoints

### RFID Scan
```
POST /rfid/scan/process
Parameters: {uid: string}
Response: {status: success/error, message: string, data: object}
```

### Get Absensi Hari Ini
```
GET /rfid/scan/get_today
Response: {status: success, data: array}
```

## 🐛 Troubleshooting

### Error: Database connection failed
- Pastikan MySQL service sudah running
- Cek konfigurasi database di file `.env`
- Pastikan database sudah di-import

### Error: Class 'Dompdf' not found
- Install Composer dependencies: `composer install`

### CSS tidak loading
- Build Tailwind CSS: `npm run build`
- Cek base_url di file `.env`

### Upload foto gagal
- Set permission folder uploads: `chmod -R 777 assets/uploads/`

## 📞 Support

Jika ada pertanyaan atau masalah, silakan buka issue di GitHub repository.

## 📄 License

MIT License - Open source untuk pengembangan lebih lanjut.

## 👨‍💻 Credits

Developed with ❤️ using:
- CodeIgniter 3
- Tailwind CSS
- DOMPDF
- PHPSpreadsheet
- WhatsApp API

---

**Happy Coding!** 🚀
