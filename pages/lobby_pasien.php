<?php
$mode = $_GET['mode'] ?? 'detail';
set_title('Lobby Pasien');

# ============================================================
# NAVIGASI
# ============================================================
$s = "SELECT 
c.id as id_pemeriksaan, 
c.singkatan 
FROM tb_paket a 
JOIN tb_paket_detail b ON a.id=b.id_paket 
JOIN tb_pemeriksaan c ON b.id_pemeriksaan=c.id 
WHERE a.id=11 -- Karyawan basic";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$count_pemeriksaan = [];
$arr_singkatan = [];
while ($d = mysqli_fetch_assoc($q)) {
  $count_pemeriksaan[$d['id_pemeriksaan']] = 0;
  $arr_singkatan[$d['id_pemeriksaan']] = $d['singkatan'];
}

$s = "SELECT 
a.*,
a.id as id_pasien,
a.nama as nama_pasien,
b.nama as jenis_pasien,
(SELECT nama FROM tb_status_pasien WHERE status=a.status) status_pasien, 
(
  SELECT CONCAT(p.singkatan,' | ',q.perusahaan) FROM tb_paket p 
  JOIN tb_order q ON p.id=q.id_paket 
  JOIN tb_pasien r ON q.order_no=r.order_no 
  WHERE r.id=a.id) info_paket,
(
  SELECT p.status_bayar FROM tb_paket_custom p WHERE a.id_paket_custom=p.id
  ) status_bayar,
(
  SELECT p.status_bayar FROM tb_pembayaran p WHERE p.id_pasien=a.id
  ) status_bayar_corporate_mandiri, 
(
  SELECT arr_tanggal_by FROM tb_hasil_pemeriksaan p WHERE p.id_pasien=a.id
  ) arr_tanggal_by

FROM tb_pasien a 
JOIN tb_jenis_pasien b ON a.jenis=b.jenis 
WHERE (a.status is null or a.status <= 9) -- a.status >= 7 AND a.status <= 9 
ORDER BY a.nama 
";

// YANG BAYAR DI BLONG ZZZ

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_pasien_segera = mysqli_num_rows($q);
$div = '';
$nav = div_alert('info', "Tidak ada pasien di Lobby Pemeriksaan | <a href='?rekap_pemeriksaan'>Rekap Hasil Pemeriksaan</a>");
$jumlah_lobby = 0;
if (mysqli_num_rows($q)) {
  while ($d = mysqli_fetch_assoc($q)) {
    $jenis = strtolower($d['jenis']);
    // echo '<pre>';
    // var_dump($d['status_bayar_corporate_mandiri']);
    // echo '</pre>';
    if ($jenis == 'cor' and $d['status_bayar'] === null and $d['status_bayar_corporate_mandiri'] === null) {
      // corporate belum bayar
      continue;
    }
    $jumlah_lobby++;
    $status = $d['status'];
    $src = "assets/img/profile_na.jpg";
    if ($d['foto_profil']) {
      $src = "assets/img/pasien/$d[foto_profil]";
      $src_ajax = "../assets/img/pasien/$d[foto_profil]";
      if (!file_exists($src_ajax)) {
        $src = "assets/img/profile_missing.jpg";
      }
    }

    $href = "?tampil_pasien&id_pasien=$d[id]";

    if (strtolower($jenis) == 'cor') {
      $info_paket = "MCU-$d[id_pasien] | $d[info_paket]";
    } else {
      $info_paket = " $d[jenis_pasien] | MCU-$d[id_pasien]";
    }
    $status_show = $d['status_pasien'] ? "<span class='warna_status_$status'>$d[status_pasien]</span>" : '<i class="f14 abu">belum pemeriksaan</i>';


    # ============================================================
    # ARR TANGGAL BY HANDLER
    # ============================================================
    $r = explode('||', $d['arr_tanggal_by']);
    foreach ($r as $v) {
      if ($v) {
        $r2 = explode('=', $v);
        $id_pemeriksaan = $r2[0];
        $count_pemeriksaan[$id_pemeriksaan]++;
      }
    }

    if ($mode == 'detail') {
      $div .= "
        <div class='mb4'>
          <a class='upper tebal ' href='$href'>$jumlah_lobby. $d[nama_pasien] $img_next</a>
        </div>
      ";
    } else {
      $div .= "
        <div>
          <div class='f14 abu'>$jumlah_lobby</div>
          <div>
            <a href='$href'>
              <img src='$src' class='foto_profil border_status_$status ' />
            </a>
          </div>
          <div>
            <div><a class=upper href='$href'>$d[nama_pasien]</a></div>
            <div class=f12>$info_paket</div>
            <div class=f12>$status_show</div>
          </div>
        </div>
      ";
    }
  } // end while

  $div .= "
    <style>
      .border_status_7,.border_status_8 {border: solid 5px #f44}
      .border_status_9 {border: solid 5px #44f}
      .warna_status_7,.warna_status_8 {color:#f44}
      .warna_status_9 {color:#44f}
    </style>
  ";



  // echo '<pre>';
  // var_dump($count_pemeriksaan);
  // echo '</pre>';

  $item = "<div><a class='btn btn-success btn-sm' href='?cari_pasien'>ALL <span class='f30'>$jumlah_lobby</span></a></div>";
  foreach ($count_pemeriksaan as $id_pemeriksaan => $count) {
    $sisa = $jumlah_lobby - $count;

    $item .= !$sisa ? '' : "<div><a class='btn btn-success btn-sm' href='?cari_pasien&id_pemeriksaan=$id_pemeriksaan'>$arr_singkatan[$id_pemeriksaan] <span class='f30'>$sisa</span></a></div>";
  }
  $nav = "<div class='flexy mb4'>$item</div>";
}




# ============================================================
# FINAL ECHO LOBBY PASIEN
# ============================================================
$div = $mode == 'detail' ? $div : "<div class='flexy flex-center wadah gradasi-toska tengah'>$div</div>";
$lobby_pasien = "
  <div class='section-title' data-aos='fade'>
    <h2 id=judul>Lobby Pasien</h2>
  </div>

  $nav

  <div class='f14 biru mb2 pb2 tengah hideit ZZZ'>
    <span class='biru f24'>$jumlah_lobby</span>
    <span class='abu f14'>pasien Siap / Sedang Pemeriksaan</span>
  </div>
  $div
";
