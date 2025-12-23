<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tahun Ajaran Model
 */
class Tahun_ajaran_model extends Base_Model {
    
    protected $table = 'tahun_ajaran';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get active tahun ajaran
     */
    public function get_active() {
        return $this->db->get_where($this->table, array('status' => 'aktif'))->row();
    }
    
    /**
     * Get all tahun ajaran with pagination
     */
    public function get_all($search = null, $limit = null, $offset = null) {
        $this->db->order_by('tahun', 'DESC');
        
        if ($search) {
            $this->db->like('tahun', $search);
        }
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Count all tahun ajaran
     */
    public function count_all($search = null) {
        if ($search) {
            $this->db->like('tahun', $search);
        }
        
        return $this->db->count_all_results($this->table);
    }
    
    /**
     * Deactivate all tahun ajaran
     */
    public function deactivate_all() {
        $this->db->update($this->table, array('status' => 'nonaktif'));
        return true;
    }
}
