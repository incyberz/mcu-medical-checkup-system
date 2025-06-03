<?php

set_title('Kirim Link ke HRD');
$id_perusahaan = $_GET['id_perusahaan'] ?? die(div_alert('danger', "Page ini membutuhkan index [id_perusahaan]"));
if (isset($_POST['btn_submit'])) {
  $s = "UPDATE tb_perusahaan SET 
    whatsapp = '$_POST[whatsapp]',
    nama_kontak = '$_POST[nama_kontak]',
    jabatan_kontak = '$_POST[jabatan_kontak]',
    gender_kontak = '$_POST[gender_kontak]'
  WHERE id=$id_perusahaan
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}







# ============================================================
# ARRAY TANGGAL 
# ============================================================
$arr_tanggal = [];
$count_arr_tanggal = [];
$s = "SELECT p.awal_periksa FROM tb_hasil_pemeriksaan p 
  JOIN tb_pasien q ON p.id_pasien=q.id 
  JOIN tb_harga_perusahaan r ON q.id_harga_perusahaan=r.id 
  WHERE r.id_perusahaan=$id_perusahaan  
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $tanggal = date('Y-m-d', strtotime($d['awal_periksa']));
  if (!in_array($tanggal, $arr_tanggal)) array_push($arr_tanggal, $tanggal);
  if (isset($count_arr_tanggal[$tanggal])) {
    $count_arr_tanggal[$tanggal]++;
  } else {
    $count_arr_tanggal[$tanggal] = 1;
  }
}






# ============================================================
# PERUSAHAAN PROPERTI
# ============================================================
$s = "SELECT 
a.nama as nama_perusahaan,
a.telepon,
a.whatsapp,
a.nama_kontak,
a.jabatan_kontak,
a.gender_kontak,
(
  SELECT COUNT(1) FROM tb_hasil_pemeriksaan p 
  JOIN tb_pasien q ON p.id_pasien=q.id 
  JOIN tb_harga_perusahaan r ON q.id_harga_perusahaan=r.id 
  WHERE r.id_perusahaan=a.id 
  AND 1 -- q.status = 10 -- pasien selesai pemeriksaan 
  ) total_peserta,
(
  SELECT p.awal_periksa FROM tb_hasil_pemeriksaan p 
  JOIN tb_pasien q ON p.id_pasien=q.id 
  JOIN tb_harga_perusahaan r ON q.id_harga_perusahaan=r.id 
  WHERE r.id_perusahaan=a.id 
  AND 1 -- q.status = 10 -- pasien selesai pemeriksaan 
  ORDER BY p.awal_periksa DESC 
  LIMIT 1
  ) last_awal_periksa,
('') kirim_link 
FROM tb_perusahaan a 
WHERE a.id='$id_perusahaan'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $last_awal_periksa = hari_tanggal($d['last_awal_periksa'], 1, 0);
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'date_created'
        || $key == 'nama_kontak'
        || $key == 'jabatan_kontak'
        || $key == 'gender_kontak'
        || $key == 'last_awal_periksa'
      ) continue;

      if ($key == 'kirim_link') {
        $edit_kontak = $_GET['edit_kontak'] ?? '';
        if (!($d['whatsapp'] and $d['nama_kontak'] and $d['nama_kontak'] and $d['gender_kontak']) || $edit_kontak) {
          $value = "
            <form method=post class='wadah gradasi-toska'>
              <div class='blue bold mb2'>Form Kontak Perusahaan</div>
              <table class='table td_trans'>
                <tr><td>Nomor Whatsapp</td><td>
                  <input required minlength=10 maxlength=14 class='form-control' id=whatsapp name=whatsapp value='$d[whatsapp]' placeholder='Whatsapp...'>
                </td></tr>
                <tr><td>Nama Kontak (HRD)</td><td>
                  <input required minlength=3 maxlength=30 class='form-control' id=nama_kontak name=nama_kontak value='$d[nama_kontak]' placeholder='Nama...'>
                </td></tr>
                <tr><td>Jabatan</td><td>
                  <select class='form-control' id=jabatan_kontak name=jabatan_kontak >
                    <option>HRD</option>
                    <option>Marketing</option>
                    <option>Kepala Cabang</option>
                    <option>Pimpinan</option>
                  </select>
                </td></tr>
                <tr><td>Gender</td><td>
                  <label>
                    <input required type=radio class='' id=gender_kontak_l name=gender_kontak value='l'> Laki-laki
                  </label>
                  <br>
                  <label>
                    <input required type=radio class='' id=gender_kontak_p name=gender_kontak value='p'> Perempuan
                  </label>
                </td></tr>
                <tr><td>&nbsp;</td><td>
                  <button class='btn btn-primary' name=btn_submit>Submit</button>
                  <a class='btn btn-secondary' href='?rekap_perusahaan&id_perusahaan=$id_perusahaan&mode=kirim_link'>Batal</a>
                </td></tr>
              </table>
            </form>
          ";
        } else { // kontak ready
          include 'include/enkrip14.php';
          $zid_perusahaan = enkrip14($id_perusahaan);
          $value = 'KIRIM LINK';

          $Tn = strtoupper($d['gender_kontak']) == 'L' ? 'Tn' : 'Ny';
          $link_akses = urlencode("https://mmc-clinic.com/l/?");
          $text_wa = "Selamat $waktu $Tn. $d[nama_kontak],%0a%0aBerikut adalah Rekapitulasi MCU Karyawan $d[nama_perusahaan]. %0a%0aLink Akses:%0a$link_akses$zid_perusahaan";

          $href_wa_header = "https://api.whatsapp.com/send?phone=$d[whatsapp]&text=";
          $link_wa = "<a id=link_wa target=_blank href='#'>$img_wa</a>";

          $href_wa_footer = "%0a%0a_Mutiara Medical System, $now _";

          $tanggals = '
            <div class="hideit bg-red" id=href_wa_header>' . $href_wa_header . '</div>
            <div class="hideit bg-red" id=href_wa_footer>' . $href_wa_footer . '</div>
            <div class="hideit bg-red" id=text_wa>' . $text_wa . '</div>
            <div class="hideit bg-red" id=link_wa_debug>link_wa</div>
            <div class="hideit bg-red" id=tanggals>tanggals</div>
            <div>Tanggal Periksa: </div>
          ';
          foreach ($arr_tanggal as $k => $v) {
            $hari = hari_tanggal($v, 1, 1, 0, 0);
            $tanggals .= "
              <div>
                <label>
                  <input type=checkbox class=tanggal_periksa value='$v'> $hari ~ $count_arr_tanggal[$v] pasien
                </label>
              </div>
            ";
          }

          $r = explode('?', $_SERVER['REQUEST_URI']);
          $value = "
            $tanggals
            <div class='wadah mt2 hideit' id=kirim_ke>
              Kirim ke : $d[whatsapp], $Tn. $d[nama_kontak] ($d[jabatan_kontak]) ~ $link_wa 
              <div>
                <a class='f14' href='?$r[1]&edit_kontak=1'>Edit Kontak $img_edit</a>
              </div>
            </div>
          ";
        }
      }

      $kolom = key2kolom($key);
      $tr .= "
        <tr>
          <td width=200px>$kolom</td>
          <td>$value</td>
        </tr>
      ";
    }
  }
}

$tb = $tr ? "
  <table class=table>
    $tr
  </table>
" : div_alert('danger', "Data perusahaan tidak ditemukan.");
echo "$tb";



?>
<script>
  function toTitleCase(str) {
    return str.replace(
      /\w\S*/g,
      function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      }
    );
  }

  $(function() {
    $('#whatsapp').keyup(function() {
      let val = $(this).val();

      if (val.length > 2) {
        if (val.substring(0, 1) == '0') {
          $(this).val('62' + val.substring(1, 100));
        }
      }

      $(this).val(
        $(this).val().replace(/[^0-9]/g, '')
      )
    })
    $('#nama_kontak').keyup(function() {
      $(this).val(toTitleCase($(this).val()));
    });

    $('.tanggal_periksa').click(function() {
      let text_wa = $('#text_wa').text();
      if ($('.tanggal_periksa:checked').length) {
        $('#kirim_ke').show();
        let tanggals = '';
        $('.tanggal_periksa:checked').each(function() {
          tanggals += $(this).val() + ',';
        })
        $('#tanggals').text(tanggals);
        let href =
          $('#href_wa_header').text() +
          text_wa +
          '%26tanggals=' + tanggals +
          $('#href_wa_footer').text();
        $('#link_wa').prop('href', href);
      } else {
        $('#kirim_ke').hide();

      }
      $('#link_wa_debug').text(text_wa);
    });
  })
</script>