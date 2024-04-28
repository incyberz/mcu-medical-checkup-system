<?php
$judul = 'Manage Produk';
$sub_judul = '';
set_title($judul);
$tr = '';

$s = "SELECT 
a.id as id_paket,
a.nama as nama_paket,
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
  $tr = '';
  $th = '';
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_paket = $d['id_paket'];
    $td = "<td>$i</td>";
    foreach ($d as $key => $value) {
      if ($key == 'id_paket') continue;
      if ($i == 1) {
        $kolom = ucwords(str_replace('_', ' ', $key));
        $th .= "<th>$kolom</th>";
      }
      $td .= "<td>$value</td>";
    }
    $tr .= "
      <tr>
        $td
        <td>
          $img_edit 
          $img_delete
        </td>
      </tr>
    ";
  }
}



echo "

    <div class='section-title'>
      <h2>$judul</h2>
      <p>$sub_judul</p>
    </div>
    <table class=table>
      <thead>
        <th>No</th>
        $th
        <th>Aksi</th>
      </thead>
      $tr
    </table>
";
