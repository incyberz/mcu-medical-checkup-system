<?php
$judul = 'Manage Order';
$img_up = img_icon('up');

$id_paket = $_GET['id_paket'] ?? '';
$nama_paket = $_GET['nama_paket'] ?? '';
$order_no = $_GET['order_no'] ?? '';
// $sub_judul = $nama_paket ? "Untuk Paket <b class=darkblue>$nama_paket</b> | <a href='?manage_order' class='f12'>All Paket</a>" : '';

$arr_filter = [];
if ($id_paket) array_push($arr_filter, "id_paket: $id_paket");
if ($nama_paket) array_push($arr_filter, "nama_paket: $nama_paket");
if ($order_no) array_push($arr_filter, "order_no: $order_no");

$filter_info = '';
if ($arr_filter) $filter_info = "<span class='f12 abu miring'>filtered by</span> : " . join(', ', $arr_filter) . " | <a href='?manage_order'>$img_up</a>";

set_title($judul);
set_h2($judul, $filter_info);
only(['admin', 'marketing']);
include 'include/arr_status_order.php';













# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_paket'])) {

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
(
  SELECT COUNT(1) FROM tb_pasien 
  WHERE order_no=a.order_no AND jadwal is not null) terjadwal, 
a.status,
a.username_pendaftar,
b.id as id_paket,
c.id as id_program,
b.singkatan as nama_paket,
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
        || $key == 'username_pendaftar'
        || $key == 'program'
        || $key == 'terjadwal'
        || $key == 'status'
        || $key == 'perusahaan'
        || $key == 'registered_pasien'
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

        // add status
        // $status = $d['status'];
        $icon = '';
        if ($status == 100) {
          $icon = "$img_check $img_check $img_check";
        } elseif ($status > 2) {
          $icon = "$img_check $img_check $img_check";
        } elseif ($status == 2) {
          $icon = "$img_check $img_check ";
        } elseif ($status == 1) {
          $icon = "$img_check ";
        }

        $style_ok = $status > 0 ? 'green' : '';
        $status_show = $status ? "$icon <div class='$style_ok f14'>$arr_status_order[$status]</div>" : "<span class='tebal red'>$img_warning Belum diperiksa</span>";

        // encap with link
        $value .= "<a href='?verifikasi-order&order_no=$order_no' >$status_show</a>";

        if ($status == -1) {
          $value .= "<div class='f12 abu'>dengan alasan $d[alasan_batal]";
        } else {
          $value .= "<div class='f12 abu mt1'>pesan: $d[pesan_tambahan]</div>";
        }
      } elseif ($key == 'jumlah_peserta') {
        if ($status > 0) {
          $icon_excel = $d['file_excel'] ? " - <a class=f12 href='$lokasi_excel/$d[file_excel]' target=_blank>Download $img_excel</a>" : '<div class="f12 abu miring">pendaftar belum upload</div>';
        } elseif ($status < 0) {
          $icon_excel = '<div class="f12 abu miring">(batal)</div>';
        } else {
          $icon_excel = '<div class="f12 abu miring">order belum diperiksa</div>';
        }

        $value = "<div>$value $icon_excel</div>";

        // registered_pasien
        if (!$d['registered_pasien']) {
          $reg_count = ($status < 0) ? '' : $d['registered_pasien']; //' <div class="f12 abu miring">belum di import</div>';
          if ($d['file_excel'] and !$d['registered_pasien']) { // file excel sudah ada tapi belum diupload
            $value .= "<a href='?list_pasien&order_no=$order_no'>$img_warning UPLOAD<div class='tebal red f14 mt1'>File Excel sudah ada. Segera upload!</div></a>";
          }
        } else {
          $reg_count = $d['registered_pasien'];
          $cek_icon = '';
          if ($d['file_excel'] and !$d['registered_pasien']) { // file excel sudah ada tapi belum diupload
            $reg_count = "$img_warning UPLOAD<div class='tebal red f14 mt1'>File Excel sudah ada. Segera upload!</div>";
            die('ZZZ');
          } elseif ($d['registered_pasien']) { // jika sudah ada count pasien
            if ($d['jumlah_peserta'] == $d['registered_pasien']) { // pasien lengkap
              $cek_icon = "Complete $img_check";
            } elseif ($d['jumlah_peserta'] > $d['registered_pasien']) {
              $cek_icon = "Imported $img_warning ";
            } else {
              $cek_icon = "Imported $img_warning ";
            }
            // $d['registered_pasien'] .= "<div class='f10 mt1'>$cek_icon</div>";
          }

          $value .= "<a href='?list_pasien&order_no=$order_no'>$reg_count <span class=f12>of $d[jumlah_peserta]</span> - <span class='f12 abu'>$cek_icon</span> $img_next</a>";

          // terjadwal
          $terjadwal = $d['terjadwal'] ?? 0;
          if ($d['terjadwal'] == $d['registered_pasien']) { // pasien lengkap
            $cek_icon = "Terjadwal $img_check";
          } else {
            $cek_icon = "Terjadwal $img_warning ";
          }

          $value .= "<div class=mt1><a href='?list_pasien&order_no=$order_no&mode=jadwal'>$terjadwal <span class=f12>of $d[registered_pasien]</span>  - <span class='f12 abu'>$cek_icon</span> $img_next</a></div>";
        }
      } elseif ($key == 'pendaftar') {
        $link_wa = '';
        if ($d['no_wa']) {
          $text_wa = "Selamat $waktu Saudara/i $d[pendaftar]! %0a%0aKami dari Marketing Mutiara Medical Center%0a%0aBerdasarkan order yang Anda pilih dengan Nomor Order: $order_no tanggal $tanggal_order_show $nama_paket $program, ...";
          $link_wa = "<div class='f10 mt1' style=min-width:100px><a href='https://api.whatsapp.com/send?phone=$whatsapp&text=$text_wa' target=_blank >$img_wa $d[no_wa]</a></div>";
        }
        $value = "$value $link_wa";
        $value .= "<div class='f12 abu mt1'>$d[jabatan]</div>";
        $value .= "<div class='f12 abu'>$d[perusahaan]</div>";

        // username
        if ($d['username_pendaftar']) {
          $value .= "<div class='mt1 abu f12'>$d[username_pendaftar] <a href='?login_as&username=$d[username_pendaftar]&role=pendaftar' target=_blank>$img_login_as</a></div>";
        }
      } elseif ($key == 'nama_paket') {
        $value .= "<div class='f14 miring abu'>$d[program]</div>";
      } elseif ($key == 'verifikator') {
        if ($value) {
          $value .= "<div class='f12'>$tanggal_verifikasi_show</div>";
        }
      }

      $value = $value ? $value : $null;
      $td .= "
        <td>
          <div class='$style_uncheck'>
            $value
            $icon_detail 
          </div>
        </td>
      ";
    }

    $tr_style = $status != '' ? 'gradasi-hijau' : 'background: linear-gradient(#fee,#fcc) !important';

    $tr .= "
      <tr style='$tr_style'>
        $td
      </tr>
    ";
  }
}

include 'include/select_program.php';

$count_next = $count_paket + 1;

echo "
  <style>td{background:none !important}</style>
  <table class=table>
    <thead>
      <th>No</th>
      $th
    </thead>
    $tr
  </table>
";
