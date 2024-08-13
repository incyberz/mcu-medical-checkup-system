<?php
$mode = $_GET['mode'] ?? 'detail';
$status_pasien = $_GET['status_pasien'] ?? '';
set_h2('Rekap Pemeriksaan', "
  List Mode 
  | <a href='?rekap_perusahaan'>Rekap Perusahaan</a>
  | <a href='?rekap_perusahaan&mode=monitoring_pasien'>Monitoring Pasien</a>
");

$jumlah_rekap = 0;
if ($status_pasien === '') {
  # ============================================================
  # TOTAL PASIEN
  # ============================================================
  $s2 = "SELECT 1 FROM tb_pasien";
  $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
  $total_pasien = mysqli_num_rows($q2);


  # ============================================================
  # MAIN SELECT STATUS
  # ============================================================
  $s = "SELECT a.* FROM tb_status_pasien a ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr = '';
  if (mysqli_num_rows($q)) {
    $i = 0;
    $total_count = 0;
    $mw = 400; // max_width in pixel
    $mwpx = $mw . 'px';
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      $s2 = "SELECT 1 FROM tb_pasien WHERE status=$d[status]";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $count = mysqli_num_rows($q2);
      $total_count += $count;

      $width = floor($mw * ($count / $total_pasien)) . 'px';
      $graf = "
        <div class='progress' style='width: $mwpx'>
          <div class='progress-bar' style='width: $width'>
        </div>
      ";

      $tr .= "
        <tr>
          <td>$d[status]</td>
          <td><a href='?rekap_pemeriksaan&status_pasien=$d[status]'>$d[nama]</a></td>
          <td>$count </td>
          <td>$graf</td>
        </tr>
      ";
    }
    $count_status0 = $total_pasien - $total_count;
    $width = floor($mw * ($count_status0 / $total_pasien)) . 'px';
    $tr = "
      <tr>
        <td>0</td>
        <td><a href='?rekap_pemeriksaan&status_pasien=0'>Belum Login / Pembayaran</a></td>
        <td>$count_status0</td>
        <td>
          <div class='progress' style='width: $mwpx'>
            <div class='progress-bar' style='width: $width'>
          </div>
        </td>
      </tr>
      $tr
    ";
  }

  echo $tr ? "
    <table class=table>
      $tr
    </table>
  " : div_alert('danger', "Data status_pasien tidak ditemukan.");

  echo div_alert('info', "Silahkan klik pada salah satu status pasien!");
} else { // status_pasien defined

  # ============================================================
  # MAIN SELECT PASIEN
  # ============================================================
  $or_status_null = $status_pasien === '0' ? " OR a.status is NULL " : '';
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
    ) arr_tanggal_by,
  (
    SELECT approv_date FROM tb_hasil_pemeriksaan p WHERE p.id_pasien=a.id
    ) approv_date,
  (
    SELECT date(awal_periksa) FROM tb_hasil_pemeriksaan p WHERE p.id_pasien=a.id
    ) tanggal_periksa,
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
  WHERE (a.status = $status_pasien $or_status_null)
  ORDER BY tanggal_periksa,a.order_no,a.id_harga_perusahaan,  a.nama 
  ";

  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $jumlah_rekap = mysqli_num_rows($q);
  $div_mobile = '';
  $i = 0;
  $jumlah_verif = 0;
  $nav = div_alert('info', "Belum ada Pasien yang Selesai Pemeriksaan | <a href='?cari_pasien'>Lobby Pasien</a>");
  if (mysqli_num_rows($q)) {
    $last_perusahaan = '';
    $last_tanggal_periksa = '';
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      if ($d['approv_date']) $jumlah_verif++;
      $jenis = strtolower($d['jenis']);
      $id_pasien = $d['id_pasien'];
      $status = $d['status'];
      $tanggal_periksa = hari_tanggal($d['tanggal_periksa'], 1, 0, 0);

      # ============================================================
      # SEPARATOR PERUSAHAAN
      # ============================================================
      $perusahaan = $d['perusahaan'] ?? $d['perusahaan_corin'];
      if (!$perusahaan) $perusahaan = "<b class=green>INDIVIDU (NON C0RPORATE) - $tanggal_periksa</b>";
      if ($last_perusahaan != $perusahaan || $last_tanggal_periksa != $tanggal_periksa) {
        $div_mobile .= "<div class='gradasi-kuning p2 border-bottom mt4 mb2 bold darkblue f20'>$perusahaan - $tanggal_periksa</div>";
        $last_perusahaan = $perusahaan;
        $last_tanggal_periksa = $tanggal_periksa;
      }

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

      if ($d['whatsapp']) {

        $zdatetime = date('idymhs');
        $rand = rand(1, 999999);
        $rand = str_replace('0', '9', $rand);
        $rand2 = rand(10, 99);
        $id_pasien2024 = ($id_pasien * 7) + 2024;
        $zid_pasien = $rand . "0$id_pasien2024$zdatetime$rand2";

        $Tn = strtoupper($d['gender']) == 'L' ? 'Tn' : 'Ny';
        $link_akses = urlencode("https://mmc-clinic.com/k/?");
        $text_wa = "Selamat $waktu $Tn. $d[nama],%0a%0aTerima kasih telah mengikuti Medical Checkup di Mutiara Medical Center. Semoga Anda selalu sehat.%0a%0aSilahkan buka hasil MCU Anda:%0a$link_akses$zid_pasien%0a%0a_Mutiara Medical System, $now _";
        $link_wa = "<a target=_blank href='https://api.whatsapp.com/send?phone=$d[whatsapp]&text=$text_wa'>$img_wa</a>";
      } else {
        $link_wa = 'ISI WA DULU';
      }

      $link_verif = $d['approv_date'] ? $link_wa : " | 
        <a class='upper tebal ' href='?hasil_pemeriksaan&id_pasien=$d[id]&jenis=mcu'>
          <b class=red>Unverified</b> $img_next
        </a>
      ";


      # ============================================================
      # FINAL LIST PASIEN
      # ============================================================
      if ($mode == 'detail') {
        $div_mobile .= "
          <div class='mb4'>
            <a target=_blank class='upper tebal ' href='$href'>$i. $d[nama_pasien] $img_detail</a>
            <a onclick='return confirm(`Login as Pasien?`)' target=_blank href='?login_as&role=pasien&username=$d[username]'>$img_login_as</a>
            $link_verif
          </div>
        ";
      } else {
        $div_mobile .= "
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
    } // end while
  }


  # ============================================================
  # FINAL ECHO REKAP
  # ============================================================
  echo "
    $div_mobile
  ";
}

echo $role != 'admin' ? '' : "
    <a class='btn btn-primary' href='?update_status_pasien'>Update Status All Pasien</a>
  ";

# ============================================================
# AUTOSAVE HEADER
# ============================================================
if ($jumlah_rekap) {
  $jumlah_unverif = $jumlah_rekap - $jumlah_verif;
  $s = "UPDATE tb_header SET 
  count_pasien_selesai = $jumlah_rekap, 
  count_pasien_unverif = $jumlah_unverif 
  WHERE id_klinik=$id_klinik";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
}