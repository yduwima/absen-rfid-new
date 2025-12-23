<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

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
        
        $this->load->model('Siswa_model');
        $this->load->model('Guru_model');
        $this->load->model('Kelas_model');
        $this->load->model('Mapel_model');
        $this->load->model('Tahun_ajaran_model');
        $this->load->model('Jadwal_model');
        $this->load->helper('app_helper');
    }
    
    // =====================================================
    // SISWA MANAGEMENT
    // =====================================================
    
    public function siswa() {
        $data['title'] = 'Data Siswa';
        
        // Get filters
        $search = $this->input->get('search');
        $kelas_id = $this->input->get('kelas_id');
        $per_page = $this->input->get('per_page') ?: 20;
        $page = $this->input->get('page') ?: 1;
        
        // Get data
        $offset = ($page - 1) * $per_page;
        $data['siswa'] = $this->Siswa_model->get_all_with_kelas($search, $kelas_id, $per_page, $offset);
        $data['total'] = $this->Siswa_model->count_all($search, $kelas_id);
        $data['kelas_list'] = $this->Kelas_model->get_all();
        
        // Pagination
        $data['pagination'] = [
            'total' => $data['total'],
            'per_page' => $per_page,
            'current_page' => $page,
            'total_pages' => ceil($data['total'] / $per_page)
        ];
        
        $data['search'] = $search;
        $data['kelas_id'] = $kelas_id;
        $data['per_page'] = $per_page;
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/master/siswa', $data);
        $this->load->view('templates/footer');
    }
    
    public function siswa_add() {
        if ($this->input->method() == 'post') {
            $data = [
                'nis' => $this->input->post('nis'),
                'nisn' => $this->input->post('nisn'),
                'nama' => $this->input->post('nama'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'no_hp_ortu' => $this->input->post('no_hp_ortu'),
                'nama_ortu' => $this->input->post('nama_ortu'),
                'kelas_id' => $this->input->post('kelas_id'),
                'uid_rfid' => $this->input->post('uid_rfid'),
                'foto' => 'default-avatar.png',
                'status' => 'aktif'
            ];
            
            // Handle photo upload
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './assets/uploads/siswa/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = 'siswa_' . time();
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $data['foto'] = $upload_data['file_name'];
                }
            }
            
            if ($this->Siswa_model->insert($data)) {
                $this->session->set_flashdata('success', 'Data siswa berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data siswa');
            }
            
            redirect('admin/master/siswa');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function siswa_edit($id) {
        if ($this->input->method() == 'post') {
            $data = [
                'nis' => $this->input->post('nis'),
                'nisn' => $this->input->post('nisn'),
                'nama' => $this->input->post('nama'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'no_hp_ortu' => $this->input->post('no_hp_ortu'),
                'nama_ortu' => $this->input->post('nama_ortu'),
                'kelas_id' => $this->input->post('kelas_id'),
                'uid_rfid' => $this->input->post('uid_rfid'),
                'status' => $this->input->post('status')
            ];
            
            // Handle photo upload
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './assets/uploads/siswa/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = 'siswa_' . time();
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $data['foto'] = $upload_data['file_name'];
                    
                    // Delete old photo
                    $old_data = $this->Siswa_model->get_by_id($id);
                    if ($old_data && $old_data->foto != 'default-avatar.png') {
                        @unlink('./assets/uploads/siswa/' . $old_data->foto);
                    }
                }
            }
            
            if ($this->Siswa_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data siswa berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data siswa');
            }
            
            redirect('admin/master/siswa');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function siswa_delete($id) {
        $siswa = $this->Siswa_model->get_by_id($id);
        
        if ($siswa) {
            // Delete photo
            if ($siswa->foto != 'default-avatar.png') {
                @unlink('./assets/uploads/siswa/' . $siswa->foto);
            }
            
            if ($this->Siswa_model->delete($id)) {
                $this->session->set_flashdata('success', 'Data siswa berhasil dihapus');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus data siswa');
            }
        }
        
        redirect('admin/master/siswa');
    }
    
    public function siswa_get($id) {
        $siswa = $this->Siswa_model->get_by_id($id);
        echo json_encode($siswa);
    }
    
    // =====================================================
    // GURU MANAGEMENT
    // =====================================================
    
    public function guru() {
        $data['title'] = 'Data Guru & Staff';
        
        // Get filters
        $search = $this->input->get('search');
        $per_page = $this->input->get('per_page') ?: 20;
        $page = $this->input->get('page') ?: 1;
        
        // Get data
        $offset = ($page - 1) * $per_page;
        $data['guru'] = $this->Guru_model->get_all($search, $per_page, $offset);
        $data['total'] = $this->Guru_model->count_all($search);
        
        // Pagination
        $data['pagination'] = [
            'total' => $data['total'],
            'per_page' => $per_page,
            'current_page' => $page,
            'total_pages' => ceil($data['total'] / $per_page)
        ];
        
        $data['search'] = $search;
        $data['per_page'] = $per_page;
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/master/guru', $data);
        $this->load->view('templates/footer');
    }
    
    public function guru_add() {
        if ($this->input->method() == 'post') {
            $data = [
                'nip' => $this->input->post('nip'),
                'nama' => $this->input->post('nama'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'email' => $this->input->post('email'),
                'jabatan' => $this->input->post('jabatan'),
                'uid_rfid' => $this->input->post('uid_rfid'),
                'foto' => 'default-avatar.png',
                'status' => 'aktif'
            ];
            
            // Handle photo upload
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './assets/uploads/guru/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = 'guru_' . time();
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $data['foto'] = $upload_data['file_name'];
                }
            }
            
            if ($this->Guru_model->insert($data)) {
                $this->session->set_flashdata('success', 'Data guru berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data guru');
            }
            
            redirect('admin/master/guru');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function guru_edit($id) {
        if ($this->input->method() == 'post') {
            $data = [
                'nip' => $this->input->post('nip'),
                'nama' => $this->input->post('nama'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'email' => $this->input->post('email'),
                'jabatan' => $this->input->post('jabatan'),
                'uid_rfid' => $this->input->post('uid_rfid'),
                'status' => $this->input->post('status')
            ];
            
            // Handle photo upload
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './assets/uploads/guru/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = 'guru_' . time();
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $data['foto'] = $upload_data['file_name'];
                    
                    // Delete old photo
                    $old_data = $this->Guru_model->get_by_id($id);
                    if ($old_data && $old_data->foto != 'default-avatar.png') {
                        @unlink('./assets/uploads/guru/' . $old_data->foto);
                    }
                }
            }
            
            if ($this->Guru_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data guru berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data guru');
            }
            
            redirect('admin/master/guru');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function guru_delete($id) {
        $guru = $this->Guru_model->get_by_id($id);
        
        if ($guru) {
            // Delete photo
            if ($guru->foto != 'default-avatar.png') {
                @unlink('./assets/uploads/guru/' . $guru->foto);
            }
            
            if ($this->Guru_model->delete($id)) {
                $this->session->set_flashdata('success', 'Data guru berhasil dihapus');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus data guru');
            }
        }
        
        redirect('admin/master/guru');
    }
    
    public function guru_get($id) {
        $guru = $this->Guru_model->get_by_id($id);
        echo json_encode($guru);
    }
    
    // =====================================================
    // KELAS MANAGEMENT
    // =====================================================
    
    public function kelas() {
        $data['title'] = 'Data Kelas';
        
        // Get filters
        $search = $this->input->get('search');
        $per_page = $this->input->get('per_page') ?: 20;
        $page = $this->input->get('page') ?: 1;
        
        // Get data
        $offset = ($page - 1) * $per_page;
        $data['kelas'] = $this->Kelas_model->get_all_with_wali($search, $per_page, $offset);
        $data['total'] = $this->Kelas_model->count_all($search);
        $data['guru_list'] = $this->Guru_model->get_aktif();
        $data['tahun_ajaran_list'] = $this->Tahun_ajaran_model->get_all();
        
        // Pagination
        $data['pagination'] = [
            'total' => $data['total'],
            'per_page' => $per_page,
            'current_page' => $page,
            'total_pages' => ceil($data['total'] / $per_page)
        ];
        
        $data['search'] = $search;
        $data['per_page'] = $per_page;
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/master/kelas', $data);
        $this->load->view('templates/footer');
    }
    
    public function kelas_add() {
        if ($this->input->method() == 'post') {
            $data = [
                'nama_kelas' => $this->input->post('nama_kelas'),
                'tingkat' => $this->input->post('tingkat'),
                'jurusan' => $this->input->post('jurusan'),
                'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id'),
                'wali_kelas_id' => $this->input->post('wali_kelas_id') ?: null
            ];
            
            if ($this->Kelas_model->insert($data)) {
                $this->session->set_flashdata('success', 'Data kelas berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data kelas');
            }
            
            redirect('admin/master/kelas');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function kelas_edit($id) {
        if ($this->input->method() == 'post') {
            $data = [
                'nama_kelas' => $this->input->post('nama_kelas'),
                'tingkat' => $this->input->post('tingkat'),
                'jurusan' => $this->input->post('jurusan'),
                'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id'),
                'wali_kelas_id' => $this->input->post('wali_kelas_id') ?: null
            ];
            
            if ($this->Kelas_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data kelas berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data kelas');
            }
            
            redirect('admin/master/kelas');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function kelas_delete($id) {
        if ($this->Kelas_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data kelas berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data kelas');
        }
        
        redirect('admin/master/kelas');
    }
    
    public function kelas_get($id) {
        $kelas = $this->Kelas_model->get_by_id($id);
        echo json_encode($kelas);
    }
    
    // =====================================================
    // MATA PELAJARAN MANAGEMENT
    // =====================================================
    
    public function mapel() {
        $data['title'] = 'Data Mata Pelajaran';
        
        // Get filters
        $search = $this->input->get('search');
        $per_page = $this->input->get('per_page') ?: 20;
        $page = $this->input->get('page') ?: 1;
        
        // Get data
        $offset = ($page - 1) * $per_page;
        $data['mapel'] = $this->Mapel_model->get_all($search, $per_page, $offset);
        $data['total'] = $this->Mapel_model->count_all($search);
        
        // Pagination
        $data['pagination'] = [
            'total' => $data['total'],
            'per_page' => $per_page,
            'current_page' => $page,
            'total_pages' => ceil($data['total'] / $per_page)
        ];
        
        $data['search'] = $search;
        $data['per_page'] = $per_page;
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/master/mapel', $data);
        $this->load->view('templates/footer');
    }
    
    public function mapel_add() {
        if ($this->input->method() == 'post') {
            $data = [
                'kode_mapel' => $this->input->post('kode_mapel'),
                'nama_mapel' => $this->input->post('nama_mapel'),
                'kategori' => $this->input->post('kategori'),
                'jam_pelajaran' => $this->input->post('jam_pelajaran')
            ];
            
            if ($this->Mapel_model->insert($data)) {
                $this->session->set_flashdata('success', 'Data mata pelajaran berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data mata pelajaran');
            }
            
            redirect('admin/master/mapel');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function mapel_edit($id) {
        if ($this->input->method() == 'post') {
            $data = [
                'kode_mapel' => $this->input->post('kode_mapel'),
                'nama_mapel' => $this->input->post('nama_mapel'),
                'kategori' => $this->input->post('kategori'),
                'jam_pelajaran' => $this->input->post('jam_pelajaran')
            ];
            
            if ($this->Mapel_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data mata pelajaran berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data mata pelajaran');
            }
            
            redirect('admin/master/mapel');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function mapel_delete($id) {
        if ($this->Mapel_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data mata pelajaran berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data mata pelajaran');
        }
        
        redirect('admin/master/mapel');
    }
    
    public function mapel_get($id) {
        $mapel = $this->Mapel_model->get_by_id($id);
        echo json_encode($mapel);
    }
    
    // =====================================================
    // TAHUN AJARAN MANAGEMENT
    // =====================================================
    
    public function tahun_ajaran() {
        $data['title'] = 'Data Tahun Ajaran';
        
        // Get filters
        $search = $this->input->get('search');
        $per_page = $this->input->get('per_page') ?: 20;
        $page = $this->input->get('page') ?: 1;
        
        // Get data
        $offset = ($page - 1) * $per_page;
        $data['tahun_ajaran'] = $this->Tahun_ajaran_model->get_all($search, $per_page, $offset);
        $data['total'] = $this->Tahun_ajaran_model->count_all($search);
        
        // Pagination
        $data['pagination'] = [
            'total' => $data['total'],
            'per_page' => $per_page,
            'current_page' => $page,
            'total_pages' => ceil($data['total'] / $per_page)
        ];
        
        $data['search'] = $search;
        $data['per_page'] = $per_page;
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/master/tahun_ajaran', $data);
        $this->load->view('templates/footer');
    }
    
    public function tahun_ajaran_add() {
        if ($this->input->method() == 'post') {
            $data = [
                'tahun' => $this->input->post('tahun'),
                'semester' => $this->input->post('semester'),
                'tanggal_mulai' => $this->input->post('tanggal_mulai'),
                'tanggal_selesai' => $this->input->post('tanggal_selesai'),
                'status' => $this->input->post('status') ?: 'nonaktif'
            ];
            
            // If set as active, deactivate others
            if ($data['status'] == 'aktif') {
                $this->Tahun_ajaran_model->deactivate_all();
            }
            
            if ($this->Tahun_ajaran_model->insert($data)) {
                $this->session->set_flashdata('success', 'Data tahun ajaran berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data tahun ajaran');
            }
            
            redirect('admin/master/tahun_ajaran');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function tahun_ajaran_edit($id) {
        if ($this->input->method() == 'post') {
            $data = [
                'tahun' => $this->input->post('tahun'),
                'semester' => $this->input->post('semester'),
                'tanggal_mulai' => $this->input->post('tanggal_mulai'),
                'tanggal_selesai' => $this->input->post('tanggal_selesai'),
                'status' => $this->input->post('status')
            ];
            
            // If set as active, deactivate others
            if ($data['status'] == 'aktif') {
                $this->Tahun_ajaran_model->deactivate_all();
            }
            
            if ($this->Tahun_ajaran_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data tahun ajaran berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data tahun ajaran');
            }
            
            redirect('admin/master/tahun_ajaran');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function tahun_ajaran_delete($id) {
        if ($this->Tahun_ajaran_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data tahun ajaran berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data tahun ajaran');
        }
        
        redirect('admin/master/tahun_ajaran');
    }
    
    public function tahun_ajaran_get($id) {
        $tahun_ajaran = $this->Tahun_ajaran_model->get_by_id($id);
        echo json_encode($tahun_ajaran);
    }
    
    // =====================================================
    // JADWAL PELAJARAN MANAGEMENT
    // =====================================================
    
    public function jadwal() {
        $data['title'] = 'Jadwal Pelajaran';
        
        // Get filters
        $kelas_id = $this->input->get('kelas_id');
        $hari = $this->input->get('hari');
        
        // Get data
        $data['jadwal'] = $this->Jadwal_model->get_all_with_details($kelas_id, $hari);
        $data['kelas_list'] = $this->Kelas_model->get_all();
        $data['guru_list'] = $this->Guru_model->get_all();
        $data['mapel_list'] = $this->Mapel_model->get_all();
        
        $data['kelas_id'] = $kelas_id;
        $data['hari'] = $hari;
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/master/jadwal', $data);
        $this->load->view('templates/footer');
    }
    
    public function jadwal_add() {
        if ($this->input->method() == 'post') {
            $data = [
                'kelas_id' => $this->input->post('kelas_id'),
                'mata_pelajaran_id' => $this->input->post('mata_pelajaran_id'),
                'guru_id' => $this->input->post('guru_id'),
                'hari' => $this->input->post('hari'),
                'jam_mulai' => $this->input->post('jam_mulai'),
                'jam_selesai' => $this->input->post('jam_selesai')
            ];
            
            if ($this->Jadwal_model->insert($data)) {
                $this->session->set_flashdata('success', 'Jadwal pelajaran berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan jadwal pelajaran');
            }
            
            redirect('admin/master/jadwal');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function jadwal_edit($id) {
        if ($this->input->method() == 'post') {
            $data = [
                'kelas_id' => $this->input->post('kelas_id'),
                'mata_pelajaran_id' => $this->input->post('mata_pelajaran_id'),
                'guru_id' => $this->input->post('guru_id'),
                'hari' => $this->input->post('hari'),
                'jam_mulai' => $this->input->post('jam_mulai'),
                'jam_selesai' => $this->input->post('jam_selesai')
            ];
            
            if ($this->Jadwal_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Jadwal pelajaran berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate jadwal pelajaran');
            }
            
            redirect('admin/master/jadwal');
        }
        
        echo json_encode(['error' => 'Invalid request']);
    }
    
    public function jadwal_delete($id) {
        if ($this->Jadwal_model->delete($id)) {
            $this->session->set_flashdata('success', 'Jadwal pelajaran berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus jadwal pelajaran');
        }
        
        redirect('admin/master/jadwal');
    }
    
    public function jadwal_get($id) {
        $jadwal = $this->Jadwal_model->get_by_id($id);
        echo json_encode($jadwal);
    }
}
