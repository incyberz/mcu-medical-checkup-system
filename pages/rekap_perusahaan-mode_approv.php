<?php
set_title('Approv Corporate');
$img_pdf = img_icon('pdf');
$blok_radio = '';
$blok_print = '';


# ============================================================
# HANYA ROLE DOKTER PJ YANG BISA APPROVE
# ============================================================
if ($role == 'dokter-pj') {
  foreach ($arr_kesimpulan as $key => $value) {
    $checked = ($key == 1 and !$arr_konsultasi and $kesimpulan_fisik == '-') ? 'checked' : '';
    $blok_radio .= "<div><label><input type=radio name=hasil__$id_pasien value=$key $checked> $value</label></div>";
  }
} else {
  $blok_radio = div_alert('info', "Hanya Role Dokter Penanggung Jawab yang dapat melakukan Approval Hasil Medical Checkup");
}


if ($kesimpulan == $belum_ada) {
  $gradasi_merah = 'gradasi-merah';
  $hideit = '';
} else { // sudah ada kesimpulan
  $gradasi_merah = '';
  $hideit = 'hideit';

  if ($pasien['whatsapp']) {

    $zdatetime = date('idymhs');
    $rand = rand(1, 999999);
    $rand = str_replace('0', '9', $rand);
    $rand2 = rand(10, 99);
    $id_pasien2024 = ($id_pasien * 7) + 2024;
    $zid_pasien = $rand . "0$id_pasien2024$zdatetime$rand2";

    $Tn = strtoupper($pasien['gender']) == 'L' ? 'Tn' : 'Ny';
    $link_akses = urlencode("https://mmc-clinic.com/k/?");
    $text_wa = "Selamat $waktu $Tn. $pasien[nama],%0a%0aTerima kasih telah mengikuti Medical Checkup di Mutiara Medical Center. Semoga Anda selalu sehat.%0a%0aSilahkan buka hasil MCU Anda:%0a$link_akses$zid_pasien%0a%0a_Mutiara Medical System, $now _";
    $link_wa = "<a class='btnz btn-success' target=_blank href='https://api.whatsapp.com/send?phone=$pasien[whatsapp]&text=$text_wa'>$img_wa</a>";
  } else {
    die(div_alert('danger', "Pasien [$nama_pasien] belum punya whatsapp."));
  }


  $blok_print = "
    <div class=mt1>
      <a target=_blank href='pdf/?id_pasien=$id_pasien' onclick='return confirm(`Download PDF untuk pasien ini?`)'>$img_pdf</a>
      $link_wa
    </div>
  ";
}
$blok_radio = "<div class='$hideit' id=blok_radio$id_pasien>$blok_radio</div>";
$kesimpulan = "
  <div class='mb1 bold '>
    <span class=btn_aksi id=blok_radio$id_pasien" . "__toggle>
      $kesimpulan
    </span>
  </div>
  $blok_radio
  $blok_print
";

# ============================================================
# IS HAIDH
# ============================================================
$gender = strtoupper($pasien['gender']);
if ($gender == 'L') {
  $is_haid_show = '';
  $Gender = 'Laki-laki';
} else {
  $Gender = 'Perempuan';
  $is_haid_show = $pasien['is_haid'] === null ? 'haidh: no-data' : 'sedang haidh';
  $is_haid_show = "<div class=red>$is_haid_show</div>";
}

$hasil_hema = $hasil_lab['HEMA'] == 'normal' ? 'normal' : "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=HEM&id_pemeriksaan=$id_pemeriksaan_dl'>$hasil_lab[HEMA]</a>";
$hasil_urine = $hasil_lab['URINE'] == 'normal' ? 'normal' : "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=URI&id_pemeriksaan=$id_pemeriksaan_uri'>$hasil_lab[URINE]</a>";
$hasil_rontgen = strpos(strtolower("salt$hasil_lab[RONTGEN]"), 'normal') ? '<span class=black>normal</span>' : "$hasil_lab[RONTGEN]";
$hasil_rontgen = "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=RON&id_pemeriksaan=$id_pemeriksaan_ron'>$hasil_rontgen</a>";

# ============================================================
# PUBLISITAS
# ============================================================
// $publish = "<span class='f10 abu miring'>belum bisa publish</span>";


# ============================================================
# TR APPROV
# ============================================================
$tr_approv .= "
  <tr>
    <td>
      <div>$no_urut</div>
      <div>MCU-$pasien[id_pasien]</div>
      <div>$pasien[nama_pasien]</div>
      <div>$Gender$is_haid_show</div>
    </td>

    <td><span class=hideit>KELUHAN</span>$keluhan</td>
    <td id='kesFis'><span class=hideit>KESIMPULAN FISIK</span>$kesimpulan_fisik</td>
    $td_tambahan
    <td><span class=hideit>DARAH LENGKAP</span>$hasil_hema</td>
    <td><span class=hideit>URINE</span>$hasil_urine</td>
    <td><span class=hideit>RONTGEN</span>$hasil_rontgen</td>
    <td class='$gradasi_merah'><span class=hideit>KESIMPULAN</span>$kesimpulan</td>
    <td><span class=hideit>KONSULTASI</span>$konsultasi</td>
    <td><span class=hideit>REKOMENDASI</span>$rekomendasi</td>        
  </tr>
";

$rekomendasi_custom = $rekomendasi == 'Dapat bekerja sesuai bidangnya' ? 'NULL' : "'$rekomendasi'";

# ============================================================
# UPDATE KONSULTASI WHEN SUBMIT
# ============================================================
if (isset($_POST['btn_submit'])) {
  echolog("Updating konsultasi + rekomendasi for [ $pasien[id_pasien] | $pasien[nama_pasien] ]");
  $s = "UPDATE tb_hasil_pemeriksaan SET 
  konsultasi = '$konsultasi',
  rekomendasi = $rekomendasi_custom
  WHERE id_pasien = $pasien[id_pasien] ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
}
