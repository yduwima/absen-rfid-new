<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Guru Model
 * Model for guru table
 */
class Guru_model extends Base_Model {
    
    protected $table = 'guru';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get guru by UID RFID
     */
    public function get_by_uid($uid_rfid) {
        return $this->db->get_where($this->table, array(
            'uid_rfid' => $uid_rfid,
            'status' => 'Aktif'
        ))->row();
    }
    
    /**
     * Search guru
     */
    public function search($keyword, $limit = null, $offset = null) {
        $this->db->from($this->table);
        $this->db->group_start();
        $this->db->like('nama', $keyword);
        $this->db->or_like('nip', $keyword);
        $this->db->or_like('nik', $keyword);
        $this->db->group_end();
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }
    
    /**
     * Count search results
     */
    public function count_search($keyword) {
        $this->db->from($this->table);
        $this->db->group_start();
        $this->db->like('nama', $keyword);
        $this->db->or_like('nip', $keyword);
        $this->db->or_like('nik', $keyword);
        $this->db->group_end();
        return $this->db->count_all_results();
    }
    
    /**
     * Check if NIP exists
     */
    public function nip_exists($nip, $exclude_id = null) {
        $this->db->where('nip', $nip);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
    
    /**
     * Check if UID exists
     */
    public function uid_exists($uid_rfid, $exclude_id = null) {
        $this->db->where('uid_rfid', $uid_rfid);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
    
    /**
     * Get all guru with pagination
     */
    public function get_all($search = null, $limit = null, $offset = null) {
        $this->db->from($this->table);
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama', $search);
            $this->db->or_like('nip', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('nama', 'ASC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }
    
    /**
     * Count all guru with filters
     */
    public function count_all($search = null) {
        $this->db->from($this->table);
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama', $search);
            $this->db->or_like('nip', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }
    
    /**
     * Get guru aktif
     */
    public function get_aktif() {
        return $this->db->get_where($this->table, array('status' => 'Aktif'))->result();
    }
    
    /**
     * Get wali kelas by guru_id
     */
    public function get_wali_kelas($guru_id, $tahun_ajaran_id = null, $semester_id = null) {
        $this->db->select('wali_kelas.*, kelas.nama as nama_kelas, kelas.tingkat, kelas.jurusan');
        $this->db->from('wali_kelas');
        $this->db->join('kelas', 'kelas.id = wali_kelas.kelas_id');
        $this->db->where('wali_kelas.guru_id', $guru_id);
        
        if ($tahun_ajaran_id) {
            $this->db->where('wali_kelas.tahun_ajaran_id', $tahun_ajaran_id);
        }
        
        if ($semester_id) {
            $this->db->where('wali_kelas.semester_id', $semester_id);
        }
        
        return $this->db->get()->row();
    }
    
    /**
     * Check if guru is wali kelas
     */
    public function is_wali_kelas($guru_id, $tahun_ajaran_id = null, $semester_id = null) {
        $this->db->from('wali_kelas');
        $this->db->where('guru_id', $guru_id);
        
        if ($tahun_ajaran_id) {
            $this->db->where('tahun_ajaran_id', $tahun_ajaran_id);
        }
        
        if ($semester_id) {
            $this->db->where('semester_id', $semester_id);
        }
        
        return $this->db->count_all_results() > 0;
    }
    
    /**
     * Get piket schedule for guru
     */
    public function get_piket_schedule($guru_id) {
        $this->db->select('guru_piket.*, guru.nama as nama_guru');
        $this->db->from('guru_piket');
        $this->db->join('guru', 'guru.id = guru_piket.guru_id');
        $this->db->where('guru_piket.guru_id', $guru_id);
        $this->db->where('guru_piket.is_active', 1);
        return $this->db->get()->result();
    }
    
    /**
     * Get guru by NIP
     */
    public function get_by_nip($nip) {
        return $this->db->get_where($this->table, ['nip' => $nip])->row();
    }
}
