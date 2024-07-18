<?php
$id_pasien = $_GET['id_pasien'] ?? die('Page ini membutuhkan index [id_pasien].');
$sampel = $_GET['sampel'] ?? die('Page ini membutuhkan index [sampel].');
if ($sampel) {
  # ============================================================
  # SAMPEL PROPERTIES
  # ============================================================
  $s = "SELECT 
  CONCAT(sampel, ' - ', zat) as nama_sampel FROM tb_sampel 
  WHERE sampel='$sampel'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    die('data pemeriksaan tidak ditemukan');
  } else {
    if (mysqli_num_rows($q) > 1) {
      die('data pemeriksaan tidak unik');
    } else {
      $d = mysqli_fetch_assoc($q);
      $nama_sampel = $d['nama_sampel'];
    }
  }
} else {
  die(erid('empty:sampel'));
}


# ============================================================
# INCLUDES 
# ============================================================
include 'include/arr_status_pasien.php';
include 'include/radio_toolbar_functions.php';
include 'pemeriksaan-functions.php';

$p = strtoupper("Sampel $nama_sampel");
$p = str_replace('SAMPEL SAMPEL', 'SAMPEL', $p);
$sub_header = "<span class='f20 darkblue'>$p</span>";
set_title($p);
only('users');









# ===========================================================
# HASIL (IF EXISTS)
# ===========================================================
$arr_id_detail = [];
$arr_sampel_tanggal = [];
$arr_sampel_by = [];
include 'pemeriksaan-hasil_at_db.php';
$tanggal_periksa = $arr_sampel_tanggal[$sampel] ?? '';
$id_pemeriksa = $arr_sampel_by[$sampel] ?? '';
$link_prev = "<div class='mt2 mb2'><a href='?tampil_pasien&id_pasien=$id_pasien'>$img_prev</a></div>";
$info_tanggal_periksa = $link_prev;
$toggle_form_sampel = '';
$hide_form_sampel = '';
if ($tanggal_periksa) {
  include 'include/arr_user.php';
  $hari = hari_tanggal($tanggal_periksa);
  $info_tanggal_periksa = div_alert('info mt2', "Sampel ini telah diperiksa oleh <b class=darkblue>$arr_user[$id_pemeriksa]</b> pada  <b class=darkblue>$hari</b>$link_prev");
  $toggle_form_sampel = "<div class='tengah mb2'><span class='btn_aksi btn btn-secondary' id=form_sampel__toggle> <i class='bx bx-refresh f20'></i> Periksa Kembali</span></div>";
  $hide_form_sampel = 'hideit';
}
//var_dump($arr_id_detail[94]);

# ===========================================================
# PROCESSORS
# ===========================================================
include 'pemeriksaan-processors.php';

# ============================================================
# MAIN SELECT PASIEN DAN FORM SAMPEL
# ============================================================
include 'pemeriksaan-data_pasien.php';
$src = "$lokasi_pasien/$foto_profil";
$status_show = $status ? "$arr_status_pasien[$status] ($status)" : '<span class="f12 red">Belum pernah login</span>';

$nama_pasien = ucwords(strtolower($pasien['nama']));
$Tn = $pasien['gender'] == 'l' ? 'Tn' : 'Ny';

$form_sampel = "
  <form method='post' class='form-pemeriksaan wadah bg-white' id=blok_form>
    <div class='flexy mb2 flex-center'>
      <input type=checkbox required id=cek>
      <label for=cek>Saya sudah mengambil sampel <b class=darkblue>$sampel</b> dari <b class=darkblue>$Tn. $nama_pasien</b>.</label>
    </div>
    <button class='btn btn-primary w-100' name=btn_submit_data_pasien value='$id_pasien'>Submit Data</button>
    <input type=hiddena name=sampel value='$sampel'>
  </form>
";







# ============================================================
# FINAL ECHO
# ============================================================
echo "
  <div class='wadah tengah gradasi-hijau'>
    $sub_header
    $link_prev
    
    <div><img src='$src' class='foto_profil'></div>
    <div class='mb1'>$gender_icon $pasien[nama]</div>
    <div class='border-bottom mb2 pb2 biru f12'> MCU-$id_pasien | $status_show</div>
    $info_tanggal_periksa
    $toggle_form_sampel
    <div class='$hide_form_sampel' id=form_sampel>
      $form_sampel
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
    $('.opsi_radio').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id_detail = rid[0];
      let value = rid[1];
      let value_default = $('#value_default__' + id_detail).text();

      // restore UI
      $('.label__' + id_detail).prop('style', '');

      console.log(id_detail, value, value_default);
      if (value_default) {
        if (value_default.trim().toLowerCase() == value.trim().toLowerCase()) {
          console.log('DEFAULT');
        } else {
          // ============================================================
          // NILAI ABNORMAL != NILAI DEFAULT
          // ============================================================
          $('#label__' + id_detail + '__' + value).prop('style', 'background:red');
        }
      }
    });


    // ============================================================
    // ALL RANGE CLICK
    // ============================================================
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
  });
</script>