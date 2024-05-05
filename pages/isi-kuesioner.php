<?php
$judul = 'Kuesioner Online';

$id_program = $_GET['id_program'] ?? die('id_program belum didefinisikan.');

$Saudara = $gender == 'l' ? 'Saudara' : 'Saudari';
$sub_judul = "<span class=blue>Yth. $Saudara $nama_user! <br><br>Agar proses pemeriksaan Medical Checkup Anda lebih cepat, sangat disarankan Anda menjawab kuesioner berikut dengan sejujur-jujurnya agar kami dapat menyimpulkan dan merekomendasikan tentang kesehatan Anda secara tepat.</span>";
$start = $_GET['start'] ?? '';
if ($start) $sub_judul = "Login as: <span class=darkblue>$nama_user</span>";
only(['pasien']);

if (!$start) {
  set_title($judul);
  set_h2($judul, $sub_judul);
  $arr = explode('?', $_SERVER['REQUEST_URI']);
  echo "<a class='btn btn-primary w-100' href='?$arr[1]&start=1'>Mulai Mengisi Kuesioner</a>";
} else {
  echo "
    <div class='mb1 f14 abu'>Progress pengisian: 70%</div>
    <div class=progress>
      <div class=progress-bar role=progressbar aria-valuenow=70 aria-valuemin=0 aria-valuemax=100 style=width:70%  >
        <span class='sr-only'>70%</span>
      </div>
    </div>

    <div class='wadah mt2'>
      Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti quia quas vel provident accusamus excepturi odio vero harum id magnam, aut fugit minus sint doloribus at optio cupiditate officia voluptatibus.
    </div>
  ";
}
