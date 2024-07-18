<?php
set_title('Lobby Pasien');
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
  WHERE r.id=a.id) info_paket 
FROM tb_pasien a 
JOIN tb_jenis_pasien b ON a.jenis=b.jenis 
WHERE a.status >= 7 AND a.status <= 9";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_pasien_segera = mysqli_num_rows($q);
if (mysqli_num_rows($q)) {
  $div = '';
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $jenis = $d['jenis'];
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
      $status_show = "<span class='warna_status_$status'>$d[status_pasien]</span>" ?? '<i class="f14 abu">belum pemeriksaan</i>';
    }

    $div .= "
      <style>
        .border_status_7,.border_status_8 {border: solid 5px #f44}
        .border_status_9 {border: solid 5px #44f}
        .warna_status_7,.warna_status_8 {color:#f44}
        .warna_status_9 {color:#44f}
      </style>
      <div>
        <div class='f14 abu'>$i</div>
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
}


# ============================================================
# FINAL ECHO LOBBY PASIEN
# ============================================================
$lobby_pasien = "
  <div class='section-title' data-aos='fade'>
    <h2 id=judul>Lobby Pasien</h2>
  </div>

  <div class='f14 biru mb2 pb2 tengah'>
    <span class='biru f24'>$jumlah_pasien_segera</span>
    <span class='abu f14'>pasien Siap / Sedang Pemeriksaan</span>
  </div>
  <div class='flexy flex-center wadah gradasi-toska tengah'>
    $div
  </div>
";
