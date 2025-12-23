<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user is wali kelas
        if ($this->session->userdata('role') != 'walikelas') {
            redirect('dashboard');
        }
        
        $this->load->model('Siswa_model');
        $this->load->model('Absensi_model');
        $this->load->model('Guru_model');
    }
    
    public function index() {
        $data['title'] = 'Monitoring Kelas';
        
        $guru_id = $this->session->userdata('user_id');
        
        // Get wali kelas info
        $wali_kelas = $this->Guru_model->get_wali_kelas($guru_id);
        
        if (!$wali_kelas) {
            $this->session->set_flashdata('error', 'Anda bukan wali kelas');
            redirect('guru/dashboard');
        }
        
        $data['kelas'] = $wali_kelas;
        $data['siswa_list'] = $this->Siswa_model->get_by_kelas($wali_kelas->id);
        
        // Get today's attendance for this class
        $data['absensi_hari_ini'] = $this->Absensi_model->get_today_by_kelas($wali_kelas->id);
        
        // Get monthly stats
        $data['stats_bulan_ini'] = $this->Absensi_model->get_monthly_stats_by_kelas($wali_kelas->id);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('walikelas/monitoring/index', $data);
        $this->load->view('templates/footer');
    }
}
