<?php
set_title('Hasil MCU');
# ============================================================
# AWAL PERIKSA, SAMPLING, DAN LIST PEMERIKSAAN
# ============================================================
include 'hasil_pemeriksaan-mcu-awal_pemeriksaan.php';

echo "<h2 class='tengah f16 mt4 bold'>HASIL PEMERIKSAAN</h2>";

# ============================================================
# HASIL FROM PASIEN
# ============================================================
include 'hasil_pemeriksaan-mcu-from_pasien.php';

# ============================================================
# PEMFIS AWAL
# ============================================================
include 'hasil_pemeriksaan-mcu-pemfis.php';

# ============================================================
# MATA 
# ============================================================
include 'hasil_pemeriksaan-mcu-mata.php';

# ============================================================
# GIGI 
# ============================================================
include 'hasil_pemeriksaan-mcu-gigi.php';

# ============================================================
# PEMFIS DOKTER
# ============================================================
include 'hasil_pemeriksaan-mcu-pemfis_dokter.php';

# ============================================================
# PEMFIS DOKTER
# ============================================================
include 'hasil_pemeriksaan-mcu-kesimpulan.php';

# ============================================================
# PEM.PENUNJANG
# ============================================================
include 'hasil_pemeriksaan-mcu-kesimpulan_penunjang.php';

include 'include/arr_kesimpulan.php';
$belum_ada = '<span class="red miring">belum diverifikasi</span>';
$hasil_at_db_show = $hasil_at_db['hasil'] ? $arr_kesimpulan[$hasil_at_db['hasil']] : $belum_ada;
blok_hasil('KESIMPULAN', $hasil_at_db_show);
