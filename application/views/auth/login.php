<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi RFID</title>
    
    <!-- Tailwind CSS CDN for initial development -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-center">
                <div class="w-20 h-20 bg-white rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                    <?php if (!empty($sekolah->logo) && file_exists('./assets/uploads/logo/' . $sekolah->logo)): ?>
                        <img src="<?php echo base_url('assets/uploads/logo/' . htmlspecialchars($sekolah->logo)); ?>" alt="Logo Sekolah" class="w-full h-full object-contain p-2">
                    <?php else: ?>
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    <?php endif; ?>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2"><?php echo !empty($sekolah->nama_sekolah) ? htmlspecialchars($sekolah->nama_sekolah) : 'Sistem Absensi RFID'; ?></h1>
                <p class="text-blue-100">Silakan login untuk melanjutkan</p>
            </div>
            
            <!-- Form -->
            <div class="p-8">
                
                <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm"><?php echo $this->session->flashdata('error'); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($this->session->flashdata('success')): ?>
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm"><?php echo $this->session->flashdata('success'); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <form action="<?php echo site_url('auth/do_login'); ?>" method="POST" class="space-y-6">
                    
                    <!-- CSRF Token -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" name="username" id="username" required 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                placeholder="Masukkan username">
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" required 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                placeholder="Masukkan password">
                        </div>
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat Saya</label>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition duration-200 hover:scale-[1.02]">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </span>
                    </button>
                </form>
                
                <!-- Footer Info -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">Default Password: <span class="font-semibold text-blue-600">password</span></p>
                </div>
            </div>
        </div>
        
        <!-- Additional Info -->
        <div class="mt-6 text-center text-sm text-gray-600">
            <p class="mb-2">Demo Accounts:</p>
            <div class="bg-white rounded-lg p-4 shadow-md space-y-2">
                <p><span class="font-semibold">Admin:</span> admin / password</p>
                <p><span class="font-semibold">Guru:</span> guru1 / password</p>
                <p><span class="font-semibold">Wali Kelas:</span> guru2 / password</p>
                <p><span class="font-semibold">BK:</span> bk1 / password</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>&copy; <?php echo date('Y'); ?> <?php echo !empty($sekolah->nama_sekolah) ? htmlspecialchars($sekolah->nama_sekolah) : 'Sistem Absensi RFID'; ?>. All rights reserved.</p>
        </div>
    </div>
    
</body>
</html>
