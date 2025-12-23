<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Panggilan BK</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 20px;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* Kop Surat */
        .letterhead {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .letterhead-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
        }
        
        .letterhead-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .letterhead h2 {
            margin: 5px 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .letterhead p {
            margin: 2px 0;
            font-size: 12px;
        }
        
        /* Content */
        .letter-content {
            margin-top: 30px;
        }
        
        .letter-number {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .letter-to {
            margin-bottom: 20px;
        }
        
        .letter-to p {
            margin: 3px 0;
        }
        
        .letter-body {
            text-align: justify;
            line-height: 1.8;
        }
        
        .letter-body p {
            margin: 15px 0;
            text-indent: 50px;
        }
        
        .letter-details {
            margin: 20px 0;
            margin-left: 50px;
        }
        
        .letter-details table {
            width: 100%;
            max-width: 500px;
        }
        
        .letter-details td {
            padding: 5px 0;
        }
        
        .letter-details td:first-child {
            width: 150px;
        }
        
        .letter-signature {
            margin-top: 50px;
            text-align: right;
        }
        
        .letter-signature p {
            margin: 5px 0;
        }
        
        .signature-space {
            height: 60px;
        }
        
        .underline {
            text-decoration: underline;
            font-weight: bold;
        }
        
        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        .back-button {
            position: fixed;
            top: 20px;
            right: 150px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            text-decoration: none;
            display: inline-block;
        }
        
        .back-button:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Cetak Surat
    </button>
    
    <!-- Back Button -->
    <a href="<?php echo site_url('bk/surat'); ?>" class="back-button no-print">
        ← Kembali
    </a>
    
    <div class="container">
        <!-- Kop Surat -->
        <div class="letterhead">
            <?php if (!empty($sekolah->logo) && file_exists('./assets/uploads/logo/' . $sekolah->logo)): ?>
                <div class="letterhead-logo">
                    <img src="<?php echo base_url('assets/uploads/logo/' . $sekolah->logo); ?>" alt="Logo">
                </div>
            <?php endif; ?>
            
            <h2><?php echo strtoupper(htmlspecialchars($sekolah->nama_sekolah ?? 'NAMA SEKOLAH')); ?></h2>
            <p><?php echo htmlspecialchars($sekolah->alamat_sekolah ?? 'Alamat Sekolah'); ?></p>
            <p>Telp: <?php echo htmlspecialchars($sekolah->telepon_sekolah ?? '-'); ?> | Email: <?php echo htmlspecialchars($sekolah->email_sekolah ?? '-'); ?></p>
        </div>
        
        <!-- Nomor Surat -->
        <div class="letter-number">
            <p style="margin: 0;">Nomor: <?php echo htmlspecialchars($surat->nomor_surat); ?></p>
            <p style="margin: 0;"><?php echo date('d F Y', strtotime($surat->tanggal_surat)); ?></p>
        </div>
        
        <!-- Lampiran & Hal -->
        <div class="letter-to">
            <p>Kepada Yth,</p>
            <p><strong>Orang Tua/Wali Siswa</strong></p>
            <p><strong><?php echo htmlspecialchars($siswa->nama_lengkap); ?></strong></p>
            <p>Kelas: <strong><?php echo htmlspecialchars($siswa->nama_kelas ?? '-'); ?></strong></p>
            <p>di -</p>
            <p style="margin-left: 30px;">Tempat</p>
        </div>
        
        <!-- Salam Pembuka -->
        <div class="letter-body">
            <p style="text-indent: 0;"><em>Assalamu'alaikum Wr. Wb.</em></p>
            <p style="text-indent: 0;">Dengan hormat,</p>
            
            <!-- Body -->
            <p>Bersama surat ini kami sampaikan bahwa putra/putri Bapak/Ibu yang bernama <strong><?php echo htmlspecialchars($siswa->nama_lengkap); ?></strong> dari kelas <strong><?php echo htmlspecialchars($siswa->nama_kelas ?? '-'); ?></strong> perlu mendapatkan perhatian khusus terkait dengan:</p>
            
            <div class="letter-details">
                <table>
                    <tr>
                        <td><strong>Perihal</strong></td>
                        <td>: <?php echo htmlspecialchars($surat->perihal); ?></td>
                    </tr>
                </table>
            </div>
            
            <p>Sehubungan dengan hal tersebut, kami mengharapkan kehadiran Bapak/Ibu untuk bertemu dengan Bimbingan Konseling (BK) sekolah kami pada:</p>
            
            <div class="letter-details">
                <table>
                    <tr>
                        <td><strong>Hari, Tanggal</strong></td>
                        <td>: <?php echo htmlspecialchars($surat->hari); ?>, <?php echo date('d F Y', strtotime($surat->tanggal_panggilan)); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Waktu</strong></td>
                        <td>: <?php echo htmlspecialchars($surat->waktu_panggilan); ?> WIB</td>
                    </tr>
                    <tr>
                        <td><strong>Tempat</strong></td>
                        <td>: Ruang BK <?php echo htmlspecialchars($sekolah->nama_sekolah ?? ''); ?></td>
                    </tr>
                </table>
            </div>
            
            <p>Demikian surat panggilan ini kami sampaikan. Atas perhatian dan kehadirannya, kami ucapkan terima kasih.</p>
            
            <p style="text-indent: 0;"><em>Wassalamu'alaikum Wr. Wb.</em></p>
        </div>
        
        <!-- Tanda Tangan -->
        <div class="letter-signature">
            <p>Koordinator BK,</p>
            <div class="signature-space"></div>
            <p class="underline"><?php echo htmlspecialchars($sekolah->nama_kepala_sekolah ?? '(Nama BK)'); ?></p>
            <p>NIP. <?php echo htmlspecialchars($sekolah->nip_kepala_sekolah ?? '-'); ?></p>
        </div>
    </div>
    
    <script>
        // Auto print dialog (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
