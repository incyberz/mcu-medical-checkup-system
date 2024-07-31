<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MMC-Kesimpulan MCU</title>
  <link rel="stylesheet" href="../assets/css/insho-styles.css">
</head>

<body>

  <?php
  $is_valid = 1;
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
  $is_valid = strtotime($tgl);
  $id_pasien = ($id_pasien_decrypt - 2024) / 7;

  echo "$id_pasien_decrypt 
  <br>$zid_pasien 
  <br>$id_pasien_zdate_rand 
  <br>$crop 
  <br>$id_pasien_decrypt 
  <br>id: $id_pasien 
  <br>$idymhs 
  <br>$tgl";

  $hijau  = $is_valid ? 'hijau' : 'merah';

  $valid_show = $is_valid ? "
    <h1 class='f26 green m2 mb4' style='margin-top: 55px;'>Document is Valid.</h1>
    <hr>
    <hr>
    <div class=f12>
    </div>
  " : "
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

  // echo $is_valid ? '<hr>VALID' : '<hr>INVALID';
  echo "
    <div class='flex flex-center' style='height:100vh; align-items: center'>
      <div class='wadah gradasi-$hijau tengah consolas' style='height:440px'>
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