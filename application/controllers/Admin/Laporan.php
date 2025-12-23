<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user is admin
        if ($this->session->userdata('role') != 'admin') {
            redirect('dashboard');
        }
        
        $this->load->model('Laporan_model');
        $this->load->model('Siswa_model');
        $this->load->model('Guru_model');
        $this->load->model('Kelas_model');
        $this->load->model('Pengaturan_model');
        $this->load->helper('app_helper');
    }
    
    // Laporan Absensi Siswa
    public function absensi_siswa() {
        $data['title'] = 'Laporan Absensi Siswa';
        $data['sekolah'] = $this->Pengaturan_model->get_pengaturan_sekolah();
        
        // Get filters
        $bulan = $this->input->get('bulan') ?: date('Y-m');
        $kelas_id = $this->input->get('kelas_id');
        
        $data['bulan'] = $bulan;
        $data['kelas_id'] = $kelas_id;
        $data['kelas_list'] = $this->Kelas_model->get_all();
        
        // Get data if filters applied
        if ($kelas_id) {
            $data['laporan'] = $this->Laporan_model->get_absensi_siswa($bulan, $kelas_id);
        } else {
            $data['laporan'] = [];
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/laporan/absensi_siswa', $data);
        $this->load->view('templates/footer');
    }
    
    // Laporan Absensi Guru
    public function absensi_guru() {
        $data['title'] = 'Laporan Absensi Guru';
        $data['sekolah'] = $this->Pengaturan_model->get_pengaturan_sekolah();
        
        // Get filters
        $bulan = $this->input->get('bulan') ?: date('Y-m');
        
        $data['bulan'] = $bulan;
        $data['laporan'] = $this->Laporan_model->get_absensi_guru($bulan);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/laporan/absensi_guru', $data);
        $this->load->view('templates/footer');
    }
    
    // Rekap Laporan Siswa
    public function rekap_siswa() {
        $data['title'] = 'Rekap Laporan Siswa';
        $data['sekolah'] = $this->Pengaturan_model->get_pengaturan_sekolah();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/laporan/rekap_siswa', $data);
        $this->load->view('templates/footer');
    }
    
    // Export PDF - Placeholder
    public function export_pdf($type) {
        // Placeholder for PDF export functionality
        $this->session->set_flashdata('info', 'Fitur export PDF sedang dalam pengembangan');
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    // Export Excel - Placeholder
    public function export_excel($type) {
        // Placeholder for Excel export functionality
        $this->session->set_flashdata('info', 'Fitur export Excel sedang dalam pengembangan');
        redirect($_SERVER['HTTP_REFERER']);
    }
}
