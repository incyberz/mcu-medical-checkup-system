<?php
echo "<h2>RIWAYAT KELUARGA (IBU/BAPAK)</h2>";
$arr = [
  'Serangan Jantung',
  'Tekanan Darah Tinggi',
  'Kencing Manis',
  'Stroke',
  'Kanker',
  'Kolesterol Tinggi',
  'Alergi Obat',
  'Alergi Makanan',
];

foreach ($arr as $item) {
  $name = 'rpk_' . strtolower($item);
  $name = str_replace(' ', '_', $name);
  echo radio_dan_input('Apakah Ayah/Ibu mengalami ' . $item, $name, "Keterangan $item", 0, 0);
}
