<!-- Main Content -->
<div class="flex-1 flex flex-col overflow-hidden">
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
        
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Data Siswa</h2>
            <p class="text-gray-600">Kelola data siswa sekolah</p>
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
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari nama atau NIS..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-blue-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- Filter & Actions -->
                <div class="flex items-center gap-2">
                    <select name="kelas_id" onchange="filterKelas(this.value)" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($kelas_list as $k): ?>
                        <option value="<?php echo $k->id; ?>" <?php echo ($kelas_id == $k->id) ? 'selected' : ''; ?>>
                            <?php echo $k->nama_kelas; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Siswa</span>
                    </button>
                    
                    <button onclick="alert('Fitur import akan segera hadir')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                        <i class="fas fa-file-excel"></i>
                        <span>Import Excel</span>
                    </button>
                </div>
            </div>
            
            <!-- Per Page -->
            <div class="mt-4 flex items-center gap-2">
                <label class="text-sm text-gray-600">Tampilkan:</label>
                <select onchange="changePerPage(this.value)" class="px-3 py-1 border border-gray-300 rounded">
                    <option value="10" <?php echo ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                    <option value="20" <?php echo ($per_page == 20) ? 'selected' : ''; ?>>20</option>
                    <option value="30" <?php echo ($per_page == 30) ? 'selected' : ''; ?>>30</option>
                    <option value="50" <?php echo ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                    <option value="100" <?php echo ($per_page == 100) ? 'selected' : ''; ?>>100</option>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS / NISN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UID RFID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($siswa)): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Tidak ada data siswa</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php 
                            $no = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
                            foreach ($siswa as $s): 
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $no++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="<?php echo base_url('assets/uploads/siswa/' . $s->foto); ?>" alt="<?php echo $s->nama; ?>" class="w-10 h-10 rounded-full object-cover">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $s->nis; ?></div>
                                    <div class="text-sm text-gray-500"><?php echo $s->nisn; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $s->nama; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $s->nama_kelas; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $s->jenis_kelamin; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-mono bg-blue-100 text-blue-800 rounded"><?php echo $s->uid_rfid; ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo ($s->status == 'aktif') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo ucfirst($s->status); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editSiswa(<?php echo $s->id; ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button onclick="deleteSiswa(<?php echo $s->id; ?>, '<?php echo $s->nama; ?>')" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pagination['current_page'] > 1): ?>
                    <a href="?page=<?php echo $pagination['current_page'] - 1; ?>&per_page=<?php echo $per_page; ?>&search=<?php echo $search; ?>&kelas_id=<?php echo $kelas_id; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                    <?php endif; ?>
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="?page=<?php echo $pagination['current_page'] + 1; ?>&per_page=<?php echo $per_page; ?>&search=<?php echo $search; ?>&kelas_id=<?php echo $kelas_id; ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium"><?php echo (($pagination['current_page'] - 1) * $pagination['per_page'] + 1); ?></span> sampai 
                            <span class="font-medium"><?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total']); ?></span> dari 
                            <span class="font-medium"><?php echo $pagination['total']; ?></span> hasil
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <?php if ($pagination['current_page'] > 1): ?>
                            <a href="?page=<?php echo $pagination['current_page'] - 1; ?>&per_page=<?php echo $per_page; ?>&search=<?php echo $search; ?>&kelas_id=<?php echo $kelas_id; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <?php if ($i == $pagination['current_page']): ?>
                                <span class="relative inline-flex items-center px-4 py-2 border border-blue-600 bg-blue-50 text-sm font-medium text-blue-600">
                                    <?php echo $i; ?>
                                </span>
                                <?php else: ?>
                                <a href="?page=<?php echo $i; ?>&per_page=<?php echo $per_page; ?>&search=<?php echo $search; ?>&kelas_id=<?php echo $kelas_id; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    <?php echo $i; ?>
                                </a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="?page=<?php echo $pagination['current_page'] + 1; ?>&per_page=<?php echo $per_page; ?>&search=<?php echo $search; ?>&kelas_id=<?php echo $kelas_id; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
    </main>
</div>

<!-- Add/Edit Modal -->
<div id="siswaModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Tambah Siswa</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="siswaForm" method="POST" enctype="multipart/form-data" action="<?php echo site_url('admin/master/siswa_add'); ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- NIS -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIS *</label>
                    <input type="text" name="nis" id="nis" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- NISN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NISN *</label>
                    <input type="text" name="nisn" id="nisn" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Nama -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama" id="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                
                <!-- Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas *</label>
                    <select name="kelas_id" id="kelas_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($kelas_list as $k): ?>
                        <option value="<?php echo $k->id; ?>"><?php echo $k->nama_kelas; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Tempat Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <!-- Nama Orang Tua -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Orang Tua/Wali</label>
                    <input type="text" name="nama_ortu" id="nama_ortu" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- No HP Orang Tua -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP Orang Tua</label>
                    <input type="text" name="no_hp_ortu" id="no_hp_ortu" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="628xxxxx">
                </div>
                
                <!-- UID RFID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">UID RFID *</label>
                    <input type="text" name="uid_rfid" id="uid_rfid" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="RFID-SISWA-XXX">
                </div>
                
                <!-- Status (only for edit) -->
                <div id="statusDiv" style="display:none;">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="alumni">Alumni</option>
                    </select>
                </div>
                
                <!-- Foto -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/jpeg,image/png" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Siswa';
    document.getElementById('siswaForm').action = '<?php echo site_url('admin/master/siswa_add'); ?>';
    document.getElementById('siswaForm').reset();
    document.getElementById('statusDiv').style.display = 'none';
    document.getElementById('siswaModal').classList.remove('hidden');
}

function editSiswa(id) {
    document.getElementById('modalTitle').textContent = 'Edit Siswa';
    document.getElementById('siswaForm').action = '<?php echo site_url('admin/master/siswa_edit/'); ?>' + id;
    document.getElementById('statusDiv').style.display = 'block';
    
    // Fetch data
    fetch('<?php echo site_url('admin/master/siswa_get/'); ?>' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('nis').value = data.nis;
            document.getElementById('nisn').value = data.nisn;
            document.getElementById('nama').value = data.nama;
            document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
            document.getElementById('kelas_id').value = data.kelas_id;
            document.getElementById('tempat_lahir').value = data.tempat_lahir || '';
            document.getElementById('tanggal_lahir').value = data.tanggal_lahir || '';
            document.getElementById('alamat').value = data.alamat || '';
            document.getElementById('nama_ortu').value = data.nama_ortu || '';
            document.getElementById('no_hp_ortu').value = data.no_hp_ortu || '';
            document.getElementById('uid_rfid').value = data.uid_rfid;
            document.getElementById('status').value = data.status;
            
            document.getElementById('siswaModal').classList.remove('hidden');
        });
}

function deleteSiswa(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus siswa "' + nama + '"?')) {
        window.location.href = '<?php echo site_url('admin/master/siswa_delete/'); ?>' + id;
    }
}

function closeModal() {
    document.getElementById('siswaModal').classList.add('hidden');
}

function filterKelas(kelasId) {
    const url = new URL(window.location.href);
    url.searchParams.set('kelas_id', kelasId);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

function changePerPage(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

// Close modal on outside click
document.getElementById('siswaModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
