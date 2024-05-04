<?php
$order_no = $_GET['order_no'] ?? die('index order_no belum terdefinisi.');
$judul = 'List Pasien';
set_title($judul);
only(['admin', 'marketing']);
include 'include/arr_status_order.php';













# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_pasien'])) {
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  $order_no = $_POST['btn_add_pasien'];

  $s = "INSERT INTO tb_pasien (
    id_klinik,
    order_no,
    nama
  ) VALUES (
    $id_klinik,
    '$order_no',
    '$_POST[new_pasien]'
  )";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Add Pasien sukses. Silahkan pilih data pasien tersebut untuk editing selanjutnya.");
  jsurl('', 1000);
} elseif (isset($_POST['btn_delete_pasien'])) {
  $s = "DELETE FROM tb_pasien WHERE id = $_POST[btn_delete_pasien]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Delete Pasien sukses.");
  jsurl('', 1000);
}













# ============================================================
# NORMAL FLOW
# ============================================================
$s = "SELECT a.*,
a.id as id_pasien,
concat('MCU-',a.id) as no_mcu  
FROM tb_pasien a WHERE order_no='$order_no'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_pasien = mysqli_num_rows($q);
$jumlah_pasien_show = number_format($jumlah_pasien, 0);

$sub_judul = "List Pasien pada Order No. $order_no sebanyak $jumlah_pasien_show orang";
set_h2($judul, $sub_judul);

$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $th = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_pasien = $d['id_pasien'];
    $td = '';
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'id_klinik'
        || $key == 'id_pasien'
        || $key == 'order_no'
        || $key == 'date_created'
      ) continue;
      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th>$kolom</th>";
      }
      $td .= "<td>$value</td>";
    }
    $tr .= "
      <tr>
        <td width=50px>$i</td>
        $td
        <td>
          <form method=post>
            <button class='btn-transparan' name=btn_delete_pasien value=$id_pasien onclick='return confirm(\"Hapus pasien ini?\")'>$img_delete</button>
          </form>
        </td>
      </tr>
    ";
  }
}

$tb = $tr ? "
  <table class=table>
    <thead><th width=50px>No</th>$th<th>Aksi</th></thead>
  </table>
  <div style='max-height:300px; overflow-y:scroll; background:#ffffdd55; position:relative'>
    <table class=table>
      $tr
    </table>
  </div>
  <div class='biru mt1 mb4'>Tekan Ctrl + F untuk pencarian data, Ctrl + Home/End menuju paling atas/bawah (klik dahulu pada tabel pasien)</div>
" : div_alert('danger', "Data pasien tidak ditemukan.");
echo "$tb";
?>
<form method=post class="wadah">
  <div class="f12 abu mb2">Form Penambahan Pasien Manual</div>
  <div class="flexy">
    <div>
      <input type="text" class="form-control" required name=new_pasien placeholder="Nama Pasien baru...">
    </div>
    <div>
      <button class="btn btn-success" name=btn_add_pasien value='<?= $order_no ?>'>Add Pasien</button>
    </div>
  </div>
</form>

<form method=post class="wadah">
  <div class="f12 abu mb2">Form Import Data Pasien</div>
  <p>Silahkan download File Excel yang di upload oleh user (atau via whatsapp) kemudian disesuaikan field-fieldnya, lalu import disini.</p>
  <div class="flexy">
    <div>
      <input type="file" class="form-control" required name=excel_pasien>
    </div>
    <div>
      <button class="btn btn-success" name=btn_import_pasien value='$order_no'>Import</button>
    </div>
  </div>
</form>