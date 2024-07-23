<?php
$arr = [];
$arr[1] = ['Penanggung Jawab', 'dr. Mutiara Putri Camelia', 'Dokter', 'Dokter MCU'];
$arr[2] = ['No. Lab', 'MCU' . date('y', strtotime($pasien['date_created'])) . "-$id_pasien", 'No. RM', '-'];
$arr[3] = ['Tgl Pemeriksaan', hari_tanggal($arr_pemeriksaan_tanggal[$id_pemeriksaan], 0, 0, 1, 0, '-'), 'Nama Pasien', ucwords(strtolower($pasien['nama']))];
$arr[4] = ['Ruang / Poli', '-', 'Tanggal Lahir', hari_tanggal($pasien['tanggal_lahir'], 1, 0, 0)];
$arr[5] = ['No. KTP', $pasien['no_ktp'] ?? '-', 'Jenis Kelamin', ucwords(gender($pasien['gender']))];
$arr[6] = ['Alamat', $pasien['alamat'] ?? '-', null, null];

$div_header = '';
foreach ($arr as $key => $v) {
  $titik_dua = (!$v[2] || $v[2] == 'nbsp;') ? '' : ':';
  $div_header .= "
    <div class=kolom_header>$v[0]</div>
    <div>:</div>
    <div>$v[1]</div>
    <div>&nbsp;</div>
    <div class=kolom_header>$v[2]</div>
    <div>$titik_dua</div>
    <div>$v[3]</div>
  ";
}


# ============================================================
# DETAIL PEMERIKSAAN
# ============================================================
$s2 = "SELECT * FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=$id_pemeriksaan";
$q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
$detail_header = '
  <div class=detail_header>PEMERIKSAAN</div>
  <div class=detail_header>HASIL</div>
  <div class=detail_header>H/L</div>
  <div class=detail_header>SATUAN</div>
  <div class=detail_header>NILAI NORMAL</div>
  <div class=detail_header>CATATAN</div>
';
$detail = '';
while ($d2 = mysqli_fetch_assoc($q2)) {
  if (strtolower($d2['label']) == 'separator') continue;
  $id_detail = $d2['id'];
  $id_pemeriksaan = $d2['id_pemeriksaan'];

  $lo = $d2['normal_lo_l'];
  $hi = $d2['normal_hi_l'];

  if ($lo and $hi and $lo < $hi) {
    if ($d2['normal_lo_p'] and $d2['normal_hi_p']) {
      $nilai_normal = "
        L (" . floatval($d2['normal_lo_l']) . " - " . floatval($d2['normal_hi_l']) . "), 
        P (" . floatval($d2['normal_lo_p']) . " - " . floatval($d2['normal_hi_p']) . ") 
      ";
      if ($gender == 'p') {
        $lo = $d2['normal_lo_p'];
        $hi = $d2['normal_hi_p'];
      }
    } else {
      $nilai_normal =  floatval($d2['normal_lo_l']) . " - " . floatval($d2['normal_hi_l']);
    }
  } else {
    $nilai_normal = '<span class="red bold">invalid</span> ';
    if (!$lo) $nilai_normal .= "<br><span class=red>Nilai minimum batas normal masih kosong</span>";
    if (!$hi) $nilai_normal .= "<br><span class=red>Nilai maximum batas normal masih kosong</span>";
    if ($lo < $hi) $nilai_normal .= "<br><span class=red>Nilai minimum > nilai maksimum</span>";
  }

  $hasil = $arr_id_detail[$id_detail];
  $hl = '-';
  if ($hasil < $lo) $hl = '<span class="red bold">L</span>';
  if ($hasil > $hi) $hl = '<span class="red bold">H</span>';

  $detail .= "
    <div class=miring>$d2[label]</div>
    <div>$hasil</div>
    <div>$hl</div>
    <div class=miring>$d2[satuan]</div>
    <div>$nilai_normal</div>
    <div>-</div>
  ";
}


echo "
  <style>
    .kolom_header{font-weight: 600; font-styleZZZ: italic}
    .detail_header{font-weight: 600; margin-bottom: 5px; letter-spacing: 1px}
  </style>
  <div class='wadah gradasi-hijau tengah'>
    Preview Hasil Laboratorium
    <div class='flexy flex-center f12'>
      <div class='bg-white p4 mt2' style='box-shadow: 0 0 8px black; padding: 1cm; width: 21cm; height: 297mm'>
        <div>$img_header_logo</div>
        <div class='border-bottom mb2 pb2 f12 mt1'>Tambun Business Park Blok C12 Tambun - Bekasi<br>Telp.(021) 29487893</div>
        
        <h3 class='p1 f16 bold'>HASIL PEMERIKSAAN LABORATORIUM</h3>


        <div class='border-top border-bottom pt1 pb1 kiri f12'>
          <div style='display: grid; grid-template-columns: 20% 2% 40% 1% 20% 2% 20%'>
            $div_header
          </div>
        </div>

        <h4 class='kiri biru f14 mt4 mb2 bold' style='letter-spacing: 2px; color: #4cc'>$d[jenis_pemeriksaan]</h4>

        
        <div 
          class='f12 left border-bottom pb2' 
          style='display: grid; grid-template-columns: 25% 10% 8% 17% 25% auto'
        >
          $detail_header
          $detail
        </div>

        <div class='f12 left mt2 border-bottom pb2'>
          <div>Catatan:</div>
          <div>Hasil Ini harus di interpretasikan oleh dokter yang menangani untuk disesuaikan dengan klinisnya.</div>
          <style>#tb_note td{padding-right:10px}</style>
          <table class=mt2 id=tb_note>
            <tr><td>Note:</td><td>- H</td><td> : High</td></tr>
            <tr><td>&nbsp;</td><td>- L</td><td> : Low</td></tr>
          </table>
        </div>

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
  </div>
";
