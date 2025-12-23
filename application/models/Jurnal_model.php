<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Jurnal Model
 * Model for jurnal_guru table
 */
class Jurnal_model extends Base_Model {
    
    protected $table = 'jurnal_guru';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get jurnal with details
     */
    public function get_with_details($jurnal_id = null) {
        $this->db->select('jurnal_guru.*, 
            mata_pelajaran.nama as nama_mapel,
            guru.nama as nama_guru,
            kelas.nama as nama_kelas');
        $this->db->from($this->table);
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_guru.mata_pelajaran_id');
        $this->db->join('guru', 'guru.id = jurnal_guru.guru_id');
        $this->db->join('kelas', 'kelas.id = jurnal_guru.kelas_id');
        
        if ($jurnal_id) {
            $this->db->where('jurnal_guru.id', $jurnal_id);
            return $this->db->get()->row();
        }
        
        $this->db->order_by('jurnal_guru.tanggal', 'DESC');
        return $this->db->get()->result();
    }
    
    /**
     * Get jurnal by guru
     */
    public function get_by_guru($guru_id, $tanggal_mulai = null, $tanggal_selesai = null) {
        $this->db->select('jurnal_guru.*, 
            mata_pelajaran.nama as nama_mapel,
            kelas.nama as nama_kelas');
        $this->db->from($this->table);
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_guru.mata_pelajaran_id');
        $this->db->join('kelas', 'kelas.id = jurnal_guru.kelas_id');
        $this->db->where('jurnal_guru.guru_id', $guru_id);
        
        if ($tanggal_mulai && $tanggal_selesai) {
            $this->db->where('jurnal_guru.tanggal >=', $tanggal_mulai);
            $this->db->where('jurnal_guru.tanggal <=', $tanggal_selesai);
        }
        
        $this->db->order_by('jurnal_guru.tanggal', 'DESC');
        return $this->db->get()->result();
    }
    
    /**
     * Get jurnal hari ini untuk guru
     */
    public function get_hari_ini_guru($guru_id, $tanggal = null) {
        if (!$tanggal) $tanggal = date('Y-m-d');
        
        $this->db->select('jurnal_guru.*, 
            mata_pelajaran.nama as nama_mapel,
            kelas.nama as nama_kelas');
        $this->db->from($this->table);
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_guru.mata_pelajaran_id');
        $this->db->join('kelas', 'kelas.id = jurnal_guru.kelas_id');
        $this->db->where('jurnal_guru.guru_id', $guru_id);
        $this->db->where('jurnal_guru.tanggal', $tanggal);
        return $this->db->get()->result();
    }
    
    /**
     * Check if jurnal exists for jadwal
     */
    public function check_jurnal_exists($jadwal_id, $tanggal) {
        return $this->db->get_where($this->table, array(
            'jadwal_id' => $jadwal_id,
            'tanggal' => $tanggal
        ))->row();
    }
    
    /**
     * Get absensi mapel by jurnal
     */
    public function get_absensi_mapel($jurnal_id) {
        $this->db->select('absensi_mapel.*, siswa.nama, siswa.nis');
        $this->db->from('absensi_mapel');
        $this->db->join('siswa', 'siswa.id = absensi_mapel.siswa_id');
        $this->db->where('absensi_mapel.jurnal_id', $jurnal_id);
        $this->db->order_by('siswa.nama');
        return $this->db->get()->result();
    }
    
    /**
     * Save absensi mapel
     */
    public function save_absensi_mapel($jurnal_id, $siswa_id, $status, $keterangan = null) {
        // Check if exists
        $exists = $this->db->get_where('absensi_mapel', array(
            'jurnal_id' => $jurnal_id,
            'siswa_id' => $siswa_id
        ))->row();
        
        $data = array(
            'status' => $status,
            'keterangan' => $keterangan
        );
        
        if ($exists) {
            $this->db->where('id', $exists->id);
            return $this->db->update('absensi_mapel', $data);
        } else {
            $data['jurnal_id'] = $jurnal_id;
            $data['siswa_id'] = $siswa_id;
            return $this->db->insert('absensi_mapel', $data);
        }
    }
    
    /**
     * Delete absensi mapel by jurnal
     */
    public function delete_absensi_mapel($jurnal_id) {
        return $this->db->delete('absensi_mapel', array('jurnal_id' => $jurnal_id));
    }
}
