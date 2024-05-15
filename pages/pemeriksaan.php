<?php
$judul = 'Pemeriksaan Pasien';
// $id_paket = $_GET['id_paket'] ?? die(div_alert('danger', 'Index id_paket belum terdefinisi.'));
// $nama_paket = $_GET['nama_paket'] ?? die(div_alert('danger', 'Index nama_paket belum terdefinisi.'));
// $sub_judul = "<a href='?manage-paket'>Back</a> | Manage Sticker untuk <b class='biru'>$nama_paket</b>";
$sub_judul = '';
set_title($judul);
set_h2($judul, $sub_judul);
only('users');
$id_pasien = $_GET['id_pasien'] ?? die('Page ini membutuhkan index [id_pasien].');
$pemeriksaan = $_GET['pemeriksaan'] ?? die('Page ini membutuhkan index [pemeriksaan].');













# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_submit_data_pasien'])) {
  $section = $_POST['btn_submit_data_pasien'];
  unset($_POST['btn_submit_data_pasien']);

  $sets = '';
  foreach ($_POST as $key => $value) {
    echo "<br>$section | $key | $value";
  }


  echo div_alert('success', "Update Data Pasien sukses.");
  // jsurl('', 8000);
}




include 'include/arr_status_pasien.php';

# ============================================================
# MAIN SELECT PASIEN
# ============================================================
$s = "SELECT 
a.order_no,
a.nama,
a.nomor as nomor_MCU,
a.gender,
a.usia,
a.tanggal_lahir,
a.nikepeg as NIK,
a.username,
a.status,
a.foto_profil,
a.riwayat_penyakit,
a.gejala_penyakit,
a.gaya_hidup,
a.keluhan

FROM tb_pasien a WHERE id='$id_pasien'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  // while (
  $d = mysqli_fetch_assoc($q);
  $foto_profil = $d['foto_profil'];
  $order_no = $d['order_no'];
  $status = $d['status'];
  $gender = $d['gender'];
  $NIK = $d['NIK'];
  $nomor_MCU = $d['nomor_MCU'];

  $gender_icon = $gender ? "<img src='$lokasi_icon/gender-$gender.png' height=20px>" : $img_warning;
  // ) {
  // $i++;
  foreach ($d as $key => $value) {
    if (
      $key == 'id'
      || $key == 'foto_profil'
    ) continue;

    if ($key == 'gender') {
      $value = gender($value);
    } elseif ($key == 'nomor_MCU') {
      $value = "MCU-$value";
    } elseif ($key == 'tanggal_lahir') {
      $value = tanggal($value);
    } elseif ($key == 'status') {
      if ($value) {
        $value = '<span class="blue tebal">' . $arr_status_pasien[$value] . " ($value)</span>";
      } else {
        $value = $null;
      }
    } elseif (
      $key == 'riwayat_penyakit'
      || $key == 'gejala_penyakit'
      || $key == 'gaya_hidup'
    ) {
      $arr = explode(',', $value);
      $value = '';
      foreach ($arr as $k => $v) if ($v) $value .= "<li>$v</li>";
      $value = "<ol class=pl4>$value</ol>";
    }

    $kolom = key2kolom($key);
    $tr .= "
      <tr>
        <td class=kolom>$kolom</td>
        <td>$value</td>
      </tr>
    ";
  }
}

include 'include/arr_fitur_dokter.php';
include 'include/arr_fitur_nakes.php';

$src = "$lokasi_pasien/$foto_profil";

$status_show = $status ? "$arr_status_pasien[$status] ($status)" : '<span class="f12 red">Belum pernah login</span>';


# ===========================================================
# AUTO CREATE TB-MCU IF NOT EXISTS
# ===========================================================
$s = "SELECT 1 FROM tb_mcu WHERE id_pasien=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  $pemeriksaan = 'awal-input-data';
}


$form_pemeriksaan = div_alert('danger', "Belum ada form untuk pemeriksaan <span class=darkblue>$pemeriksaan</span><hr>Mohon segera lapor developer.");
$file_form = "$lokasi_pages/form-pemeriksaan/$pemeriksaan.php";
if (file_exists($file_form)) {
  include $file_form;
} else {
  echolog("Belum ada file-form-pemeriksaan untuk pemeriksaan: <span class='tebal darkblue'>$pemeriksaan</span>");
}

echo "
  <div class='wadah tengah gradasi-hijau'>
    <div><a href='?tampil-pasien&id_pasien=$id_pasien'>$img_prev</a></div>
    <div><img src='$src' class='foto_profil'></div>
    <div class='mb1'>$gender_icon $d[nama]</div>
    <div class='border-bottom mb2 pb2 biru f12'>$NIK | MCU-$nomor_MCU | $status_show</div>
    <div class=''>
      $form_pemeriksaan
    </div>
  </div>
  <div class='tengah mb4'><span class=btn_aksi id=tb_detail__toggle>$img_detail</span></div>

  <div class=hideit id=tb_detail>
    <table class='table '>
      $tr
    </table>
  </div>
";




?>
<script>
  $(function() {
    $('.range').click(function() {
      $('.range').change();
    })
    $('.range').change(function() {
      let val = $(this).val();
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      // console.log(aksi, id, val);
      $('#' + id).val(val)
    })
  })
</script>