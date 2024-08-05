<?php
# ============================================================
# PROCESSING PEMERIKSAAN FISIK AWAL
# ============================================================
# 1. Tinggi Badan => 2,
# 2. Berat Badan => 1,
# 3. Lingkar Perut => 6,
# 4. IMT => fx,
# 5. Status Lingkar Perut => fx,
# 6. Tes Buta warna => 11 fx,
# 1. Tekanan Darah => [7, 8],
# 2. Nadi => 140,
# 3. Pernafasan => 9,
# 4. Suhu => 148,
# 5. Saturasi Oksigen => 10,
# 6. Pakai Kacamata => 13,
# ============================================================
$pasien['pemeriksaan_fisik_awal'] = [
  'ki' => [
    'Tinggi Badan' => "$arr_id_detail[2] " . $arr_pemeriksaan_detail[2]['satuan'],
    'Berat Badan' => "$arr_id_detail[1] " . $arr_pemeriksaan_detail[1]['satuan'],
    'Lingkar Perut' => "$arr_id_detail[6] " . $arr_pemeriksaan_detail[6]['satuan'],
    'IMT' => 'FX',
    'Resiko Lingkar Perut' => 'FX',
    'Tes Buta Warna' => 'FX',
  ],
  'ka' => [
    'Tekanan Darah' => "$arr_id_detail[7]/$arr_id_detail[8] " . $arr_pemeriksaan_detail[7]['satuan'],
    'Nadi' => "$arr_id_detail[140] " . $arr_pemeriksaan_detail[140]['satuan'],
    'Pernafasan' => "$arr_id_detail[9] " . $arr_pemeriksaan_detail[9]['satuan'],
    'Suhu' => "$arr_id_detail[148] " . $arr_pemeriksaan_detail[148]['satuan'],
    'Saturasi Oksigen' => "$arr_id_detail[10] " . $arr_pemeriksaan_detail[10]['satuan'],
    'Pakai Kacamata' => "$arr_id_detail[13] " . $arr_pemeriksaan_detail[13]['satuan'],
  ],
];
