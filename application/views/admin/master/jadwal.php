<!-- Main Content -->
<div class="flex-1 flex flex-col overflow-hidden">
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
        
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Jadwal Pelajaran</h2>
            <p class="text-gray-600">Kelola jadwal pelajaran per kelas dan hari</p>
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
        
        <!-- Filter Bar -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Kelas</label>
                    <select name="kelas_id" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($kelas_list as $kelas): ?>
                            <option value="<?php echo $kelas->id; ?>" <?php echo ($kelas_id == $kelas->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kelas->nama_kelas); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Hari</label>
                    <select name="hari" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Hari</option>
                        <?php 
                        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        foreach ($hari_list as $h): 
                        ?>
                            <option value="<?php echo $h; ?>" <?php echo ($hari == $h) ? 'selected' : ''; ?>>
                                <?php echo $h; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="button" onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Jadwal</span>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($jadwal)): ?>
                            <?php $no = 1; foreach ($jadwal as $j): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $no++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($j->nama_kelas ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        <?php echo htmlspecialchars($j->hari); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?php echo htmlspecialchars($j->nama_mapel ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?php echo htmlspecialchars($j->nama_guru ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo substr($j->jam_mulai, 0, 5); ?> - <?php echo substr($j->jam_selesai, 0, 5); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button onclick="editData(<?php echo $j->id; ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo base_url('admin/master/jadwal_delete/' . $j->id); ?>" 
                                       onclick="return confirm('Yakin ingin menghapus jadwal ini?')"
                                       class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                    <p>Tidak ada data jadwal</p>
                                    <?php if ($kelas_id || $hari): ?>
                                        <p class="text-sm mt-1">Coba ubah filter pencarian</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </main>
</div>

<!-- Modal Add/Edit -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 id="modalTitle" class="text-lg font-semibold mb-4">Tambah Jadwal Pelajaran</h3>
        
        <form id="modalForm" method="POST">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                <select name="kelas_id" id="kelas_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($kelas_list as $kelas): ?>
                        <option value="<?php echo $kelas->id; ?>">
                            <?php echo htmlspecialchars($kelas->nama_kelas); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Mata Pelajaran</option>
                    <?php foreach ($mapel_list as $mapel): ?>
                        <option value="<?php echo $mapel->id; ?>">
                            <?php echo htmlspecialchars($mapel->nama_mapel); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Guru <span class="text-red-500">*</span></label>
                <select name="guru_id" id="guru_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Guru</option>
                    <?php foreach ($guru_list as $guru): ?>
                        <option value="<?php echo $guru->id; ?>">
                            <?php echo htmlspecialchars($guru->nama); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hari <span class="text-red-500">*</span></label>
                <select name="hari" id="hari" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Hari</option>
                    <?php 
                    $hari_options = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    foreach ($hari_options as $h): 
                    ?>
                        <option value="<?php echo $h; ?>"><?php echo $h; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_mulai" id="jam_mulai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_selesai" id="jam_selesai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="flex gap-2">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
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
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Jadwal Pelajaran';
    document.getElementById('modalForm').action = '<?php echo base_url('admin/master/jadwal_add'); ?>';
    document.getElementById('modalForm').reset();
    document.getElementById('modal').classList.remove('hidden');
}

function editData(id) {
    fetch('<?php echo base_url('admin/master/jadwal_get/'); ?>' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Edit Jadwal Pelajaran';
            document.getElementById('modalForm').action = '<?php echo base_url('admin/master/jadwal_edit/'); ?>' + id;
            document.getElementById('kelas_id').value = escapeHtml(data.kelas_id || '');
            document.getElementById('mata_pelajaran_id').value = escapeHtml(data.mata_pelajaran_id || '');
            document.getElementById('guru_id').value = escapeHtml(data.guru_id || '');
            document.getElementById('hari').value = escapeHtml(data.hari || '');
            document.getElementById('jam_mulai').value = escapeHtml(data.jam_mulai || '');
            document.getElementById('jam_selesai').value = escapeHtml(data.jam_selesai || '');
            document.getElementById('modal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data. Silakan coba lagi.');
        });
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text ? String(text).replace(/[&<>"']/g, function(m) { return map[m]; }) : '';
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
