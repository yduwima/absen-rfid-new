<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user is BK
        if ($this->session->userdata('role') != 'bk') {
            redirect('dashboard');
        }
        
        $this->load->model('Laporan_model');
        $this->load->model('Siswa_model');
    }
    
    public function index() {
        $data['title'] = 'Detail Monitoring Siswa';
        
        $siswa_id = $this->input->get('siswa_id');
        
        if ($siswa_id) {
            $data['siswa'] = $this->Siswa_model->get_by_id($siswa_id);
            $data['detail_absensi'] = $this->Laporan_model->get_detail_absensi_siswa($siswa_id);
            $data['riwayat_pelanggaran'] = $this->db->where('siswa_id', $siswa_id)
                                                      ->order_by('created_at', 'DESC')
                                                      ->get('monitoring_bk')->result();
        } else {
            $data['siswa'] = null;
            $data['detail_absensi'] = [];
            $data['riwayat_pelanggaran'] = [];
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('bk/monitoring/index', $data);
        $this->load->view('templates/footer');
    }
}
