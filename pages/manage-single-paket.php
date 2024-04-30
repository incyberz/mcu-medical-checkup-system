<?php
$judul = 'Manage Single Paket';
$sub_judul = '';
set_title($judul);
set_h2($judul, $sub_judul);
only(['admin', 'marketing']);


$s = "SELECT 
a.id as id_paket,
a.nama as nama_paket,
a.image as image_paket,
b.nama as nama_program,
(
  SELECT COUNT(1) FROM tb_paket_detail
  WHERE id_paket=a.id) count_paket_detail,
(
  SELECT COUNT(1) FROM tb_order
  WHERE id_paket=a.id) count_order
FROM tb_paket a 
JOIN tb_program b ON a.id_program=b.id
JOIN tb_jenis_program c ON b.jenis=c.jenis
WHERE b.id_klinik=$id_klinik";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  $tr = div_alert('danger', 'Belum ada program pada klinik ini.');
} else {
  $d = mysqli_fetch_assoc($q);

  $id_paket = $d['id_paket'];
  $nama_paket = $d['nama_paket'];
  $image_paket = $d['image_paket'];
  $nama_program = $d['nama_program'];
  $count_paket_detail = $d['count_paket_detail'];
  $count_order = $d['count_order'];
}

// list pemeriksaan
if (!$count_paket_detail) {
  $list_pemeriksaan = div_alert('danger', 'Belum ada detail pemeriksaan pada paket ini.');
} else {
  $list_pemeriksaan = '';
  // get list pemeriksaan
  $s = "SELECT b.nama as nama_pemeriksaan 
  FROM tb_paket_detail a 
  JOIN tb_pemeriksaan b ON a.id_pemeriksaan=b.id 
  WHERE id_paket = $id_paket";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    $list_pemeriksaan .= "<li>$d[nama_pemeriksaan] <span class=on-dev>$img_delete</span></li>";
  }
  $list_pemeriksaan = "<ol>$list_pemeriksaan</ol>";
}

// list order
if (!$count_paket_detail) {
  $list_order = div_alert('danger', 'Belum ada history order pada paket ini.');
} else {
  $list_order = '';
  // get list order
  $s = "SELECT 
  a.order_no,
  a.perusahaan, 
  a.tanggal_order,
  (SELECT nama FROM tb_status_order WHERE status=a.status) status_order 
  FROM tb_order a 
  WHERE a.id_paket = $id_paket";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    $order_no = $d['order_no'];
    $status_order = $d['status_order'] ?? 'belum diproses';
    $eta = eta(strtotime($d['tanggal_order']) - strtotime('now'));
    $list_order .= "<li><a href='?order&order_no=$order_no' class=on-dev>$d[perusahaan]</a> - <span class='f12 miring darkred consolas'>$status_order</span> - <span class='f12 miring darkblue'>$eta</span> <span class=on-dev>$img_delete</span></li>";
  }
  $list_order = "<ol>$list_order</ol>";
}

echo "
  <div class=wadah>
    <table class=table>
      <tr>
        <td>Nama Paket</td>
        <td>$nama_paket</td>
      </tr>
      <tr>
        <td>Program</td>
        <td>$nama_program</td>
      </tr>
      <tr>
        <td>Paket Detail</td>
        <td>
          $list_pemeriksaan
          <form method=post>
            <button class='on-dev btn btn-danger mb4' name=btn_delete_all_pemeriksaan value=$id_paket>Delete All Pemeriksaan</button>
          </form>
        </td>
      </tr>
      <tr>
        <td>Order History</td>
        <td>
          $list_order
          <div class='flexy flex-between'>
            <div>
              <span class='on-dev pointer hijau'>$img_add Tambah by Operator</span> 
            </div>
            <div>
              <form method=post>
                <button class='on-dev btn btn-danger mb4' name=btn_delete_all_order_history value=$id_paket>Delete All Order History</button>
              </form>
            </div>
          </div>
        </td>
      </tr>
    </table>
  </div>
";
