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
  $hari = hari_tanggal($tanggal_periksa);
  $info_tanggal_periksa = div_alert('info mt2', "Pemeriksaan ini telah diperiksa oleh <b class=darkblue>$arr_user[$id_pemeriksa]</b> pada  <b class=darkblue>$hari</b>$link_prev");
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









# ============================================================
# ROLE MANAGEMENTS ZZZ
# ============================================================
// include 'include/arr_fitur_dokter.php';
// include 'include/arr_fitur_nakes.php';

$src = "$lokasi_pasien/$foto_profil";
$status_show = $status ? "$arr_status_pasien[$status] ($status)" : '<span class="f12 red">Belum pernah login</span>';


# ===========================================================
# CREATE TB-MCU IF NOT EXISTS
# ===========================================================
if (!$punya_hasil) {
  include 'pemeriksaan-awal_periksa.php';
} elseif ($ambil_sampel) {

  $sampel = $_GET['sampel'] ?? die('Page ini membutuhkan index [sampel].');

  $tanggal_periksa_sampel = $arr_sampel_tanggal[$sampel] ?? '';
  $id_pemeriksa_sampel = $arr_sampel_by[$sampel] ?? '';
  $toggle_form_sampel = '';
  $hide_form_sampel = '';
  if ($tanggal_periksa_sampel) {
    include 'include/arr_user.php';
    $hari = hari_tanggal($tanggal_periksa_sampel);
    $info_tanggal_periksa = div_alert('info mt2', "Sampel ini telah diperiksa oleh <b class=darkblue>$arr_user[$id_pemeriksa_sampel]</b> pada  <b class=darkblue>$hari</b>$link_prev");
    $toggle_form_sampel = "<div class='tengah mb2'><span class='btn_aksi btn btn-secondary' id=form_sampel__toggle> <i class='bx bx-refresh f20'></i> Periksa Kembali</span></div>";
    $hide_form_sampel = 'hideit';
  }


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
  # FORM SAMPEL
  # ============================================================
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
  # FINAL ECHO AMBIL SAMPEL
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
  // exit;
} else { // punya data MCU

  # ============================================================
  # FORM PEMERIKSAAN
  # ============================================================
  $form_pemeriksaan = '';

  # ============================================================
  # EXCEPTION FOR PEM.FISIK.DOKTER
  # ============================================================
  if (strtolower($pemeriksaan['jenis']) == 'mcu' and strtolower($pemeriksaan['singkatan']) == 'pemfis' and $role != 'dokter') {
    die(div_alert('danger', "$link_prev Untuk Pemeriksaan Dokter MCU hanya dapat dilakukan oleh Role Dokter <hr><a onclick='return confirm(`Logout?`)' href='?logout'>Logout dan Relogin</a>"));
  }

  $s = "SELECT * FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=$id_pemeriksaan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    $arr_input = [];
    while ($d = mysqli_fetch_assoc($q)) {
      $arr_input[$d['id']] = $d;
    }


    # ============================================================
    # PENENTUAN BLOK INPUT
    # ============================================================
    # $arr_input['blok'] : - radio-toolbar
    #                      - input-range
    #                      - select
    # ============================================================
    $blok_inputs = '';
    include 'pemeriksaan-blok_input_handler.php';

    $tanggal_show = date('d-F-Y H:i');

    $form_pemeriksaan = "
      <form method='post' class='form-pemeriksaan wadah bg-white' id=blok_form>

        <!-- =========================================================== -->
        <!-- BLOK INPUTS -->
        <!-- =========================================================== -->
        $blok_inputs

        <div class='flexy mb2 flex-center'>
          <input type=checkbox required id=cek>
          <label for=cek>Saya menyatakan bahwa data diatas sudah benar.</label>
        </div>
        <button class='btn btn-primary w-100' name=btn_submit_data_pasien value='$id_pasien'>Submit Data</button>
        <input type=hiddena name=last_pemeriksaan value='$nama_pemeriksaan by $nama_user'>
        <input type=hiddena name=id_pemeriksaan value='$id_pemeriksaan'>
      </form>
    ";
  } else { // end ada detail
    $form_pemeriksaan =  div_alert('danger tengah', "Detail Pemeriksaan belum ada | <a href='?manage_pemeriksaan_detail&id_pemeriksaan=$id_pemeriksaan&nama_pemeriksaan=$nama_pemeriksaan'>Manage</a>");
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