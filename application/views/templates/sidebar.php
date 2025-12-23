<?php
$role = $this->session->userdata('role');
$nama = $this->session->userdata('nama');
$current_url = uri_string();
?>

<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex-shrink-0 transition-transform duration-300 transform lg:translate-x-0 -translate-x-full fixed lg:static h-full z-30 sidebar-scrollbar overflow-y-auto">
    
    <!-- Logo -->
    <div class="p-6 border-b border-blue-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-id-card text-blue-600 text-xl"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold">RFID Absensi</h1>
                <p class="text-xs text-blue-200">SMK Negeri 1</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="px-4 py-6 space-y-2">
        
        <?php if ($role == 'admin'): ?>
            <!-- ADMIN MENU -->
            <a href="<?php echo site_url('admin/dashboard'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?php echo (strpos($current_url, 'admin/dashboard') !== false) ? 'bg-blue-700' : ''; ?>">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <!-- Pengaturan -->
            <div class="mt-4">
                <p class="px-4 text-xs text-blue-300 uppercase font-semibold mb-2">Pengaturan</p>
                <a href="<?php echo site_url('admin/pengaturan/sekolah'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-school w-5"></i>
                    <span>Data Sekolah</span>
                </a>
                <a href="<?php echo site_url('admin/pengaturan/jam_kerja'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-clock w-5"></i>
                    <span>Jam Kerja</span>
                </a>
            </div>
            
            <!-- Data Master -->
            <div class="mt-4">
                <p class="px-4 text-xs text-blue-300 uppercase font-semibold mb-2">Data Master</p>
                <a href="<?php echo site_url('admin/master/tahun_ajaran'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span>Tahun Ajaran</span>
                </a>
                <a href="<?php echo site_url('admin/master/kelas'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-door-open w-5"></i>
                    <span>Kelas</span>
                </a>
                <a href="<?php echo site_url('admin/master/siswa'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-user-graduate w-5"></i>
                    <span>Siswa</span>
                </a>
                <a href="<?php echo site_url('admin/master/guru'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-chalkboard-teacher w-5"></i>
                    <span>Guru & Staff</span>
                </a>
                <a href="<?php echo site_url('admin/master/mapel'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-book w-5"></i>
                    <span>Mata Pelajaran</span>
                </a>
                <a href="<?php echo site_url('admin/master/jadwal'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-calendar-week w-5"></i>
                    <span>Jadwal Pelajaran</span>
                </a>
            </div>
            
            <!-- Laporan -->
            <div class="mt-4">
                <p class="px-4 text-xs text-blue-300 uppercase font-semibold mb-2">Laporan</p>
                <a href="<?php echo site_url('admin/laporan/absensi_siswa'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-file-alt w-5"></i>
                    <span>Absensi Siswa</span>
                </a>
                <a href="<?php echo site_url('admin/laporan/absensi_guru'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-file-alt w-5"></i>
                    <span>Absensi Guru</span>
                </a>
                <a href="<?php echo site_url('admin/laporan/rekap_siswa'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span>Rekap Siswa</span>
                </a>
            </div>
            
            <!-- WhatsApp -->
            <div class="mt-4">
                <p class="px-4 text-xs text-blue-300 uppercase font-semibold mb-2">Notifikasi</p>
                <a href="<?php echo site_url('admin/wa/pengaturan'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fab fa-whatsapp w-5"></i>
                    <span>WhatsApp</span>
                </a>
            </div>
            
        <?php elseif (in_array($role, ['guru', 'walikelas', 'piket'])): ?>
            <!-- GURU MENU -->
            <a href="<?php echo site_url('guru/dashboard'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?php echo (strpos($current_url, 'guru/dashboard') !== false) ? 'bg-blue-700' : ''; ?>">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="<?php echo site_url('guru/jurnal'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-book-open w-5"></i>
                <span>Jurnal & Absensi</span>
            </a>
            
            <a href="<?php echo site_url('guru/laporan'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-chart-line w-5"></i>
                <span>Laporan Kinerja</span>
            </a>
            
            <?php if (in_array($role, ['walikelas', 'piket'])): ?>
            <div class="mt-4">
                <p class="px-4 text-xs text-blue-300 uppercase font-semibold mb-2">Tambahan</p>
                
                <?php if ($role == 'walikelas'): ?>
                <a href="<?php echo site_url('walikelas/izin'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-user-check w-5"></i>
                    <span>Input Izin/Sakit</span>
                </a>
                <a href="<?php echo site_url('walikelas/monitoring'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-users w-5"></i>
                    <span>Monitoring Kelas</span>
                </a>
                <?php endif; ?>
                
                <?php if ($role == 'piket'): ?>
                <a href="<?php echo site_url('piket/izin'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Izin Siswa KBM</span>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <a href="<?php echo site_url('guru/profile'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-user-circle w-5"></i>
                <span>Profile</span>
            </a>
            
        <?php elseif ($role == 'bk'): ?>
            <!-- BK MENU -->
            <a href="<?php echo site_url('bk/dashboard'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition <?php echo (strpos($current_url, 'bk/dashboard') !== false) ? 'bg-blue-700' : ''; ?>">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="<?php echo site_url('bk/monitoring'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-exclamation-triangle w-5"></i>
                <span>Monitoring Siswa</span>
            </a>
            
            <a href="<?php echo site_url('bk/surat'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-envelope w-5"></i>
                <span>Surat Panggilan</span>
            </a>
            
            <a href="<?php echo site_url('bk/profile'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-user-circle w-5"></i>
                <span>Profile</span>
            </a>
        <?php endif; ?>
        
    </nav>
    
    <!-- Logout Button -->
    <div class="px-4 py-6 border-t border-blue-700 mt-auto">
        <a href="<?php echo site_url('auth/logout'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 transition">
            <i class="fas fa-sign-out-alt w-5"></i>
            <span>Logout</span>
        </a>
    </div>
    
</aside>
