<?php
$judul = 'Manage Order';

$id_paket = $_GET['id_paket'] ?? '';
$nama_paket = $_GET['nama_paket'] ?? '';
$order_no = $_GET['order_no'] ?? '';
$sub_judul = $nama_paket ? "Untuk Paket <b class=darkblue>$nama_paket</b> | <a href='?manage_order' class='f12'>All Paket</a>" : '';

set_title($judul);
set_h2($judul, $sub_judul);
only(['admin', 'marketing']);
include 'include/arr_status_order.php';













# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_paket'])) {
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  $s = "SELECT 1 FROM tb_paket WHERE id_program=$_POST[id_program]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $nomor = mysqli_num_rows($q) + 1;


  $s = "INSERT INTO tb_paket (
    id_program,
    no,
    nama,
    deskripsi
  ) VALUES (
    $_POST[id_program],
    $nomor,
    '$_POST[new_paket]',
    'deskripsi paket baru...'
  )";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Add Paket sukses. Silahkan pilih paket tersebut untuk editing selanjutnya.");
  jsurl('', 3000);
} elseif (isset($_POST['btn_delete_paket'])) {
  $s = "DELETE FROM tb_paket WHERE id = $_POST[btn_delete_paket]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Delete Paket sukses.");
  jsurl('', 3000);
}














$order_by = $_GET['order_by'] ?? 'tanggal_order DESC';
$sql_id_paket = $id_paket ? "a.id_paket=$id_paket" : 1;
$sql_order_no = $order_no ? "a.order_no='$order_no'" : 1;

$s = "SELECT 
a.order_no,
a.tanggal_order,
a.pendaftar,
a.perusahaan,
a.jumlah_peserta,
(
  SELECT COUNT(1) FROM tb_pasien 
  WHERE order_no=a.order_no) registered_pasien, 
a.status,
a.username_pendaftar,
b.id as id_paket,
c.id as id_program,
b.nama as nama_paket,
c.nama as program,
(
  SELECT file_excel FROM tb_user 
  WHERE id=a.diverifikasi_oleh) file_excel, 
(
  SELECT nama FROM tb_user 
  WHERE id=a.diverifikasi_oleh) verifikator,
a.tanggal_verifikasi,
a.jabatan, 
a.no_wa, 
a.pesan_tambahan, 
a.alasan_batal 

FROM tb_order a 
JOIN tb_paket b ON a.id_paket=b.id 
JOIN tb_program c ON b.id_program=c.id 
WHERE c.id_klinik=$id_klinik 
AND $sql_id_paket 
AND $sql_order_no 
ORDER BY $order_by
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$count_paket = mysqli_num_rows($q);

$tr = '';
if (!mysqli_num_rows($q)) {
  $tr = div_alert('danger', 'Belum ada program pada klinik ini.');
} else {
  $tr = '';
  $th = '';
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $order_no = $d['order_no'];
    $id_paket = $d['id_paket'];
    $id_program = $d['id_program'];
    $nama_paket = $d['nama_paket'];
    $program = $d['program'];
    $status = $d['status'];
    $tanggal_verifikasi_show = $d['tanggal_verifikasi'] ? date('d-M-y H:i', strtotime($d['tanggal_verifikasi'])) : $null_gray;
    $tanggal_order_show =  date('d-M-y H:i', strtotime($d['tanggal_order']));

    $td = "<td>$i</td>";
    foreach ($d as $key => $value) {
      if (
        $key == 'id_paket'
        || $key == 'id_program'
        || $key == 'order_no'
        || $key == 'diverifikasi_oleh'
        || $key == 'ip_address'
        || $key == 'pesan_tambahan'
        || $key == 'jabatan'
        || $key == 'alasan_batal'
        || $key == 'no_wa'
        || $key == 'file_excel'
        || $key == 'tanggal_verifikasi'
      ) continue;
      if ($i == 1) {
        $kolom = ucwords(str_replace('_', ' ', $key));
        $desc = $key == $order_by ? 'desc' : '';
        $th .= "<th><a href='?$parameter&order_by=$key $desc'>$kolom</a></th>";
      }
      $style_uncheck = $status ? '' : 'brown';
      $style_uncheck = $status == -1 ? 'f12 abu miring' : $style_uncheck;

      $icon_detail = '';
      if ($key == 'tanggal_order') {
        $value = "<span class='f12'>$tanggal_order_show</span>";
        $value .= "<div class='f10'>$order_no</div>";
      } elseif ($key == 'jumlah_peserta') {
        $icon_excel = $d['file_excel'] ? "<a href='$lokasi_excel/$d[file_excel]' target=_blank>$img_excel</a>" : '';

        $value .= "<div>$icon_excel</div>";
        $value .= "<div class='f12 abu mt1'>$d[pesan_tambahan]</div>";
      } elseif ($key == 'pendaftar') {
        $link_wa = '';
        if ($d['no_wa']) {
          $text_wa = "Selamat $waktu Saudara/i $d[pendaftar]! %0a%0aKami dari Marketing Mutiara Medical Center%0a%0aBerdasarkan order yang Anda pilih dengan Nomor Order: $order_no tanggal $tanggal_order_show $nama_paket $program, ...";
          $link_wa = "<div class='f10 mt1' style=min-width:100px><a href='https://api.whatsapp.com/send?phone=$whatsapp&text=$text_wa' target=_blank >$img_wa $d[no_wa]</a></div>";
        }
        $value = "$value $link_wa";
        $value .= "<div class='f12 abu mt1'>$d[jabatan]</div>";
      } elseif ($key == 'registered_pasien') {
        if ($d['file_excel'] and !$value) { // file excel sudah ada tapi belum diupload
          $value = "$img_warning UPLOAD<div class='tebal red f14 mt1'>File Excel sudah ada. Segera upload!</div>";
        } elseif ($value) { // jika sudah ada count pasien
          if ($d['jumlah_peserta'] == $d['registered_pasien']) { // pasien lengkap
            $cek = "$img_check $img_check Complete";
          } elseif ($d['jumlah_peserta'] > $d['registered_pasien']) {
            $cek = "$img_warning Sebagian";
          } else {
            $cek = "$img_check Melebihi";
          }
          $value .= "<div class='f10 mt1'>$cek</div>";
        } else { // jika count pasien masih nol dan belum ada file excel dari pendaftar
          $value = $null_gray;
        }

        //encap
        $value = "<a href='?list-pasien&order_no=$order_no'>$value</a>";
      } elseif ($key == 'status') {
        $icon = '';
        if ($value == 100) {
          $icon = "$img_check $img_check $img_check";
        } elseif ($value > 2) {
          $icon = "$img_check $img_check $img_check";
        } elseif ($value == 2) {
          $icon = "$img_check $img_check ";
        } elseif ($value == 1) {
          $icon = "$img_check ";
        }

        $style_ok = $value > 0 ? 'green' : '';
        $value = $value ? "$icon <div class='$style_ok f14'>$arr_status_order[$status]</div>" : "<span class='tebal red'>$img_warning Belum diperiksa</span>";

        // encap with link
        $value = "<a href='?verifikasi-order&order_no=$order_no' >$value</a>";

        if ($status == -1) {
          $value .= "<div class='f12 abu'>dengan alasan $d[alasan_batal]";
        }
      } elseif ($key == 'username_pendaftar') {
        if ($value) {
          $value .= "<div class='mt1'><a href='?login-as&username=$value' target=_blank>$img_login_as</a></div>";
          $value = "<div class='f12'>$value</div>";
        }
      } elseif ($key == 'verifikator') {
        if ($value) {
          $value .= "<div class='f12'>$tanggal_verifikasi_show</div>";
        }
      }

      $value = $value ? $value : $null_gray;
      $td .= "
        <td>
          <div class='$style_uncheck'>
            $value
            $icon_detail 
          </div>
        </td>
      ";
    }

    $tr_style = $status > 1 ? 'gradasi-hijau' : '';

    $tr .= "
      <tr class='$tr_style'>
        $td
      </tr>
    ";
  }
}

include 'include/select_program.php';

$count_next = $count_paket + 1;

echo "
  <table class=table>
    <thead>
      <th>No</th>
      $th
    </thead>
    $tr
  </table>
";
