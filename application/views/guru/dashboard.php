<!-- Dashboard Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Jadwal Hari Ini -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Jadwal Hari Ini</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo count($jadwal_hari_ini); ?></h3>
                <p class="text-blue-100 text-xs mt-1"><?php echo get_hari(); ?></p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-calendar-day text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Jurnal Terisi -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Jurnal Hari Ini</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo count($jurnal_hari_ini); ?></h3>
                <p class="text-green-100 text-xs mt-1">Sudah terisi</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-book-open text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Jumlah Kelas -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Kelas Diampu</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo $jumlah_kelas; ?></h3>
                <p class="text-purple-100 text-xs mt-1">Semester ini</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Mengajar -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Mengajar Bulan Ini</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo $total_mengajar_bulan_ini; ?></h3>
                <p class="text-orange-100 text-xs mt-1">Hari mengajar</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-chalkboard-teacher text-3xl"></i>
            </div>
        </div>
    </div>
    
</div>

<?php if ($is_wali_kelas && isset($wali_kelas_info)): ?>
<!-- Wali Kelas Info -->
<div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 rounded-r-lg">
    <div class="flex items-center">
        <i class="fas fa-user-tie text-blue-600 text-2xl mr-4"></i>
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Wali Kelas</h3>
            <p class="text-gray-600">Anda adalah wali kelas <?php echo $wali_kelas_info->nama_kelas; ?></p>
        </div>
        <a href="<?php echo site_url('walikelas/monitoring'); ?>" class="ml-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Monitoring Kelas
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Jadwal Hari Ini -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
        <h3 class="text-xl font-bold flex items-center">
            <i class="fas fa-calendar-alt mr-3"></i>
            Jadwal Mengajar Hari Ini (<?php echo get_hari(); ?>)
        </h3>
    </div>
    
    <div class="p-6">
        <?php if (!empty($jadwal_hari_ini)): ?>
            <div class="space-y-4">
                <?php foreach ($jadwal_hari_ini as $jadwal): ?>
                    <?php
                    // Check if jurnal already filled
                    $jurnal_exists = false;
                    foreach ($jurnal_hari_ini as $jurnal) {
                        if ($jurnal->jadwal_id == $jadwal->id) {
                            $jurnal_exists = true;
                            break;
                        }
                    }
                    ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-blue-100 text-blue-600 px-3 py-1 rounded-lg font-semibold">
                                        <?php echo date('H:i', strtotime($jadwal->jam_mulai)); ?> - <?php echo date('H:i', strtotime($jadwal->jam_selesai)); ?>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800"><?php echo $jadwal->nama_mapel; ?></h4>
                                        <p class="text-sm text-gray-600">
                                            <?php echo $jadwal->nama_kelas; ?>
                                            <?php if ($jadwal->ruangan): ?>
                                                • <?php echo $jadwal->ruangan; ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <?php if ($jurnal_exists): ?>
                                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-medium">
                                        <i class="fas fa-check-circle mr-1"></i> Sudah Terisi
                                    </span>
                                <?php else: ?>
                                    <a href="<?php echo site_url('guru/jurnal/add/'.$jadwal->id); ?>" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                        <i class="fas fa-plus-circle mr-1"></i> Isi Jurnal
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-calendar-times text-6xl mb-4 opacity-50"></i>
                <p class="text-lg">Tidak ada jadwal mengajar hari ini</p>
                <p class="text-sm mt-2">Nikmati hari Anda!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <a href="<?php echo site_url('guru/jurnal'); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="bg-blue-100 text-blue-600 rounded-full p-4">
                <i class="fas fa-book text-2xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Jurnal Mengajar</h4>
                <p class="text-sm text-gray-600">Lihat & kelola jurnal</p>
            </div>
        </div>
    </a>
    
    <a href="<?php echo site_url('guru/laporan'); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="bg-green-100 text-green-600 rounded-full p-4">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Laporan Kinerja</h4>
                <p class="text-sm text-gray-600">Statistik mengajar</p>
            </div>
        </div>
    </a>
    
    <a href="<?php echo site_url('guru/profile'); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="bg-purple-100 text-purple-600 rounded-full p-4">
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Profile Saya</h4>
                <p class="text-sm text-gray-600">Ubah data pribadi</p>
            </div>
        </div>
    </a>
    
</div>
