<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Dashboard Controller
 */
class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        is_logged_in();
        check_role(array('admin'));
        
        $this->load->model('Siswa_model');
        $this->load->model('Guru_model');
        $this->load->model('Absensi_model');
    }
    
    /**
     * Admin Dashboard
     */
    public function index() {
        $data['title'] = 'Dashboard Admin';
        $data['page_title'] = 'Dashboard';
        
        // Get statistics
        $data['total_siswa'] = $this->Siswa_model->count_by(array('status' => 'Aktif'));
        $data['total_guru'] = $this->Guru_model->count_by(array('status' => 'Aktif'));
        
        // Absensi hari ini
        $today = date('Y-m-d');
        $data['absen_siswa_hari_ini'] = $this->db->query("
            SELECT COUNT(*) as total 
            FROM absensi_harian 
            WHERE tanggal = '$today' AND user_type = 'siswa'
        ")->row()->total;
        
        $data['absen_guru_hari_ini'] = $this->db->query("
            SELECT COUNT(*) as total 
            FROM absensi_harian 
            WHERE tanggal = '$today' AND user_type = 'guru'
        ")->row()->total;
        
        // Siswa belum absen
        $data['siswa_belum_absen'] = $data['total_siswa'] - $data['absen_siswa_hari_ini'];
        
        // Siswa terlambat hari ini
        $data['siswa_terlambat'] = $this->db->query("
            SELECT COUNT(*) as total 
            FROM absensi_harian 
            WHERE tanggal = '$today' 
            AND user_type = 'siswa' 
            AND status_masuk = 'Terlambat'
        ")->row()->total;
        
        // Get absensi terbaru
        $data['absensi_terbaru'] = $this->Absensi_model->get_today($today);
        
        // Chart data - Absensi 7 hari terakhir
        $chart_labels = array();
        $chart_siswa = array();
        $chart_guru = array();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chart_labels[] = date('d M', strtotime($date));
            
            $siswa_count = $this->db->query("
                SELECT COUNT(*) as total 
                FROM absensi_harian 
                WHERE tanggal = '$date' AND user_type = 'siswa'
            ")->row()->total;
            
            $guru_count = $this->db->query("
                SELECT COUNT(*) as total 
                FROM absensi_harian 
                WHERE tanggal = '$date' AND user_type = 'guru'
            ")->row()->total;
            
            $chart_siswa[] = $siswa_count;
            $chart_guru[] = $guru_count;
        }
        
        $data['chart_labels'] = json_encode($chart_labels);
        $data['chart_siswa'] = json_encode($chart_siswa);
        $data['chart_guru'] = json_encode($chart_guru);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        echo '<div class="flex-1 flex flex-col overflow-hidden">';
        $this->load->view('templates/topbar', $data);
        echo '<main class="flex-1 overflow-y-auto bg-gray-50 p-6">';
        $this->load->view('admin/dashboard/index', $data);
        echo '</main></div>';
        $this->load->view('templates/footer');
    }
}
