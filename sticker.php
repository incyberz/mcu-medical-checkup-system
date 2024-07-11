<style>
  #tb_label {
    max-width: 5cm;
  }

  #tb_label tr {
    max-width: 5cm;
  }

  #tb_label td {
    /* border: solid 1px red !important; */
    vertical-align: top;

  }

  .img-qr {
    margin-top: 0.2cm;
    height: 1.8cm;
  }

  .nama_sticker {
    margin-top: 0.35cm;
    font-family: consolas;
    font-size: 11px;
    overflow: hidden;
    white-space: nowrap;
    height: 16px;
  }

  .nomor_mcu {
    font-size: 12px;
    overflow: hidden;
    white-space: nowrap;
    height: 18px;
  }

  .nama_pasien {
    font-size: 10px;
    overflow: hidden;
    white-space: nowrap;
    height: 13px;
  }

  .nik_pasien {
    font-size: 10px;
    overflow: hidden;
    white-space: nowrap;
    height: 16px;
  }

  .nama_sticker,
  .nama_pasien,
  .nomor_mcu,
  .nik_pasien {
    /* border: solid 1px red; */
    border: none
  }
</style>
<?php
$print = $_GET['print'] ?? '';
if ($print)  $arr = explode('|||', $_POST['data']);



# ============================================================
# LOOP JUMLAH STICKER PAKET INI
# ============================================================

echo "<table id=tb_label>";

foreach ($arr as $key => $value) {
  if (!$value) continue;

  $arr2 = explode(',', $value);
  $nama_sticker = $arr2[0]  ?? die('Array index 0 belum terdefinisi');
  $nomor_mcu = $arr2[1] ?? die('Array index 1 belum terdefinisi');
  $nama_pasien = $arr2[2] ?? die('Array index 2 belum terdefinisi');
  $info_perusahaan = $arr2[3] ?? die('Array index 3 belum terdefinisi');

  echo  "
    <tr>
      <td>
        <img src='assets/img/qr.png' class='img-qr'/>
      </td>
      <td>
        <div class='nama_sticker'>$nama_sticker</div>
        <div class='nomor_mcu'>$nomor_mcu</div>
        <div class='nama_pasien'>$nama_pasien</div>
        <div class='nik_pasien'>$info_perusahaan</div>
      </td>
    </tr>
  ";
}


echo "</table>";
if ($print) echo "<script>window.print()</script>";
