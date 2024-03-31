<?php
echo "<h2>RIWAYAT PENGOBATAN</h2>";
$arr = [
  'TBC',
  'Serangan Jantung',
  'Tekanan Darah Tinggi',
  'Kencing Manis',
  'Stroke',
  'Kanker',
  'Kolesterol Tinggi',
  'Alergi Obat',
  'Alergi Makanan',
  'Operasi',
  'Rawat Inap',
];

foreach ($arr as $item) {
  $name = 'rp_' . strtolower($item);
  $name = str_replace(' ', '_', $name);
  echo radio_dan_input($item, $name, "Keterangan $item", 0, 0);
}
