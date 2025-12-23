<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

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
        
        $this->load->model('Guru_model');
    }
    
    public function index() {
        $data['title'] = 'Profile Guru';
        
        $guru_id = $this->session->userdata('user_id');
        $data['guru'] = $this->Guru_model->get_by_id($guru_id);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('guru/profile/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function update() {
        $guru_id = $this->session->userdata('user_id');
        
        $data = [
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'alamat' => $this->input->post('alamat'),
        ];
        
        // Handle photo upload
        if ($_FILES['foto']['name']) {
            $config['upload_path'] = './assets/uploads/guru/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['file_name'] = 'guru_' . $guru_id . '_' . time();
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('foto')) {
                $upload_data = $this->upload->data();
                $data['foto'] = $upload_data['file_name'];
                
                // Delete old photo
                $old_guru = $this->Guru_model->get_by_id($guru_id);
                if ($old_guru->foto && file_exists('./assets/uploads/guru/' . $old_guru->foto)) {
                    unlink('./assets/uploads/guru/' . $old_guru->foto);
                }
            }
        }
        
        if ($this->Guru_model->update($guru_id, $data)) {
            $this->session->set_flashdata('success', 'Profile berhasil diperbarui');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui profile');
        }
        
        redirect('guru/profile');
    }
}
