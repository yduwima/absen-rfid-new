<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat extends CI_Controller {

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
        
        $this->load->model('Siswa_model');
        $this->load->model('Pengaturan_model');
    }
    
    public function index() {
        $data['title'] = 'Surat Panggilan BK';
        
        // Get list of letters
        $data['surat_list'] = $this->db->order_by('created_at', 'DESC')
                                         ->get('surat_bk')->result();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('bk/surat/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function create($siswa_id) {
        $data['title'] = 'Buat Surat Panggilan';
        
        $data['siswa'] = $this->Siswa_model->get_by_id($siswa_id);
        $data['sekolah'] = $this->Pengaturan_model->get_pengaturan_sekolah();
        
        // Generate nomor surat otomatis
        $bulan_romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $bulan = $bulan_romawi[date('n') - 1];
        $tahun = date('Y');
        
        $last_surat = $this->db->where('YEAR(created_at)', date('Y'))
                                ->where('MONTH(created_at)', date('m'))
                                ->order_by('id', 'DESC')
                                ->limit(1)
                                ->get('surat_bk')->row();
        
        $nomor_urut = $last_surat ? ((int)substr($last_surat->nomor_surat, 0, 3)) + 1 : 1;
        $data['nomor_surat'] = sprintf('%03d/BK/%s/%d', $nomor_urut, $bulan, $tahun);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('bk/surat/create', $data);
        $this->load->view('templates/footer');
    }
    
    public function save() {
        $data = [
            'siswa_id' => $this->input->post('siswa_id'),
            'nomor_surat' => $this->input->post('nomor_surat'),
            'tanggal_surat' => $this->input->post('tanggal_surat'),
            'hari' => $this->input->post('hari'),
            'tanggal_panggilan' => $this->input->post('tanggal_panggilan'),
            'waktu_panggilan' => $this->input->post('waktu_panggilan'),
            'perihal' => $this->input->post('perihal'),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        $this->db->insert('surat_bk', $data);
        $surat_id = $this->db->insert_id();
        
        $this->session->set_flashdata('success', 'Surat panggilan berhasil dibuat');
        redirect('bk/surat/view/' . $surat_id);
    }
    
    public function view($id) {
        $data['title'] = 'Preview Surat Panggilan';
        
        $data['surat'] = $this->db->where('id', $id)->get('surat_bk')->row();
        $data['siswa'] = $this->Siswa_model->get_by_id($data['surat']->siswa_id);
        $data['sekolah'] = $this->Pengaturan_model->get_pengaturan_sekolah();
        
        $this->load->view('bk/surat/view', $data);
    }
    
    public function pdf($id) {
        // Placeholder for PDF generation
        $this->session->set_flashdata('info', 'Fitur cetak PDF sedang dalam pengembangan');
        redirect('bk/surat/view/' . $id);
    }
}
