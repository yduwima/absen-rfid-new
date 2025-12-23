<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title; ?></h1>
    </div>
    
    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
            <button onclick="printReport()" class="btn btn-sm btn-success no-print">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </div>
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-4">
                    <label>Bulan</label>
                    <input type="month" name="bulan" class="form-control" value="<?php echo $bulan; ?>" required>
                </div>
                <div class="col-md-4">
                    <label>Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($kelas_list as $kelas): ?>
                            <option value="<?php echo $kelas->id; ?>" <?php echo ($kelas_id == $kelas->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kelas->nama_kelas); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Report Content -->
    <?php if (!empty($laporan)): ?>
    <div id="printable-area">
        <!-- Kop Surat untuk Print -->
        <div class="report-header" style="display: none;">
            <table style="width: 100%; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
                <tr>
                    <td style="width: 100px; text-align: center; vertical-align: top;">
                        <?php if (!empty($sekolah->logo) && file_exists('./assets/uploads/logo/' . $sekolah->logo)): ?>
                            <img src="<?php echo base_url('assets/uploads/logo/' . $sekolah->logo); ?>" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;">
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <h2 style="margin: 5px 0; font-size: 18px; font-weight: bold; text-transform: uppercase;">
                            <?php echo strtoupper(htmlspecialchars($sekolah->nama_sekolah ?? 'NAMA SEKOLAH')); ?>
                        </h2>
                        <p style="margin: 2px 0; font-size: 12px;"><?php echo htmlspecialchars($sekolah->alamat_sekolah ?? ''); ?></p>
                        <p style="margin: 2px 0; font-size: 12px;">
                            Telp: <?php echo htmlspecialchars($sekolah->telepon_sekolah ?? '-'); ?> | 
                            Email: <?php echo htmlspecialchars($sekolah->email_sekolah ?? '-'); ?>
                        </p>
                    </td>
                    <td style="width: 100px;"></td>
                </tr>
            </table>
            
            <h3 style="text-align: center; margin: 20px 0;">LAPORAN ABSENSI SISWA</h3>
            <p style="text-align: center; margin-bottom: 20px;">
                Periode: <?php echo date('F Y', strtotime($bulan . '-01')); ?>
            </p>
        </div>
        
        <!-- Report Table -->
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="width: 100%;">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Hadir</th>
                                <th>Sakit</th>
                                <th>Izin</th>
                                <th>Alpha</th>
                                <th>Total</th>
                                <th>Keterlambatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($laporan as $row): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row->nis); ?></td>
                                <td><?php echo htmlspecialchars($row->nama_lengkap); ?></td>
                                <td class="text-center"><?php echo $row->hadir ?? 0; ?></td>
                                <td class="text-center"><?php echo $row->sakit ?? 0; ?></td>
                                <td class="text-center"><?php echo $row->izin ?? 0; ?></td>
                                <td class="text-center"><?php echo $row->alpha ?? 0; ?></td>
                                <td class="text-center font-weight-bold"><?php echo ($row->hadir ?? 0) + ($row->sakit ?? 0) + ($row->izin ?? 0) + ($row->alpha ?? 0); ?></td>
                                <td class="text-center"><?php echo $row->total_terlambat ?? 0; ?> kali</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Silakan pilih filter untuk menampilkan laporan
    </div>
    <?php endif; ?>
</div>

<style>
@media print {
    .no-print, .sidebar, .topbar, .footer, .card-header { display: none !important; }
    .report-header { display: block !important; }
    body { margin: 0; padding: 20px; }
    .card { border: none; box-shadow: none; }
    table { font-size: 11px; }
}
</style>

<script>
function printReport() {
    window.print();
}
</script>
