    </div><!-- End main flex container -->
    
    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <!-- Global Scripts -->
    <script>
        // Toggle Sidebar (Mobile)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
        
        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });
        
        // Toast Notification Function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
            
            toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform transition-all duration-300 translate-x-full`;
            toast.innerHTML = `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>${message}</span>
            `;
            
            document.getElementById('toast-container').appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }
        
        // Show flash messages if any
        <?php if ($message = $this->session->flashdata('success')): ?>
            showToast('<?php echo addslashes($message); ?>', 'success');
        <?php endif; ?>
        
        <?php if ($message = $this->session->flashdata('error')): ?>
            showToast('<?php echo addslashes($message); ?>', 'error');
        <?php endif; ?>
        
        <?php if ($message = $this->session->flashdata('warning')): ?>
            showToast('<?php echo addslashes($message); ?>', 'warning');
        <?php endif; ?>
        
        <?php if ($message = $this->session->flashdata('info')): ?>
            showToast('<?php echo addslashes($message); ?>', 'info');
        <?php endif; ?>
        
        // Confirm Delete
        function confirmDelete(url, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            if (confirm(message)) {
                window.location.href = url;
            }
        }
        
        // Loading Indicator
        function showLoading() {
            const loading = document.createElement('div');
            loading.id = 'loading-overlay';
            loading.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            loading.innerHTML = `
                <div class="bg-white p-6 rounded-lg shadow-xl">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-700">Memproses...</p>
                </div>
            `;
            document.body.appendChild(loading);
        }
        
        function hideLoading() {
            const loading = document.getElementById('loading-overlay');
            if (loading) {
                loading.remove();
            }
        }
    </script>
    
    <?php if (isset($extra_js)) echo $extra_js; ?>
    
</body>
</html>
