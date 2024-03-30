<?php
echo "<h2>GEJALA PENYAKIT</h2>";
$arr = [
  ['Batuk Menahun', 'batuk'],
  ['TBC', 'tbc'],
  ['Asma', 'asma'],
  ['Radang Lambung/Maag', 'maag'],
  ['Diare Kronik (Menahun)', 'diare'],
  ['Susah BAB (Sembelit)', 'sembelit'],
  ['BAB Berdarah', 'bab-berdarah'],
  ['Infeksi Saluran Kencing (Nyeri)', 'infeksi-saluran-kencing'],
  ['Kencing Berdarah', 'kencing-berdarah'],
  ['Batu Ginjal', 'batu-ginjal'],
  ['Gagal Ginjal', 'gagal-ginjal'],
  ['Kecelakaan', 'kecelakaan'],
  ['Sakit Liver', 'sakit-liver'],
  ['Sakit Kuning', 'sakit-kuning'],
  ['Pingsan', 'pingsan'],
  ['Kejang', 'kejang'],
  ['Ayan/Sawan/Epilepsi', 'epilepsi'],
  ['Mata Ikan', 'mata-ikan'],
  ['Varises', 'varises'],
];

foreach ($arr as $item) {
  $name = 'rpk_' . strtolower($item[0]);
  $name = str_replace(' ', '_', $name);
  $img = ilustrasi($item[1]);
  echo "<div class='tengah'>$img</div>";
  echo radio_dan_textarea('Gejala ' . $item[0], $name, "Keterangan Gejala $item[0]", '', 0);
}
