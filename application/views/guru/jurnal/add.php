<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Tambah Jurnal Mengajar</h1>
    <a href="<?php echo site_url('guru/dashboard'); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<!-- Jadwal Info Card -->
<div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-6 rounded-r-lg">
    <div class="flex items-center">
        <i class="fas fa-info-circle text-blue-600 text-2xl mr-4"></i>
        <div>
            <h3 class="text-lg font-semibold text-gray-800"><?php echo $jadwal->nama_mapel; ?></h3>
            <p class="text-gray-600">Kelas: <?php echo $jadwal->nama_kelas; ?> • Waktu: <?php echo date('H:i', strtotime($jadwal->jam_mulai)); ?> - <?php echo date('H:i', strtotime($jadwal->jam_selesai)); ?></p>
        </div>
    </div>
</div>

<!-- Form -->
<form action="<?php echo site_url('guru/jurnal/add/'.$jadwal->id); ?>" method="POST">
    <!-- CSRF Token -->
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- Jurnal Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-book mr-2"></i> Data Jurnal
                </h3>
                
                <div class="space-y-4">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" required
                               value="<?php echo set_value('tanggal', date('Y-m-d')); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <?php echo form_error('tanggal', '<p class="text-red-500 text-xs mt-1">', '</p>'); ?>
                    </div>
                    
                    <!-- Materi -->
                    <div>
                        <label for="materi" class="block text-sm font-medium text-gray-700 mb-2">Materi Pembelajaran</label>
                        <textarea name="materi" id="materi" rows="4" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Contoh: Pengenalan HTML dan CSS"><?php echo set_value('materi'); ?></textarea>
                        <?php echo form_error('materi', '<p class="text-red-500 text-xs mt-1">', '</p>'); ?>
                    </div>
                    
                    <!-- Kegiatan -->
                    <div>
                        <label for="kegiatan" class="block text-sm font-medium text-gray-700 mb-2">Kegiatan Pembelajaran</label>
                        <textarea name="kegiatan" id="kegiatan" rows="4" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Contoh: Praktik membuat website sederhana"><?php echo set_value('kegiatan'); ?></textarea>
                        <?php echo form_error('kegiatan', '<p class="text-red-500 text-xs mt-1">', '</p>'); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Absensi Siswa -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-users mr-2"></i> Absensi Siswa (<?php echo count($siswa_list); ?> siswa)
                </h3>
                
                <!-- Legend -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4 flex flex-wrap items-center justify-center gap-4">
                    <div class="flex items-center">
                        <span class="w-8 h-8 bg-green-500 text-white rounded-lg flex items-center justify-center font-bold mr-2">H</span>
                        <span class="text-sm text-gray-700">Hadir</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-8 h-8 bg-yellow-500 text-white rounded-lg flex items-center justify-center font-bold mr-2">S</span>
                        <span class="text-sm text-gray-700">Sakit</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-8 h-8 bg-blue-500 text-white rounded-lg flex items-center justify-center font-bold mr-2">I</span>
                        <span class="text-sm text-gray-700">Izin</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-8 h-8 bg-red-500 text-white rounded-lg flex items-center justify-center font-bold mr-2">A</span>
                        <span class="text-sm text-gray-700">Alpha</span>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex gap-2 mb-4">
                    <button type="button" onclick="setAllStatus('H')" class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition">
                        Semua Hadir
                    </button>
                    <button type="button" onclick="setAllStatus('S')" class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 transition">
                        Semua Sakit
                    </button>
                    <button type="button" onclick="setAllStatus('I')" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition">
                        Semua Izin
                    </button>
                    <button type="button" onclick="setAllStatus('A')" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                        Semua Alpha
                    </button>
                </div>
                
                <!-- Siswa List -->
                <div class="max-h-96 overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($siswa_list)): ?>
                                <?php $no = 1; foreach ($siswa_list as $siswa): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo $no++; ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo $siswa->nis; ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $siswa->nama; ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex justify-center gap-2">
                                            <input type="radio" name="absensi[<?php echo $siswa->id; ?>]" value="H" id="h_<?php echo $siswa->id; ?>" checked class="hidden">
                                            <label for="h_<?php echo $siswa->id; ?>" class="status-btn w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center font-bold cursor-pointer hover:bg-green-600 transition">H</label>
                                            
                                            <input type="radio" name="absensi[<?php echo $siswa->id; ?>]" value="S" id="s_<?php echo $siswa->id; ?>" class="hidden">
                                            <label for="s_<?php echo $siswa->id; ?>" class="status-btn w-10 h-10 bg-gray-200 text-gray-600 rounded-lg flex items-center justify-center font-bold cursor-pointer hover:bg-yellow-500 hover:text-white transition">S</label>
                                            
                                            <input type="radio" name="absensi[<?php echo $siswa->id; ?>]" value="I" id="i_<?php echo $siswa->id; ?>" class="hidden">
                                            <label for="i_<?php echo $siswa->id; ?>" class="status-btn w-10 h-10 bg-gray-200 text-gray-600 rounded-lg flex items-center justify-center font-bold cursor-pointer hover:bg-blue-500 hover:text-white transition">I</label>
                                            
                                            <input type="radio" name="absensi[<?php echo $siswa->id; ?>]" value="A" id="a_<?php echo $siswa->id; ?>" class="hidden">
                                            <label for="a_<?php echo $siswa->id; ?>" class="status-btn w-10 h-10 bg-gray-200 text-gray-600 rounded-lg flex items-center justify-center font-bold cursor-pointer hover:bg-red-500 hover:text-white transition">A</label>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        Tidak ada siswa di kelas ini
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Submit Button -->
    <div class="flex justify-end gap-4">
        <a href="<?php echo site_url('guru/dashboard'); ?>" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            Batal
        </a>
        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-save mr-2"></i> Simpan Jurnal
        </button>
    </div>
</form>

<script>
    // Update button styles when radio is selected
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const siswaId = this.name.match(/\[(\d+)\]/)[1];
            const status = this.value;
            
            // Reset all buttons for this student
            document.querySelectorAll(`input[name="absensi[${siswaId}]"]`).forEach(r => {
                const label = document.querySelector(`label[for="${r.id}"]`);
                label.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-blue-500', 'bg-red-500', 'text-white');
                label.classList.add('bg-gray-200', 'text-gray-600');
            });
            
            // Highlight selected button
            const label = document.querySelector(`label[for="${this.id}"]`);
            label.classList.remove('bg-gray-200', 'text-gray-600');
            label.classList.add('text-white');
            
            switch(status) {
                case 'H': label.classList.add('bg-green-500'); break;
                case 'S': label.classList.add('bg-yellow-500'); break;
                case 'I': label.classList.add('bg-blue-500'); break;
                case 'A': label.classList.add('bg-red-500'); break;
            }
        });
    });
    
    // Set all status function
    function setAllStatus(status) {
        document.querySelectorAll('input[type="radio"][value="' + status + '"]').forEach(radio => {
            radio.checked = true;
            radio.dispatchEvent(new Event('change'));
        });
    }
</script>
