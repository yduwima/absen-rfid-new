<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends CI_Controller {

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
        
        $this->load->model('Pengaturan_model');
        $this->load->helper('app_helper');
    }
    
    // =====================================================
    // PENGATURAN SEKOLAH
    // =====================================================
    
    public function sekolah() {
        $data['title'] = 'Pengaturan Sekolah';
        $data['pengaturan'] = $this->Pengaturan_model->get_pengaturan_sekolah();
        
        if ($this->input->method() == 'post') {
            $update_data = [
                'nama_sekolah' => $this->input->post('nama_sekolah'),
                'alamat_sekolah' => $this->input->post('alamat_sekolah'),
                'nama_kepala_sekolah' => $this->input->post('nama_kepala_sekolah'),
                'nip_kepala_sekolah' => $this->input->post('nip_kepala_sekolah'),
                'telepon_sekolah' => $this->input->post('telepon_sekolah'),
                'email_sekolah' => $this->input->post('email_sekolah')
            ];
            
            // Handle logo upload
            if (!empty($_FILES['logo_sekolah']['name'])) {
                $config['upload_path'] = './assets/img/logo/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = 'logo_sekolah_' . time();
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('logo_sekolah')) {
                    $upload_data = $this->upload->data();
                    $update_data['logo_sekolah'] = $upload_data['file_name'];
                    
                    // Delete old logo
                    if ($data['pengaturan'] && $data['pengaturan']->logo_sekolah != 'logo.png') {
                        @unlink('./assets/img/logo/' . $data['pengaturan']->logo_sekolah);
                    }
                }
            }
            
            if ($this->Pengaturan_model->update_pengaturan_sekolah($update_data)) {
                $this->session->set_flashdata('success', 'Pengaturan sekolah berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate pengaturan sekolah');
            }
            
            redirect('admin/pengaturan/sekolah');
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/pengaturan/sekolah', $data);
        $this->load->view('templates/footer');
    }
    
    // =====================================================
    // PENGATURAN JAM KERJA
    // =====================================================
    
    public function jam_kerja() {
        $data['title'] = 'Pengaturan Jam Kerja';
        $data['jam_kerja'] = $this->Pengaturan_model->get_jam_kerja();
        $data['hari_kerja'] = $this->Pengaturan_model->get_hari_kerja();
        $data['hari_libur'] = $this->Pengaturan_model->get_hari_libur();
        
        if ($this->input->method() == 'post') {
            $action = $this->input->post('action');
            
            if ($action == 'update_jam') {
                $update_data = [
                    'jam_masuk' => $this->input->post('jam_masuk'),
                    'jam_pulang' => $this->input->post('jam_pulang'),
                    'toleransi_keterlambatan' => $this->input->post('toleransi_keterlambatan')
                ];
                
                if ($this->Pengaturan_model->update_jam_kerja($update_data)) {
                    $this->session->set_flashdata('success', 'Jam kerja berhasil diupdate');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate jam kerja');
                }
            }
            else if ($action == 'update_hari') {
                $hari_aktif = $this->input->post('hari_aktif') ?: [];
                
                if ($this->Pengaturan_model->update_hari_kerja($hari_aktif)) {
                    $this->session->set_flashdata('success', 'Hari kerja berhasil diupdate');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate hari kerja');
                }
            }
            else if ($action == 'tambah_libur') {
                $libur_data = [
                    'tanggal' => $this->input->post('tanggal'),
                    'keterangan' => $this->input->post('keterangan')
                ];
                
                if ($this->Pengaturan_model->tambah_hari_libur($libur_data)) {
                    $this->session->set_flashdata('success', 'Hari libur berhasil ditambahkan');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan hari libur');
                }
            }
            
            redirect('admin/pengaturan/jam_kerja');
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/pengaturan/jam_kerja', $data);
        $this->load->view('templates/footer');
    }
    
    public function hapus_libur($id) {
        if ($this->Pengaturan_model->hapus_hari_libur($id)) {
            $this->session->set_flashdata('success', 'Hari libur berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus hari libur');
        }
        
        redirect('admin/pengaturan/jam_kerja');
    }
}
