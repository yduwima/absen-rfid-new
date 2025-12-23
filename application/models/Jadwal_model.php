<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Jadwal Model
 * Model for jadwal_pelajaran table
 */
class Jadwal_model extends Base_Model {
    
    protected $table = 'jadwal_pelajaran';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get jadwal by kelas
     */
    public function get_by_kelas($kelas_id, $tahun_ajaran_id = null, $semester_id = null) {
        $this->db->select('jadwal_pelajaran.*, 
            mata_pelajaran.nama as nama_mapel,
            guru.nama as nama_guru,
            kelas.nama as nama_kelas');
        $this->db->from($this->table);
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id');
        $this->db->join('guru', 'guru.id = jadwal_pelajaran.guru_id');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id');
        $this->db->where('jadwal_pelajaran.kelas_id', $kelas_id);
        
        if ($tahun_ajaran_id) {
            $this->db->where('jadwal_pelajaran.tahun_ajaran_id', $tahun_ajaran_id);
        }
        
        if ($semester_id) {
            $this->db->where('jadwal_pelajaran.semester_id', $semester_id);
        }
        
        $this->db->order_by('FIELD(jadwal_pelajaran.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")');
        $this->db->order_by('jadwal_pelajaran.jam_mulai');
        
        return $this->db->get()->result();
    }
    
    /**
     * Get jadwal by guru
     */
    public function get_by_guru($guru_id, $tahun_ajaran_id = null, $semester_id = null, $hari = null) {
        $this->db->select('jadwal_pelajaran.*, 
            mata_pelajaran.nama as nama_mapel,
            kelas.nama as nama_kelas,
            kelas.tingkat');
        $this->db->from($this->table);
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id');
        $this->db->where('jadwal_pelajaran.guru_id', $guru_id);
        
        if ($tahun_ajaran_id) {
            $this->db->where('jadwal_pelajaran.tahun_ajaran_id', $tahun_ajaran_id);
        }
        
        if ($semester_id) {
            $this->db->where('jadwal_pelajaran.semester_id', $semester_id);
        }
        
        if ($hari) {
            $this->db->where('jadwal_pelajaran.hari', $hari);
        }
        
        $this->db->order_by('FIELD(jadwal_pelajaran.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")');
        $this->db->order_by('jadwal_pelajaran.jam_mulai');
        
        return $this->db->get()->result();
    }
    
    /**
     * Get jadwal hari ini untuk guru
     */
    public function get_hari_ini_guru($guru_id, $hari = null) {
        if (!$hari) $hari = get_hari();
        
        // Get active tahun ajaran and semester
        $this->load->model('Pengaturan_model');
        $tahun_ajaran = $this->Pengaturan_model->get_tahun_ajaran_aktif();
        $semester = $this->Pengaturan_model->get_semester_aktif();
        
        if (!$tahun_ajaran || !$semester) {
            return array();
        }
        
        return $this->get_by_guru($guru_id, $tahun_ajaran->id, $semester->id, $hari);
    }
    
    /**
     * Get all jadwal with details
     */
    public function get_all_with_details($kelas_id = null, $hari = null) {
        $this->db->select('jadwal_pelajaran.*, 
            mata_pelajaran.nama_mapel,
            guru.nama as nama_guru,
            kelas.nama_kelas');
        $this->db->from($this->table);
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id');
        $this->db->join('guru', 'guru.id = jadwal_pelajaran.guru_id');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id');
        
        if ($kelas_id) {
            $this->db->where('jadwal_pelajaran.kelas_id', $kelas_id);
        }
        
        if ($hari) {
            $this->db->where('jadwal_pelajaran.hari', $hari);
        }
        
        $this->db->order_by('FIELD(jadwal_pelajaran.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")');
        $this->db->order_by('jadwal_pelajaran.jam_mulai');
        
        return $this->db->get()->result();
    }
}
