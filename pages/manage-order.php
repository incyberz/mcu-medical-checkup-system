<?php
$judul = 'Manage Order';
$sub_judul = '';
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














$order_by = $_GET['order_by'] ?? 'date_created DESC';
$s = "SELECT 
a.*,
b.id as id_paket,
c.id as id_program,
b.nama as nama_paket,
c.nama as program,
(
  SELECT nama FROM tb_user 
  WHERE id=a.diverifikasi_oleh) verifikator 
FROM tb_order a 
JOIN tb_paket b ON a.id_paket=b.id 
JOIN tb_program c ON b.id_program=c.id 
WHERE c.id_klinik=$id_klinik 
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
        || $key == 'diverifikasi_oleh'
        || $key == 'ip_address'
        || $key == 'pesan_tambahan'
        || $key == 'jabatan'
        || $key == 'alasan_batal'
        || $key == 'no_wa'
      ) continue;
      if ($i == 1) {
        $kolom = ucwords(str_replace('_', ' ', $key));
        $desc = $key == $order_by ? 'desc' : '';
        $th .= "<th><a href='?$parameter&order_by=$key $desc'>$kolom</a></th>";
      }
      $style_uncheck = $status ? '' : 'brown';
      $style_uncheck = $status == -1 ? 'f12 abu miring' : $style_uncheck;

      $icon_detail = '';
      if ($key == 'order_no') {
        $value = "<a href='?verifikasi-order&order_no=$order_no' class='f12'>$value</a>";
      } elseif ($key == 'tanggal_verifikasi') {
        $value = $tanggal_verifikasi_show;
      } elseif ($key == 'tanggal_order') {
        $value = "<span class='f12'>$tanggal_order_show</span>";
      } elseif ($key == 'jumlah_peserta') {
        $value .= "<div class='f12 abu mt1'>$d[pesan_tambahan]</div>";
      } elseif ($key == 'pendaftar') {
        $link_wa = '';
        if ($d['no_wa']) {
          $text_wa = "Selamat $waktu Saudara/i $d[pendaftar]! %0a%0aKami dari Marketing Mutiara Medical Center%0a%0aBerdasarkan order yang Anda pilih dengan Nomor Order: $order_no tanggal $tanggal_order_show $nama_paket $program, ...";
          $link_wa = "<div class='f10 mt1' style=min-width:100px><a href='https://api.whatsapp.com/send?phone=$whatsapp&text=$text_wa' target=_blank >$img_wa $d[no_wa]</a></div>";
        }
        $value = "$value $link_wa";
        $value .= "<div class='f12 abu mt1'>$d[jabatan]</div>";
      } elseif ($key == 'status') {
        $icon = '';
        if ($value == 100) {
          $icon = "$img_check $img_check $img_check";
        } elseif ($value > 1) {
          $icon = "$img_check $img_check";
        } elseif ($value == 1) {
          $icon = "$img_check ";
        }

        $style_ok = $value > 0 ? 'green' : '';
        $value = $value ? "$icon <span class='$style_ok'>$arr_status_order[$status]</span>" : "<span class='tebal red'>$img_warning Belum diperiksa</span>";

        if ($status == -1) {
          $value .= "<div class='f12 abu'>dengan alasan $d[alasan_batal]";
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


    $tr .= "
      <tr>
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
