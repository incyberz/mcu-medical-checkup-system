<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MMC-QR Verification</title>
  <link rel="stylesheet" href="../assets/css/insho-styles.css">
</head>

<body>

  <?php
  $is_valid = 1;
  # ================================================
  # GET URL PARAMETER
  # ================================================
  $parameter = '';
  foreach ($_GET as $key => $value) {
    $parameter = $key;
    break;
  }

  if (strlen($parameter) == 12) {
    include 'qr12.php';
    $caption_date = 'Created at:';
  } elseif (strlen($parameter) == 15) {
    $caption_date = 'last update:';
    include 'qr14.php';
  } else {
    die('Invalid QR Code');
  }



  $hijau  = $is_valid ? 'hijau' : 'merah';

  $valid_show = $is_valid ? "
    <h1 class='f26 green m2 mb4' style='margin-top: 55px;'>Document is Valid.</h1>
    <hr>
    <div>$jenis_pemeriksaan</div>
    <div>$nama_pemeriksaan</div>
    <hr>
    <div class=f12>
      <span>$caption_date</span> 
      <div class=mt1>$awal_periksa</div>
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
        <img src='logo.png' alt='logo'>
        <div class='darkblue mt2 mb2 f20 bold'>QR-DOCUMENT SYSTEM</div>
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