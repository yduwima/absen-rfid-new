<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pengaturan Model
 * Model for pengaturan tables
 */
class Pengaturan_model extends Base_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get pengaturan sekolah
     */
    public function get_sekolah() {
        return $this->db->get('pengaturan_sekolah')->row();
    }
    
    /**
     * Update pengaturan sekolah
     */
    public function update_sekolah($data) {
        $exists = $this->get_sekolah();
        
        if ($exists) {
            $this->db->where('id', $exists->id);
            return $this->db->update('pengaturan_sekolah', $data);
        } else {
            return $this->db->insert('pengaturan_sekolah', $data);
        }
    }
    
    /**
     * Get jam kerja
     */
    public function get_jam_kerja() {
        return $this->db->get('pengaturan_jam_kerja')->row();
    }
    
    /**
     * Update jam kerja
     */
    public function update_jam_kerja($data) {
        $exists = $this->get_jam_kerja();
        
        if ($exists) {
            $this->db->where('id', $exists->id);
            return $this->db->update('pengaturan_jam_kerja', $data);
        } else {
            return $this->db->insert('pengaturan_jam_kerja', $data);
        }
    }
    
    /**
     * Get hari kerja
     */
    public function get_hari_kerja() {
        return $this->db->get_where('hari_kerja', array('is_active' => 1))->result();
    }
    
    /**
     * Is hari kerja
     */
    public function is_hari_kerja($hari) {
        $result = $this->db->get_where('hari_kerja', array(
            'hari' => $hari,
            'is_active' => 1
        ))->row();
        
        return $result ? TRUE : FALSE;
    }
    
    /**
     * Update hari kerja
     */
    public function update_hari_kerja($hari, $is_active) {
        $this->db->where('hari', $hari);
        return $this->db->update('hari_kerja', array('is_active' => $is_active));
    }
    
    /**
     * Get hari libur
     */
    public function get_hari_libur($tahun = null) {
        if ($tahun) {
            $this->db->where('YEAR(tanggal)', $tahun);
        }
        $this->db->order_by('tanggal', 'ASC');
        return $this->db->get('hari_libur')->result();
    }
    
    /**
     * Is hari libur
     */
    public function is_hari_libur($tanggal) {
        $result = $this->db->get_where('hari_libur', array('tanggal' => $tanggal))->row();
        return $result ? TRUE : FALSE;
    }
    
    /**
     * Add hari libur
     */
    public function add_hari_libur($data) {
        return $this->db->insert('hari_libur', $data);
    }
    
    /**
     * Delete hari libur
     */
    public function delete_hari_libur($id) {
        return $this->db->delete('hari_libur', array('id' => $id));
    }
    
    /**
     * Get tahun ajaran
     */
    public function get_tahun_ajaran() {
        $this->db->order_by('tahun_mulai', 'DESC');
        return $this->db->get('tahun_ajaran')->result();
    }
    
    /**
     * Get tahun ajaran aktif
     */
    public function get_tahun_ajaran_aktif() {
        return $this->db->get_where('tahun_ajaran', array('is_active' => 1))->row();
    }
    
    /**
     * Get semester
     */
    public function get_semester($tahun_ajaran_id = null) {
        if ($tahun_ajaran_id) {
            $this->db->where('tahun_ajaran_id', $tahun_ajaran_id);
        }
        return $this->db->get('semester')->result();
    }
    
    /**
     * Get semester aktif
     */
    public function get_semester_aktif() {
        return $this->db->get_where('semester', array('is_active' => 1))->row();
    }
}
