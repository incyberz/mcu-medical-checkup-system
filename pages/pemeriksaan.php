<?php
$judul = 'Pemeriksaan Pasien';
$id_pasien = $_GET['id_pasien'] ?? die('Page ini membutuhkan index [id_pasien].');
$pemeriksaan = $_GET['pemeriksaan'] ?? '';
if (!$pemeriksaan) die('Page ini membutuhkan index [pemeriksaan].');
// $id_paket = $_GET['id_paket'] ?? die(div_alert('danger', 'Index id_paket belum terdefinisi.'));
// $nama_paket = $_GET['nama_paket'] ?? die(div_alert('danger', 'Index nama_paket belum terdefinisi.'));
// $sub_judul = "<a href='?manage_paket'>Back</a> | Manage Sticker untuk <b class='biru'>$nama_paket</b>";

# ============================================================
# INCLUDES 
# ============================================================
include 'include/arr_status_pasien.php';
include 'include/arr_pemeriksaan.php';
include 'pemeriksaan-functions.php';

$p = $arr_pemeriksaan[$pemeriksaan] ?? die(div_alert('danger', "Belum ada pemeriksaan $pemeriksaan pada database."));
$sub_judul = "<span class='f20 darkblue'>Pemeriksaan $p</span>";
set_h2($judul, $sub_judul);
only('users');









# ===========================================================
# PROCESSORS
# ===========================================================
include 'pemeriksaan-processors.php';





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
a.keluhan,
(SELECT 1 FROM tb_mcu WHERE id_pasien=a.id) punya_data

FROM tb_pasien a WHERE id='$id_pasien'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$punya_data = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  // while (
  $pasien = mysqli_fetch_assoc($q);
  $foto_profil = $pasien['foto_profil'];
  $order_no = $pasien['order_no'];
  $status = $pasien['status'];
  $NIK = $pasien['NIK'];
  $nomor_MCU = $pasien['nomor_MCU'];
  $punya_data = $pasien['punya_data'];

  $gender = $pasien['gender'];
  $gender_icon = $gender ? "<img src='$lokasi_icon/gender-$gender.png' height=20px>" : $img_warning;
  $gender_show = gender($gender);

  foreach ($pasien as $key => $value) {
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











# ============================================================
# GET DATA MCU 
# ============================================================
if ($pasien['punya_data']) {
  $s = "SELECT * FROM tb_mcu WHERE id_pasien=$id_pasien";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $mcu = mysqli_fetch_assoc($q);
} else {
  $mcu = [];
}









include 'include/arr_fitur_dokter.php';
include 'include/arr_fitur_nakes.php';

$src = "$lokasi_pasien/$foto_profil";
$status_show = $status ? "$arr_status_pasien[$status] ($status)" : '<span class="f12 red">Belum pernah login</span>';


# ===========================================================
# CREATE TB-MCU IF NOT EXISTS
# ===========================================================
if (!$punya_data) {
  include 'awal-pemeriksaan.php';
} else { // punya data MCU

  // $s = "SELECT 1 FROM tb_mcu WHERE id_pasien=$id_pasien";
  // $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  // if (!mysqli_num_rows($q)) {
  // }


  $form_pemeriksaan = div_alert('danger', "Belum ada form untuk pemeriksaan <span class=darkblue>$pemeriksaan</span><hr>Mohon segera lapor developer.");
  $file_form = "$lokasi_pages/form-pemeriksaan/$pemeriksaan.php";
  if (file_exists($file_form)) {
    // $form_pemeriksaan diubah pada sub-file ini
    include $file_form;


    $hide_form = '';
    $hasil_form = '';
    $caption = 'Submit';
    $btn_notif = "Diperiksa oleh <span class='darkblue'>$nama_user</span> pada tanggal <span class=consolas>$tanggal_show</span>";
    $tanggal_pemeriksaan = $mcu['tanggal_simpan_' . $pemeriksaan] ?? '';

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
        // echo '<pre>';
        // var_dump($arr);
        // echo '</pre>';
        // exit;
        foreach ($arr as $key => $value) {
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
      // include "$lokasi_pages/form-pemeriksaan/hasil-$pemeriksaan.php";
      if ($pemeriksaan == 'tb_bb') {
        $imt = round($mcu['berat_badan'] / (($mcu['tinggi_badan'] / 100) * ($mcu['tinggi_badan'] / 100)), 2);

        if ($imt >= 27) {
          $obesitas1 = $obesitas;
        } elseif (
          $imt >= 25
        ) {
          $obesitas1 = 'gemuk';
        } elseif ($imt >= 18.5) {
          $obesitas1 = 'normal';
        } elseif ($imt >= 17) {
          $obesitas1 = 'kurus';
        } else {
          $obesitas1 = 'sangat kurus';
        }

        $blp = $gender == 'l' ? 90 : 80;
        $obesitas2 = $mcu['lingkar_perut'] >= $blp ? $obesitas : 'dalam batas normal';
        $kesimpulan = "
          <h4 class='darkblue miring f18'>Index Masa Tubuh</h4>
          <ul>
            <li>Index Masa Tubuh (IMT) = $imt</li>
            <li>Kesimpulan Berat Tubuh : $obesitas1</li>
          </ul>

          <h4 class='darkblue miring f18'>Lingkar Perut</h4>
          <ul>
            <li>Batas lingkar perut untuk $gender_show yaitu $blp</li>
            <li>Kesimpulan Lingkar Perut : $obesitas2</li>
          </ul>

        ";
      } else {
        $kesimpulan = "Belum ada analogi kesimpulan untuk pemeriksaan $pemeriksaan.";
      }



      $hasil_form = "
      <div id=blok_hasil>
          <h2>HASIL PEMERIKSAAN</h2>
          <div>
            <b class=blue>Telah Diperiksa</b> oleh <span class='darkblue'>$pemeriksa</span> pada tanggal <span class=consolas>$tanggal_show</span> | <span class='abu f12'>$eta</span>
          </div>
  
          <div class='flex-center'>
            <div style=''>
              <table class='table table-hover'>
                $hasil_form
              </table>
            </div>
          </div>
  
          <div class='wadah gradasi-toska kiri'>
            <h3 class=mb4>Kesimpulan Pemeriksaan $arr_pemeriksaan[$pemeriksaan]</h3>
            $kesimpulan
          </div>
        </div>
  
        
        <button class='btn btn-secondary btn-sm mb2' id=btn_ubah_nilai>Ubah Nilai</button>
      ";

      echo "
      <script>
        $(function() {
          $('#btn_ubah_nilai').click(function() {
            $('#blok_hasil').slideToggle();
            $('#blok_form').slideToggle();
            if ($(this).text() == 'Ubah Nilai') {
              $(this).text('Lihat Hasil');
            } else {
              $(this).text('Ubah Nilai');
            }
          })
        })
      </script>
      ";

      $tanggal_show = date('d-F-Y H:i:s', strtotime($mcu['tanggal_simpan_tb_bb'])) . ' | ' . eta2($mcu['tanggal_simpan_tb_bb']);
      $btn_notif = "<b class=blue>Telah Diperiksa</b> oleh <span class='darkblue'>$pemeriksa</span> pada tanggal <span class=consolas>$tanggal_show</span>";
    }

    $blok_inputs = '';
    foreach ($arr as $key => $v) {

      $required = $v['required'] ?? 'required';

      if ($v == 'separator') {
        $blok_sub_input = '';
      } elseif ($v['blok'] == 'input-range') {
        $div_range = '';
        $min_range = 0;
        $max_range = 0;
        $i = 0;
        foreach ($v['range'] as $key2 => $range_value) {
          $i++;
          if ($i == 1) $min_range = $range_value;
          $div_range .= "<div>$range_value</div>";
          $max_range = $range_value;
        }
        $value = $v['value'] ?? $mcu[$key];
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
      } elseif ($v['blok'] == 'multi-radio') {
        $blok_sub_input = create_radio(
          $v['question'], // $question,
          $v['labels'] ?? [], // $labels,
          $v['values'], // $values,
          $key, // $name,
          $v['value_default'] ?? '', // $value_default = '',
          '', // $required = 'required',
          '', // $question_class = '',
          '', // $radios_class = '',
          '', // $wrapper_radio_class = '',
          '', // $global_class = '',

        );
      } elseif ($v['blok'] == 'array_gigi') {
        $blok_sub_input = array_gigi(
          $v['question'], // $question,
          $v['array_gigi'], // $value_default = '',
        );
      } else {
        die(div_alert('danger', "Belum ada UI untuk input-blok: $v[blok]. Harap segera lapor developer!"));
      }
      $blok_inputs .= !$blok_sub_input ? '<hr style="border: solid 5px #ccc; margin:50px 0">' : "
        <div class='wadah gradasi-toska' >
          $blok_sub_input
        </div>  
      ";
    }

    $tanggal_show = date('d-F-Y H:i');

    $form_pemeriksaan = "
      $hasil_form
      <form method='post' class='$hide_form form-pemeriksaan wadah bg-white' id=blok_form>
        $blok_inputs
        <div class='flexy mb2 flex-center'>
          <input type=checkbox required id=cek>
          <label for=cek>Saya menyatakan bahwa data diatas sudah benar.</label>
        </div>
        <button class='btn btn-primary w-100' name=btn_submit_data_pasien value='$pemeriksaan'>$caption Data $arr_pemeriksaan[$pemeriksaan]</button>
        <div class='tengah f12 mt1 abu'>
          $btn_notif
        </div>
      </form>
  
    ";
  } else {
    // hanya notif
    echolog("Belum ada data konfigurasi untuk pemeriksaan: <span class='tebal darkblue'>$pemeriksaan</span>");
  }
} // end punya data MCU

# ============================================================
# FINAL ECHO
# ============================================================
echo "
  <div class='wadah tengah gradasi-hijau'>
    <div><a href='?tampil_pasien&id_pasien=$id_pasien'>$img_prev</a></div>
    <div><img src='$src' class='foto_profil'></div>
    <div class='mb1'>$gender_icon $pasien[nama]</div>
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