<?php
$id_pasien = $_GET['id_pasien'] ?? die('Page ini membutuhkan index [id_pasien].');
$id_pemeriksaan = $_GET['id_pemeriksaan'] ?? false;
$ambil_sampel = $_GET['ambil_sampel'] ?? false;
if (!$id_pemeriksaan and !$ambil_sampel) die('Page ini membutuhkan index [id_pemeriksaan] atau [ambil_sampel].');
$pemeriksaan = [];
if ($id_pemeriksaan) {
  # ============================================================
  # PEMERIKSAAN PROPERTIES
  # ============================================================
  $s = "SELECT 
  nama as nama_pemeriksaan,
  singkatan,
  jenis 
  FROM tb_pemeriksaan 
  WHERE id=$id_pemeriksaan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    die('data pemeriksaan tidak ditemukan');
  } else {
    if (mysqli_num_rows($q) > 1) {
      die('data pemeriksaan tidak unik');
    } else {
      $pemeriksaan = mysqli_fetch_assoc($q);
      $nama_pemeriksaan = $pemeriksaan['nama_pemeriksaan'];
      $singkatan = $pemeriksaan['singkatan'];
    }
  }
} else {
  if (!$ambil_sampel) {
    die(erid('empty:id_pemeriksaan'));
  } else {
    $nama_pemeriksaan = 'Pengambilan Sampel';
  }
}


# ============================================================
# INCLUDES 
# ============================================================
include 'include/arr_status_pasien.php';
include 'include/radio_toolbar_functions.php';
include 'pemeriksaan-functions.php';

$p = strtoupper("Pemeriksaan $nama_pemeriksaan");
$p = str_replace('PEMERIKSAAN PEMERIKSAAN', 'PEMERIKSAAN', $p);
$sub_header = "<span class='f20 darkblue'>$p</span>";
set_title($p);
only('users');









# ===========================================================
# HASIL (IF EXISTS)
# ===========================================================
$arr_id_detail = [];
$arr_pemeriksaan_tanggal = [];
$arr_pemeriksaan_by = [];

$arr_sampel_tanggal = [];
$arr_sampel_by = [];

include 'pemeriksaan-hasil_at_db.php';
$tanggal_periksa = $arr_pemeriksaan_tanggal[$id_pemeriksaan] ?? '';
$id_pemeriksa = $arr_pemeriksaan_by[$id_pemeriksaan] ?? '';
$link_prev = "<div class='mt2 mb2'><a href='?tampil_pasien&id_pasien=$id_pasien'>$img_prev</a></div>";
$info_tanggal_periksa = $link_prev;
$toggle_form_pemeriksaan = '';
$hide_form_pemeriksaan = '';
if ($tanggal_periksa) {
  include 'include/arr_user.php';
  $JENIS = strtoupper($pemeriksaan['jenis']);
  $link_preview = $JENIS == 'MCU' ? '' : "<a class='btn btn-success' href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=$JENIS&id_pemeriksaan=$id_pemeriksaan'>Preview</a>";


  $hari = hari_tanggal($tanggal_periksa);
  $info_tanggal_periksa = div_alert('info mt2', "Pemeriksaan ini telah diperiksa oleh <b class=darkblue>$arr_user[$id_pemeriksa]</b> pada  <b class=darkblue>$hari</b>$link_prev $link_preview");
  $toggle_form_pemeriksaan = "<div class='tengah mb2'><span class='btn_aksi btn btn-secondary' id=form_pemeriksaan__toggle> <i class='bx bx-refresh f20'></i> Periksa Kembali</span></div>";
  $hide_form_pemeriksaan = 'hideit';
}




//var_dump($arr_id_detail[94]);

# ===========================================================
# PROCESSORS
# ===========================================================
include 'pemeriksaan-processors.php';

# ============================================================
# MAIN SELECT PASIEN
# ============================================================
include 'pemeriksaan-data_pasien.php';









$src = "$lokasi_pasien/$foto_profil";
$status_show = $status ? "$arr_status_pasien[$status] ($status)" : '<span class="f12 red">Belum pernah login</span>';


if (!$punya_hasil) {
  # ===========================================================
  # CREATE TB-MCU IF NOT EXISTS
  # ===========================================================
  include 'pemeriksaan-awal_periksa.php';
  if ($ambil_sampel) echo $form_pemeriksaan;
} elseif ($ambil_sampel) {
  include 'pemeriksaan-ambil_sampel.php';
} else { // punya data MCU


  # ============================================================
  # ROLE VALIDATION | EXCEPTION FOR PEM.FISIK.DOKTER | RONTGEN
  # ============================================================
  if (strtolower($pemeriksaan['jenis']) == 'mcu') {
    $singkatan = strtolower($pemeriksaan['singkatan']);
    if ($singkatan == 'pemfis' || $singkatan == 'rontgen') {
      if (!($role == 'dokter' || $role == 'dokter-pj')) {
        die(div_alert('danger', "$link_prev Untuk Pemeriksaan Dokter MCU hanya dapat dilakukan oleh Role Dokter <hr>Role Anda : [ $role ]<hr><a onclick='return confirm(`Logout?`)' href='?logout'>Logout dan Relogin</a>"));
      }
    }
  }

  # ============================================================
  # FORM PEMERIKSAAN
  # ============================================================
  $form_pemeriksaan = '';

  if (strtolower($pemeriksaan['jenis']) == 'ron') {
    # ============================================================
    # UI FOR RONTGEN
    # ============================================================
    include 'pemeriksaan-rontgen.php';
  } else {
    # ============================================================
    # FORM PEMERIKSAAN FOR OTHERS | HEMA, URINE, DLL
    # ============================================================
    include 'pemeriksaan-form_pemeriksaan_detail.php';
  }
} // end punya data MCU




if (!$ambil_sampel) {
  # ============================================================
  # FINAL ECHO | NON SAMPEL
  # ============================================================
  echo "
    <div class='wadah tengah gradasi-hijau'>
      $sub_header
      $link_prev 
      
      <div><img src='$src' class='foto_profil'></div>
      <div class='mb1'>$gender_icon $pasien[nama]</div>
      <div class='border-bottom mb2 pb2 biru f12'> MCU-$id_pasien | $status_show</div>
      $info_tanggal_periksa
      $toggle_form_pemeriksaan
      <div class='$hide_form_pemeriksaan' id=form_pemeriksaan>
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
}


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

      // console.log(id_detail, value, value_default);
      if (value_default) {
        if (value_default.trim().toLowerCase() == value.trim().toLowerCase()) {
          // console.log('DEFAULT');
        } else {
          // console.log('SET RED');
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
      // $('.range').change();
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