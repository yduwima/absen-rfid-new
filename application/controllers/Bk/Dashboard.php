<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * BK Dashboard Controller
 */
class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        is_logged_in();
        check_role(array('bk'));
        
        $this->load->model('Laporan_model');
        $this->load->model('Siswa_model');
    }
    
    /**
     * BK Dashboard
     */
    public function index() {
        $data['title'] = 'Dashboard BK';
        $data['page_title'] = 'Dashboard Bimbingan Konseling';
        
        $bulan = date('m');
        $tahun = date('Y');
        
        // Get siswa untuk monitoring (alpha >= 3 atau terlambat >= 5)
        $data['siswa_monitoring'] = $this->Laporan_model->get_siswa_for_bk_monitoring($bulan, $tahun);
        
        // Count statistik
        $data['total_alpha'] = 0;
        $data['total_terlambat'] = 0;
        $data['total_siswa_bermasalah'] = count($data['siswa_monitoring']);
        
        foreach ($data['siswa_monitoring'] as $siswa) {
            if ($siswa->jumlah_alpha >= 3) {
                $data['total_alpha']++;
            }
            if ($siswa->jumlah_terlambat >= 5) {
                $data['total_terlambat']++;
            }
        }
        
        // Get total siswa
        $data['total_siswa'] = $this->Siswa_model->count_by(array('status' => 'Aktif'));
        
        // Get surat yang sudah dibuat bulan ini
        $this->db->select('COUNT(*) as total');
        $this->db->from('surat_bk');
        $this->db->where('MONTH(tanggal_surat)', $bulan);
        $this->db->where('YEAR(tanggal_surat)', $tahun);
        $surat_count = $this->db->get()->row();
        $data['total_surat_bulan_ini'] = $surat_count ? $surat_count->total : 0;
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        echo '<div class="flex-1 flex flex-col overflow-hidden">';
        $this->load->view('templates/topbar', $data);
        echo '<main class="flex-1 overflow-y-auto bg-gray-50 p-6">';
        $this->load->view('bk/dashboard', $data);
        echo '</main></div>';
        $this->load->view('templates/footer');
    }
}
