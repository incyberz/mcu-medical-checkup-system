<?php
$s = "SELECT a.* 
FROM tb_klinik_data a 
WHERE id_klinik=$id_klinik
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q) == 0) {
  echo div_alert('danger', "Belum ada data klinik.");
} else {
  $klinik = [];
  while ($d = mysqli_fetch_assoc($q)) {
    $klinik[$d['field']] = $d['value'];
  }
}

// header & topbar
$nama_sistem = $klinik['nama_sistem'];
$singkatan_sistem = $klinik['singkatan_sistem'];
$whatsapp = $klinik['whatsapp'];
$email = $klinik['email'];
$telepon = $klinik['telepon'];
$alamat = $klinik['alamat'];
$header_logo = $klinik['header_logo'];

$twitter = $klinik['twitter'] ?? ''; // opsional
$facebook = $klinik['facebook'] ?? ''; // opsional
$instagram = $klinik['instagram'] ?? ''; // opsional
$linkedin = $klinik['linkedin'] ?? ''; // opsional

// header logo
$img_header_logo = $singkatan_sistem;
if ($header_logo) {
  $src = "assets/img/$header_logo";
  if (file_exists($src)) {
    $img_header_logo = "<a href='index.php' class='logo me-auto'><img src='$src' alt='$singkatan_sistem' class='img-fluid'></a>";
  } else {
    // warning logo missing
  }
}



$jam = date('H');

if ($jam >= 9) {
  $waktu = "Siang";
} elseif ($jam >= 15) {
  $waktu = "Sore";
} elseif ($jam >= 18) {
  $waktu = "Malam";
} else {
  $waktu = "Pagi";
}


$text_wa = "Selamat $waktu Customer Service $nama_sistem!%0a%0aSetelah melihat website $nama_sistem, saya ingin bertanya perihal:%0a%0a";
$href_wa = "https://api.whatsapp.com/send?phone=$whatsapp";
$link_wa = "<a href='$href_wa&text=$text_wa' target=_blank id=no_wa_marketing>$whatsapp</a>";

$social_links = [
  'twitter' => $twitter,
  'facebook' => $facebook,
  'instagram' => $instagram,
  'linkedin' => $linkedin
];


//hero 
$hero_header = $klinik['hero_header'] ?? "Welcome to SISTEM KAMI";
$hero_desc = $klinik['hero_desc'] ?? 'Kalimat welcome belum Anda isi...';
$hero_button = $klinik['hero_button'] ?? "Program Kami";
$hero_href = $klinik['hero_href'] ?? "#produk";
$bg_hero = $klinik['bg_hero'] ?? 'hero-bg.jpg';

//why us


$mengapa_kami = "Mengapa Pilih $nama_sistem?";
$mengapa_kami_desc = "Target utama kami adalah <i>Corporate</i>, <i>basic-price</i> fully negotiable</i>. Kami memberikan biaya yang sangat terjangkau dan kompetitif dengan kualitas layanan yang profesional. Peralatan lengkap, onsite ke perusahaan, dokter yang berpengalaman, dan sistem informasi medis online realtime menjadikan kami sebagai pilihan terbaik untuk kebutuhan Pelayanan Kesehatan Anda.";
$arr_keunggulan = [];
for ($i = 0; $i < 10; $i++) {
  $no = $i + 1;
  if (isset($klinik["why-us-$no"])) {
    $why_us = $klinik["why-us-$no"];
    if ($why_us) {
      $arr_tmp = explode('~~~', $why_us);
      $arr_keunggulan[$arr_tmp[0]]['icon'] = $arr_tmp[1];
      $arr_keunggulan[$arr_tmp[0]]['desc'] = $arr_tmp[2];
    }
  }
}


for ($i = 0; $i < 10; $i++) {
  $kelebihan[$i] = '';
  $kelebihan_desc[$i] = '';
  $kelebihan_icon[$i] = '';
}

$i = 0;
foreach ($arr_keunggulan as $key => $value) {
  $kelebihan[$i] = $key;
  $kelebihan_desc[$i] = $arr_keunggulan[$key]['desc'];
  $kelebihan_icon[$i] = $arr_keunggulan[$key]['icon'];
  $i++;
}




//produk
$produk = [
  'title' => 'Program Kami',
  'desc' => 'Buktikan dan dapatkan harga terbaik paling terjangkau untuk semua program kami!',
];


// Team
$team_header = $klinik['team_header'] ?? 'Dokter dan Team';
$team_desc = $klinik['team_desc'] ?? 'Perkenalkan kami adalah Para Dokter Professional dan juga Tim Medis berpengalaman.';
$gallery_header = $klinik['gallery_header'] ?? 'Gallery';
$gallery_desc = $klinik['gallery_desc'] ?? 'Berikut adalah kegiatan kami saat proses Medical Checkup';
