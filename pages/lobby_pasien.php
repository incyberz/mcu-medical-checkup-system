<?php
$mode = $_GET['mode'] ?? 'detail';
$at_id_pemeriksaan = $_GET['at_id_pemeriksaan'] ?? '';
$set_all_rontgen_normal = $_GET['set_all_rontgen_normal'] ?? '';
$get_id_paket = $_GET['id_paket'] ?? '11'; // default Paket Basic
set_title('Lobby Pasien');

$id_pemeriksaan_glu_pu = 44; // glu_pu
$id_pemeriksaan_glu_se = 46; // glu_se
$id_pemeriksaan_dl = 3; // glu_se


# ============================================================
# NAVIGASI
# ============================================================
$s = "SELECT 
c.id as id_pemeriksaan, 
c.singkatan 
FROM tb_paket a 
JOIN tb_paket_detail b ON a.id=b.id_paket 
JOIN tb_pemeriksaan c ON b.id_pemeriksaan=c.id 
WHERE a.id=$get_id_paket -- Karyawan basic || paket lain";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$count_pemeriksaan = [];
$arr_singkatan = [];
while ($d = mysqli_fetch_assoc($q)) {
  $count_pemeriksaan[$d['id_pemeriksaan']] = 0;
  $arr_singkatan[$d['id_pemeriksaan']] = $d['singkatan'];
}


# ============================================================
# SELECT PASIEN
# ============================================================
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
  SELECT arr_hasil FROM tb_hasil_pemeriksaan p WHERE p.id_pasien=a.id
  ) arr_hasil,
(
  SELECT arr_tanggal_by FROM tb_hasil_pemeriksaan p WHERE p.id_pasien=a.id
  ) arr_tanggal_by,
(
  SELECT awal_periksa FROM tb_hasil_pemeriksaan p WHERE p.id_pasien=a.id
  ) awal_periksa,
(
  SELECT p.nama FROM tb_perusahaan p 
  JOIN tb_order q ON p.id=q.id_perusahaan 
  WHERE q.order_no=a.order_no
  ) perusahaan,
(
  SELECT p.nama FROM tb_perusahaan p 
  JOIN tb_harga_perusahaan q ON p.id=q.id_perusahaan 
  WHERE q.id=a.id_harga_perusahaan
  ) perusahaan_corin

FROM tb_pasien a 
JOIN tb_jenis_pasien b ON a.jenis=b.jenis 
WHERE (a.status is null or a.status <= 9) -- a.status >= 7 AND a.status <= 9 
ORDER BY a.order_no, a.id_harga_perusahaan, a.nama 
";

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_pasien_segera = mysqli_num_rows($q);
$div = '';
$nav = div_alert('info', "
  Tidak ada pasien di Lobby Pemeriksaan | 
  <a href='?rekap_pemeriksaan'>Rekap Hasil Pemeriksaan</a>
  <a href='?monitoring_pasien'>Monitoring Pasien Corporate</a>
");
$total_sisa = 0;
$jumlah_lobby = 0;
$jumlah_lobby_filtered = 0;
if (mysqli_num_rows($q)) {
  $last_perusahaan = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $jenis = strtolower($d['jenis']);
    if ($jenis == 'cor' and $d['status_bayar'] === null and $d['status_bayar_corporate_mandiri'] === null) {
    }
    $jumlah_lobby++;
    $status = $d['status'];
    $perusahaan = $d['perusahaan'] ?? $d['perusahaan_corin'];

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
    $id_pemeriksaan_yang_sudah = [];
    foreach ($r as $v) {
      if ($v) {
        $r2 = explode('=', $v);
        $id_pemeriksaan = $r2[0];

        if (!in_array($id_pemeriksaan, $id_pemeriksaan_yang_sudah)) {
          array_push($id_pemeriksaan_yang_sudah, $id_pemeriksaan);

          if (!isset($count_pemeriksaan[$id_pemeriksaan])) {
            $count_pemeriksaan[$id_pemeriksaan] = 1;
          } else {
            $count_pemeriksaan[$id_pemeriksaan]++;
          }
        }

        // if glukosa puasa add sudah ke glukosa sewaktu
        if ($id_pemeriksaan == $id_pemeriksaan_glu_pu) {
          array_push($id_pemeriksaan_yang_sudah, $id_pemeriksaan_glu_se);
        }

        // if glukosa sewaktu add sudah ke glukosa puasa 
        if ($id_pemeriksaan == $id_pemeriksaan_glu_se) {
          array_push($id_pemeriksaan_yang_sudah, $id_pemeriksaan_glu_pu);
        }
      }
    }

    # ============================================================
    # LOBBY FILTERED
    # ============================================================
    if (!$at_id_pemeriksaan || !in_array($at_id_pemeriksaan, $id_pemeriksaan_yang_sudah)) {
      if ($last_perusahaan != $perusahaan) {
        $separator = "<div class='gradasi-kuning p2 border-bottom mb2 mt4 f18 abu bold'>$perusahaan</div>";
        $last_perusahaan = $perusahaan;
        $jumlah_lobby_filtered = 0;
      } else {
        $separator = '';
      }
      $jumlah_lobby_filtered++;
      $nama = !$d['awal_periksa'] ? "<span class='red bold'>$d[nama_pasien]</span>" : $d['nama_pasien'];

      if ($set_all_rontgen_normal and $role == 'admin' and $at_id_pemeriksaan == 9) {
        # ============================================================
        # SET ALL RONTGEN NORMAL
        # ============================================================
        $id_detail = 134; // id detail rontgen

        $new_arr_hasil = "$id_detail=normal||$d[arr_hasil]";
        $new_arr_tanggal_by = "$at_id_pemeriksaan=$now,$id_user||$d[arr_tanggal_by]";

        $s2 = "UPDATE tb_hasil_pemeriksaan SET 
        arr_hasil = '$new_arr_hasil',
        arr_tanggal_by = '$new_arr_tanggal_by' 
        WHERE id_pasien = $d[id_pasien] 
        ";
        $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      }



      # ============================================================
      # FINAL DIV | LIST PASIEN YANG BELUM PEMERIKSAAN
      # ============================================================
      if ($mode == 'detail') {
        $div .= "
          $separator
          <div class='mb4'>
            <a target=_blank class='upper tebal ' href='$href'>$jumlah_lobby_filtered. $nama $img_next </a>
          </div>
        ";
      } else {
        $div .= "
          <div>
            <div class='f14 abu'>$jumlah_lobby_filtered</div>
            <div>
              <a target=_blank href='$href'>
                <img src='$src' class='foto_profil border_status_$status ' />
              </a>
            </div>
            <div>
              <div><a target=_blank class=upper href='$href'>$nama</a></div>
              <div class=f12>$info_paket</div>
              <div class=f12>$status_show</div>
            </div>
          </div>
        ";
      }
    } // end if filtered
  } // end while

  echo "
    <style>
      .border_status_7,.border_status_8 {border: solid 5px #f44}
      .border_status_9 {border: solid 5px #44f}
      .warna_status_7,.warna_status_8 {color:#f44}
      .warna_status_9 {color:#44f}
    </style>
  ";



  # ============================================================
  # NAVIGASI PAKET YANG DITAMPILKAN
  # ============================================================
  $arr_paket = [
    11 => 'Paket Basic',
    23 => 'Paket Guru SMK-TB',
  ];
  $paket_nav = '';
  foreach ($arr_paket as $id_paket => $nama_paket) {
    if ($get_id_paket == $id_paket) {
      $paket_nav .= "<div class='gradasi-kuning p1 biru bold mb2 wadah_active'>$nama_paket</div>";
    } else {
      $paket_nav .= "<div><a href='?cari_pasien&id_paket=$id_paket'>$nama_paket</a></div>";
    }
  }


  $item_btn_link = "<div><a class='btn btn-secondary btn-sm' href='?cari_pasien&id_paket=$id_paket'>ALL <span class='f30'>$jumlah_lobby</span></a></div>";
  $total_sisa = 0; // not ALL (karena glu_pu | glu_se)
  foreach ($count_pemeriksaan as $id_pemeriksaan => $count) {
    $sisa = $jumlah_lobby - $count;

    // jika glu_pu
    if ($id_pemeriksaan == $id_pemeriksaan_glu_pu) {
      $sisa = $jumlah_lobby - $count - $count_pemeriksaan[$id_pemeriksaan_glu_se];
    }

    // jika glu_se
    if ($id_pemeriksaan == $id_pemeriksaan_glu_se) {
      $sisa = $jumlah_lobby - $count - $count_pemeriksaan[$id_pemeriksaan_glu_pu];
    }

    $total_sisa += $sisa;

    $primary = $id_pemeriksaan == $at_id_pemeriksaan ? 'primary' : 'secondary';
    $singkatan = $arr_singkatan[$id_pemeriksaan] ?? '<span class=yellow>?</span>';
    $item_btn_link .= !$sisa ? '' : "
      <div>
      <a class='btn btn-$primary btn-sm' href='?cari_pasien&at_id_pemeriksaan=$id_pemeriksaan&id_paket=$id_paket'>
        $singkatan 
        <span class='f30'>$sisa</span>
      </a>
      </div>
    ";
  }

  # ============================================================
  # FINAL NAV BTN LINK
  # ============================================================
  $nav = "
    <div class='wadah gradasi-merah mb4'>
      <div class='flexy f12 mb1'>
        <div class='darkred'>Sisa Pemeriksaan pada:</div>
        $paket_nav
      </div>
      <div class='flexy'>$item_btn_link</div>
    </div>
  ";
}

$blok_set_all_rontgen_normal = ($at_id_pemeriksaan != 9 || $role != 'admin') ? '' : "
  <div class='wadah gradasi-kuning'>
    <a class='btn btn-danger' href='?cari_pasien&at_id_pemeriksaan=$at_id_pemeriksaan&id_paket=$id_paket&set_all_rontgen_normal=1'>Set All Rontgen Normal</a>
  </div>
";


# ============================================================
# FINAL ECHO LOBBY PASIEN
# ============================================================
$div = $mode == 'detail' ? $div : "<div class='flexy flex-center wadah gradasi-toska tengah'>$div</div>";
$lobby_pasien = $total_sisa ?  "
  <div class='section-title' data-aos='fade'>
    <h2 id=judul>Lobby Pasien</h2>
  </div>


  $nav

  $blok_set_all_rontgen_normal

  $div
" : div_alert('success', "Semua Pasien telah menjalani pemeriksaan.");
