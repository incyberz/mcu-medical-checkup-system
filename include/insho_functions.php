<?php
// v.1.3.7 set_h2 id, set_judul, set_sub_judul
// v.1.3.6 function gender
// v.1.3.5 eta2 updated
// v.1.3.4 seth2 dan key2kolom
// v.1.3.3 tr_col colspan=100%
// v.1.3.2 baca_csv update
// v.1.3.1 echolog update
// v.1.3.0 revision with echolog
// v.1.2.0 revision with function baca_csv

function set_h2($judul, $sub_judul = '')
{
  echo "
    <div class='section-title'>
      <h2 id=judul>$judul</h2>
      <p id=sub_judul>$sub_judul</p>
    </div>
  ";
}

function key2kolom($key)
{
  return ucwords(str_replace('_', ' ', $key));
}


function tr_col($pesan, $td_class = '', $tr_class = '', $jumlah_col = 100)
{
  $colspan = $jumlah_col < 10 ? $jumlah_col : "$jumlah_col%";
  return "<tr class='$tr_class'><td class='$td_class' colspan='$colspan'>$pesan</td></tr>";
}

function echolog($pesan, $break = true)
{
  $br = ($break and $pesan != 'sukses') ? '<br>' : '';
  $dots = ($break and $pesan != 'sukses') ? '... ' : '';
  $dots = '... ';
  echo "<span class='log'>$pesan$dots</span>$br";
}

function baca_csv($file, $separator = ',')
{

  if (file_exists($file)) {
    $file = fopen($file, 'r');
    $data = array();

    while (!feof($file)) {
      $data[] = fgetcsv($file, null, $separator);
    }

    fclose($file);
    return $data;
  } else {
    die("File <b class='consolas'>$file</b> tidak ditemukan.");
  }
}

function th($rank)
{
  if ($rank % 10 == 1) {
    return 'st';
  } elseif ($rank % 10 == 2) {
    return 'nd';
  } elseif ($rank % 10 == 3) {
    return 'rd';
  } else {
    return 'th';
  }
}

function hm($nilai)
{
  if ($nilai >= 85) {
    return 'A';
  } elseif ($nilai >= 70) {
    return 'B';
  } elseif ($nilai >= 60) {
    return 'C';
  } elseif ($nilai >= 40) {
    return 'D';
  } elseif ($nilai >= 1) {
    return 'E';
  } elseif ($nilai == 0) {
    return 'TL';
  } else {
    return false;
  }
}

function eta2($eta, $indo = 1)
{
  return eta(strtotime($eta) - strtotime('now'));
}

function eta($eta, $indo = 1)
{
  $menit = '';
  $jam = '';
  $hari = '';
  $minggu = '';
  $bulan = '';

  if ($eta >= 0) {
    if ($eta < 60) {
      return $indo ? "$eta detik lagi" : "$eta seconds left";
    } elseif ($eta < 60 * 60) {
      $menit = ceil($eta / 60);
      return $indo ? "$menit menit lagi" : "$menit minutes left";
    } elseif ($eta < 60 * 60 * 24) {
      $jam = ceil($eta / (60 * 60));
      return $indo ? "$jam jam lagi" : "$jam hours left";
    } elseif ($eta < 60 * 60 * 24 * 7) {
      $hari = ceil($eta / (60 * 60 * 24));
      return $indo ? "$hari hari lagi" : "$hari days left";
    } elseif ($eta < 60 * 60 * 24 * 7 * 4) {
      $minggu = ceil($eta / (60 * 60 * 24 * 7));
      return $indo ? "$minggu minggu lagi" : "$minggu weeks left";
    } elseif ($eta < 60 * 60 * 24 * 365) {
      $bulan = ceil($eta / (60 * 60 * 24 * 7 * 4));
      return $indo ? "$bulan bulan lagi" : "$bulan monts left";
    } else {
      $tahun = ceil($eta / (60 * 60 * 24 * 365));
      return $indo ? "$tahun tahun lagi" : "$tahun years left";
    }
  } else {
    if ($eta > -60) {
      $eta = -$eta;
      return $indo ? "$eta detik yang lalu" : "$eta seconds ago";
    } elseif ($eta > -60 * 60) {
      $menit = ceil($eta / 60);
      $menit = -$menit;
      return $indo ? "$menit menit yang lalu" : "$menit minutes ago";
    } elseif ($eta > -60 * 60 * 24) {
      $jam = ceil($eta / (60 * 60));
      $jam = -$jam;
      return $indo ? "$jam jam yang lalu" : "$jam hours ago";
    } elseif ($eta > -60 * 60 * 24 * 7) {
      $hari = ceil($eta / (60 * 60 * 24));
      $hari = -$hari;
      return $indo ? "$hari hari yang lalu" : "$hari days ago";
    } elseif ($eta > -60 * 60 * 24 * 7 * 4) {
      $minggu = ceil($eta / (60 * 60 * 24 * 7));
      $minggu = -$minggu;
      return $indo ? "$minggu minggu yang lalu" : "$minggu weeks ago";
    } elseif ($eta > -60 * 60 * 24 * 365) {
      $bulan = ceil($eta / (60 * 60 * 24 * 7 * 4));
      $bulan = -$bulan;
      return $indo ? "$bulan bulan yang lalu" : "$bulan monts ago";
    } else {
      $tahun = ceil($eta / (60 * 60 * 24 * 365));
      $tahun = -$tahun;
      return $indo ? "$tahun tahun yang lalu" : "$tahun years ago";
    }
  }
}

function jsurl($a = '', $milidetik = 0)
{ // v1.1 revision with duration milidetik
  if ($a == '') {
    $arr = explode('?', $_SERVER['REQUEST_URI']);
    jsurl("?$arr[1]", $milidetik);
    exit;
  }
  echo "
    <div class='consolas f12 abu'>Please wait, redirecting in $milidetik mili seconds...</div>
    <script>
      setTimeout(()=>{
        location.replace('$a');
      },$milidetik);
    </script>
  ";
  exit;
}

function jsreload()
{
  echo "<script>location.reload()</script>";
  exit;
}


// function penyebut($nilai) {
//   $nilai = abs($nilai);
//   $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
//   $temp = '';

//   if ($nilai < 12) {
//     $temp = " ". $huruf[$nilai];
//   } else if ($nilai <20) {
//     $temp = penyebut($nilai - 10). " belas";
//   } else if ($nilai < 100) {
//     $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
//   } else if ($nilai < 200) {
//     $temp = " seratus" . penyebut($nilai - 100);
//   } else if ($nilai < 1000) {
//     $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
//   } else if ($nilai < 2000) {
//     $temp = " seribu" . penyebut($nilai - 1000);
//   } else if ($nilai < 1000000) {
//     $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
//   } else if ($nilai < 1000000000) {
//     $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
//   } else if ($nilai < 1000000000000) {
//     $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
//   } else if ($nilai < 1000000000000000) {
//     $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
//   }     
//   return $temp;
// }

// function terbilang($nilai) {
//   if($nilai<0) {
//     $hasil = "minus ". trim(penyebut($nilai));
//   } else {
//     $hasil = trim(penyebut($nilai));
//   }         
//   return $hasil;
// }

function set_title($text)
{
  echo '<script>$(function(){$("title").text("' . $text . '");})</script>';
}

function set_judul($text, $sub_judul = '')
{
  $set_sub_judul = !$sub_judul ? '' : "$('#sub_judul').text('$sub_judul');";
  echo "
    <script>
      $(function(){
        $('#judul').text('$text');
        $set_sub_judul
      })
    </script>
  ";
}


function gender($lp)
{
  if ((strtolower($lp) == 'l')) {
    return 'Laki-laki';
  } elseif (strtolower($lp) == 'p') {
    return 'Perempuan';
  } elseif (strtolower($lp) == '') {
    return '<i>null</i>';
  } else {
    return "<i style=color:red>gender $lp undefined</i>";
  }
}

function tanggal($date, $format = 'd-M-Y')
{
  if (strtotime($date) > 0) {
    return date($format, strtotime($date));
  } else {
    if ($date == '') {
      return '<i>null</i>';
    } else {
      return '<i style=color:red>tanggal invalid</i>';
    }
  }
}

?>
<script>
  const rupiah = (number) => {
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR"
    }).format(number);
  }
</script>