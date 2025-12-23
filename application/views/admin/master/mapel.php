<!-- Main Content -->
<div class="flex-1 flex flex-col overflow-hidden">
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
        
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Data Mata Pelajaran</h2>
            <p class="text-gray-600">Kelola data mata pelajaran sekolah</p>
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
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari mata pelajaran..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-blue-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- Actions -->
                <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Mapel</span>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mapel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Pel/Minggu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($mapel)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Tidak ada data mata pelajaran</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php 
                            $no = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
                            foreach ($mapel as $m): 
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo $no++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-mono bg-blue-100 text-blue-800 rounded"><?php echo $m->kode_mapel; ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium"><?php echo $m->nama_mapel; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo $m->kategori; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo $m->jam_pelajaran; ?> JP</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editMapel(<?php echo $m->id; ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button onclick="deleteMapel(<?php echo $m->id; ?>, '<?php echo $m->nama_mapel; ?>')" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </main>
</div>

<!-- Add/Edit Modal -->
<div id="mapelModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium" id="modalTitle">Tambah Mata Pelajaran</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="mapelForm" method="POST" action="<?php echo site_url('admin/master/mapel_add'); ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kode Mapel *</label>
                    <input type="text" name="kode_mapel" id="kode_mapel" required class="w-full px-3 py-2 border rounded-md" placeholder="MAT-01">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1">Kategori</label>
                    <select name="kategori" id="kategori" class="w-full px-3 py-2 border rounded-md">
                        <option value="Wajib">Wajib</option>
                        <option value="Produktif">Produktif</option>
                        <option value="Muatan Lokal">Muatan Lokal</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Nama Mata Pelajaran *</label>
                    <input type="text" name="nama_mapel" id="nama_mapel" required class="w-full px-3 py-2 border rounded-md" placeholder="Matematika">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Jam Pelajaran per Minggu</label>
                    <input type="number" name="jam_pelajaran" id="jam_pelajaran" min="1" max="20" value="2" class="w-full px-3 py-2 border rounded-md">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Mata Pelajaran';
    document.getElementById('mapelForm').action = '<?php echo site_url('admin/master/mapel_add'); ?>';
    document.getElementById('mapelForm').reset();
    document.getElementById('mapelModal').classList.remove('hidden');
}

function editMapel(id) {
    document.getElementById('modalTitle').textContent = 'Edit Mata Pelajaran';
    document.getElementById('mapelForm').action = '<?php echo site_url('admin/master/mapel_edit/'); ?>' + id;
    
    fetch('<?php echo site_url('admin/master/mapel_get/'); ?>' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('kode_mapel').value = data.kode_mapel;
            document.getElementById('nama_mapel').value = data.nama_mapel;
            document.getElementById('kategori').value = data.kategori;
            document.getElementById('jam_pelajaran').value = data.jam_pelajaran;
            document.getElementById('mapelModal').classList.remove('hidden');
        });
}

function deleteMapel(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus mata pelajaran "' + nama + '"?')) {
        window.location.href = '<?php echo site_url('admin/master/mapel_delete/'); ?>' + id;
    }
}

function closeModal() {
    document.getElementById('mapelModal').classList.add('hidden');
}

function changePerPage(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

document.getElementById('mapelModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
