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
$header_logo = $klinik['header-logo'];

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
$link_wa = "<a href='https://api.whatsapp.com/send?phone=$whatsapp&text=$text_wa' target=_blank id=no_wa_marketing>$whatsapp</a>";

$social_links = [
  'twitter' => '#',
  'facebook' => '#',
  'instagram' => '#',
  'linkedin' => '#'
];


//hero 
$hero_header = "Welcome to $nama_sistem";
$hero_desc = "Kami adalah Penyedia Jasa Layanan Kesehatan Professional dan Komprehensif bidang Medical Checkup, In-House Clinic, dan Klinik Pratama";
$hero_button = "Program Kami";
$hero_href = "#produk";
$bg_hero = 'hero-bg3.jpg';

//why us
$mengapa_kami = "Mengapa Pilih $nama_sistem?";
$mengapa_kami_desc = "Target utama kami adalah <i>Corporate</i>, <i>basic-price</i> fully negotiable</i>. Kami memberikan biaya yang sangat terjangkau dan kompetitif dengan kualitas layanan yang profesional. Peralatan lengkap, onsite ke perusahaan, dokter yang berpengalaman, dan sistem informasi medis online realtime menjadikan kami sebagai pilihan terbaik untuk kebutuhan Pelayanan Kesehatan Anda.";
$keunggulan = [
  'High Security Data Rekam Medis' =>
  [
    'desc' => 'Data Rekam Medis Pasien kami jamin kerahasiaannya. Kami menggunakan teknologi enkripsi untuk setiap data sensitive, memisahkan data antar corporate (separated-database), dan data hanya dapat diakses oleh pihak yang berwenang.',
    'icon' => 'check-shield',
  ],
  'Realtime Reporting MCU' =>
  [
    'desc' => 'Anda sebagai Corporate maupun karyawan (individual) dapat memantau proses MCU dimulai proses pendaftaran, pemeriksaan, hingga hasil MCU dengan login masing-masing via smartphone',
    'icon' => 'mobile',
  ],
  'Biaya Sangat Terjangkau' =>
  [
    'desc' => 'Basic Price dari kami adalah the best price bagi Corporate/Lembaga Anda. Untuk biaya bersifat negotiable sesuai Jumlah Peserta MCU dan lokasi perusahaan Anda! Silahkan Anda hubungi Marketing kami dan buktikan!',
    'icon' => 'dollar-circle',
  ],
  'Onsite ke Perusahaan' =>
  [
    'desc' => 'Tidak perlu ada waktu yang terbuang! Dengan minimal 100 karyawan kami dapat melayani Medical Checkup di Perusahaan Anda (onsite)!',
    'icon' => 'car',
  ],
  'Program Pelayanan Medis' =>
  [
    'desc' => 'Pengalaman kami lebih dari 20 tahun di bidang Pelayanan Medis. Kami juga memiliki beberapa Klinik Pratama, In-House Clinic, serta Fasilitas Medical Checkup dan Laboratorium Medis',
    'icon' => 'medal',
  ],
  'Peralatan Medis Lengkap' =>
  [
    'desc' => 'Peralatan penunjang medis kami yang canggih dan terakreditasi Paripurna membuat waktu pemeriksaan bagi Anda (karyawan Anda) lebih cepat, efisien, dan akurat.',
    'icon' => 'cog',
  ],
];


// tentang kami
$tentang = [
  'title' => "Tentang $nama_sistem",
  'desc' => 'Kami adalah Penyedia Jasa Pelayanan Kesehatan Terpadu di Bekasi - Jawa Barat',
  'video' => [
    'src' => '#',
    'bg' => 'assets/img/video-bg.jpg',
  ],
  'visi' => [
    'title' => 'Visi',
    'icon' => 'star',
    'desc' => 'Menjadi penyedia jasa Medical Checkup terpercaya dan terkemuka di Indonesia',
    'href' => '?visi-misi'
  ],
  'misi' => [
    'title' => 'Misi',
    'icon' => 'calendar-star',
    'list' => [
      'Memberikan layanan kesehatan yang professional, terjangkau, dan berkualitas tinggi',
      'Mengembangkan teknologi informasi untuk meningkatkan efisiensi dan kualitas layanan',
      'Menjadi mitra terpercaya bagi perusahaan dalam penyediaan layanan Medical Checkup',
      'Terus berinovasi untuk memberikan solusi kesehatan yang terbaik bagi masyarakat',

    ],
    'href' => '?visi-misi'
  ],
  'sejarah' => [
    'title' => 'Sejarah',
    'icon' => 'history',
    'list' => [
      '20 tahun berdiri sejak 2002 dan telah melayani berbagai perusahaan di Indonesia',
      'Didirikan oleh dr. Mutiara, seorang dokter dengan pengalaman lebih dari 30 tahun di bidang kesehatan',
      'Memiliki cabang klinik dan laboratorium di berbagai wilayah di Indonesia',
      'Telah melayani lebih dari 1 juta pasien seluruh Indonesia'

    ],
    'href' => '?sejarah'

  ],
  'goals' => [
    'title' => 'Goals',
    'icon' => 'medal',
    'list' => [
      'Menjadi pusat layanan Medical Checkup terbaik di Indonesia',
      'Membantu perusahaan menjaga kesehatan karyawannya melalui layanan Medical Checkup yang efektif dan efisien',
      'Meningkatkan kesadaran masyarakat akan pentingnya pemeriksaan kesehatan secara rutin',
      'Berkontribusi dalam meningkatkan kualitas hidup masyarakat Indonesia',

    ],
    'href' => '?goals'

  ]
];


//produk
$produk = [
  'title' => 'Program Kami',
  'desc' => 'Buktikan dan dapatkan harga terbaik paling terjangkau untuk semua program kami!',
];
