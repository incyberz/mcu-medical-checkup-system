<?php
set_title('Replacer Hasil MCU');
$id_perusahaan = 29; //LJK
$remove_id_detail = 11; // buta warna
$replace_str = '||11=15||';
$replace_with = '||';
$count_replace = 0;
$count_replace_available = 0;
$s = "SELECT 
a.id as id_pasien,
a.nama as nama_pasien,
c.nama as perusahaan,
d.arr_hasil,
1

FROM tb_pasien a 
JOIN tb_harga_perusahaan b ON a.id_harga_perusahaan=b.id 
JOIN tb_perusahaan c ON b.id_perusahaan=c.id 
JOIN tb_hasil_pemeriksaan d ON d.id_pasien=a.id 
WHERE b.id_perusahaan=$id_perusahaan";

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $th = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $td = '';
    $id_pasien = $d['id_pasien'];
    $new_arr_hasil = '';
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'date_created'
      ) continue;
      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th>$kolom</th>";
      }

      if ($key == 'arr_hasil') {
        $sep = "||$remove_id_detail=";
        if (strpos("||$value", $sep)) $count_replace_available++;
        if (strpos("||$value", $sep) and strpos("||$value", $replace_str)) {
          $count_replace++;
          $tmp = explode($sep, $value);
          $old_value = $value;
          $value = $tmp[0] . "<b class='red f30'>$sep</b>" . $tmp[1];

          if ($replace_str and $replace_with) {
            $new_arr_hasil = str_replace($replace_str, $replace_with, $old_value);

            # ============================================================
            # MAIN EXECUTE REPLACE
            # ============================================================
            $s2 = "UPDATE tb_hasil_pemeriksaan SET arr_hasil='$new_arr_hasil' WHERE id_pasien=$id_pasien";
            echolog($s2);
            $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
          }
        }
      }


      $td .= "<td>$value</td>";
    }
    $tr .= "
      <tr>
        <td>$i</td>
        $td
        <td>$new_arr_hasil</td>
      </tr>
    ";
  }
}

$tb = $tr ? "
  <table class=table>
    <thead>
      <th>No</th>
      $th
      <th>Replaced String</th>
    </thead>
    $tr
  </table>
" : div_alert('danger', "Data XXX tidak ditemukan.");
echo "
  <h1>count_replace: $count_replace | available: $count_replace_available</h1>
  $tb 
";
