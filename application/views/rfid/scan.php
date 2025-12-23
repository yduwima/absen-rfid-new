<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Sistem Absensi RFID</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        @keyframes pulse-slow {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .pulse-slow {
            animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes slideInUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .slide-in-up {
            animation: slideInUp 0.5s ease-out;
        }
        
        @keyframes checkmark {
            0% {
                stroke-dashoffset: 100;
            }
            100% {
                stroke-dashoffset: 0;
            }
        }
        
        .checkmark {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.8s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 min-h-screen overflow-hidden">
    
    <!-- Header -->
    <header class="bg-black bg-opacity-30 backdrop-blur-sm border-b border-white border-opacity-20">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-id-card text-blue-600 text-2xl"></i>
                    </div>
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Sistem Absensi RFID</h1>
                        <p class="text-sm text-blue-200">SMK Negeri 1 Contoh</p>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div id="current-date" class="text-lg font-semibold"></div>
                    <div id="current-time" class="text-3xl font-bold"></div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Left Side - Scan Area -->
            <div class="space-y-6">
                
                <!-- Scan Card -->
                <div class="bg-white rounded-3xl shadow-2xl p-8 text-center">
                    <div class="mb-6">
                        <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-4 pulse-slow shadow-lg">
                            <i class="fas fa-wifi text-white text-5xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Tap Kartu RFID Anda</h2>
                        <p class="text-gray-600">Dekatkan kartu ke reader untuk absensi</p>
                    </div>
                    
                    <!-- Status Display -->
                    <div id="status-display" class="hidden mb-6 slide-in-up">
                        <!-- Will be populated by JavaScript -->
                    </div>
                    
                    <!-- Manual Input (for testing) -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-3">Testing Mode:</p>
                        <form id="manual-scan-form" class="flex space-x-2">
                            <input type="text" id="uid-input" placeholder="Masukkan UID RFID" 
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                Scan
                            </button>
                        </form>
                        <p class="text-xs text-gray-400 mt-2">Contoh UID: RFID-SISWA-001, RFID-GURU-001</p>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Siswa Hadir</p>
                                <p id="siswa-count" class="text-3xl font-bold text-blue-600">0</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-4">
                                <i class="fas fa-user-graduate text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Guru Hadir</p>
                                <p id="guru-count" class="text-3xl font-bold text-green-600">0</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-4">
                                <i class="fas fa-chalkboard-teacher text-green-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Right Side - Attendance List -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-list-ul mr-3"></i>
                        Absensi Hari Ini
                    </h3>
                </div>
                
                <div id="attendance-list" class="p-6 max-h-[600px] overflow-y-auto">
                    <!-- Will be populated by JavaScript -->
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-inbox text-6xl mb-4 opacity-50"></i>
                        <p>Belum ada data absensi</p>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    
    <!-- Success/Error Modal -->
    <div id="notification-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div id="modal-content" class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-0 transition-transform duration-300">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
    
    <!-- Audio for notifications -->
    <audio id="success-sound" preload="auto">
        <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBi2Iz/LTgTIGHm+98+KVRAsPVank6KBRFw9Mnubyu28gBS+P0vPPfC4FI3fC8NmSQAoUYrno6KBSFhBHnN/yuG0fBTKS1fPLeCkFH22/9O2QOwgVb7jo56VcFRJMm97wt28fBS6Iz/LWfywFInK98Ny" type="audio/wav">
    </audio>
    
    <script>
        // Global variables
        let lastUpdate = 0;
        
        // Update clock
        function updateClock() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            
            $('#current-date').text(now.toLocaleDateString('id-ID', dateOptions));
            $('#current-time').text(timeString);
        }
        
        // Load attendance data
        function loadAttendance() {
            $.ajax({
                url: '<?php echo site_url('rfid/scan/get_today'); ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.data.length > 0) {
                        displayAttendance(response.data);
                        updateStatistics(response.data);
                    }
                }
            });
        }
        
        // Display attendance list
        function displayAttendance(data) {
            let html = '';
            
            data.forEach(function(item) {
                const foto = item.foto ? '<?php echo base_url('assets/uploads/'); ?>' + item.foto : '';
                const initial = item.nama.charAt(0).toUpperCase();
                const badgeColor = item.user_type === 'siswa' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
                const statusBadge = item.status_masuk === 'Terlambat' ? 
                    '<span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Terlambat (' + item.keterlambatan + ' mnt)</span>' :
                    '<span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Tepat Waktu</span>';
                
                html += `
                    <div class="flex items-center space-x-4 p-4 hover:bg-gray-50 rounded-lg transition border-b border-gray-100 slide-in-up">
                        <div class="flex-shrink-0">
                            ${foto ? 
                                '<img src="' + foto + '" alt="' + item.nama + '" class="w-12 h-12 rounded-full object-cover border-2 border-blue-500">' :
                                '<div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">' + initial + '</div>'
                            }
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">${item.nama}</p>
                            <p class="text-xs text-gray-500">${item.kelas_jabatan || '-'}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-1 text-xs rounded-full ${badgeColor} mb-1">
                                ${item.user_type === 'siswa' ? 'Siswa' : 'Guru'}
                            </span>
                            <p class="text-xs text-gray-600">
                                <i class="far fa-clock mr-1"></i>${item.jam_masuk ? item.jam_masuk.substr(0, 5) : '-'}
                                ${item.jam_pulang ? ' / ' + item.jam_pulang.substr(0, 5) : ''}
                            </p>
                            ${item.jam_masuk && item.status_masuk ? statusBadge : ''}
                        </div>
                    </div>
                `;
            });
            
            $('#attendance-list').html(html);
        }
        
        // Update statistics
        function updateStatistics(data) {
            const siswaCount = data.filter(item => item.user_type === 'siswa').length;
            const guruCount = data.filter(item => item.user_type === 'guru').length;
            
            $('#siswa-count').text(siswaCount);
            $('#guru-count').text(guruCount);
        }
        
        // Process scan
        function processScan(uid) {
            $.ajax({
                url: '<?php echo site_url('rfid/scan/process'); ?>',
                method: 'POST',
                data: { 
                    uid: uid,
                    <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    showNotification(response);
                    loadAttendance(); // Reload attendance list
                    
                    // Play sound
                    if (response.sound === 'success') {
                        $('#success-sound')[0].play();
                    }
                },
                error: function() {
                    showNotification({
                        status: 'error',
                        message: 'Terjadi kesalahan koneksi'
                    });
                }
            });
        }
        
        // Show notification modal
        function showNotification(response) {
            let icon, color, title;
            
            if (response.status === 'success') {
                icon = '<i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>';
                color = 'green';
                title = 'Berhasil!';
            } else if (response.status === 'error') {
                icon = '<i class="fas fa-times-circle text-6xl text-red-500 mb-4"></i>';
                color = 'red';
                title = 'Gagal!';
            } else {
                icon = '<i class="fas fa-info-circle text-6xl text-blue-500 mb-4"></i>';
                color = 'blue';
                title = 'Informasi';
            }
            
            let html = '<div class="text-center">' + icon;
            html += '<h3 class="text-2xl font-bold text-gray-800 mb-2">' + title + '</h3>';
            html += '<p class="text-gray-600 mb-4">' + response.message + '</p>';
            
            if (response.data) {
                const foto = response.data.foto ? '<?php echo base_url('assets/uploads/'); ?>' + response.data.foto : '';
                const initial = response.data.nama ? response.data.nama.charAt(0).toUpperCase() : '?';
                
                html += '<div class="bg-gray-50 rounded-lg p-6 mb-4">';
                html += '<div class="flex items-center justify-center mb-4">';
                html += foto ? 
                    '<img src="' + foto + '" alt="' + response.data.nama + '" class="w-24 h-24 rounded-full object-cover border-4 border-' + color + '-500">' :
                    '<div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-4xl border-4 border-' + color + '-500">' + initial + '</div>';
                html += '</div>';
                html += '<p class="text-xl font-bold text-gray-800">' + response.data.nama + '</p>';
                html += '<p class="text-sm text-gray-600">' + (response.data.kelas_jabatan || '-') + '</p>';
                
                if (response.data.type === 'masuk') {
                    html += '<p class="text-lg font-semibold text-gray-700 mt-3">Jam Masuk: ' + (response.data.jam_masuk || '-') + '</p>';
                    if (response.data.status === 'Terlambat') {
                        html += '<p class="text-sm text-red-600 mt-1">Terlambat ' + response.data.keterlambatan + ' menit</p>';
                    }
                } else if (response.data.type === 'pulang') {
                    html += '<p class="text-lg font-semibold text-gray-700 mt-3">Jam Pulang: ' + (response.data.jam_pulang || '-') + '</p>';
                }
                
                html += '</div>';
            }
            
            html += '</div>';
            
            $('#modal-content').html(html);
            $('#notification-modal').removeClass('hidden');
            $('#modal-content').removeClass('scale-0').addClass('scale-100');
            
            // Auto close after 3 seconds
            setTimeout(function() {
                closeNotification();
            }, 3000);
        }
        
        // Close notification
        function closeNotification() {
            $('#modal-content').removeClass('scale-100').addClass('scale-0');
            setTimeout(function() {
                $('#notification-modal').addClass('hidden');
            }, 300);
        }
        
        // Manual scan form
        $('#manual-scan-form').on('submit', function(e) {
            e.preventDefault();
            const uid = $('#uid-input').val().trim();
            
            if (uid) {
                processScan(uid);
                $('#uid-input').val('');
            }
        });
        
        // Close modal on click outside
        $('#notification-modal').on('click', function(e) {
            if (e.target === this) {
                closeNotification();
            }
        });
        
        // Initialize
        $(document).ready(function() {
            updateClock();
            setInterval(updateClock, 1000);
            
            loadAttendance();
            setInterval(loadAttendance, 5000); // Refresh every 5 seconds
        });
    </script>
    
</body>
</html>
