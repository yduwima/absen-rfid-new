# System Architecture - RFID Attendance System

## Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    RFID Attendance System                        │
│                  (CodeIgniter 3 + Tailwind CSS)                  │
└─────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│                         PRESENTATION LAYER                           │
├──────────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │
│  │ Public Pages │  │  Auth Pages  │  │ Admin Panel  │              │
│  │  - RFID Scan │  │  - Login     │  │ - Dashboard  │              │
│  │              │  │  - Logout    │  │ - Settings   │              │
│  └──────────────┘  └──────────────┘  │ - Master Data│              │
│                                       │ - Reports    │              │
│  ┌──────────────┐  ┌──────────────┐  └──────────────┘              │
│  │ Teacher Panel│  │   BK Panel   │                                │
│  │  - Dashboard │  │ - Monitoring │  ┌──────────────┐              │
│  │  - Journal   │  │ - Letters    │  │ Wali Kelas   │              │
│  │  - Reports   │  └──────────────┘  │ - Izin/Sakit │              │
│  └──────────────┘                     │ - Monitoring │              │
│                                       └──────────────┘              │
└──────────────────────────────────────────────────────────────────────┘
                                  │
                                  ▼
┌──────────────────────────────────────────────────────────────────────┐
│                        APPLICATION LAYER                             │
├──────────────────────────────────────────────────────────────────────┤
│                        CONTROLLERS                                   │
│  ┌────────────┐ ┌────────────┐ ┌────────────┐ ┌────────────┐       │
│  │   Auth     │ │   RFID     │ │   Admin    │ │    Guru    │       │
│  │  - login   │ │  - scan    │ │ - dashboard│ │ - dashboard│       │
│  │  - logout  │ │  - process │ │ - settings │ │ - jurnal   │       │
│  └────────────┘ └────────────┘ └────────────┘ └────────────┘       │
│                                                                      │
│  ┌────────────┐ ┌────────────┐ ┌────────────┐                      │
│  │ Walikelas  │ │   Piket    │ │     BK     │                      │
│  │  - izin    │ │  - izin    │ │ - monitoring│                     │
│  └────────────┘ └────────────┘ └────────────┘                      │
└──────────────────────────────────────────────────────────────────────┘
                                  │
                                  ▼
┌──────────────────────────────────────────────────────────────────────┐
│                         BUSINESS LOGIC LAYER                         │
├──────────────────────────────────────────────────────────────────────┤
│                            MODELS                                    │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐                │
│  │  User_model  │ │ Siswa_model  │ │  Guru_model  │                │
│  │  Kelas_model │ │Absensi_model │ │Jurnal_model  │                │
│  │  Mapel_model │ │Jadwal_model  │ │Pengaturan_m. │                │
│  │   Wa_model   │ │Laporan_model │ │Base_Model    │                │
│  └──────────────┘ └──────────────┘ └──────────────┘                │
│                                                                      │
│                         LIBRARIES                                    │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐                │
│  │     PDF      │ │    Excel     │ │  WhatsApp    │                │
│  │   (DOMPDF)   │ │(PHPSpreadsheet)│ │   (API)     │                │
│  └──────────────┘ └──────────────┘ └──────────────┘                │
│                                                                      │
│                          HELPERS                                     │
│  ┌──────────────────────────────────────────────────────┐           │
│  │  app_helper: Authentication, Date, File, Flash, etc │           │
│  └──────────────────────────────────────────────────────┘           │
└──────────────────────────────────────────────────────────────────────┘
                                  │
                                  ▼
┌──────────────────────────────────────────────────────────────────────┐
│                          DATA LAYER                                  │
├──────────────────────────────────────────────────────────────────────┤
│                       MySQL Database                                 │
│  ┌────────────────────────────────────────────────────────┐         │
│  │ 24 Tables:                                             │         │
│  │ - users, siswa, guru, kelas                            │         │
│  │ - tahun_ajaran, semester, mata_pelajaran               │         │
│  │ - jadwal_pelajaran, wali_kelas, guru_piket            │         │
│  │ - absensi_harian, absensi_mapel, jurnal_guru          │         │
│  │ - izin_siswa, pengaturan_sekolah, pengaturan_jam_kerja│         │
│  │ - hari_kerja, hari_libur                               │         │
│  │ - wa_setting, wa_template, wa_queue, wa_notif_kelas   │         │
│  │ - monitoring_bk, surat_bk                              │         │
│  └────────────────────────────────────────────────────────┘         │
└──────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│                     EXTERNAL INTEGRATIONS                            │
├──────────────────────────────────────────────────────────────────────┤
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐        │
│  │ RFID Hardware  │  │ WhatsApp API   │  │  Cron Jobs     │        │
│  │ - RC522 Reader │  │ - Send Notif   │  │ - WA Queue     │        │
│  │ - ESP8266/32   │  │ - Templates    │  │ - Auto Notif   │        │
│  │ - Arduino      │  │                │  │ - Backup       │        │
│  └────────────────┘  └────────────────┘  └────────────────┘        │
└──────────────────────────────────────────────────────────────────────┘
```

## Data Flow Diagram

### RFID Attendance Flow

```
┌──────────────┐
│ RFID Reader  │
│  (Hardware)  │
└──────┬───────┘
       │ HTTP POST
       │ uid=RFID-XXX
       ▼
┌──────────────────┐
│  RFID Controller │
│  /rfid/scan/     │
│    process       │
└──────┬───────────┘
       │
       ├─► Validate UID
       │   (Siswa_model / Guru_model)
       │
       ├─► Check Today's Attendance
       │   (Absensi_model)
       │
       ├─► Calculate Late
       │   (Helper: hitung_keterlambatan)
       │
       ├─► Save Attendance
       │   (Tap Masuk / Tap Pulang)
       │
       ├─► Add to WA Queue
       │   (Wa_model)
       │
       └─► Return JSON Response
           {status, message, data}
```

### Authentication Flow

```
┌─────────────┐
│ Login Form  │
└──────┬──────┘
       │ POST credentials
       ▼
┌──────────────────┐
│ Auth Controller  │
│   do_login()     │
└──────┬───────────┘
       │
       ├─► Validate Input
       │   (Form_validation)
       │
       ├─► Verify Credentials
       │   (User_model)
       │   - Check username
       │   - Verify password (bcrypt)
       │   - Check is_active
       │
       ├─► Create Session
       │   (Session data: user_id, role, etc)
       │
       └─► Redirect by Role
           - admin → /admin/dashboard
           - guru → /guru/dashboard
           - bk → /bk/dashboard
```

### WhatsApp Notification Flow

```
┌──────────────────┐
│ RFID Tap Event   │
└────────┬─────────┘
         │
         ▼
┌──────────────────────┐
│  RFID Controller     │
│  _send_wa_notification│
└────────┬─────────────┘
         │
         ├─► Check Kelas Active
         │   (Wa_model)
         │
         ├─► Get Template
         │   (wa_template table)
         │
         ├─► Parse Template
         │   Replace {nama}, {kelas}, etc
         │
         └─► Add to Queue
             (wa_queue table)
                 │
                 ▼
         ┌───────────────┐
         │  Cron Job     │
         │  (Every 1min) │
         └───────┬───────┘
                 │
                 ├─► Get Pending
                 │   (status=pending)
                 │
                 ├─► Send via API
                 │   (WhatsApp Library)
                 │
                 └─► Update Status
                     (sent/failed)
```

## Role-Based Access Control (RBAC)

```
┌────────────────────────────────────────────────────────────┐
│                        ROLES                               │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  ┌──────────┐                                              │
│  │  ADMIN   │  Full Access                                │
│  │          │  - All Settings                              │
│  │          │  - All Master Data                           │
│  │          │  - All Reports                               │
│  │          │  - WA Configuration                          │
│  └──────────┘                                              │
│                                                            │
│  ┌──────────┐                                              │
│  │   GURU   │  Teacher Access                             │
│  │          │  - Dashboard                                 │
│  │          │  - Journal Entry                             │
│  │          │  - Subject Attendance                        │
│  │          │  - Personal Reports                          │
│  │          │  - Profile                                   │
│  └──────────┘                                              │
│       │                                                    │
│       ├──────► WALI KELAS (Guru + Additional)             │
│       │        - Input Izin/Sakit                          │
│       │        - Class Monitoring                          │
│       │                                                    │
│       └──────► PIKET (Guru + Additional)                  │
│                - Input Izin during Class                   │
│                - Leave History                             │
│                                                            │
│  ┌──────────┐                                              │
│  │    BK    │  Counselor Access                           │
│  │          │  - Monitoring Dashboard                      │
│  │          │  - Violation Detection                       │
│  │          │  - Letter Generation                         │
│  │          │  - Profile                                   │
│  └──────────┘                                              │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

## Technology Stack

```
┌─────────────────────────────────────────────────────────────┐
│                    FRONTEND                                 │
├─────────────────────────────────────────────────────────────┤
│  - Tailwind CSS 3.4 (Utility-first CSS)                    │
│  - jQuery 3.7 (DOM manipulation, AJAX)                      │
│  - Chart.js (Data visualization)                            │
│  - Font Awesome 6.4 (Icons)                                 │
│  - Vanilla JavaScript (Custom interactions)                 │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                     BACKEND                                 │
├─────────────────────────────────────────────────────────────┤
│  - PHP 7.4+ (Server-side language)                          │
│  - CodeIgniter 3 (MVC Framework)                            │
│  - MySQL 5.7+ (Relational database)                         │
│  - Apache/Nginx (Web server)                                │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                   LIBRARIES                                 │
├─────────────────────────────────────────────────────────────┤
│  - DOMPDF (PDF generation)                                  │
│  - PHPSpreadsheet (Excel import/export)                     │
│  - WhatsApp API Client (Notifications)                      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                   HARDWARE                                  │
├─────────────────────────────────────────────────────────────┤
│  - RFID RC522 Reader Module                                 │
│  - ESP8266/ESP32 (WiFi-enabled microcontroller)            │
│  - Arduino Compatible Board                                 │
│  - RFID Cards/Tags (13.56MHz)                               │
└─────────────────────────────────────────────────────────────┘
```

## Security Architecture

```
┌────────────────────────────────────────────────────────────┐
│                   SECURITY LAYERS                          │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  ┌───────────────────────────────────────────────┐        │
│  │ Layer 1: Input Validation                     │        │
│  │  - Form_validation library                    │        │
│  │  - XSS filtering (xss_clean)                  │        │
│  │  - File upload validation                     │        │
│  └───────────────────────────────────────────────┘        │
│                                                            │
│  ┌───────────────────────────────────────────────┐        │
│  │ Layer 2: Authentication                       │        │
│  │  - Password hashing (bcrypt)                  │        │
│  │  - Session-based auth                         │        │
│  │  - Role-based access control                  │        │
│  └───────────────────────────────────────────────┘        │
│                                                            │
│  ┌───────────────────────────────────────────────┐        │
│  │ Layer 3: CSRF Protection                      │        │
│  │  - Token generation                           │        │
│  │  - Token validation                           │        │
│  │  - Exclusions for public APIs                 │        │
│  └───────────────────────────────────────────────┘        │
│                                                            │
│  ┌───────────────────────────────────────────────┐        │
│  │ Layer 4: Database Security                    │        │
│  │  - Prepared statements                        │        │
│  │  - SQL injection prevention                   │        │
│  │  - Database encryption                        │        │
│  └───────────────────────────────────────────────┘        │
│                                                            │
│  ┌───────────────────────────────────────────────┐        │
│  │ Layer 5: Output Security                      │        │
│  │  - HTML escaping                              │        │
│  │  - Content Security Policy                    │        │
│  │  - X-Frame-Options                            │        │
│  └───────────────────────────────────────────────┘        │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

---

**Document Version:** 1.0  
**Last Updated:** December 23, 2024  
**Author:** Development Team
