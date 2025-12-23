<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * App Helper Functions
 * Helper functions for the application
 */

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     */
    function is_logged_in() {
        $ci =& get_instance();
        if (!$ci->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }
}

if (!function_exists('check_role')) {
    /**
     * Check user role
     */
    function check_role($allowed_roles = array()) {
        $ci =& get_instance();
        $user_role = $ci->session->userdata('role');
        
        if (!in_array($user_role, $allowed_roles)) {
            show_error('Anda tidak memiliki akses ke halaman ini', 403, 'Akses Ditolak');
        }
    }
}

if (!function_exists('get_user_data')) {
    /**
     * Get current user data
     */
    function get_user_data($key = null) {
        $ci =& get_instance();
        
        if ($key) {
            return $ci->session->userdata($key);
        }
        
        return array(
            'user_id' => $ci->session->userdata('user_id'),
            'username' => $ci->session->userdata('username'),
            'role' => $ci->session->userdata('role'),
            'nama' => $ci->session->userdata('nama'),
            'email' => $ci->session->userdata('email')
        );
    }
}

if (!function_exists('format_tanggal')) {
    /**
     * Format tanggal Indonesia
     */
    function format_tanggal($date, $format = 'd-m-Y') {
        if (empty($date)) return '-';
        return date($format, strtotime($date));
    }
}

if (!function_exists('format_tanggal_indonesia')) {
    /**
     * Format tanggal ke bahasa Indonesia
     */
    function format_tanggal_indonesia($date) {
        if (empty($date)) return '-';
        
        $bulan = array(
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        );
        
        $split = explode('-', $date);
        return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
    }
}

if (!function_exists('format_waktu')) {
    /**
     * Format waktu
     */
    function format_waktu($time) {
        if (empty($time)) return '-';
        return date('H:i', strtotime($time));
    }
}

if (!function_exists('get_hari')) {
    /**
     * Get hari dalam bahasa Indonesia
     */
    function get_hari($date = null) {
        if (!$date) $date = date('Y-m-d');
        
        $hari = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        );
        
        return $hari[date('l', strtotime($date))];
    }
}

if (!function_exists('hitung_keterlambatan')) {
    /**
     * Hitung keterlambatan dalam menit
     */
    function hitung_keterlambatan($jam_masuk_seharusnya, $jam_masuk_aktual) {
        $seharusnya = strtotime($jam_masuk_seharusnya);
        $aktual = strtotime($jam_masuk_aktual);
        
        if ($aktual <= $seharusnya) {
            return 0;
        }
        
        $diff = $aktual - $seharusnya;
        return round($diff / 60);
    }
}

if (!function_exists('upload_file')) {
    /**
     * Upload file helper
     */
    function upload_file($field_name, $upload_path, $allowed_types = 'jpg|jpeg|png', $max_size = 2048) {
        $ci =& get_instance();
        $ci->load->library('upload');
        
        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = $allowed_types;
        $config['max_size']      = $max_size;
        $config['encrypt_name']  = TRUE;
        
        $ci->upload->initialize($config);
        
        if ($ci->upload->do_upload($field_name)) {
            return $ci->upload->data();
        }
        
        return FALSE;
    }
}

if (!function_exists('delete_file')) {
    /**
     * Delete file helper
     */
    function delete_file($file_path) {
        if (file_exists($file_path)) {
            unlink($file_path);
            return TRUE;
        }
        return FALSE;
    }
}

if (!function_exists('generate_csrf')) {
    /**
     * Generate CSRF token for forms
     */
    function generate_csrf() {
        $ci =& get_instance();
        return array(
            'name' => $ci->security->get_csrf_token_name(),
            'hash' => $ci->security->get_csrf_hash()
        );
    }
}

if (!function_exists('set_flashdata')) {
    /**
     * Set flash message
     */
    function set_flashdata($type, $message) {
        $ci =& get_instance();
        $ci->session->set_flashdata('message_type', $type);
        $ci->session->set_flashdata('message', $message);
    }
}

if (!function_exists('get_flashdata')) {
    /**
     * Get flash message
     */
    function get_flashdata() {
        $ci =& get_instance();
        $type = $ci->session->flashdata('message_type');
        $message = $ci->session->flashdata('message');
        
        if ($message) {
            return array('type' => $type, 'message' => $message);
        }
        
        return NULL;
    }
}

if (!function_exists('rupiah')) {
    /**
     * Format to Rupiah
     */
    function rupiah($angka) {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('get_pengaturan')) {
    /**
     * Get pengaturan sekolah
     */
    function get_pengaturan() {
        $ci =& get_instance();
        $ci->load->model('Pengaturan_model');
        return $ci->Pengaturan_model->get_sekolah();
    }
}

if (!function_exists('get_jam_kerja')) {
    /**
     * Get jam kerja
     */
    function get_jam_kerja() {
        $ci =& get_instance();
        $ci->load->model('Pengaturan_model');
        return $ci->Pengaturan_model->get_jam_kerja();
    }
}

if (!function_exists('is_hari_kerja')) {
    /**
     * Check if today is working day
     */
    function is_hari_kerja($date = null) {
        if (!$date) $date = date('Y-m-d');
        
        $ci =& get_instance();
        $ci->load->model('Pengaturan_model');
        
        $hari = get_hari($date);
        return $ci->Pengaturan_model->is_hari_kerja($hari);
    }
}

if (!function_exists('is_hari_libur')) {
    /**
     * Check if date is holiday
     */
    function is_hari_libur($date = null) {
        if (!$date) $date = date('Y-m-d');
        
        $ci =& get_instance();
        $ci->load->model('Pengaturan_model');
        return $ci->Pengaturan_model->is_hari_libur($date);
    }
}

if (!function_exists('get_status_absensi_label')) {
    /**
     * Get label for absensi status
     */
    function get_status_absensi_label($status) {
        $labels = array(
            'H' => '<span class="badge-success">Hadir</span>',
            'S' => '<span class="badge-warning">Sakit</span>',
            'I' => '<span class="badge-info">Izin</span>',
            'A' => '<span class="badge-danger">Alpha</span>'
        );
        
        return isset($labels[$status]) ? $labels[$status] : '-';
    }
}
