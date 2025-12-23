<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Jurnal Mengajar</h1>
    <a href="<?php echo site_url('guru/dashboard'); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<!-- Jurnal List -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($jurnal_list)): ?>
                    <?php foreach ($jurnal_list as $jurnal): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo tanggal_indonesia($jurnal->tanggal); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo $jurnal->nama_mapel; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo $jurnal->nama_kelas; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-md truncate" title="<?php echo $jurnal->materi; ?>">
                                <?php echo character_limiter($jurnal->materi, 50); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo date('H:i', strtotime($jurnal->jam_mulai)); ?> - <?php echo date('H:i', strtotime($jurnal->jam_selesai)); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo site_url('guru/jurnal/detail/'.$jurnal->id); ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                            <p>Belum ada jurnal yang dibuat</p>
                            <p class="text-sm text-gray-400 mt-1">Mulai mengisi jurnal dari dashboard</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (!empty($pagination)): ?>
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <?php echo $pagination; ?>
    </div>
    <?php endif; ?>
</div>
