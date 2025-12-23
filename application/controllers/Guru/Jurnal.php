<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Jurnal Guru Controller
 */
class Jurnal extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        is_logged_in();
        check_role(array('guru', 'walikelas', 'piket'));
        
        $this->load->model('Jadwal_model');
        $this->load->model('Jurnal_model');
        $this->load->model('Siswa_model');
        $this->load->model('Kelas_model');
    }
    
    /**
     * List all journals
     */
    public function index() {
        $data['title'] = 'Jurnal Mengajar';
        $data['page_title'] = 'Jurnal Mengajar';
        
        $guru_id = $this->session->userdata('guru_id');
        
        // Get jurnal with pagination
        $config['base_url'] = site_url('guru/jurnal/index');
        $config['total_rows'] = $this->db->where('guru_id', $guru_id)->count_all_results('jurnal_guru');
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        // Get jurnal
        $this->db->select('jurnal_guru.*, mata_pelajaran.nama as nama_mapel, kelas.nama as nama_kelas');
        $this->db->from('jurnal_guru');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_guru.mata_pelajaran_id');
        $this->db->join('kelas', 'kelas.id = jurnal_guru.kelas_id');
        $this->db->where('jurnal_guru.guru_id', $guru_id);
        $this->db->order_by('jurnal_guru.tanggal', 'DESC');
        $this->db->limit($config['per_page'], $page);
        $data['jurnal_list'] = $this->db->get()->result();
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        echo '<div class="flex-1 flex flex-col overflow-hidden">';
        $this->load->view('templates/topbar', $data);
        echo '<main class="flex-1 overflow-y-auto bg-gray-50 p-6">';
        $this->load->view('guru/jurnal/index', $data);
        echo '</main></div>';
        $this->load->view('templates/footer');
    }
    
    /**
     * Add journal
     */
    public function add($jadwal_id = null) {
        $guru_id = $this->session->userdata('guru_id');
        
        if (!$jadwal_id) {
            $this->session->set_flashdata('error', 'Jadwal tidak ditemukan');
            redirect('guru/dashboard');
        }
        
        // Get jadwal
        $this->db->select('jadwal_pelajaran.*, mata_pelajaran.nama as nama_mapel, kelas.nama as nama_kelas');
        $this->db->from('jadwal_pelajaran');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id');
        $this->db->where('jadwal_pelajaran.id', $jadwal_id);
        $this->db->where('jadwal_pelajaran.guru_id', $guru_id);
        $jadwal = $this->db->get()->row();
        
        if (!$jadwal) {
            $this->session->set_flashdata('error', 'Jadwal tidak ditemukan');
            redirect('guru/dashboard');
        }
        
        if ($this->input->post()) {
            // Process form
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
            $this->form_validation->set_rules('materi', 'Materi', 'required');
            $this->form_validation->set_rules('kegiatan', 'Kegiatan', 'required');
            
            if ($this->form_validation->run()) {
                // Save jurnal
                $jurnal_data = array(
                    'jadwal_id' => $jadwal_id,
                    'guru_id' => $guru_id,
                    'tanggal' => $this->input->post('tanggal'),
                    'materi' => $this->input->post('materi'),
                    'kegiatan' => $this->input->post('kegiatan'),
                    'kelas_id' => $jadwal->kelas_id,
                    'mata_pelajaran_id' => $jadwal->mata_pelajaran_id,
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai
                );
                
                $jurnal_id = $this->Jurnal_model->insert($jurnal_data);
                
                // Save absensi mapel
                $absensi_data = $this->input->post('absensi');
                if ($absensi_data) {
                    foreach ($absensi_data as $siswa_id => $status) {
                        $this->Jurnal_model->save_absensi_mapel($jurnal_id, $siswa_id, $status);
                    }
                }
                
                $this->session->set_flashdata('success', 'Jurnal berhasil disimpan');
                redirect('guru/dashboard');
            }
        }
        
        // Get siswa in kelas
        $data['siswa_list'] = $this->Siswa_model->get_by_kelas($jadwal->kelas_id);
        $data['jadwal'] = $jadwal;
        $data['title'] = 'Tambah Jurnal';
        $data['page_title'] = 'Tambah Jurnal Mengajar';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        echo '<div class="flex-1 flex flex-col overflow-hidden">';
        $this->load->view('templates/topbar', $data);
        echo '<main class="flex-1 overflow-y-auto bg-gray-50 p-6">';
        $this->load->view('guru/jurnal/add', $data);
        echo '</main></div>';
        $this->load->view('templates/footer');
    }
}
