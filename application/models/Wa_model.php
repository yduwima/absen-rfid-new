<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * WhatsApp Model
 * Model for WhatsApp queue and settings
 */
class Wa_model extends Base_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get WA settings
     */
    public function get_setting() {
        return $this->db->get_where('wa_setting', array('is_active' => 1))->row();
    }
    
    /**
     * Update WA settings
     */
    public function update_setting($data) {
        $exists = $this->get_setting();
        
        if ($exists) {
            $this->db->where('id', $exists->id);
            return $this->db->update('wa_setting', $data);
        } else {
            $data['is_active'] = 1;
            return $this->db->insert('wa_setting', $data);
        }
    }
    
    /**
     * Get template by jenis
     */
    public function get_template($jenis) {
        return $this->db->get_where('wa_template', array(
            'jenis' => $jenis,
            'is_active' => 1
        ))->row();
    }
    
    /**
     * Get all templates
     */
    public function get_all_templates() {
        return $this->db->get_where('wa_template', array('is_active' => 1))->result();
    }
    
    /**
     * Update template
     */
    public function update_template($id, $template) {
        return $this->db->update('wa_template', array('template' => $template), array('id' => $id));
    }
    
    /**
     * Add to queue
     */
    public function add_to_queue($nomor_tujuan, $pesan) {
        return $this->db->insert('wa_queue', array(
            'nomor_tujuan' => $nomor_tujuan,
            'pesan' => $pesan,
            'status' => 'pending',
            'retry_count' => 0
        ));
    }
    
    /**
     * Get pending queue
     */
    public function get_pending_queue($limit = 10) {
        $this->db->where('status', 'pending');
        $this->db->or_where('status', 'failed');
        $this->db->where('retry_count <', 3);
        $this->db->limit($limit);
        return $this->db->get('wa_queue')->result();
    }
    
    /**
     * Update queue status
     */
    public function update_queue_status($id, $status, $error_message = null) {
        $data = array('status' => $status);
        
        if ($status == 'sent') {
            $data['sent_at'] = date('Y-m-d H:i:s');
        }
        
        if ($error_message) {
            $data['error_message'] = $error_message;
        }
        
        // Increment retry count if failed
        if ($status == 'failed') {
            $this->db->set('retry_count', 'retry_count + 1', FALSE);
        }
        
        $this->db->where('id', $id);
        return $this->db->update('wa_queue', $data);
    }
    
    /**
     * Get kelas for notification
     */
    public function get_notif_kelas() {
        $this->db->select('wa_notif_kelas.*, kelas.nama as nama_kelas');
        $this->db->from('wa_notif_kelas');
        $this->db->join('kelas', 'kelas.id = wa_notif_kelas.kelas_id');
        $this->db->where('wa_notif_kelas.is_active', 1);
        return $this->db->get()->result();
    }
    
    /**
     * Check if kelas active for notification
     */
    public function is_kelas_active($kelas_id) {
        $result = $this->db->get_where('wa_notif_kelas', array(
            'kelas_id' => $kelas_id,
            'is_active' => 1
        ))->row();
        
        return $result ? TRUE : FALSE;
    }
    
    /**
     * Toggle kelas notification
     */
    public function toggle_kelas_notif($kelas_id, $is_active) {
        // Check if exists
        $exists = $this->db->get_where('wa_notif_kelas', array('kelas_id' => $kelas_id))->row();
        
        if ($exists) {
            $this->db->where('kelas_id', $kelas_id);
            return $this->db->update('wa_notif_kelas', array('is_active' => $is_active));
        } else {
            return $this->db->insert('wa_notif_kelas', array(
                'kelas_id' => $kelas_id,
                'is_active' => $is_active
            ));
        }
    }
    
    /**
     * Parse template
     */
    public function parse_template($template, $data) {
        foreach ($data as $key => $value) {
            $template = str_replace('{'.$key.'}', $value, $template);
        }
        return $template;
    }
}
