<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Izin extends CI_Controller {

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
        $this->load->model('Kelas_model');
        $this->load->model('Guru_model');
        $this->load->helper('app_helper');
    }
    
    public function index() {
        $data['title'] = 'Input Izin/Sakit Siswa';
        
        $guru_id = $this->session->userdata('user_id');
        
        // Get wali kelas info
        $wali_kelas = $this->Guru_model->get_wali_kelas($guru_id);
        
        if (!$wali_kelas) {
            $this->session->set_flashdata('error', 'Anda bukan wali kelas');
            redirect('guru/dashboard');
        }
        
        $data['kelas'] = $wali_kelas;
        $data['siswa_list'] = $this->Siswa_model->get_by_kelas($wali_kelas->id);
        $data['izin_list'] = $this->db->where('kelas_id', $wali_kelas->id)
                                        ->order_by('created_at', 'DESC')
                                        ->limit(50)
                                        ->get('izin_siswa')->result();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('walikelas/izin/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function add() {
        $guru_id = $this->session->userdata('user_id');
        $wali_kelas = $this->Guru_model->get_wali_kelas($guru_id);
        
        if (!$wali_kelas) {
            echo json_encode(['success' => false, 'message' => 'Anda bukan wali kelas']);
            return;
        }
        
        $data = [
            'siswa_id' => $this->input->post('siswa_id'),
            'kelas_id' => $wali_kelas->id,
            'tanggal' => $this->input->post('tanggal'),
            'jenis' => $this->input->post('jenis'), // sakit, izin
            'keterangan' => $this->input->post('keterangan'),
            'input_by' => $guru_id,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        $this->db->insert('izin_siswa', $data);
        
        $this->session->set_flashdata('success', 'Data izin berhasil ditambahkan');
        redirect('walikelas/izin');
    }
    
    public function delete($id) {
        $this->db->delete('izin_siswa', ['id' => $id]);
        $this->session->set_flashdata('success', 'Data izin berhasil dihapus');
        redirect('walikelas/izin');
    }
}
