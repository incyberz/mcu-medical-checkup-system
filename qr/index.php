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

  // echo "<br>$parameter";

  $j = 0;
  $id_pasien = '';
  $id_pemeriksaan = '';
  for ($i = 0; $i < strlen($parameter); $i++) {
    $j++;
    // if ($j % 2 == 0) echo '<hr>' . substr($parameter, $i, 1);
    if ($j % 2 == 0) $id_pasien .= substr($parameter, $i, 1);
    if ($j % 2 != 0) $id_pemeriksaan .= substr($parameter, $i, 1);
  }

  // echo '<hr>id_pasien: ';
  // echo intval($id_pasien);

  // echo '<hr>id_pemeriksaan: ';
  // echo intval($id_pemeriksaan);

  # ============================================================
  # XXXDDD => DDD = id_pemeriksaan
  # ============================================================
  $id_pemeriksaan = intval(substr($id_pemeriksaan, 3, 3));
  // echo "<hr>id_pemeriksaan: $id_pemeriksaan";

  include '../conn.php';
  $s = "SELECT awal_periksa FROM tb_hasil_pemeriksaan 
  WHERE id_pasien=$id_pasien";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {

    $d = mysqli_fetch_assoc($q);
    $awal_periksa = $d['awal_periksa'];

    $s = "SELECT 
    a.nama as nama_pemeriksaan, 
    b.nama as jenis_pemeriksaan 
    FROM tb_pemeriksaan a 
    JOIN tb_jenis_pemeriksaan b ON a.jenis=b.jenis 
    WHERE a.id=$id_pemeriksaan";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (mysqli_num_rows($q)) {
      $d = mysqli_fetch_assoc($q);
      $nama_pemeriksaan = $d['nama_pemeriksaan'];
      $jenis_pemeriksaan = $d['jenis_pemeriksaan'];
    } else {
      $is_valid = 0;
    }
  } else {
    $is_valid = 0;
  }

  $hijau  = $is_valid ? 'hijau' : 'merah';

  $valid_show = $is_valid ? "
    <h1 class='f26 green m2 mb4' style='margin-top: 55px;'>Document is Valid.</h1>
    <hr>
    <div>$jenis_pemeriksaan</div>
    <div>$nama_pemeriksaan</div>
    <hr>
    <div class=f12>
      <span>Created at:</span> 
      <div>$awal_periksa</div>
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