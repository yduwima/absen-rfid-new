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

// Admin Settings Routes
$route['admin/pengaturan/sekolah'] = 'admin/pengaturan/sekolah';
$route['admin/pengaturan/jam_kerja'] = 'admin/pengaturan/jam_kerja';

// Admin Master Data Routes
$route['admin/master/siswa'] = 'admin/master/siswa';
$route['admin/master/siswa/add'] = 'admin/master/siswa_add';
$route['admin/master/siswa/edit/(:num)'] = 'admin/master/siswa_edit/$1';
$route['admin/master/siswa/delete/(:num)'] = 'admin/master/siswa_delete/$1';
$route['admin/master/siswa/get/(:num)'] = 'admin/master/siswa_get/$1';

$route['admin/master/guru'] = 'admin/master/guru';
$route['admin/master/guru/add'] = 'admin/master/guru_add';
$route['admin/master/guru/edit/(:num)'] = 'admin/master/guru_edit/$1';
$route['admin/master/guru/delete/(:num)'] = 'admin/master/guru_delete/$1';
$route['admin/master/guru/get/(:num)'] = 'admin/master/guru_get/$1';

$route['admin/master/kelas'] = 'admin/master/kelas';
$route['admin/master/kelas/add'] = 'admin/master/kelas_add';
$route['admin/master/kelas/edit/(:num)'] = 'admin/master/kelas_edit/$1';
$route['admin/master/kelas/delete/(:num)'] = 'admin/master/kelas_delete/$1';
$route['admin/master/kelas/get/(:num)'] = 'admin/master/kelas_get/$1';

$route['admin/master/mapel'] = 'admin/master/mapel';
$route['admin/master/mapel/add'] = 'admin/master/mapel_add';
$route['admin/master/mapel/edit/(:num)'] = 'admin/master/mapel_edit/$1';
$route['admin/master/mapel/delete/(:num)'] = 'admin/master/mapel_delete/$1';
$route['admin/master/mapel/get/(:num)'] = 'admin/master/mapel_get/$1';

$route['admin/master/tahun_ajaran'] = 'admin/master/tahun_ajaran';
$route['admin/master/tahun_ajaran/add'] = 'admin/master/tahun_ajaran_add';
$route['admin/master/tahun_ajaran/edit/(:num)'] = 'admin/master/tahun_ajaran_edit/$1';
$route['admin/master/tahun_ajaran/delete/(:num)'] = 'admin/master/tahun_ajaran_delete/$1';
$route['admin/master/tahun_ajaran/get/(:num)'] = 'admin/master/tahun_ajaran_get/$1';

$route['admin/master/jadwal'] = 'admin/master/jadwal';
$route['admin/master/jadwal/add'] = 'admin/master/jadwal_add';
$route['admin/master/jadwal/edit/(:num)'] = 'admin/master/jadwal_edit/$1';
$route['admin/master/jadwal/delete/(:num)'] = 'admin/master/jadwal_delete/$1';
$route['admin/master/jadwal/get/(:num)'] = 'admin/master/jadwal_get/$1';

// Admin Reports Routes
$route['admin/laporan/absensi_siswa'] = 'admin/laporan/absensi_siswa';
$route['admin/laporan/absensi_guru'] = 'admin/laporan/absensi_guru';
$route['admin/laporan/rekap_siswa'] = 'admin/laporan/rekap_siswa';
$route['admin/laporan/rekap_guru'] = 'admin/laporan/rekap_guru';

// Admin WhatsApp Routes
$route['admin/wa/pengaturan'] = 'admin/wa/pengaturan';
$route['admin/wa/template'] = 'admin/wa/template';
$route['admin/wa/notif_kelas'] = 'admin/wa/notif_kelas';

// RFID Routes (Public - no login required)
$route['rfid'] = 'rfid/scan/index';
$route['rfid/scan'] = 'rfid/scan/index';
$route['rfid/scan/process'] = 'rfid/scan/process';
$route['rfid/scan/get_today'] = 'rfid/scan/get_today';

// Guru Routes
$route['guru/dashboard'] = 'guru/dashboard/index';
$route['guru/jurnal'] = 'guru/jurnal/index';
$route['guru/jurnal/add/(:num)'] = 'guru/jurnal/add/$1';
$route['guru/jurnal/detail/(:num)'] = 'guru/jurnal/detail/$1';
$route['guru/laporan'] = 'guru/laporan/index';
$route['guru/profile'] = 'guru/profile/index';
$route['guru/profile/update'] = 'guru/profile/update';
$route['guru/profile/change_password'] = 'guru/profile/change_password';

// Wali Kelas Routes
$route['walikelas/izin'] = 'walikelas/izin/index';
$route['walikelas/izin/add'] = 'walikelas/izin/add';
$route['walikelas/monitoring'] = 'walikelas/monitoring/index';

// Piket Routes
$route['piket/izin'] = 'piket/izin/index';
$route['piket/izin/add'] = 'piket/izin/add';

// BK Routes
$route['bk/dashboard'] = 'bk/dashboard/index';
$route['bk/monitoring'] = 'bk/monitoring/index';
$route['bk/surat'] = 'bk/surat/index';
$route['bk/profile'] = 'bk/profile/index';
