<style>
  #info_pasien h3 {
    font-size: 18px;
    color: darkblue;
    text-align: center;
    border-bottom: solid 1px #cccccccc;
    padding-bottom: 10px;
  }

  .kolom {
    color: #888 !important;
    font-style: italic;
  }

  .radio-toolbar label {
    padding: 7px 10px;
  }

  .nomor {
    font-family: consolas;
    letter-spacing: 1.5px;
    font-size: 20px;
  }
</style>
<?php
$judul = 'Pasien Home';
$sub_judul = "Selamat datang $nama_user di MMC Information System";
set_title($judul);
set_h2($judul, $sub_judul);
only(['pasien', 'pendaftar']);
// require_once 'include/mcu_functions.php';

















































# ============================================================
# NORMAL FLOW
# ============================================================
$s = "SELECT 
a.*, 
a.id as id_pasien,
a.status, -- status pasien
b.pendaftar,
b.tanggal_order,
c.id as id_paket,
c.nama as paket,
d.nama as program,
d.id as id_program,
(SELECT nama FROM tb_status_pasien WHERE status=a.status) status_pasien 
FROM tb_pasien a 
JOIN tb_order b ON a.order_no=b.order_no 
JOIN tb_paket c ON b.id_paket=c.id
JOIN tb_program d ON c.id_program=d.id 
WHERE a.id='$id_user'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (!mysqli_num_rows($q)) {
  echo div_alert('danger', "Data pasien tidak ditemukan.");
  exit;
} else {
  $d = mysqli_fetch_assoc($q);
  $id_pasien = $d['id_pasien'];
  $id_program = $d['id_program'];
  $nama = $d['nama'];
  $gender = $d['gender'];
  $foto_profil = $d['foto_profil'];
  $usia = $d['usia'];
  $no_ktp = $d['no_ktp'];
  $nikepeg = $d['nikepeg'];
  $nikepeg_or_strip = $nikepeg ?? '-';
  $status = $d['status'];

  $riwayat_penyakit = $d['riwayat_penyakit'];
  $tanggal_mengisi_riwayat_penyakit = $d['tanggal_mengisi_riwayat_penyakit'];
  $gejala_penyakit = $d['gejala_penyakit'];
  $tanggal_mengisi_gejala_penyakit = $d['tanggal_mengisi_gejala_penyakit'];
  $gaya_hidup = $d['gaya_hidup'];
  $tanggal_mengisi_gaya_hidup = $d['tanggal_mengisi_gaya_hidup'];
  $keluhan = $d['keluhan'];
  $tanggal_mengisi_keluhan = $d['tanggal_mengisi_keluhan'];


  if (!$d['status_pasien']) {
    $s = "UPDATE tb_pasien SET status=1 WHERE id=$id_pasien";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo div_alert('success', 'Updating MCU Status berhasil.');
    jsurl();
  }
  if (!$d['foto_profil']) {
    $d['status_pasien'] = '<span class="red f14">Belum Upload Foto Profil</span>';
  }
  $id_paket = $d['id_paket'];
  foreach ($d as $key => $value) {
    if (
      $key == 'id'
      || $key == 'date_created'
    ) continue;

    $kolom = key2kolom($key);
    $tr .= "
      <tr>
        <td class=kolom>$kolom</td>
        <td>$value</td>
      </tr>
    ";
  }
}

$s2 = "SELECT 
a.nama as nama_pemeriksaan 
FROM tb_pemeriksaan a 
JOIN tb_paket_detail b ON a.id=b.id_pemeriksaan
WHERE b.id_paket='$id_paket'";
$q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
$li = '';
if (mysqli_num_rows($q2)) {
  while ($d2 = mysqli_fetch_assoc($q2)) {
    $li .= "<li>$d2[nama_pemeriksaan]</li>";
  }
}
$detail_pemeriksaan = "<ol>$li</ol>";

$tanggal_order_show = date('d-m-Y', strtotime($d['tanggal_order']));
$last_update_show = $d['last_update'] . ' | ' . eta(strtotime($d['last_update']) - strtotime('now'));
$id_pasien_show = substr("000$id_pasien", -4);




# ============================================================
# LIST STATUS PASIEN
# ============================================================
$s2 = "SELECT a.* 
FROM tb_status_pasien a ";
$q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q2)) {
  while ($d2 = mysqli_fetch_assoc($q2)) {
    $blue = $d2['status'] == $status ? 'biru tebal' : 'green f14';
    $blue = $d2['status'] > $status ? 'abu f14 miring' : $blue;
    $tr .= "
      <tr>
        <td><span class='$blue'>$d2[nama] ($d2[status])</span></td>
      </tr>
    ";
  }
}

$list_status = $tr ? "<table class=table>$tr</table>" : div_alert('danger', "Data status_pasien tidak ditemukan.");
$list_status = "<div class='wadah gradasi-kuning mt2 tengah'><h4>Info Status Pasien</h4>$list_status</div>";






# ============================================================
# BLOK INFO PAKET
# ============================================================
$blok_info_paket = "
  <div class='card mb4 gradasi-hijau'>
    <div class='card-body'>
      <h3>Paket Medical Checkup Anda</h3>
      <table class=table>
        <tr><td class=kolom>Paket</td><td>$d[paket]</td></tr>
        <tr><td class=kolom>Program</td><td>$d[program]</td></tr>
        <tr><td class=kolom>Didaftarkan oleh</td><td>$d[pendaftar]</td></tr>
        <tr><td class=kolom>Tanggal</td><td>$tanggal_order_show</td></tr>
        <tr>
          <td colspan=100%>
            <div class=''><span class='btn_aksi darkblue' id=detail_pemeriksaan__toggle>Lihat detail pemeriksaan $img_detail</span></div>
            <div id=detail_pemeriksaan class='mt2 hideit'>$detail_pemeriksaan</div>
          </td>
        </tr>
      </table>

      <h3 class='mt4 green'>Status Anda</h3>
      <div class='tengah biru tebal'>
        $d[status_pasien] ($status)
      </div>
      <div class=tengah><span class=btn_aksi id=list_status__toggle>$img_detail</span></div>
      <div id=list_status class=hideit>$list_status</div>

    </div>
  </div>
";


# ============================================================
# BLOK FOTO PROFIL
# ============================================================
include 'pasien_home-foto-profil.php';

# ============================================================
# BLOK BIODATA | JADWAL
# ============================================================
$blok_biodata = '';
$blok_jadwal = '';
$blok_kuesioner = '';
$blok_gaya_hidup = '';
$blok_keluhan = '';
$blok_kesiapan = '';

if ($foto_profil) {
  include 'pasien_home-biodata.php';
  include 'pasien_home-jadwal.php';
  include 'pasien_home-kuesioner.php';
  if ($status >= 4) include 'pasien_home-gaya-hidup.php';
  if ($status >= 5) include 'pasien_home-keluhan.php';
  if ($status >= 6) include 'pasien_home-kesiapan.php';
}








$BLOK = "
  <div id=info_pasien>


    $blok_info_paket
    $blok_foto_profil
    $blok_biodata
    $blok_jadwal
    $blok_kuesioner
    $blok_gaya_hidup
    $blok_keluhan
    $blok_kesiapan
    

  </div>

";
echo "$BLOK";
?>

<script>
  $(function() {
    $('#range_usia').change(function() {
      let val = $(this).val();
      $('#usia_text').text(val);
      $('#usia').val(val);
    });

    $('.usia_adjust').click(function() {
      let usia = $('#usia').val();
      if (!usia) usia = 20;
      let isi = $(this).text();
      if (isi == '+') {
        usia++;
      } else {
        usia--;
      }
      if (usia < 1) usia = 1;
      if (usia > 99) usia = 99;
      $('#usia').val(usia);
      $('#range_usia').val(usia);
      $('#usia_text').text(usia);
    });

    $('.input_bio').change(function() {
      let input_bio = document.getElementsByClassName('input_bio');
      console.log(input_bio);
    });


    $('#nikepeg').keyup(function() {
      // $(this).val(
      //   $(this).val()
      //   .replace(/[^0-9]/g, '')
      // );
      $('#digit_nikepeg').text($(this).val().length);

    })
    $('#nama_pasien').keyup(function() {
      $(this).val(
        $(this).val()
        .replace(/['"]/g, '`')
        .replace(/[!@#$%^&*()+\-_=\[\]{}.,;:\\|<>\/?~0-9]/gim, '')
        .replace(/  /g, ' ')
        .toUpperCase()
        // .replace(
        //   /\w\S*/g,
        //   function(txt) {
        //     return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        //   }
        // )
      );
    });
  })
</script>