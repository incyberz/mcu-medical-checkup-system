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
</style>
<?php
$judul = 'Pasien Home';
$sub_judul = "Selamat datang $nama_user di MMC Information System";
set_title($judul);
set_h2($judul, $sub_judul);
only(['pasien', 'pendaftar']);
require_once 'include/mcu_functions.php';


$s = "SELECT 
a.*, 
a.id as id_pasien,
b.pendaftar,
b.tanggal_order,
c.id as id_paket,
c.nama as paket,
d.nama as program,
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
  if (!$d['status_pasien']) {
    $s = "UPDATE tb_pasien SET status=1 WHERE id=$id_pasien";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo div_alert('success', 'Updating MCU Status berhasil.');
    jsurl();
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
// $jadwal =  date('d-m-Y', strtotime('+13 day', strtotime($d['tanggal_order'])));
$jadwal = '10-05-2024'; // zzz
$jadwal_pukul = '13:20 WIB'; // zzz
$lokasi = 'Onsite di Perusahaan'; // zzz

$id_pasien_show = substr("000$id_pasien", -4);
$radio_gender = radio_tf('', 'gender', '', '', '', 'Laki-laki', 'Perempuan');

$tb = "
  <div id=info_pasien>
    <div class='card mb4 gradasi-hijau'>
      <div class='card-body'>
        <h3>Paket Medical Checkup Anda</h3>
        <table class=table>
          <tr><td class=kolom>Paket</td><td>$d[paket]</td></tr>
          <tr><td class=kolom>Program</td><td>$d[program]</td></tr>
          <tr><td class=kolom>Didaftarkan oleh</td><td>$d[pendaftar]</td></tr>
          <tr><td class=kolom>Tanggal</td><td>$tanggal_order_show</td></tr>
          <tr><td class=kolom>MCU Status</td><td>$d[status_pasien]</td></tr>
          <tr>
            <td colspan=100%>
              <div class=''><span class='btn_aksi darkblue' id=detail_pemeriksaan__toggle>Lihat detail pemeriksaan $img_detail</span></div>
              <div id=detail_pemeriksaan class='mt2 hideit'>$detail_pemeriksaan</div>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class='card mb4 gradasi-hijau'>
      <div class='card-body'>
        <h3>Biodata Anda</h3>
        <form method=post>
          <table class=table>
            <tr><td colspan=100%><input class='form-control tengah' value='$d[nama]' /></td></tr>
            <tr>
              <td colspan=100%>
                <div class='radio-toolbar abu'>
                  <div class='row'>
                    <div class='col-6'>
                      <input type='radio' name='gender' id='gender__l' class='opsi_radio' required value='l'>
                      <label class='proper f14' for='gender__l'>Laki-laki</label>
                    </div>
                    <div class='col-6'>
                      <input type='radio' name='gender' id='gender__p' class='opsi_radio' required value='p'>
                      <label class='proper f14 p0' for='gender__p'>Perempuan</label>
                    </div>
                  </div>
                </div>
              </td>
            </tr>

            <tr>
              <td colspan=100%>
                <input type=hidden name=usia value='' id=usia required>
                <div class='f12 abu tengah'> Usia <span id=usia_text>??</span> tahun</div>
                <table width=100%>
                  <tr>
                    <td width=30px>
                      <span class='btn btn-success btn-sm usia_adjust'>-</span>
                    </td>
                    <td>
                      <input type=range class='form-range' id=range_usia min=20 max=60 value=25>
                    </td>
                    <td width=30px>
                      <span class='btn btn-success btn-sm usia_adjust'>+</span>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr><td colspan=100%><input required class='form-control tengah' minlength=16 maxlength=16 value='$d[no_ktp]' placeholder='Nomor KTP' /></td></tr>
            <tr><td colspan=100%><input class='form-control tengah' minlength=5 maxlength=16 value='$d[nikepeg]' placeholder='Nomor Induk Karyawan' /></td></tr>
          </table>
          <button class='btn btn-primary w-100'>Update Biodata</button>
        </form>
      </div>
    </div>

    <div class='card mb4 gradasi-hijau'>
      <div class='card-body'>
        <h3>Jadwal Medical Checkup Anda</h3>
        <table class=table>
          <tr><td class='kolom tengah' colspan=100%>Nomor MCU / Antrian</td></tr>
          <tr>
            <td class=' tengah' colspan=100%>
              <span class='green f50'>$id_pasien_show</span>
            </td>
          </tr>
          <tr><td class=kolom>Jadwal</td><td>$jadwal</td></tr>
          <tr><td class=kolom>Pukul</td><td>$jadwal_pukul</td></tr>
          <tr><td class=kolom>Lokasi</td><td>$lokasi</td></tr>
        </table>
      </div>
    </div>

    

  </div>

";
echo "$tb";
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
    })
  })
</script>