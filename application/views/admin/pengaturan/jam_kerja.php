<!-- Main Content -->
<div class="flex-1 flex flex-col overflow-hidden">
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
        
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Pengaturan Jam Kerja</h2>
            <p class="text-gray-600">Kelola jam kerja, hari kerja, dan hari libur</p>
        </div>
        
        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
            <p><?php echo $this->session->flashdata('success'); ?></p>
        </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
            <p><?php echo $this->session->flashdata('error'); ?></p>
        </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Jam Kerja Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">
                    <i class="fas fa-clock mr-2 text-blue-600"></i>
                    Jam Masuk & Pulang
                </h3>
                
                <form method="POST" action="<?php echo base_url('admin/pengaturan/jam_kerja'); ?>">
                    <input type="hidden" name="action" value="update_jam">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam Masuk</label>
                        <input type="time" name="jam_masuk" value="<?php echo $jam_kerja->jam_masuk ?? '07:00'; ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam Pulang</label>
                        <input type="time" name="jam_pulang" value="<?php echo $jam_kerja->jam_pulang ?? '15:00'; ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Toleransi Keterlambatan (menit)</label>
                        <input type="number" name="toleransi_keterlambatan" value="<?php echo $jam_kerja->toleransi_keterlambatan ?? 15; ?>" required min="0" max="60" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Jam Kerja
                    </button>
                </form>
            </div>
            
            <!-- Hari Kerja Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">
                    <i class="fas fa-calendar-week mr-2 text-blue-600"></i>
                    Hari Kerja
                </h3>
                
                <form method="POST" action="<?php echo base_url('admin/pengaturan/jam_kerja'); ?>">
                    <input type="hidden" name="action" value="update_hari">
                    
                    <?php 
                    $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    $hari_aktif_array = [];
                    if ($hari_kerja) {
                        foreach ($hari_kerja as $hk) {
                            $hari_aktif_array[] = $hk->hari;
                        }
                    }
                    ?>
                    
                    <div class="space-y-3 mb-4">
                        <?php foreach ($hari_list as $hari): ?>
                        <label class="flex items-center">
                            <input type="checkbox" name="hari_aktif[]" value="<?php echo $hari; ?>" 
                                <?php echo in_array($hari, $hari_aktif_array) ? 'checked' : ''; ?>
                                class="mr-3 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-gray-700"><?php echo $hari; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Hari Kerja
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Hari Libur -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-umbrella-beach mr-2 text-blue-600"></i>
                    Hari Libur Nasional
                </h3>
                <button onclick="openAddLiburModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Hari Libur</span>
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($hari_libur)): ?>
                            <?php $no = 1; foreach ($hari_libur as $libur): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $no++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date('d/m/Y', strtotime($libur->tanggal)); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($libur->keterangan); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <a href="<?php echo base_url('admin/pengaturan/hapus_libur/' . $libur->id); ?>" 
                                       onclick="return confirm('Yakin ingin menghapus hari libur ini?')"
                                       class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                    <p>Belum ada hari libur</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </main>
</div>

<!-- Modal Tambah Hari Libur -->
<div id="addLiburModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Tambah Hari Libur</h3>
        
        <form method="POST" action="<?php echo base_url('admin/pengaturan/jam_kerja'); ?>">
            <input type="hidden" name="action" value="tambah_libur">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan <span class="text-red-500">*</span></label>
                <input type="text" name="keterangan" required placeholder="Contoh: Hari Kemerdekaan RI" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex gap-2">
                <button type="button" onclick="closeAddLiburModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddLiburModal() {
    document.getElementById('addLiburModal').classList.remove('hidden');
}

function closeAddLiburModal() {
    document.getElementById('addLiburModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('addLiburModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddLiburModal();
    }
});
</script>
