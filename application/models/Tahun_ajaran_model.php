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
        return $this->db->get_where($this->table, array('is_active' => 1))->row();
    }
    
    /**
     * Get all tahun ajaran
     */
    public function get_all() {
        $this->db->order_by('tahun_mulai', 'DESC');
        return $this->db->get($this->table)->result();
    }
}
