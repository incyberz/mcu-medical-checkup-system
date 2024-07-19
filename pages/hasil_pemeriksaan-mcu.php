<?php
set_title('Hasil MCU');
$tidak_ada = '<i class=hasil>--tidak ada--</i>';
include 'hasil_pemeriksaan-styles.php';
include 'include/arr_pemeriksaan.php';


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
      <div class='bg-white p4 mt2' style='box-shadow: 0 0 8px black; padding: 1cm; width: 21cm; height-ZZZ: 297mm' id=kertas>
        <div>$img_header_logo</div>
        <div class='border-bottom mb2 pb2 f12 mt1'>Tambun Business Park Blok C12 Tambun - Bekasi<br>Telp.(021) 29487893</div>
        
        <h3 class='p1 f16 bold'>HASIL MEDICAL CHECKUP</h3>

        $div_header
        ";



# ============================================================
# HASIL RIWAYAT PENYAKIT
# ============================================================
$dt = explode(',', $pasien['riwayat_penyakit']);
$arr = [
  'RIWAYAT PENYAKIT' => 'riwayat',
  'RIWAYAT PENGOBATAN' => 'pengobatan',
  'RIWAYAT PENYAKIT AYAH' => 'ayah',
  'RIWAYAT PENYAKIT IBU' => 'ibu'
];
$riw = [];
foreach ($arr as $k1 => $v1) if ($v1) $riw[$v1] = '';

foreach ($dt as $k2 => $v2) {
  $t = explode('--', $v2);
  if ($v2) $riw[$t[0]] .= "<li>$v2</li>";
}

foreach ($arr as $k1 => $v1) {

  // if (!$riw[$v1]) continue;
  $riw[$v1] = $riw[$v1] ? "<ul class='hasil m0'>$riw[$v1]</ul>" : $tidak_ada;
  blok_hasil($k1, $riw[$v1]);
}


# ============================================================
# HASIL GEJALA PENYAKIT
# ============================================================
$dt = explode(',', $pasien['gejala_penyakit']);
$str_hasil = '';
foreach ($dt as $k => $v)  if ($v) $str_hasil .= "<li>$v</li>";

$str_hasil = $str_hasil ? "<ul class='hasil m0'>$str_hasil</ul>" : $tidak_ada;
blok_hasil('GEJALA PENYAKIT', $str_hasil);



# ============================================================
# HASIL GAYA HIDUP
# ============================================================
$dt = explode(',', $pasien['gaya_hidup']);
$str_hasil = '';
foreach ($dt as $k => $v)  if ($v) $str_hasil .= "<li>$v</li>";

$str_hasil = $str_hasil ? "<ul class='hasil m0'>$str_hasil</ul>" : $tidak_ada;
blok_hasil('GAYA HIDUP', $str_hasil);



# ============================================================
# HASIL KELUHAN
# ============================================================
$str_hasil = strlen($pasien['keluhan']) > 3 ? "<span class='hasil m0'>$pasien[keluhan]</span>" : $tidak_ada;
blok_hasil('KELUHAN', $str_hasil);

# ============================================================
# AWAL PEMERIKSAAN
# ============================================================
blok_hasil('AWAL PEMERIKSAAN', $hasil['awal_periksa'], 1);

# ============================================================
# SAMPLING
# ============================================================
$li = '';
foreach ($arr_sampel_by as $key => $value) {
  $by = $arr_user[$value];
  $at = $arr_sampel_tanggal[$key];
  $li .= "<li>$key by $by at $at </li>";
}
$str_hasil = $li ? "<ul class='hasil m0'>$li</ul>" : $tidak_ada;
blok_hasil('SAMPLING', $str_hasil);

# ============================================================
# PEMERIKSAAN
# ============================================================
$li = '';
foreach ($arr_pemeriksaan_by as $key => $value) {
  $by = $arr_user[$value];
  $at = $arr_pemeriksaan_tanggal[$key];
  $li .= "<li>$arr_pemeriksaan[$key] by $by at $at </li>";
}
$str_hasil = $li ? "<ul class='hasil m0'>$li</ul>" : $tidak_ada;
blok_hasil('PEMERIKSAAN', $str_hasil);


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