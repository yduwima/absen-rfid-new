<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mata Pelajaran Model
 * Model for mata_pelajaran table
 */
class Mapel_model extends Base_Model {
    
    protected $table = 'mata_pelajaran';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get mapel aktif
     */
    public function get_aktif() {
        return $this->db->get_where($this->table, array('is_active' => 1))->result();
    }
    
    /**
     * Get all mapel with pagination
     */
    public function get_all($search = null, $limit = null, $offset = null) {
        $this->db->from($this->table);
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_mapel', $search);
            $this->db->or_like('kode_mapel', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('nama_mapel', 'ASC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }
    
    /**
     * Count all mapel with filters
     */
    public function count_all($search = null) {
        $this->db->from($this->table);
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_mapel', $search);
            $this->db->or_like('kode_mapel', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }
    
    /**
     * Check if kode exists
     */
    public function kode_exists($kode, $exclude_id = null) {
        $this->db->where('kode', $kode);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
}
