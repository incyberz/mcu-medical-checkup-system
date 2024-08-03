<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kesimpulan MCU Pasien</title>
  <link rel="stylesheet" href="../assets/css/insho-styles.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

  <?php
  $post_id_pasien = $_POST['id_pasien'] ?? '';
  $is_valid = 1;
  $day_expire = 7;
  # ================================================
  # GET URL PARAMETER
  # ================================================
  $zid_pasien = '';
  foreach ($_GET as $key => $value) {
    $zid_pasien = $key;
    break;
  }

  $pos = strpos($zid_pasien, '0');
  $id_pasien_zdate_rand = substr($zid_pasien, $pos + 1, 100);
  $crop = substr($id_pasien_zdate_rand, 0, strlen($id_pasien_zdate_rand) - 2);
  $id_pasien_decrypt = substr($crop, 0, strlen($crop) - 12);
  $idymhs = substr($crop, strlen($id_pasien_decrypt), 12);
  $arr = ['i', 'd', 'y', 'm', 'h', 's'];
  $part = [];
  $k = 0;
  foreach ($arr as $value) {
    $part[$value] = substr($idymhs, 0 + $k, 2);
    $k += 2;
  }
  $tgl = "$part[y]-$part[m]-$part[d] $part[h]:$part[i]:$part[s]";
  include '../conn.php';
  $is_valid = strtotime($tgl);
  $nama = '';
  if ($is_valid || $post_id_pasien) {
    $id_pasien = $post_id_pasien ? $post_id_pasien : ($id_pasien_decrypt - 2024) / 7;
    $_SESSION['mcu_id_pasien'] = $id_pasien;
    $s = "SELECT * FROM tb_pasien WHERE id=$id_pasien";

    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (mysqli_num_rows($q)) {
      $pasien = mysqli_fetch_assoc($q);
      $nama = strtoupper($pasien['nama']);
    }
  }

  // echo "$id_pasien_decrypt 
  // <br>$zid_pasien 
  // <br>$id_pasien_zdate_rand 
  // <br>$crop 
  // <br>$id_pasien_decrypt 
  // <br>id: $id_pasien 
  // <br>$idymhs 
  // <br>$tgl";

  if (($is_valid && $nama) || $post_id_pasien) {
    $hijau  = 'hijau';

    $expire_info = '';
    if (!$post_id_pasien) {
      $selisih = $day_expire * 24 * 60 * 60 + $is_valid - strtotime('now');
      include '../include/insho_functions.php';
      $eta = eta($selisih);
      $expire_info = "<div class='mb2 f12 abu'>link expire dalam $eta</div>";
    }

    $valid_show = "
      <div>$nama</div>
      <div class='mb2 f12 abu'>MCU-$id_pasien</div>
      <a href='../?hasil_pemeriksaan&jenis=mcu' target=_blank class='btn btn-primary w-100 mb2'>Kesimpulan Fisik MCU</a>
      <a href='../?hasil_pemeriksaan&jenis=uri' target=_blank class='btn btn-primary w-100 mb2'>Hasil MCU Urine</a>
      <a href='../?hasil_pemeriksaan&jenis=hem' target=_blank class='btn btn-primary w-100 mb2'>Hasil MCU Darah</a>
      <a href='../?hasil_pemeriksaan&jenis=ron' target=_blank class='btn btn-primary w-100 mb2'>Hasil Rontgen</a>
      $expire_info
    ";
  } else {
    $hijau  = 'merah';
    $valid_show = "
      <div class='red'>
        <h1 class='f24 red m2 mb4' style='margin-top: 55px;'>Document NOT Valid.</h1>
        <hr>
        <div>UNKNOWN DOCUMENT</div>
        <hr>
        <div class=f12 style='max-width: 250px'>
          Your document is not valid. You may call our team at MMC Homepage for more verification...
        </div>
      </div>
    
    ";
  }


  // echo $is_valid ? '<hr>VALID' : '<hr>INVALID';
  echo "
    <div class='flex flex-center' style='height:100vh; align-items: center'>
      <div class='p2 pt4 gradasi-$hijau tengah consolas' style='height:100vh'>
        <img src='../qr/logo.png' alt='logo'>
        <div class='darkblue mt2 mb2 f18 bold'>MEDICAL RESULT</div>
        <hr>
        $valid_show
        <div class='mb4' style='margin-top: 55px'>
          <a href='../?'>MMC Homepage</a>
        </div>
        <hr>
        <div class='f12 mt4'>Mutiara Medical System 2024</div>
      </div>
    </div>  
  ";
  ?>



</body>

</html>