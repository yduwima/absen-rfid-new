<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Siswa Model
 * Model for siswa table
 */
class Siswa_model extends Base_Model {
    
    protected $table = 'siswa';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get siswa with kelas
     */
    public function get_with_kelas($siswa_id = null) {
        $this->db->select('siswa.*, kelas.nama as nama_kelas, kelas.tingkat, kelas.jurusan');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        
        if ($siswa_id) {
            $this->db->where('siswa.id', $siswa_id);
            return $this->db->get()->row();
        }
        
        return $this->db->get()->result();
    }
    
    /**
     * Get siswa by UID RFID
     */
    public function get_by_uid($uid_rfid) {
        $this->db->select('siswa.*, kelas.nama as nama_kelas');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.uid_rfid', $uid_rfid);
        $this->db->where('siswa.status', 'Aktif');
        return $this->db->get()->row();
    }
    
    /**
     * Get siswa by kelas
     */
    public function get_by_kelas($kelas_id, $status = 'Aktif') {
        $this->db->select('siswa.*');
        $this->db->from($this->table);
        $this->db->where('siswa.kelas_id', $kelas_id);
        $this->db->where('siswa.status', $status);
        $this->db->order_by('siswa.nama', 'ASC');
        return $this->db->get()->result();
    }
    
    /**
     * Search siswa
     */
    public function search($keyword, $limit = null, $offset = null) {
        $this->db->select('siswa.*, kelas.nama as nama_kelas');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->group_start();
        $this->db->like('siswa.nama', $keyword);
        $this->db->or_like('siswa.nis', $keyword);
        $this->db->or_like('siswa.nisn', $keyword);
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
        $this->db->or_like('nis', $keyword);
        $this->db->or_like('nisn', $keyword);
        $this->db->group_end();
        return $this->db->count_all_results();
    }
    
    /**
     * Check if NIS exists
     */
    public function nis_exists($nis, $exclude_id = null) {
        $this->db->where('nis', $nis);
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
     * Get all siswa with kelas and pagination
     */
    public function get_all_with_kelas($search = null, $kelas_id = null, $limit = null, $offset = null) {
        $this->db->select('siswa.*, kelas.nama_kelas as nama_kelas');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('siswa.nama', $search);
            $this->db->or_like('siswa.nis', $search);
            $this->db->or_like('siswa.nisn', $search);
            $this->db->group_end();
        }
        
        if ($kelas_id) {
            $this->db->where('siswa.kelas_id', $kelas_id);
        }
        
        $this->db->order_by('siswa.nama', 'ASC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }
    
    /**
     * Count all siswa with filters
     */
    public function count_all($search = null, $kelas_id = null) {
        $this->db->from($this->table);
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama', $search);
            $this->db->or_like('nis', $search);
            $this->db->or_like('nisn', $search);
            $this->db->group_end();
        }
        
        if ($kelas_id) {
            $this->db->where('kelas_id', $kelas_id);
        }
        
        return $this->db->count_all_results();
    }
    
    /**
     * Naik kelas
     */
    public function naik_kelas($kelas_lama_id, $kelas_baru_id) {
        $this->db->where('kelas_id', $kelas_lama_id);
        $this->db->where('status', 'Aktif');
        return $this->db->update($this->table, array('kelas_id' => $kelas_baru_id));
    }
    
    /**
     * Lulus kan siswa
     */
    public function luluskan($kelas_id) {
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('status', 'Aktif');
        return $this->db->update($this->table, array('status' => 'Lulus'));
    }
    
    /**
     * Get siswa by NIS
     */
    public function get_by_nis($nis) {
        return $this->db->get_where($this->table, ['nis' => $nis])->row();
    }
    
    /**
     * Get siswa by NISN
     */
    public function get_by_nisn($nisn) {
        return $this->db->get_where($this->table, ['nisn' => $nisn])->row();
    }
}
