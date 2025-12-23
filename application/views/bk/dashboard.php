<!-- Dashboard Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Siswa -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Siswa</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo number_format($total_siswa); ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-user-graduate text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Siswa Bermasalah -->
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Perlu Perhatian</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo number_format($total_siswa_bermasalah); ?></h3>
                <p class="text-red-100 text-xs mt-1">Bulan ini</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Alpha >= 3 -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Alpha ≥ 3x</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo number_format($total_alpha); ?></h3>
                <p class="text-orange-100 text-xs mt-1">Siswa</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-user-times text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Terlambat >= 5 -->
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Terlambat ≥ 5x</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo number_format($total_terlambat); ?></h3>
                <p class="text-yellow-100 text-xs mt-1">Siswa</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-clock text-3xl"></i>
            </div>
        </div>
    </div>
    
</div>

<!-- Siswa Yang Perlu Perhatian -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-red-600 to-orange-600 p-6 text-white">
        <h3 class="text-xl font-bold flex items-center">
            <i class="fas fa-user-shield mr-3"></i>
            Siswa yang Perlu Perhatian (<?php echo date('F Y'); ?>)
        </h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alpha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terlambat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($siswa_monitoring)): ?>
                    <?php $no = 1; foreach ($siswa_monitoring as $siswa): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $no++; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $siswa->nis; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo $siswa->nama; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $siswa->nama_kelas; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($siswa->jumlah_alpha >= 3): ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <?php echo $siswa->jumlah_alpha; ?>x
                                </span>
                            <?php else: ?>
                                <span class="text-sm text-gray-500"><?php echo $siswa->jumlah_alpha; ?>x</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($siswa->jumlah_terlambat >= 5): ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <?php echo $siswa->jumlah_terlambat; ?>x
                                </span>
                            <?php else: ?>
                                <span class="text-sm text-gray-500"><?php echo $siswa->jumlah_terlambat; ?>x</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($siswa->jumlah_alpha >= 3 && $siswa->jumlah_terlambat >= 5): ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Sangat Perlu Perhatian
                                </span>
                            <?php elseif ($siswa->jumlah_alpha >= 3): ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Sering Alpha
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Sering Terlambat
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo site_url('bk/monitoring/detail/'.$siswa->id); ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="<?php echo site_url('bk/surat/create/'.$siswa->id); ?>" 
                               class="text-green-600 hover:text-green-900">
                                <i class="fas fa-envelope"></i> Buat Surat
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-smile text-4xl text-gray-300 mb-2"></i>
                            <p>Tidak ada siswa yang perlu perhatian bulan ini</p>
                            <p class="text-sm text-gray-400 mt-1">Semua siswa memiliki kehadiran yang baik!</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <a href="<?php echo site_url('bk/monitoring'); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="bg-red-100 text-red-600 rounded-full p-4">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Monitoring Lengkap</h4>
                <p class="text-sm text-gray-600">Lihat semua data</p>
            </div>
        </div>
    </a>
    
    <a href="<?php echo site_url('bk/surat'); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="bg-green-100 text-green-600 rounded-full p-4">
                <i class="fas fa-envelope text-2xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Surat Panggilan</h4>
                <p class="text-sm text-gray-600"><?php echo $total_surat_bulan_ini; ?> surat bulan ini</p>
            </div>
        </div>
    </a>
    
    <a href="<?php echo site_url('bk/profile'); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="bg-blue-100 text-blue-600 rounded-full p-4">
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Profile Saya</h4>
                <p class="text-sm text-gray-600">Ubah data pribadi</p>
            </div>
        </div>
    </a>
    
</div>
