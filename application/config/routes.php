<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/

$route['default_controller'] = 'auth/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth Routes
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['auth/do_login'] = 'auth/do_login';

// Dashboard Routes
$route['dashboard'] = 'dashboard/index';

// Admin Routes
$route['admin/dashboard'] = 'admin/dashboard/index';
$route['admin/pengaturan/sekolah'] = 'admin/pengaturan/sekolah';
$route['admin/pengaturan/jam-kerja'] = 'admin/pengaturan/jam_kerja';

// RFID Routes (Public - no login required)
$route['rfid'] = 'rfid/scan/index';
$route['rfid/scan'] = 'rfid/scan/index';
$route['rfid/scan/process'] = 'rfid/scan/process';
$route['rfid/scan/get_today'] = 'rfid/scan/get_today';

// Guru Routes
$route['guru/dashboard'] = 'guru/dashboard/index';
$route['guru/jurnal'] = 'guru/jurnal/index';
$route['guru/profile'] = 'guru/profile/index';

// Wali Kelas Routes
$route['walikelas/izin'] = 'walikelas/izin/index';

// Piket Routes
$route['piket/izin'] = 'piket/izin/index';

// BK Routes
$route['bk/dashboard'] = 'bk/dashboard/index';
$route['bk/monitoring'] = 'bk/monitoring/index';
$route['bk/surat'] = 'bk/surat/index';
$route['bk/profile'] = 'bk/profile/index';
