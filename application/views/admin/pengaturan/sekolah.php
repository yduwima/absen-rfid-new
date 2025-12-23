<!-- Main Content -->
<main class="flex-1 overflow-y-auto bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Pengaturan Sekolah</h1>
            <p class="text-gray-600">Kelola informasi dan data sekolah</p>
        </div>
        
        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p class="font-medium">Sukses!</p>
                <p><?php echo $this->session->flashdata('success'); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p class="font-medium">Error!</p>
                <p><?php echo $this->session->flashdata('error'); ?></p>
            </div>
        <?php endif; ?>
        
        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            
            <form action="<?php echo site_url('admin/pengaturan/sekolah'); ?>" method="POST" enctype="multipart/form-data">
                <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                
                <!-- Logo Preview -->
                <div class="mb-6 text-center">
                    <div class="inline-block">
                        <img src="<?php echo base_url('assets/img/logo/' . ($pengaturan->logo_sekolah ?? 'logo.png')); ?>" 
                             alt="Logo Sekolah" 
                             class="h-32 w-32 object-contain mx-auto border-2 border-gray-200 rounded-lg p-2"
                             id="logo-preview">
                    </div>
                </div>
                
                <!-- Logo Upload -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Upload Logo Sekolah</label>
                    <input type="file" 
                           name="logo_sekolah" 
                           accept="image/jpeg,image/png,image/jpg"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           onchange="previewLogo(this)">
                    <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG (Max 2MB)</p>
                </div>
                
                <!-- Nama Sekolah -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Sekolah <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="nama_sekolah" 
                           value="<?php echo $pengaturan->nama_sekolah ?? 'SMK Negeri 1'; ?>"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: SMK Negeri 1 Kota">
                </div>
                
                <!-- Alamat Sekolah -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Alamat Sekolah <span class="text-red-500">*</span></label>
                    <textarea name="alamat_sekolah" 
                              rows="3"
                              required
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Alamat lengkap sekolah"><?php echo $pengaturan->alamat_sekolah ?? 'Jl. Contoh No. 1'; ?></textarea>
                </div>
                
                <!-- Grid for Kepala Sekolah and Contact -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nama Kepala Sekolah</label>
                        <input type="text" 
                               name="nama_kepala_sekolah" 
                               value="<?php echo $pengaturan->nama_kepala_sekolah ?? ''; ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Nama lengkap">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">NIP Kepala Sekolah</label>
                        <input type="text" 
                               name="nip_kepala_sekolah" 
                               value="<?php echo $pengaturan->nip_kepala_sekolah ?? ''; ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="NIP">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Telepon Sekolah</label>
                        <input type="text" 
                               name="telepon_sekolah" 
                               value="<?php echo $pengaturan->telepon_sekolah ?? ''; ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="(021) 12345678">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email Sekolah</label>
                        <input type="email" 
                               name="email_sekolah" 
                               value="<?php echo $pengaturan->email_sekolah ?? ''; ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="email@sekolah.sch.id">
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" 
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Reset
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
                
            </form>
            
        </div>
        
    </div>
</main>

<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logo-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
