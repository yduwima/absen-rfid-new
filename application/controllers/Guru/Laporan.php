<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user is guru, walikelas, or piket
        if (!in_array($this->session->userdata('role'), ['guru', 'walikelas', 'piket'])) {
            redirect('dashboard');
        }
        
        $this->load->model('Laporan_model');
        $this->load->model('Guru_model');
    }
    
    public function index() {
        $data['title'] = 'Laporan Kinerja';
        
        $guru_id = $this->session->userdata('user_id');
        
        // Get teacher performance stats
        $data['stats'] = $this->Laporan_model->get_guru_stats($guru_id);
        $data['jurnal_bulan_ini'] = $this->Laporan_model->get_jurnal_bulan_ini($guru_id);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('guru/laporan/index', $data);
        $this->load->view('templates/footer');
    }
}
