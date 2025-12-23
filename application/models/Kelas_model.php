<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kelas Model
 * Model for kelas table
 */
class Kelas_model extends Base_Model {
    
    protected $table = 'kelas';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get kelas aktif
     */
    public function get_aktif() {
        return $this->db->get_where($this->table, array('is_active' => 1))->result();
    }
    
    /**
     * Get all kelas with pagination
     */
    public function get_all_with_wali($search = null, $limit = null, $offset = null) {
        $this->db->select('kelas.*, guru.nama as wali_kelas_nama, COUNT(siswa.id) as jumlah_siswa');
        $this->db->from($this->table);
        $this->db->join('guru', 'guru.id = kelas.wali_kelas_id', 'left');
        $this->db->join('siswa', 'siswa.kelas_id = kelas.id AND siswa.status = "aktif"', 'left');
        
        if ($search) {
            $this->db->like('kelas.nama_kelas', $search);
        }
        
        $this->db->group_by('kelas.id');
        $this->db->order_by('kelas.tingkat, kelas.nama_kelas', 'ASC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }
    
    /**
     * Count all kelas with filters
     */
    public function count_all($search = null) {
        $this->db->from($this->table);
        
        if ($search) {
            $this->db->like('nama_kelas', $search);
        }
        
        return $this->db->count_all_results();
    }
    
    /**
     * Get kelas by tingkat
     */
    public function get_by_tingkat($tingkat) {
        return $this->db->get_where($this->table, array(
            'tingkat' => $tingkat,
            'is_active' => 1
        ))->result();
    }
    
    /**
     * Get kelas with count siswa
     */
    public function get_with_count_siswa() {
        $this->db->select('kelas.*, COUNT(siswa.id) as jumlah_siswa');
        $this->db->from($this->table);
        $this->db->join('siswa', 'siswa.kelas_id = kelas.id AND siswa.status = "Aktif"', 'left');
        $this->db->group_by('kelas.id');
        $this->db->order_by('kelas.tingkat, kelas.nama');
        return $this->db->get()->result();
    }
    
    /**
     * Get kelas with wali kelas
     */
    public function get_with_wali_kelas($tahun_ajaran_id, $semester_id) {
        $this->db->select('kelas.*, guru.nama as wali_kelas_nama, wali_kelas.id as wali_kelas_id');
        $this->db->from($this->table);
        $this->db->join('wali_kelas', 'wali_kelas.kelas_id = kelas.id AND wali_kelas.tahun_ajaran_id = '.$tahun_ajaran_id.' AND wali_kelas.semester_id = '.$semester_id, 'left');
        $this->db->join('guru', 'guru.id = wali_kelas.guru_id', 'left');
        $this->db->where('kelas.is_active', 1);
        $this->db->order_by('kelas.tingkat, kelas.nama');
        return $this->db->get()->result();
    }
}
