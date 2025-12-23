<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Izin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user is piket
        if ($this->session->userdata('role') != 'piket') {
            redirect('dashboard');
        }
        
        $this->load->model('Siswa_model');
        $this->load->model('Kelas_model');
    }
    
    public function index() {
        $data['title'] = 'Izin Siswa Selama KBM';
        
        $data['kelas_list'] = $this->Kelas_model->get_all();
        $data['izin_list'] = $this->db->where('DATE(created_at)', date('Y-m-d'))
                                        ->where('jenis', 'izin_keluar_kbm')
                                        ->order_by('created_at', 'DESC')
                                        ->get('izin_siswa')->result();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('piket/izin/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function add() {
        $guru_id = $this->session->userdata('user_id');
        
        $data = [
            'siswa_id' => $this->input->post('siswa_id'),
            'kelas_id' => $this->input->post('kelas_id'),
            'tanggal' => date('Y-m-d'),
            'jenis' => 'izin_keluar_kbm',
            'jam_keluar' => $this->input->post('jam_keluar'),
            'jam_kembali' => $this->input->post('jam_kembali'),
            'keterangan' => $this->input->post('keterangan'),
            'input_by' => $guru_id,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        $this->db->insert('izin_siswa', $data);
        
        $this->session->set_flashdata('success', 'Izin siswa berhasil dicatat');
        redirect('piket/izin');
    }
    
    public function rekap() {
        $data['title'] = 'Rekap Izin Siswa';
        
        $bulan = $this->input->get('bulan') ?: date('Y-m');
        
        $data['bulan'] = $bulan;
        $data['izin_list'] = $this->db->where('DATE_FORMAT(tanggal, "%Y-%m")', $bulan)
                                        ->where('jenis', 'izin_keluar_kbm')
                                        ->order_by('tanggal', 'DESC')
                                        ->get('izin_siswa')->result();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('piket/izin/rekap', $data);
        $this->load->view('templates/footer');
    }
}
