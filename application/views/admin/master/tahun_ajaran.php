<!-- Main Content -->
<div class="flex-1 flex flex-col overflow-hidden">
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
        
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Data Tahun Ajaran</h2>
            <p class="text-gray-600">Kelola data tahun ajaran dan semester</p>
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
        
        <!-- Actions Bar -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <!-- Search -->
                <form method="GET" class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari tahun ajaran..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-blue-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- Actions -->
                <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Tahun Ajaran</span>
                </button>
            </div>
            
            <!-- Per Page -->
            <div class="mt-4 flex items-center gap-2">
                <label class="text-sm text-gray-600">Tampilkan:</label>
                <select onchange="changePerPage(this.value)" class="px-3 py-1 border border-gray-300 rounded">
                    <option value="10" <?php echo ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                    <option value="20" <?php echo ($per_page == 20) ? 'selected' : ''; ?>>20</option>
                    <option value="50" <?php echo ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                </select>
                <span class="text-sm text-gray-600">dari <?php echo $total; ?> data</span>
            </div>
        </div>
        
        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Semester</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Selesai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($tahun_ajaran)): ?>
                            <?php $no = (($pagination['current_page'] - 1) * $per_page) + 1; ?>
                            <?php foreach ($tahun_ajaran as $ta): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $no++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($ta->tahun); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($ta->semester); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date('d/m/Y', strtotime($ta->tanggal_mulai)); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date('d/m/Y', strtotime($ta->tanggal_selesai)); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($ta->status == 'aktif'): ?>
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button onclick="editData(<?php echo $ta->id; ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?php echo base_url('admin/master/tahun_ajaran_delete/' . $ta->id); ?>" 
                                       onclick="return confirm('Yakin ingin menghapus tahun ajaran ini?')"
                                       class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-calendar-alt text-4xl mb-2"></i>
                                    <p>Tidak ada data tahun ajaran</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Halaman <?php echo $pagination['current_page']; ?> dari <?php echo $pagination['total_pages']; ?>
                    </div>
                    <div class="flex gap-2">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <a href="?page=<?php echo $pagination['current_page'] - 1; ?>&search=<?php echo $search; ?>&per_page=<?php echo $per_page; ?>" 
                               class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="?page=<?php echo $pagination['current_page'] + 1; ?>&search=<?php echo $search; ?>&per_page=<?php echo $per_page; ?>" 
                               class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
    </main>
</div>

<!-- Modal Add/Edit -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 id="modalTitle" class="text-lg font-semibold mb-4">Tambah Tahun Ajaran</h3>
        
        <form id="modalForm" method="POST">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun <span class="text-red-500">*</span></label>
                <input type="text" name="tahun" id="tahun" required placeholder="Contoh: 2024/2025" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                <select name="semester" id="semester" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Semester</option>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="nonaktif">Nonaktif</option>
                    <option value="aktif">Aktif</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">*Set aktif akan menonaktifkan tahun ajaran lainnya</p>
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
function changePerPage(value) {
    window.location.href = '?per_page=' + value + '&search=<?php echo $search; ?>';
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Tahun Ajaran';
    document.getElementById('modalForm').action = '<?php echo base_url('admin/master/tahun_ajaran_add'); ?>';
    document.getElementById('modalForm').reset();
    document.getElementById('modal').classList.remove('hidden');
}

function editData(id) {
    fetch('<?php echo base_url('admin/master/tahun_ajaran_get/'); ?>' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Edit Tahun Ajaran';
            document.getElementById('modalForm').action = '<?php echo base_url('admin/master/tahun_ajaran_edit/'); ?>' + id;
            document.getElementById('tahun').value = data.tahun;
            document.getElementById('semester').value = data.semester;
            document.getElementById('tanggal_mulai').value = data.tanggal_mulai;
            document.getElementById('tanggal_selesai').value = data.tanggal_selesai;
            document.getElementById('status').value = data.status;
            document.getElementById('modal').classList.remove('hidden');
        });
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
