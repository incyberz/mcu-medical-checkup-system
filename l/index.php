<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>List Hasil MCU</title>
  <link rel="stylesheet" href="../assets/css/insho-styles.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

  <?php


  $is_valid = 1;
  $day_expire = 7;
  # ================================================
  # GET URL PARAMETER
  # ================================================
  $key = '';
  foreach ($_GET as $key => $value) {
    $key = $key;
    break;
  }

  include '../include/enkrip14.php';
  $id_perusahaan = dekrip14($key);

  include '../conn.php';
  include '../include/arr_kesimpulan.php';

  $s = "SELECT 
  a.hasil as status_hasil,
  b.id as id_pasien, 
  b.nama as nama_pasien 
  FROM tb_hasil_pemeriksaan a 
  JOIN tb_pasien b ON a.id_pasien=b.id 
  JOIN tb_harga_perusahaan c ON b.id_harga_perusahaan=c.id
  WHERE c.id_perusahaan=$id_perusahaan 
  AND b.status = 10 -- selesai pemeriksaan 
  ORDER BY b.nama 
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $count_pasien = mysqli_num_rows($q);
  if ($count_pasien) {
    $tr = '';

    $i = 0;
    $count_arr_kesimpulan = [];
    $count_arr_kesimpulan[1] = 0;
    $count_arr_kesimpulan[2] = 0;
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      $id_pasien = $d['id_pasien'];
      $status_hasil = $d['status_hasil'];
      $kesimpulan = $arr_kesimpulan[0];
      if ($status_hasil) {
        $count_arr_kesimpulan[$status_hasil]++;
        $kesimpulan = $arr_kesimpulan[$status_hasil];
      }
      $tr .= "
        <tr>
          <td>$i</td>
          <td>$d[nama_pasien]</td>
          <td>[$status_hasil] $kesimpulan</td>
          <td>
            <form method=post action='../k/?' target=_blank>
              <button value=$id_pasien name=id_pasien class='btn btn-sm btn-success'>Hasil MCU</button>
            </form>
          </td>
          
        </tr>
      ";
    }

    if ($tr) {
      $count_arr_kesimpulan[0] = $count_pasien - $count_arr_kesimpulan[1] - $count_arr_kesimpulan[2];
      $valid_show = "
        <table class='kiri table table-hover table-striped'>
          $tr
        </table>

        <table class='kiri table table-hover table-striped'>
          <tr><td>$arr_kesimpulan[1]</td><td>$count_arr_kesimpulan[1]</td></tr>
          <tr><td>$arr_kesimpulan[2]</td><td>$count_arr_kesimpulan[2]</td></tr>
          <tr><td>$arr_kesimpulan[0]</td><td>$count_arr_kesimpulan[0]</td></tr>
        </table>

      ";
    }


    // include '../include/insho_functions.php';
    $date_now = date('Y-m-d H:i:s');
    // $selisih = $day_expire * 24 * 60 * 60 + $is_valid - strtotime('now');
    // $selisih =  60 * 60 + $is_valid - strtotime('now');
    // $eta = eta($selisih);
    $hijau  = 'hijau';
    // $tgl_time = strtotime($tgl);
    // $date_now_time = strtotime($date_now);

    // $valid_show = "
    //   <div>$nama</div>
    //   <div class='mb2 f12 abu'>MCU-$id_pasien</div>
    //   <a href='../?hasil_pemeriksaan&jenis=mcu' target=_blank class='btn btn-primary w-100 mb2'>Kesimpulan Fisik MCU</a>
    //   <a href='../?hasil_pemeriksaan&jenis=uri' target=_blank class='btn btn-primary w-100 mb2'>Hasil MCU Urine</a>
    //   <a href='../?hasil_pemeriksaan&jenis=hem' target=_blank class='btn btn-primary w-100 mb2'>Hasil MCU Darah</a>
    //   <a href='../?hasil_pemeriksaan&jenis=ron' target=_blank class='btn btn-primary w-100 mb2'>Hasil Rontgen</a>
    //   <div class='mb2 f12 abu'>link expire dalam $eta</div>
    // ";

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
    <div class='flex flex-center' style=' align-items: center'>
      <div class='p2 pt4 gradasi-$hijau tengah consolas' style='min-width:600px; min-height:100vh'>
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