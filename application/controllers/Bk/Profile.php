<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user has BK role
        $role = $this->session->userdata('role');
        if ($role != 'bk') {
            redirect('dashboard');
        }
        
        $this->load->model('User_model');
        $this->load->model('Guru_model');
    }
    
    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        // Get user data
        $data['user'] = $this->User_model->get($user_id);
        
        // Get guru data if exists
        $data['guru'] = $this->Guru_model->get_by_user_id($user_id);
        
        $data['title'] = 'Profile BK';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('bk/profile', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function update() {
        $user_id = $this->session->userdata('user_id');
        
        // Validation rules
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/profile');
        }
        
        // Get guru data
        $guru = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan');
            redirect('bk/profile');
        }
        
        $data = [
            'nama_lengkap' => $this->input->post('nama'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'alamat' => $this->input->post('alamat')
        ];
        
        // Handle photo upload
        if (!empty($_FILES['foto']['name'])) {
            $config['upload_path'] = './assets/uploads/guru/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = 'guru_' . $guru->id . '_' . time();
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('foto')) {
                $upload_data = $this->upload->data();
                $data['foto'] = $upload_data['file_name'];
                
                // Delete old photo if exists
                if ($guru->foto && file_exists('./assets/uploads/guru/' . $guru->foto)) {
                    unlink('./assets/uploads/guru/' . $guru->foto);
                }
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('bk/profile');
            }
        }
        
        // Update guru data
        if ($this->Guru_model->update($guru->id, $data)) {
            // Update session nama
            $this->session->set_userdata('nama', $data['nama_lengkap']);
            
            $this->session->set_flashdata('success', 'Profile berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate profile');
        }
        
        redirect('bk/profile');
    }
    
    public function change_password() {
        $user_id = $this->session->userdata('user_id');
        
        // Validation rules
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('password_konfirmasi', 'Konfirmasi Password', 'required|matches[password_baru]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/profile');
        }
        
        // Get user data
        $user = $this->User_model->get($user_id);
        
        // Verify old password
        if (!password_verify($this->input->post('password_lama'), $user->password)) {
            $this->session->set_flashdata('error', 'Password lama tidak sesuai');
            redirect('bk/profile');
        }
        
        // Update password
        $data = [
            'password' => password_hash($this->input->post('password_baru'), PASSWORD_BCRYPT)
        ];
        
        if ($this->User_model->update($user_id, $data)) {
            $this->session->set_flashdata('success', 'Password berhasil diubah');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah password');
        }
        
        redirect('bk/profile');
    }
}
