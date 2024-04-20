<?php
// header & topbar
$nama_sistem = 'Mutiara Medical Center';
$singkatan_sistem = 'MMC';
$header_logo = 'header-logo.png';
$no_wa = '6281381112693'; // meyda
$no_wa = '6287888129250'; // pa ahmad

$email = 'mmcpjk3@gmail.com';
$phone = '021-8909 5776';

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
$link_wa = "<a href='https://api.whatsapp.com/send?phone=$no_wa&text=$text_wa' target=_blank id=no_wa_marketing>$no_wa</a>";

$social_links = [
  'twitter' => '#',
  'facebook' => '#',
  'instagram' => '#',
  'linkedin' => '#'
];


//hero 
$welcome_msg = "Welcome to $nama_sistem";
$welcome_msg2 = "Kami adalah Penyedia Jasa Layanan Kesehatan Professional dan Komprehensif bidang Medical Checkup, In-House Clinic, dan Klinik Pratama";
$button_msg = "Program Kami";
$button_href = "#produk";

//why us
$mengapa_kami = "Mengapa Pilih $nama_sistem?";
$mengapa_kami_desc = "Target utama kami adalah <i>Corporate</i>, <i>basic-price</i> fully negotiable</i>. Kami memberikan biaya yang sangat terjangkau dan kompetitif dengan kualitas layanan yang profesional. Peralatan lengkap, onsite ke perusahaan, dokter yang berpengalaman, dan sistem informasi medis online realtime menjadikan kami sebagai pilihan terbaik untuk kebutuhan Pelayanan Kesehatan Anda.";
$keunggulan = [
  'High Security Data Rekam Medis' =>
  [
    'desc' => 'Data Rekam Medis Pasien kami jamin kerahasiaannya. Kami menggunakan teknologi enkripsi untuk setiap data sensitive, memisahkan data antar corporate (separated-database), dan data hanya dapat diakses oleh pihak yang berwenang.',
    'icon' => 'check-shield',
    'image' => 'high-security'
  ],
  'Realtime Reporting MCU' =>
  [
    'desc' => 'Anda sebagai Corporate maupun karyawan (individual) dapat memantau proses MCU dimulai proses pendaftaran, pemeriksaan, hingga hasil MCU dengan login masing-masing via smartphone',
    'icon' => 'mobile',
    'image' => 'high-security'
  ],
  'Biaya Sangat Terjangkau' =>
  [
    'desc' => 'Basic Price dari kami adalah the best price bagi Corporate/Lembaga Anda. Untuk biaya bersifat negotiable sesuai Jumlah Peserta MCU dan lokasi perusahaan Anda! Silahkan Anda hubungi Marketing kami dan buktikan!',
    'icon' => 'dollar-circle',
    'image' => 'high-security'
  ],
  'Onsite ke Perusahaan' =>
  [
    'desc' => 'Tidak perlu ada waktu yang terbuang! Dengan minimal 100 karyawan kami dapat melayani Medical Checkup di Perusahaan Anda (onsite)!',
    'icon' => 'car',
    'image' => 'high-security'
  ],
  'Program Pelayanan Medis' =>
  [
    'desc' => 'Pengalaman kami lebih dari 20 tahun di bidang Pelayanan Medis. Kami juga memiliki beberapa Klinik Pratama, In-House Clinic, serta Fasilitas Medical Checkup dan Laboratorium Medis',
    'icon' => 'medal',
    'image' => 'high-security'
  ],
  'Peralatan Medis Lengkap' =>
  [
    'desc' => 'Peralatan penunjang medis kami yang canggih dan terakreditasi Paripurna membuat waktu pemeriksaan bagi Anda (karyawan Anda) lebih cepat, efisien, dan akurat.',
    'icon' => 'cog',
    'image' => 'high-security'
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
