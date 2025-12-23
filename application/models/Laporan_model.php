<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Laporan Model
 * Model for generating reports
 */
class Laporan_model extends Base_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get laporan absensi harian siswa
     */
    public function get_absensi_harian_siswa($start_date, $end_date, $kelas_id = null) {
        $this->db->select('absensi_harian.*, siswa.nama, siswa.nis, kelas.nama as nama_kelas');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.user_type', 'siswa');
        $this->db->where('absensi_harian.tanggal >=', $start_date);
        $this->db->where('absensi_harian.tanggal <=', $end_date);
        
        if ($kelas_id) {
            $this->db->where('siswa.kelas_id', $kelas_id);
        }
        
        $this->db->order_by('absensi_harian.tanggal', 'DESC');
        $this->db->order_by('siswa.nama', 'ASC');
        return $this->db->get()->result();
    }
    
    /**
     * Get laporan absensi harian guru
     */
    public function get_absensi_harian_guru($start_date, $end_date) {
        $this->db->select('absensi_harian.*, guru.nama, guru.nip, guru.jabatan');
        $this->db->from('absensi_harian');
        $this->db->join('guru', 'guru.id = absensi_harian.user_id');
        $this->db->where('absensi_harian.user_type', 'guru');
        $this->db->where('absensi_harian.tanggal >=', $start_date);
        $this->db->where('absensi_harian.tanggal <=', $end_date);
        $this->db->order_by('absensi_harian.tanggal', 'DESC');
        $this->db->order_by('guru.nama', 'ASC');
        return $this->db->get()->result();
    }
    
    /**
     * Get rekap absensi siswa per bulan
     */
    public function get_rekap_siswa_bulanan($bulan, $tahun, $kelas_id = null) {
        $this->db->select('siswa.id, siswa.nama, siswa.nis, kelas.nama as nama_kelas,
            COUNT(DISTINCT absensi_harian.tanggal) as total_hadir,
            SUM(CASE WHEN absensi_harian.status_masuk = "Terlambat" THEN 1 ELSE 0 END) as total_terlambat,
            SUM(absensi_harian.keterlambatan) as total_menit_terlambat');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->join('absensi_harian', 'absensi_harian.user_id = siswa.id AND absensi_harian.user_type = "siswa" AND MONTH(absensi_harian.tanggal) = '.$bulan.' AND YEAR(absensi_harian.tanggal) = '.$tahun, 'left');
        $this->db->where('siswa.status', 'Aktif');
        
        if ($kelas_id) {
            $this->db->where('siswa.kelas_id', $kelas_id);
        }
        
        $this->db->group_by('siswa.id');
        $this->db->order_by('siswa.nama', 'ASC');
        return $this->db->get()->result();
    }
    
    /**
     * Get rekap absensi guru per bulan
     */
    public function get_rekap_guru_bulanan($bulan, $tahun) {
        $this->db->select('guru.id, guru.nama, guru.nip, guru.jabatan,
            COUNT(DISTINCT absensi_harian.tanggal) as total_hadir,
            SUM(CASE WHEN absensi_harian.status_masuk = "Terlambat" THEN 1 ELSE 0 END) as total_terlambat,
            SUM(absensi_harian.keterlambatan) as total_menit_terlambat');
        $this->db->from('guru');
        $this->db->join('absensi_harian', 'absensi_harian.user_id = guru.id AND absensi_harian.user_type = "guru" AND MONTH(absensi_harian.tanggal) = '.$bulan.' AND YEAR(absensi_harian.tanggal) = '.$tahun, 'left');
        $this->db->where('guru.status', 'Aktif');
        $this->db->group_by('guru.id');
        $this->db->order_by('guru.nama', 'ASC');
        return $this->db->get()->result();
    }
    
    /**
     * Get rekap absensi mapel
     */
    public function get_rekap_absensi_mapel($kelas_id, $mapel_id, $start_date, $end_date) {
        $this->db->select('siswa.id, siswa.nama, siswa.nis,
            COUNT(CASE WHEN absensi_mapel.status = "H" THEN 1 END) as hadir,
            COUNT(CASE WHEN absensi_mapel.status = "S" THEN 1 END) as sakit,
            COUNT(CASE WHEN absensi_mapel.status = "I" THEN 1 END) as izin,
            COUNT(CASE WHEN absensi_mapel.status = "A" THEN 1 END) as alpha');
        $this->db->from('siswa');
        $this->db->join('absensi_mapel', 'absensi_mapel.siswa_id = siswa.id', 'left');
        $this->db->join('jurnal_guru', 'jurnal_guru.id = absensi_mapel.jurnal_id AND jurnal_guru.mata_pelajaran_id = '.$mapel_id.' AND jurnal_guru.tanggal >= "'.$start_date.'" AND jurnal_guru.tanggal <= "'.$end_date.'"', 'left');
        $this->db->where('siswa.kelas_id', $kelas_id);
        $this->db->where('siswa.status', 'Aktif');
        $this->db->group_by('siswa.id');
        $this->db->order_by('siswa.nama', 'ASC');
        return $this->db->get()->result();
    }
    
    /**
     * Get siswa untuk monitoring BK
     */
    public function get_siswa_for_bk_monitoring($bulan, $tahun) {
        // Get siswa dengan alpha >= 3 atau terlambat >= 5
        $this->db->select('siswa.id, siswa.nama, siswa.nis, kelas.nama as nama_kelas,
            COUNT(DISTINCT CASE WHEN absensi_harian.jam_masuk IS NULL THEN absensi_harian.tanggal END) as jumlah_alpha,
            COUNT(DISTINCT CASE WHEN absensi_harian.status_masuk = "Terlambat" THEN absensi_harian.tanggal END) as jumlah_terlambat');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id');
        $this->db->join('absensi_harian', 'absensi_harian.user_id = siswa.id AND absensi_harian.user_type = "siswa" AND MONTH(absensi_harian.tanggal) = '.$bulan.' AND YEAR(absensi_harian.tanggal) = '.$tahun, 'left');
        $this->db->where('siswa.status', 'Aktif');
        $this->db->group_by('siswa.id');
        $this->db->having('jumlah_alpha >= 3 OR jumlah_terlambat >= 5');
        $this->db->order_by('jumlah_alpha', 'DESC');
        $this->db->order_by('jumlah_terlambat', 'DESC');
        return $this->db->get()->result();
    }
}
