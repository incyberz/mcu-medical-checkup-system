<?php
set_title('Hasil MCU');
$tidak_ada = '<i class=hasil>--tidak ada--</i>';
include 'hasil_pemeriksaan-styles.php';
include 'include/arr_pemeriksaan.php';
include 'include/arr_pemeriksaan_detail.php';


# ============================================================
# HASIL MEDICAL AT DB
# ============================================================
$hasil = [];
$arr_id_detail = [];
$arr_pemeriksaan_tanggal = [];
$arr_pemeriksaan_by = [];
$arr_sampel_tanggal = [];
$arr_sampel_by = [];
include 'pemeriksaan-hasil_at_db.php';


# ============================================================
# DIV HEADER
# ============================================================
$div_header = '';
include 'hasil_pemeriksaan-header.php';
include 'hasil_pemeriksaan-functions.php';

# ============================================================
# DETAIL PEMERIKSAAN
# ============================================================
// $s = "SELECT * FROM tb_pasien WHERE id=$id_pasien";
// $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
// $pasien = mysqli_fetch_assoc($q);
$s = "SELECT * FROM tb_pemeriksaan WHERE jenis='mcu'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));





echo "
  <div class='wadah gradasi-hijau tengah'>
    Preview Hasil Laboratorium
    <div class='flexy flex-center f12'>
      <div class='kertas bg-white p4 mt2' id=kertas__mcu>
        <div>$img_header_logo</div>
        <div class='border-bottom mb2 pb2 f12 mt1'>Tambun Business Park Blok C12 Tambun - Bekasi<br>Telp.(021) 29487893</div>
        
        <h3 class='p1 f16 bold'>HASIL MEDICAL CHECKUP</h3>

        $div_header
        ";





# ============================================================
# HASIL FROM PASIEN
# ============================================================
include 'hasil_pemeriksaan-mcu-from_pasien.php';

# ============================================================
# AWAL PERIKSA, SAMPLING, DAN LIST PEMERIKSAAN
# ============================================================
include 'hasil_pemeriksaan-mcu-awal_pemeriksaan.php';

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
# HASIL FOOTER
# ============================================================
echo "
        <div class='mt2 kiri f11' style='margin-left:11cm'>
          <div>
            <span class='abu miring'>Printed at:</span> 
            Bekasi, 8 Juli 2024 10:24:34
          </div>
          <div>
            <span class='abu miring'>From:</span> 
            Mutiara Medical System, https://mmc-clinic.com
          </div>
          <div>
            <span class='abu miring'>By:</span> 
            Hani Arisma Setyarum, S.Tr.Kes
          </div>
          <img src=tmp/qr.jpg style=height:3cm />
        </div>


      </div>

      
      </div>
      <button onclick=window.print()>Print</button>
  </div>
";
?>
<!-- <script>
  $(function() {
    function print() {
      $('#kertas').print();
    }
  })
</script> -->