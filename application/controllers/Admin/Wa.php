<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wa extends CI_Controller {

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
        
        $this->load->model('Wa_model');
        $this->load->model('Kelas_model');
    }
    
    public function pengaturan() {
        $data['title'] = 'Pengaturan WhatsApp Notifikasi';
        
        // Get current settings
        $data['wa_setting'] = $this->Wa_model->get_setting();
        $data['wa_templates'] = $this->Wa_model->get_templates();
        $data['kelas_list'] = $this->Kelas_model->get_all();
        $data['notif_kelas'] = $this->Wa_model->get_notif_kelas();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/wa/pengaturan', $data);
        $this->load->view('templates/footer');
    }
    
    public function save_setting() {
        $data = [
            'api_url' => $this->input->post('api_url'),
            'api_key' => $this->input->post('api_key'),
            'sender' => $this->input->post('sender'),
            'link_url' => $this->input->post('link_url'),
        ];
        
        if ($this->Wa_model->update_setting($data)) {
            $this->session->set_flashdata('success', 'Pengaturan WhatsApp berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan pengaturan');
        }
        
        redirect('admin/wa/pengaturan');
    }
    
    public function save_template() {
        $id = $this->input->post('id');
        $data = [
            'pesan' => $this->input->post('pesan'),
        ];
        
        if ($this->Wa_model->update_template($id, $data)) {
            $this->session->set_flashdata('success', 'Template berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan template');
        }
        
        redirect('admin/wa/pengaturan');
    }
    
    public function toggle_kelas() {
        $kelas_id = $this->input->post('kelas_id');
        $enabled = $this->input->post('enabled');
        
        if ($enabled == '1') {
            $this->Wa_model->enable_kelas($kelas_id);
        } else {
            $this->Wa_model->disable_kelas($kelas_id);
        }
        
        echo json_encode(['success' => true]);
    }
}
