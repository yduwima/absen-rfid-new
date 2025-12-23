-- ============================================================
-- Database Schema for RFID Attendance System
-- CodeIgniter 3 + MySQL
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

-- Create Database
CREATE DATABASE IF NOT EXISTS `absensi_rfid` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `absensi_rfid`;

-- ============================================================
-- 1. TABEL USERS - Data Login Semua Pengguna
-- ============================================================
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru','walikelas','piket','bk') NOT NULL DEFAULT 'guru',
  `guru_id` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_role` (`role`),
  KEY `fk_users_guru` (`guru_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. TABEL TAHUN AJARAN
-- ============================================================
CREATE TABLE `tahun_ajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) NOT NULL,
  `tahun_mulai` year(4) NOT NULL,
  `tahun_selesai` year(4) NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. TABEL SEMESTER
-- ============================================================
CREATE TABLE `semester` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_ajaran_id` int(11) NOT NULL,
  `nama` enum('Ganjil','Genap') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_semester_tahun` (`tahun_ajaran_id`),
  KEY `idx_active` (`is_active`),
  CONSTRAINT `fk_semester_tahun` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. TABEL KELAS
-- ============================================================
CREATE TABLE `kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `tingkat` enum('10','11','12') NOT NULL,
  `jurusan` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tingkat` (`tingkat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. TABEL GURU - Data Guru dan Staff
-- ============================================================
CREATE TABLE `guru` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(30) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `uid_rfid` varchar(50) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `status` enum('Aktif','Tidak Aktif','Cuti') DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_rfid` (`uid_rfid`),
  KEY `idx_status` (`status`),
  KEY `idx_nip` (`nip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. TABEL SISWA - Data Siswa Lengkap
-- ============================================================
CREATE TABLE `siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(20) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `uid_rfid` varchar(50) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `nama_ortu` varchar(100) DEFAULT NULL,
  `telepon_ortu` varchar(20) DEFAULT NULL,
  `status` enum('Aktif','Lulus','Pindah','Keluar') DEFAULT 'Aktif',
  `tahun_masuk` year(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nis` (`nis`),
  UNIQUE KEY `uid_rfid` (`uid_rfid`),
  KEY `fk_siswa_kelas` (`kelas_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. TABEL MATA PELAJARAN
-- ============================================================
CREATE TABLE `mata_pelajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. TABEL JADWAL PELAJARAN
-- ============================================================
CREATE TABLE `jadwal_pelajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_ajaran_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_jadwal_tahun` (`tahun_ajaran_id`),
  KEY `fk_jadwal_semester` (`semester_id`),
  KEY `fk_jadwal_kelas` (`kelas_id`),
  KEY `fk_jadwal_mapel` (`mata_pelajaran_id`),
  KEY `fk_jadwal_guru` (`guru_id`),
  KEY `idx_hari` (`hari`),
  CONSTRAINT `fk_jadwal_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jadwal_mapel` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jadwal_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jadwal_tahun` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. TABEL WALI KELAS
-- ============================================================
CREATE TABLE `wali_kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_ajaran_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_wali_tahun` (`tahun_ajaran_id`),
  KEY `fk_wali_semester` (`semester_id`),
  KEY `fk_wali_guru` (`guru_id`),
  KEY `fk_wali_kelas` (`kelas_id`),
  CONSTRAINT `fk_wali_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wali_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wali_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wali_tahun` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. TABEL GURU PIKET
-- ============================================================
CREATE TABLE `guru_piket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guru_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_piket_guru` (`guru_id`),
  KEY `idx_hari` (`hari`),
  CONSTRAINT `fk_piket_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 11. TABEL PENGATURAN SEKOLAH
-- ============================================================
CREATE TABLE `pengaturan_sekolah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_sekolah` varchar(100) NOT NULL,
  `alamat` text,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `nama_kepsek` varchar(100) DEFAULT NULL,
  `nip_kepsek` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 12. TABEL PENGATURAN JAM KERJA
-- ============================================================
CREATE TABLE `pengaturan_jam_kerja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jam_masuk` time NOT NULL DEFAULT '07:00:00',
  `jam_pulang` time NOT NULL DEFAULT '15:00:00',
  `toleransi_keterlambatan` int(11) DEFAULT 15 COMMENT 'dalam menit',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 13. TABEL HARI KERJA
-- ============================================================
CREATE TABLE `hari_kerja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hari` (`hari`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 14. TABEL HARI LIBUR
-- ============================================================
CREATE TABLE `hari_libur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `keterangan` varchar(200) NOT NULL,
  `jenis` enum('Nasional','Sekolah') DEFAULT 'Nasional',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tanggal` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 15. TABEL ABSENSI HARIAN - Absensi RFID (Siswa & Guru)
-- ============================================================
CREATE TABLE `absensi_harian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `user_type` enum('siswa','guru') NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'siswa_id or guru_id',
  `uid_rfid` varchar(50) NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status_masuk` enum('Hadir','Terlambat') DEFAULT 'Hadir',
  `keterlambatan` int(11) DEFAULT 0 COMMENT 'dalam menit',
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_user` (`user_type`,`user_id`),
  KEY `idx_uid` (`uid_rfid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 16. TABEL ABSENSI MAPEL - Absensi Per Mata Pelajaran
-- ============================================================
CREATE TABLE `absensi_mapel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jurnal_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `status` enum('H','S','I','A') NOT NULL COMMENT 'H=Hadir, S=Sakit, I=Izin, A=Alpha',
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_absensi_jurnal` (`jurnal_id`),
  KEY `fk_absensi_siswa` (`siswa_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 17. TABEL JURNAL GURU
-- ============================================================
CREATE TABLE `jurnal_guru` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `materi` text NOT NULL,
  `kegiatan` text,
  `kelas_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_jurnal_jadwal` (`jadwal_id`),
  KEY `fk_jurnal_guru` (`guru_id`),
  KEY `fk_jurnal_kelas` (`kelas_id`),
  KEY `fk_jurnal_mapel` (`mata_pelajaran_id`),
  KEY `idx_tanggal` (`tanggal`),
  CONSTRAINT `fk_jurnal_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jurnal_jadwal` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jurnal_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jurnal_mapel` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 18. TABEL IZIN SISWA
-- ============================================================
CREATE TABLE `izin_siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('Sakit','Izin','Izin Masuk','Izin Pulang') NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `keterangan` text,
  `dibuat_oleh` int(11) NOT NULL COMMENT 'user_id guru/wali kelas/piket',
  `approved_by` int(11) DEFAULT NULL COMMENT 'user_id yang approve',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_izin_siswa` (`siswa_id`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_jenis` (`jenis`),
  CONSTRAINT `fk_izin_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 19. TABEL WA SETTING - Pengaturan WhatsApp API
-- ============================================================
CREATE TABLE `wa_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_api` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 20. TABEL WA TEMPLATE - Template Pesan WA
-- ============================================================
CREATE TABLE `wa_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `template` text NOT NULL COMMENT 'Gunakan {nama}, {kelas}, {waktu}, dll sebagai placeholder',
  `jenis` enum('Masuk','Pulang','Alpha','Terlambat') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 21. TABEL WA QUEUE - Antrian Notifikasi WA
-- ============================================================
CREATE TABLE `wa_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_tujuan` varchar(20) NOT NULL,
  `pesan` text NOT NULL,
  `status` enum('pending','processing','sent','failed') DEFAULT 'pending',
  `retry_count` int(11) DEFAULT 0,
  `error_message` text,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 22. TABEL MONITORING BK
-- ============================================================
CREATE TABLE `monitoring_bk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `jumlah_alpha` int(11) DEFAULT 0,
  `jumlah_terlambat` int(11) DEFAULT 0,
  `jenis_pelanggaran` enum('Alpha','Terlambat','Both') NOT NULL,
  `status` enum('Baru','Diproses','Selesai') DEFAULT 'Baru',
  `catatan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_monitoring_siswa` (`siswa_id`),
  KEY `idx_periode` (`bulan`,`tahun`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_monitoring_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 23. TABEL SURAT BK
-- ============================================================
CREATE TABLE `surat_bk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_surat` varchar(50) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `monitoring_id` int(11) DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `hari_panggilan` varchar(20) DEFAULT NULL,
  `tanggal_panggilan` date DEFAULT NULL,
  `waktu_panggilan` time DEFAULT NULL,
  `keterangan` text,
  `status` enum('Draft','Terkirim') DEFAULT 'Draft',
  `file_pdf` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_surat` (`nomor_surat`),
  KEY `fk_surat_siswa` (`siswa_id`),
  KEY `fk_surat_monitoring` (`monitoring_id`),
  CONSTRAINT `fk_surat_monitoring` FOREIGN KEY (`monitoring_id`) REFERENCES `monitoring_bk` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_surat_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 24. TABEL WA NOTIFICATION KELAS (untuk setting kelas mana yang dapat notif)
-- ============================================================
CREATE TABLE `wa_notif_kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kelas_id` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_wanotif_kelas` (`kelas_id`),
  CONSTRAINT `fk_wanotif_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- FOREIGN KEY untuk tabel users
-- ============================================================
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE;

ALTER TABLE `absensi_mapel`
  ADD CONSTRAINT `fk_absensi_jurnal` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal_guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_absensi_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE;

-- ============================================================
-- INSERT DATA DUMMY UNTUK TESTING
-- ============================================================

-- Insert Pengaturan Sekolah
INSERT INTO `pengaturan_sekolah` (`nama_sekolah`, `alamat`, `telepon`, `email`, `website`, `nama_kepsek`, `nip_kepsek`) VALUES
('SMK Negeri 1 Contoh', 'Jl. Pendidikan No. 123, Jakarta', '021-12345678', 'info@smkn1contoh.sch.id', 'https://smkn1contoh.sch.id', 'Drs. Ahmad Suryanto, M.Pd', '196501011990031001');

-- Insert Pengaturan Jam Kerja
INSERT INTO `pengaturan_jam_kerja` (`jam_masuk`, `jam_pulang`, `toleransi_keterlambatan`) VALUES
('07:00:00', '15:00:00', 15);

-- Insert Hari Kerja (Senin - Sabtu)
INSERT INTO `hari_kerja` (`hari`, `is_active`) VALUES
('Senin', 1),
('Selasa', 1),
('Rabu', 1),
('Kamis', 1),
('Jumat', 1),
('Sabtu', 1),
('Minggu', 0);

-- Insert Tahun Ajaran
INSERT INTO `tahun_ajaran` (`nama`, `tahun_mulai`, `tahun_selesai`, `is_active`) VALUES
('2023/2024', 2023, 2024, 0),
('2024/2025', 2024, 2025, 1);

-- Insert Semester
INSERT INTO `semester` (`tahun_ajaran_id`, `nama`, `tanggal_mulai`, `tanggal_selesai`, `is_active`) VALUES
(1, 'Ganjil', '2023-07-01', '2023-12-31', 0),
(1, 'Genap', '2024-01-01', '2024-06-30', 0),
(2, 'Ganjil', '2024-07-01', '2024-12-31', 1),
(2, 'Genap', '2025-01-01', '2025-06-30', 0);

-- Insert Kelas
INSERT INTO `kelas` (`nama`, `tingkat`, `jurusan`, `is_active`) VALUES
('X RPL 1', '10', 'Rekayasa Perangkat Lunak', 1),
('X RPL 2', '10', 'Rekayasa Perangkat Lunak', 1),
('XI RPL 1', '11', 'Rekayasa Perangkat Lunak', 1),
('XI RPL 2', '11', 'Rekayasa Perangkat Lunak', 1),
('XII RPL 1', '12', 'Rekayasa Perangkat Lunak', 1),
('XII RPL 2', '12', 'Rekayasa Perangkat Lunak', 1);

-- Insert Guru
INSERT INTO `guru` (`nip`, `nik`, `nama`, `uid_rfid`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `telepon`, `email`, `jabatan`, `status`) VALUES
('198501012009031001', '3201010185010001', 'Ahmad Fauzi, S.Pd', 'RFID-GURU-001', 'L', 'Jakarta', '1985-01-01', 'Jl. Guru No. 1', '08123456789', 'ahmad.fauzi@smkn1.sch.id', 'Guru Mapel', 'Aktif'),
('198502022009032002', '3201020285020002', 'Siti Nurhaliza, S.Pd', 'RFID-GURU-002', 'P', 'Bandung', '1985-02-02', 'Jl. Guru No. 2', '08123456790', 'siti.nur@smkn1.sch.id', 'Guru Mapel', 'Aktif'),
('198503032009033003', '3201030385030003', 'Budi Santoso, M.Pd', 'RFID-GURU-003', 'L', 'Surabaya', '1985-03-03', 'Jl. Guru No. 3', '08123456791', 'budi.santoso@smkn1.sch.id', 'Guru BK', 'Aktif'),
('198504042009034004', '3201040485040004', 'Dewi Lestari, S.Pd', 'RFID-GURU-004', 'P', 'Yogyakarta', '1985-04-04', 'Jl. Guru No. 4', '08123456792', 'dewi.lestari@smkn1.sch.id', 'Guru Mapel', 'Aktif'),
('198505052009035005', '3201050585050005', 'Rudi Hermawan, S.Kom', 'RFID-GURU-005', 'L', 'Semarang', '1985-05-05', 'Jl. Guru No. 5', '08123456793', 'rudi.hermawan@smkn1.sch.id', 'Guru Mapel', 'Aktif');

-- Insert Users (Admin, Guru, Wali Kelas, Piket, BK)
INSERT INTO `users` (`username`, `password`, `role`, `guru_id`, `email`, `is_active`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 'admin@smkn1.sch.id', 1),
('guru1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru', 1, 'ahmad.fauzi@smkn1.sch.id', 1),
('guru2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'walikelas', 2, 'siti.nur@smkn1.sch.id', 1),
('bk1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bk', 3, 'budi.santoso@smkn1.sch.id', 1),
('piket1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'piket', 4, 'dewi.lestari@smkn1.sch.id', 1);
-- Password untuk semua user: password

-- Insert Siswa
INSERT INTO `siswa` (`nis`, `nisn`, `nama`, `uid_rfid`, `kelas_id`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `telepon`, `email`, `nama_ortu`, `telepon_ortu`, `status`, `tahun_masuk`) VALUES
('2024001', '0012345678', 'Andi Pratama', 'RFID-SISWA-001', 1, 'L', 'Jakarta', '2008-01-15', 'Jl. Siswa No. 1', '08111111111', 'andi@student.com', 'Bapak Andi', '08211111111', 'Aktif', 2024),
('2024002', '0012345679', 'Bella Angelina', 'RFID-SISWA-002', 1, 'P', 'Bandung', '2008-02-20', 'Jl. Siswa No. 2', '08111111112', 'bella@student.com', 'Bapak Bella', '08211111112', 'Aktif', 2024),
('2024003', '0012345680', 'Citra Dewi', 'RFID-SISWA-003', 1, 'P', 'Surabaya', '2008-03-10', 'Jl. Siswa No. 3', '08111111113', 'citra@student.com', 'Bapak Citra', '08211111113', 'Aktif', 2024),
('2023001', '0012345681', 'Doni Saputra', 'RFID-SISWA-004', 3, 'L', 'Jakarta', '2007-04-12', 'Jl. Siswa No. 4', '08111111114', 'doni@student.com', 'Bapak Doni', '08211111114', 'Aktif', 2023),
('2023002', '0012345682', 'Eka Putri', 'RFID-SISWA-005', 3, 'P', 'Yogyakarta', '2007-05-18', 'Jl. Siswa No. 5', '08111111115', 'eka@student.com', 'Bapak Eka', '08211111115', 'Aktif', 2023);

-- Insert Mata Pelajaran
INSERT INTO `mata_pelajaran` (`kode`, `nama`, `deskripsi`, `is_active`) VALUES
('MTK', 'Matematika', 'Mata pelajaran matematika', 1),
('BIN', 'Bahasa Indonesia', 'Mata pelajaran bahasa Indonesia', 1),
('BING', 'Bahasa Inggris', 'Mata pelajaran bahasa Inggris', 1),
('PWPB', 'Pemrograman Web dan Perangkat Bergerak', 'Mata pelajaran produktif RPL', 1),
('BDG', 'Basis Data', 'Mata pelajaran basis data', 1),
('PPB', 'Pemrograman Berorientasi Objek', 'Mata pelajaran OOP', 1);

-- Insert Jadwal Pelajaran (contoh untuk kelas X RPL 1)
INSERT INTO `jadwal_pelajaran` (`tahun_ajaran_id`, `semester_id`, `kelas_id`, `mata_pelajaran_id`, `guru_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`) VALUES
(2, 3, 1, 1, 1, 'Senin', '07:00:00', '08:30:00', 'R-101'),
(2, 3, 1, 2, 2, 'Senin', '08:30:00', '10:00:00', 'R-101'),
(2, 3, 1, 4, 5, 'Selasa', '07:00:00', '09:30:00', 'Lab-Komputer'),
(2, 3, 1, 5, 1, 'Rabu', '07:00:00', '08:30:00', 'Lab-Komputer');

-- Insert Wali Kelas
INSERT INTO `wali_kelas` (`tahun_ajaran_id`, `semester_id`, `guru_id`, `kelas_id`) VALUES
(2, 3, 2, 1),
(2, 3, 4, 3);

-- Insert Guru Piket
INSERT INTO `guru_piket` (`guru_id`, `hari`, `is_active`) VALUES
(4, 'Senin', 1),
(5, 'Selasa', 1),
(1, 'Rabu', 1);

-- Insert WA Setting (contoh)
INSERT INTO `wa_setting` (`url_api`, `api_key`, `sender`, `link_url`, `is_active`) VALUES
('https://api.whatsapp.com/send', 'your-api-key-here', '628123456789', 'https://smkn1contoh.sch.id', 1);

-- Insert WA Template
INSERT INTO `wa_template` (`nama`, `template`, `jenis`, `is_active`) VALUES
('Template Masuk', 'Assalamualaikum, {nama} ({kelas}) telah masuk sekolah pada pukul {waktu}. Terima kasih.', 'Masuk', 1),
('Template Pulang', 'Assalamualaikum, {nama} ({kelas}) telah pulang sekolah pada pukul {waktu}. Terima kasih.', 'Pulang', 1),
('Template Terlambat', 'Assalamualaikum, {nama} ({kelas}) terlambat masuk sekolah pada pukul {waktu}. Keterlambatan: {menit} menit.', 'Terlambat', 1);

-- Insert Hari Libur (contoh)
INSERT INTO `hari_libur` (`tanggal`, `keterangan`, `jenis`) VALUES
('2024-08-17', 'Hari Kemerdekaan RI', 'Nasional'),
('2024-12-25', 'Hari Natal', 'Nasional'),
('2025-01-01', 'Tahun Baru', 'Nasional');

-- ============================================================
-- END OF DATABASE SCHEMA
-- ============================================================
