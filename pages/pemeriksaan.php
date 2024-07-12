<?php
$judul = 'Pemeriksaan';
$id_pasien = $_GET['id_pasien'] ?? die('Page ini membutuhkan index [id_pasien].');
$JENIS = $_GET['JENIS'] ?? die('Page ini membutuhkan index [JENIS].');
// $id_paket = $_GET['id_paket'] ?? die('Page ini membutuhkan index [id_paket].');
$id_pemeriksaan = $_GET['id_pemeriksaan'] ?? die('Page ini membutuhkan index [id_pemeriksaan].');
if ($id_pemeriksaan) {
  $s = "SELECT nama as nama_pemeriksaan,singkatan FROM tb_pemeriksaan WHERE id=$id_pemeriksaan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    die('data pemeriksaan tidak ditemukan');
  } else {
    if (mysqli_num_rows($q) > 1) {
      die('data pemeriksaan tidak unik');
    } else {
      $d = mysqli_fetch_assoc($q);
      $nama_pemeriksaan = $d['nama_pemeriksaan'];
      $singkatan = $d['singkatan'];
    }
  }
  $pemeriksaan = $d['singkatan'] ?? $d['nama'];
} else {
  die(erid('empty:id_pemeriksaan'));
}


# ============================================================
# INCLUDES 
# ============================================================
include 'include/arr_status_pasien.php';
include 'include/arr_pemeriksaan.php';
include 'include/radio_toolbar_functions.php';
include 'pemeriksaan-functions.php';

$p = $arr_pemeriksaan[$id_pemeriksaan] ?? die(div_alert('danger', "Belum ada pemeriksaan $nama_pemeriksaan pada database."));
$sub_judul = "<span class='f20 darkblue'>Pemeriksaan $p</span>";
set_title($judul, $sub_judul);
only('users');









# ===========================================================
# HASIL (IF EXISTS)
# ===========================================================
$arr_id_detail = [];
include 'pemeriksaan-hasil_at_db.php';

# ===========================================================
# PROCESSORS
# ===========================================================
include 'pemeriksaan-processors.php';

# ============================================================
# MAIN SELECT PASIEN
# ============================================================
include 'pemeriksaan-data_pasien.php';

# ============================================================
# GET DATA MCU 
# ============================================================
// if ($pasien['punya_hasil']) {
//   $s = "SELECT * FROM tb_hasil_pemeriksaan WHERE id_pasien=$id_pasien";
//   $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
//   $hasil = mysqli_fetch_assoc($q);
// } else {
//   $hasil = [];
// }









// include 'include/arr_fitur_dokter.php';
// include 'include/arr_fitur_nakes.php';

$src = "$lokasi_pasien/$foto_profil";
$status_show = $status ? "$arr_status_pasien[$status] ($status)" : '<span class="f12 red">Belum pernah login</span>';


# ===========================================================
# CREATE TB-MCU IF NOT EXISTS
# ===========================================================
if (!$punya_hasil) {
  include 'pemeriksaan-awal_periksa.php';
} else { // punya data MCU

  // $s = "SELECT 1 FROM tb_mcu WHERE id_pasien=$id_pasien";
  // $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  // if (!mysqli_num_rows($q)) {
  // }


  // $form_pemeriksaan = div_alert('danger', "Belum ada form untuk pemeriksaan <span class=darkblue>$nama_pemeriksaan</span><hr>Mohon segera lapor developer.");
  // $file_form = "$lokasi_pages/form-pemeriksaan/pemeriksaan-$id_pemeriksaan.php";

  # ============================================================
  # FORM PEMERIKSAAN
  # ============================================================
  $s = "SELECT * FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=$id_pemeriksaan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $list = '';

  if (mysqli_num_rows($q)) {
    // $form_pemeriksaan diubah pada sub-file ini
    // include $file_form;
    // echolog('geting data detail');
    $arr_input = [];
    while ($d = mysqli_fetch_assoc($q)) {
      // echolog('loop ' . $d['label']);
      $list .= "
        <br>$d[label]
      ";
      $arr_input[$d['id']] = $d;
    }


    // $form_pemeriksaan = !$list
    //   ?  div_alert('danger', "Detail Pemeriksaan belum ada | <a href='?manage_pemeriksaan_detail&id_pemeriksaan=$id_pemeriksaan&nama_pemeriksaan=$nama_pemeriksaan'>Manage</a>")
    //   : "
    //   <div class='wadah bg-white'>
    //     $list
    //   </div>
    // ";

    $hide_form = '';
    $hasil_form = '';
    $caption = 'Submit';
    $btn_notif = "Diperiksa oleh <span class='darkblue'>$nama_user</span> pada tanggal <span class=consolas>$tanggal_show</span>";
    $tanggal_pemeriksaan = $mcu['tanggal_simpan_' . $id_pemeriksaan] ?? '';

    if ($tanggal_pemeriksaan) {
      # ============================================================
      # MODE SUDAH MENGISI
      # ============================================================
      include 'include/arr_user.php';


      $hide_form = 'hideit';
      $caption = 'Update';
      $obesitas = '<span class="red bold">obesitas</span>';

      $hasil_form = '';
      if ($pemeriksaan == 'gigi') {
        include "$lokasi_pages/hasil-pemeriksaan/hasil-pemeriksaan-gigi.php";
      } else {
        foreach ($arr_input as $key => $value) {
          if (strlen($key) < 3) {
            $hasil_form .= "<tr><td colspan=100%><hr style='border: solid 5px #ccc'></td></tr>";
          } else {
            $kolom = key2kolom($key);
            $satuan = $value['satuan'] ?? '';
            $hasil_form .= "
              <tr>
                <td class='kiri miring darkblue'>$kolom</td>
                <td class='kanan darkblue tebal'>$mcu[$key] $satuan</td>
              </tr>
            ";
          }
        }
      }

      $pemeriksa = $arr_user[$mcu['pemeriksa_' . $pemeriksaan]];
      $tanggal_show = date('d F Y, H:i:s', strtotime($mcu['tanggal_simpan_' . $pemeriksaan]));
      $eta = eta2($mcu['tanggal_simpan_' . $pemeriksaan]);


      # ============================================================
      # INCLUDE HASIL PENGUKURAN
      # ============================================================
      $hasil_form = '';
      include "pemeriksaan-hasil_pemeriksaan_ui.php";


      // $tanggal_show = date('d-F-Y H:i:s', strtotime($mcu['tanggal_simpan_tb_bb'])) . ' | ' . eta2($mcu['tanggal_simpan_tb_bb']);
      // $btn_notif = "<b class=blue>Telah Diperiksa</b> oleh <span class='darkblue'>$pemeriksa</span> pada tanggal <span class=consolas>$tanggal_show</span>";
    }

    $blok_inputs = '';


    foreach ($arr_input as $key => $v) {


      # ============================================================
      # FILL DATA FROM DB
      # ============================================================
      $id_detail = $v['id'];
      if (array_key_exists($id_detail, $arr_id_detail)) {
        $v['value'] = $arr_id_detail[$id_detail];
      }

      $v['required'] = 'required'; // ZZZ forced to required
      $required = $v['required'] ?? 'required';

      if ($v == 'separator') {
        $blok_sub_input = '';
      } elseif ($v['blok'] == 'input-range') {

        # ============================================================
        # CREATE RANGE
        # ============================================================
        $arr_range = [];
        $jumlah_titik = 8;
        $div_range = '';
        for ($i = 0; $i <= $jumlah_titik; $i++) {
          $range_value = round($v['minrange'] + ($i * (($v['maxrange'] - $v['minrange']) / $jumlah_titik)), 0);
          $div_range .= "<div>$range_value</div>";
        }
        $min_range = $v['minrange'];
        $max_range = $v['maxrange'];

        $value = $v['value'] ?? '';
        $val_range = $value ? $value : intval(($max_range - $min_range) / 2) + $min_range;
        $step = $v['step'] ?? 1;
        $placeholder = $v['placeholder'] ?? '...';
        $type = $v['type'] ?? 'text';
        $min = $v['min'] ?? '';
        $max = $v['max'] ?? '';
        $minlength = $v['minlength'] ?? '';
        $maxlength = $v['maxlength'] ?? '';
        $class = $v['class'] ?? '';
        $satuan = $v['satuan'] ?? '';

        $blok_sub_input = "
          <div class='flexy flex-center'>
            <div class='f14 darkblue miring pt1'>$v[label]</div>
            <div>
              <input 
                id='$key' 
                name='$key' 
                value='$value' 
                step='$step' 
                placeholder='$placeholder' 
                type='$type' 
                $required
                class='form-control mb2 $class' 
                min='$min' 
                max='$max' 
                minlength='$minlength' 
                maxlength='$maxlength' 
                style='max-width:100px'
              >          
            </div>
            <div class='f14 abu miring pt1'>$satuan</div>
          </div>
          <input type='range' class='form-range range' min='$min_range' max='$max_range' id='range__$key' value='$val_range' step='$step'>
          <div class='flexy flex-between f12 consolas abu'>
            $div_range
          </div>
        ";
      } elseif ($v['blok'] == 'radio') {
        // $pakai_kacamata = radio_tf('Apakah Anda memakai kacamata?', 'kacamata', '', 'Pakai', 0);

        $Ya = $v['label_ya'] ?? 'Ya';
        $Tidak = $v['label_tidak'] ?? 'Tidak';
        $value_tidak = $v['value_tidak'] ?? 0;
        $value_ya = $v['value_ya'] ?? 1;

        $blok_sub_input = create_radio_yt(
          $v['label'], // label radio 
          $key, // name radio
          '', // nilai default
          '', // caption
          $Ya, // Ya
          $Tidak, // Tidak
          '', // class label
          $value_tidak,
          $value_ya
        );
      } elseif ($v['blok'] == 'radio-toolbar') {
        # ============================================================
        # RADIO TOOLBAR FUNCTION
        # ============================================================
        $blok_sub_input = radio_toolbar2(
          $v['label'],
          $id_detail,
          $v['option_values'],
          $v['option_labels'],
          $v['class'],
          $v['option_class']
        );
      } elseif ($v['blok'] == 'array_gigi') {
        $blok_sub_input = array_gigi(
          $v['question'], // $question,
          $v['array_gigi'], // $value_default = '',
        );
      } elseif ($v['blok'] == 'input') {
        $blok_sub_input = 'INPUT BIASA';
      } else {
        die(div_alert('danger', "Belum ada UI untuk v-blok: <b class=darkblue>$v[blok]</b>. Harap segera lapor developer!"));
      }
      $blok_inputs .= !$blok_sub_input ? '<hr style="border: solid 5px #ccc; margin:50px 0">' : "
        <div class='wadah gradasi-toska' >
          $blok_sub_input
        </div>  
      ";
    }

    $tanggal_show = date('d-F-Y H:i');

    // $ZZZ = "
    //     <input type=hiddena name=id_paket value=$id_paket>
    //     <input type=hiddena name=JENIS value=$JENIS>
    // ";

    $ZZZ = '';

    $form_pemeriksaan = "
      $hasil_form
      <form method='post' class='$hide_form form-pemeriksaan wadah bg-white' id=blok_form>
        $blok_inputs
        <div class='flexy mb2 flex-center'>
          <input type=checkbox required id=cek>
          <label for=cek>Saya menyatakan bahwa data diatas sudah benar.</label>
        </div>
        <button class='btn btn-primary w-100' name=btn_submit_data_pasien value='$id_pasien'>$caption Data</button>
        <div class='tengah f12 mt1 abu'>
          $btn_notif
        </div>
        <input type=hiddena name=last_pemeriksaan value='$nama_pemeriksaan by $nama_user'>
        <input type=hiddena name=id_pemeriksaan value='$id_pemeriksaan'>
        $ZZZ
      </form>
    ";
  } else { // end ada detail
    echo  div_alert('danger', "Detail Pemeriksaan belum ada | <a href='?manage_pemeriksaan_detail&id_pemeriksaan=$id_pemeriksaan&nama_pemeriksaan=$nama_pemeriksaan'>Manage</a>");
  }
} // end punya data MCU







# ============================================================
# FINAL ECHO
# ============================================================
echo "
  <div class='wadah tengah gradasi-hijau'>
    $sub_judul
    <div class='mt2 mb2'><a href='?tampil_pasien&id_pasien=$id_pasien&JENIS=$JENIS'>$img_prev</a></div>
    <div><img src='$src' class='foto_profil'></div>
    <div class='mb1'>$gender_icon $pasien[nama]</div>
    <div class='border-bottom mb2 pb2 biru f12'> MCU-$id_pasien | $status_show</div>
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