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
    
    <!-- Total Guru -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Total Guru</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo number_format($total_guru); ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-chalkboard-teacher text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Absen Siswa Hari Ini -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Absen Siswa Hari Ini</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo number_format($absen_siswa_hari_ini); ?></h3>
                <p class="text-purple-100 text-xs mt-1">Belum: <?php echo $siswa_belum_absen; ?></p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-clipboard-check text-3xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Absen Guru Hari Ini -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Absen Guru Hari Ini</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo number_format($absen_guru_hari_ini); ?></h3>
                <p class="text-orange-100 text-xs mt-1">Dari <?php echo $total_guru; ?> guru</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-user-check text-3xl"></i>
            </div>
        </div>
    </div>
    
</div>

<!-- Chart & Recent Attendance -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Absensi 7 Hari Terakhir</h3>
        <canvas id="attendanceChart" height="100"></canvas>
    </div>
    
    <!-- Quick Stats -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Hari Ini</h3>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="bg-red-500 text-white rounded-full p-2">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Siswa Terlambat</p>
                        <p class="text-2xl font-bold text-red-600"><?php echo $siswa_terlambat; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="bg-yellow-500 text-white rounded-full p-2">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Belum Absen</p>
                        <p class="text-2xl font-bold text-yellow-600"><?php echo $siswa_belum_absen; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-500 text-white rounded-full p-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sudah Absen</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo $absen_siswa_hari_ini; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <a href="<?php echo site_url('admin/laporan/absensi_siswa'); ?>" class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Lihat Laporan Lengkap
            </a>
        </div>
    </div>
    
</div>

<!-- Recent Attendance -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Absensi Terbaru Hari Ini</h3>
            <a href="<?php echo site_url('rfid'); ?>" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Lihat Halaman RFID <i class="fas fa-external-link-alt ml-1"></i>
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas/Jabatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Pulang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($absensi_terbaru)): ?>
                    <?php foreach (array_slice($absensi_terbaru, 0, 10) as $absen): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo date('H:i:s', strtotime($absen->created_at)); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <?php if ($absen->foto && file_exists('./assets/uploads/'.$absen->foto)): ?>
                                        <img class="h-10 w-10 rounded-full object-cover" src="<?php echo base_url('assets/uploads/'.$absen->foto); ?>" alt="">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                            <?php echo strtoupper(substr($absen->nama, 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $absen->nama; ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full <?php echo $absen->user_type == 'siswa' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'; ?>">
                                <?php echo ucfirst($absen->user_type); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $absen->kelas_jabatan ?? '-'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo $absen->jam_masuk ? date('H:i', strtotime($absen->jam_masuk)) : '-'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo $absen->jam_pulang ? date('H:i', strtotime($absen->jam_pulang)) : '-'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($absen->status_masuk == 'Terlambat'): ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    Terlambat (<?php echo $absen->keterlambatan; ?> menit)
                                </span>
                            <?php elseif ($absen->jam_masuk): ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    Tepat Waktu
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                    -
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                            <p>Belum ada data absensi hari ini</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Chart Configuration
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo $chart_labels; ?>,
            datasets: [
                {
                    label: 'Siswa',
                    data: <?php echo $chart_siswa; ?>,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Guru',
                    data: <?php echo $chart_guru; ?>,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>
