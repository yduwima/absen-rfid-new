<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RFID Scan Controller
 * Public page for RFID scanning (no login required)
 */
class Scan extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Siswa_model');
        $this->load->model('Guru_model');
        $this->load->model('Absensi_model');
        $this->load->model('Pengaturan_model');
        $this->load->model('Wa_model');
    }
    
    /**
     * RFID Scan Page (Public - No Login Required)
     */
    public function index() {
        $data['title'] = 'Scan Kartu RFID';
        $this->load->view('rfid/scan', $data);
    }
    
    /**
     * Process RFID Tap
     * Endpoint for RFID reader to send UID
     */
    public function process() {
        // Get UID from POST
        $uid = $this->input->post('uid', TRUE);
        
        if (empty($uid)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'UID tidak ditemukan'
            ));
            return;
        }
        
        // Check if UID exists in siswa or guru
        $siswa = $this->Siswa_model->get_by_uid($uid);
        $guru = null;
        
        if (!$siswa) {
            $guru = $this->Guru_model->get_by_uid($uid);
        }
        
        if (!$siswa && !$guru) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Kartu tidak terdaftar',
                'sound' => 'error'
            ));
            return;
        }
        
        // Determine user type and data
        $user_type = $siswa ? 'siswa' : 'guru';
        $user_id = $siswa ? $siswa->id : $guru->id;
        $user_data = $siswa ? $siswa : $guru;
        $nama = $user_data->nama;
        $kelas_jabatan = $siswa ? $siswa->nama_kelas : $guru->jabatan;
        $foto = $user_data->foto;
        
        // Get jam kerja settings
        $jam_kerja = $this->Pengaturan_model->get_jam_kerja();
        $jam_masuk_seharusnya = $jam_kerja ? $jam_kerja->jam_masuk : '07:00:00';
        $toleransi = $jam_kerja ? $jam_kerja->toleransi_keterlambatan : 15;
        
        // Check if already tapped today
        $today = date('Y-m-d');
        $existing = $this->Absensi_model->check_today($user_type, $user_id, $today);
        
        $jam_sekarang = date('H:i:s');
        
        if ($existing && $existing->jam_masuk && $existing->jam_pulang) {
            // Already tapped in and out
            echo json_encode(array(
                'status' => 'info',
                'message' => 'Anda sudah melakukan absensi masuk dan pulang hari ini',
                'data' => array(
                    'nama' => $nama,
                    'kelas_jabatan' => $kelas_jabatan,
                    'foto' => $foto,
                    'jam_masuk' => $existing->jam_masuk,
                    'jam_pulang' => $existing->jam_pulang,
                    'user_type' => $user_type
                ),
                'sound' => 'info'
            ));
            return;
        }
        
        if ($existing && $existing->jam_masuk && !$existing->jam_pulang) {
            // Tap pulang
            $this->Absensi_model->tap_pulang($user_type, $user_id, $uid);
            
            // Add to WA queue
            $this->_send_wa_notification($user_type, $user_data, 'Pulang', $jam_sekarang, $kelas_jabatan);
            
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Absensi pulang berhasil',
                'data' => array(
                    'nama' => $nama,
                    'kelas_jabatan' => $kelas_jabatan,
                    'foto' => $foto,
                    'jam_pulang' => $jam_sekarang,
                    'type' => 'pulang',
                    'user_type' => $user_type
                ),
                'sound' => 'success'
            ));
            return;
        }
        
        // Tap masuk
        // Calculate keterlambatan
        $keterlambatan = hitung_keterlambatan($jam_masuk_seharusnya, $jam_sekarang);
        $status_masuk = ($keterlambatan > $toleransi) ? 'Terlambat' : 'Hadir';
        
        $this->Absensi_model->tap_masuk($user_type, $user_id, $uid, $jam_sekarang, $status_masuk, $keterlambatan);
        
        // Add to WA queue
        $jenis_notif = ($status_masuk == 'Terlambat') ? 'Terlambat' : 'Masuk';
        $this->_send_wa_notification($user_type, $user_data, $jenis_notif, $jam_sekarang, $kelas_jabatan, $keterlambatan);
        
        echo json_encode(array(
            'status' => 'success',
            'message' => 'Absensi masuk berhasil',
            'data' => array(
                'nama' => $nama,
                'kelas_jabatan' => $kelas_jabatan,
                'foto' => $foto,
                'jam_masuk' => $jam_sekarang,
                'status' => $status_masuk,
                'keterlambatan' => $keterlambatan,
                'type' => 'masuk',
                'user_type' => $user_type
            ),
            'sound' => ($status_masuk == 'Terlambat') ? 'warning' : 'success'
        ));
    }
    
    /**
     * Get today's attendance (for real-time display)
     */
    public function get_today() {
        $today = date('Y-m-d');
        $absensi = $this->Absensi_model->get_today($today);
        
        echo json_encode(array(
            'status' => 'success',
            'data' => $absensi
        ));
    }
    
    /**
     * Send WhatsApp notification
     */
    private function _send_wa_notification($user_type, $user_data, $jenis, $waktu, $kelas_jabatan, $keterlambatan = 0) {
        // Only send for siswa
        if ($user_type != 'siswa') {
            return;
        }
        
        // Check if kelas is active for notification
        if (!$this->Wa_model->is_kelas_active($user_data->kelas_id)) {
            return;
        }
        
        // Get template
        $template = $this->Wa_model->get_template($jenis);
        
        if (!$template) {
            return;
        }
        
        // Get nomor ortu
        $nomor_tujuan = $user_data->telepon_ortu;
        
        if (empty($nomor_tujuan)) {
            return;
        }
        
        // Parse template
        $pesan = $this->Wa_model->parse_template($template->template, array(
            'nama' => $user_data->nama,
            'kelas' => $kelas_jabatan,
            'waktu' => $waktu,
            'menit' => $keterlambatan
        ));
        
        // Add to queue
        $this->Wa_model->add_to_queue($nomor_tujuan, $pesan);
    }
}
