<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Guru Dashboard Controller
 */
class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        is_logged_in();
        check_role(array('guru', 'walikelas', 'piket'));
        
        $this->load->model('Jadwal_model');
        $this->load->model('Jurnal_model');
        $this->load->model('Guru_model');
        $this->load->model('Pengaturan_model');
    }
    
    /**
     * Guru Dashboard
     */
    public function index() {
        $data['title'] = 'Dashboard Guru';
        $data['page_title'] = 'Dashboard';
        
        $guru_id = $this->session->userdata('guru_id');
        
        // Get jadwal hari ini
        $hari = get_hari();
        $data['jadwal_hari_ini'] = $this->Jadwal_model->get_hari_ini_guru($guru_id, $hari);
        
        // Get jurnal hari ini
        $today = date('Y-m-d');
        $data['jurnal_hari_ini'] = $this->Jurnal_model->get_hari_ini_guru($guru_id, $today);
        
        // Get statistik mengajar bulan ini
        $bulan = date('m');
        $tahun = date('Y');
        
        $this->db->select('COUNT(DISTINCT tanggal) as total_mengajar');
        $this->db->from('jurnal_guru');
        $this->db->where('guru_id', $guru_id);
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $stats = $this->db->get()->row();
        
        $data['total_mengajar_bulan_ini'] = $stats ? $stats->total_mengajar : 0;
        
        // Get jumlah kelas yang diajar
        $tahun_ajaran = $this->Pengaturan_model->get_tahun_ajaran_aktif();
        $semester = $this->Pengaturan_model->get_semester_aktif();
        
        if ($tahun_ajaran && $semester) {
            $this->db->select('COUNT(DISTINCT kelas_id) as jumlah_kelas');
            $this->db->from('jadwal_pelajaran');
            $this->db->where('guru_id', $guru_id);
            $this->db->where('tahun_ajaran_id', $tahun_ajaran->id);
            $this->db->where('semester_id', $semester->id);
            $kelas_stats = $this->db->get()->row();
            
            $data['jumlah_kelas'] = $kelas_stats ? $kelas_stats->jumlah_kelas : 0;
        } else {
            $data['jumlah_kelas'] = 0;
        }
        
        // Check if wali kelas
        $data['is_wali_kelas'] = $this->Guru_model->is_wali_kelas($guru_id);
        if ($data['is_wali_kelas']) {
            $data['wali_kelas_info'] = $this->Guru_model->get_wali_kelas($guru_id);
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        echo '<div class="flex-1 flex flex-col overflow-hidden">';
        $this->load->view('templates/topbar', $data);
        echo '<main class="flex-1 overflow-y-auto bg-gray-50 p-6">';
        $this->load->view('guru/dashboard', $data);
        echo '</main></div>';
        $this->load->view('templates/footer');
    }
}
