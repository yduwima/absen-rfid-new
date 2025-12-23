<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Absensi Model
 * Model for absensi_harian table
 */
class Absensi_model extends Base_Model {
    
    protected $table = 'absensi_harian';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get absensi hari ini
     */
    public function get_today($tanggal = null) {
        if (!$tanggal) $tanggal = date('Y-m-d');
        
        $this->db->select('absensi_harian.*, 
            CASE 
                WHEN absensi_harian.user_type = "siswa" THEN siswa.nama
                WHEN absensi_harian.user_type = "guru" THEN guru.nama
            END as nama,
            CASE 
                WHEN absensi_harian.user_type = "siswa" THEN siswa.foto
                WHEN absensi_harian.user_type = "guru" THEN guru.foto
            END as foto,
            CASE 
                WHEN absensi_harian.user_type = "siswa" THEN kelas.nama
                ELSE guru.jabatan
            END as kelas_jabatan');
        $this->db->from($this->table);
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id AND absensi_harian.user_type = "siswa"', 'left');
        $this->db->join('guru', 'guru.id = absensi_harian.user_id AND absensi_harian.user_type = "guru"', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.tanggal', $tanggal);
        $this->db->order_by('absensi_harian.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    /**
     * Check if already absent today
     */
    public function check_today($user_type, $user_id, $tanggal = null) {
        if (!$tanggal) $tanggal = date('Y-m-d');
        
        return $this->db->get_where($this->table, array(
            'tanggal' => $tanggal,
            'user_type' => $user_type,
            'user_id' => $user_id
        ))->row();
    }
    
    /**
     * Tap masuk
     */
    public function tap_masuk($user_type, $user_id, $uid_rfid, $jam_masuk, $status_masuk, $keterlambatan) {
        $tanggal = date('Y-m-d');
        
        // Check if already exists
        $exists = $this->check_today($user_type, $user_id, $tanggal);
        
        if ($exists) {
            // Update if jam_masuk is null
            if (!$exists->jam_masuk) {
                return $this->update($exists->id, array(
                    'jam_masuk' => $jam_masuk,
                    'status_masuk' => $status_masuk,
                    'keterlambatan' => $keterlambatan
                ));
            }
            return FALSE; // Already tapped in
        }
        
        // Insert new record
        return $this->insert(array(
            'tanggal' => $tanggal,
            'user_type' => $user_type,
            'user_id' => $user_id,
            'uid_rfid' => $uid_rfid,
            'jam_masuk' => $jam_masuk,
            'status_masuk' => $status_masuk,
            'keterlambatan' => $keterlambatan
        ));
    }
    
    /**
     * Tap pulang
     */
    public function tap_pulang($user_type, $user_id, $uid_rfid) {
        $tanggal = date('Y-m-d');
        $jam_pulang = date('H:i:s');
        
        $exists = $this->check_today($user_type, $user_id, $tanggal);
        
        if ($exists) {
            // Update jam pulang
            return $this->update($exists->id, array('jam_pulang' => $jam_pulang));
        }
        
        // Insert new record with only pulang
        return $this->insert(array(
            'tanggal' => $tanggal,
            'user_type' => $user_type,
            'user_id' => $user_id,
            'uid_rfid' => $uid_rfid,
            'jam_pulang' => $jam_pulang
        ));
    }
    
    /**
     * Get absensi by periode
     */
    public function get_by_periode($user_type, $start_date, $end_date, $user_id = null) {
        $this->db->select('absensi_harian.*, 
            CASE 
                WHEN absensi_harian.user_type = "siswa" THEN siswa.nama
                WHEN absensi_harian.user_type = "guru" THEN guru.nama
            END as nama,
            CASE 
                WHEN absensi_harian.user_type = "siswa" THEN siswa.nis
                WHEN absensi_harian.user_type = "guru" THEN guru.nip
            END as nomor_induk,
            CASE 
                WHEN absensi_harian.user_type = "siswa" THEN kelas.nama
                ELSE guru.jabatan
            END as kelas_jabatan');
        $this->db->from($this->table);
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id AND absensi_harian.user_type = "siswa"', 'left');
        $this->db->join('guru', 'guru.id = absensi_harian.user_id AND absensi_harian.user_type = "guru"', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.user_type', $user_type);
        $this->db->where('absensi_harian.tanggal >=', $start_date);
        $this->db->where('absensi_harian.tanggal <=', $end_date);
        
        if ($user_id) {
            $this->db->where('absensi_harian.user_id', $user_id);
        }
        
        $this->db->order_by('absensi_harian.tanggal', 'DESC');
        return $this->db->get()->result();
    }
    
    /**
     * Get statistik absensi
     */
    public function get_statistik($user_type, $bulan, $tahun) {
        $this->db->select('COUNT(*) as total_hadir, 
            SUM(CASE WHEN status_masuk = "Terlambat" THEN 1 ELSE 0 END) as total_terlambat,
            AVG(keterlambatan) as rata_keterlambatan');
        $this->db->from($this->table);
        $this->db->where('user_type', $user_type);
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        return $this->db->get()->row();
    }
    
    /**
     * Count siswa belum absen
     */
    public function count_siswa_belum_absen($tanggal = null, $kelas_id = null) {
        if (!$tanggal) $tanggal = date('Y-m-d');
        
        $this->db->select('COUNT(siswa.id) as total');
        $this->db->from('siswa');
        $this->db->where('siswa.status', 'Aktif');
        
        if ($kelas_id) {
            $this->db->where('siswa.kelas_id', $kelas_id);
        }
        
        $this->db->where('siswa.id NOT IN (
            SELECT user_id FROM absensi_harian 
            WHERE user_type = "siswa" AND tanggal = "'.$tanggal.'"
        )', NULL, FALSE);
        
        $result = $this->db->get()->row();
        return $result ? $result->total : 0;
    }
}
