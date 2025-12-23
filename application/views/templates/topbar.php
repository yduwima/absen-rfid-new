<?php
$nama = $this->session->userdata('nama');
$role = $this->session->userdata('role');
$foto = $this->session->userdata('foto');

// Map role ke bahasa Indonesia
$role_name = array(
    'admin' => 'Administrator',
    'guru' => 'Guru',
    'walikelas' => 'Wali Kelas',
    'piket' => 'Guru Piket',
    'bk' => 'Guru BK'
);
?>

<!-- Top Bar -->
<div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
    
    <!-- Left Side -->
    <div class="flex items-center space-x-4">
        <!-- Mobile Menu Toggle -->
        <button id="sidebar-toggle" onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <!-- Page Title -->
        <h2 class="text-xl font-semibold text-gray-800">
            <?php echo isset($page_title) ? $page_title : 'Dashboard'; ?>
        </h2>
    </div>
    
    <!-- Right Side -->
    <div class="flex items-center space-x-4">
        
        <!-- Clock -->
        <div class="hidden md:flex items-center space-x-2 text-gray-600">
            <i class="far fa-clock"></i>
            <span id="current-time" class="text-sm font-medium"></span>
        </div>
        
        <!-- Notifications -->
        <div class="relative">
            <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
        </div>
        
        <!-- User Profile Dropdown -->
        <div class="relative group">
            <button class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg transition">
                <?php if ($foto && file_exists('./assets/uploads/' . $foto)): ?>
                    <img src="<?php echo base_url('assets/uploads/' . $foto); ?>" alt="Profile" class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                <?php else: ?>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold">
                        <?php echo strtoupper(substr($nama, 0, 1)); ?>
                    </div>
                <?php endif; ?>
                
                <div class="hidden md:block text-left">
                    <p class="text-sm font-semibold text-gray-700"><?php echo $nama; ?></p>
                    <p class="text-xs text-gray-500"><?php echo $role_name[$role]; ?></p>
                </div>
                
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <!-- Dropdown Menu -->
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-20">
                <div class="py-2">
                    <?php if ($role != 'admin'): ?>
                    <a href="<?php echo site_url(strtolower($role).'/profile'); ?>" class="flex items-center space-x-3 px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                        <i class="fas fa-user-circle w-5"></i>
                        <span>Profile Saya</span>
                    </a>
                    <hr class="my-2">
                    <?php endif; ?>
                    
                    <a href="<?php echo site_url('auth/logout'); ?>" class="flex items-center space-x-3 px-4 py-2 text-red-600 hover:bg-red-50 transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update Clock
    function updateClock() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        document.getElementById('current-time').textContent = now.toLocaleDateString('id-ID', options);
    }
    
    updateClock();
    setInterval(updateClock, 1000);
</script>
