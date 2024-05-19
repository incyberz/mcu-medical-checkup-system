<?php
$arr =  [
  'kepala_inspeksi' => [
    'blok' => 'multi-radio',
    'question' => 'Bentuk Kepala secara keseluruhan?',
    'value_default' => $mcu['kepala_inspeksi'] ?? 'Simetris', // default simetris
    'values' => ['Asimetris', 'Simetris'],
  ],
  'kepala_deformitas' => [
    'blok' => 'multi-radio',
    'question' => 'Apakah terdapat deformitas pada kepala?',
    'value_default' => $mcu['kepala_deformitas'] ?? 'Tidak ada', // default tidak ada
    'values' => ['Tidak ada', 'Ada'],
  ],
  'kepala_luka' => [
    'blok' => 'multi-radio',
    'question' => 'Apakah terdapat luka pada kepala?',
    'value_default' => $mcu['kepala_luka'] ?? 'Tidak ada', // default tidak ada
    'values' => ['Tidak ada', 'Ada'],
  ],
  'kepala_tumor' => [
    'blok' => 'multi-radio',
    'question' => 'Apakah terdapat tumor pada kepala?',
    'value_default' => $mcu['kepala_tumor'] ?? 'Tidak ada', // default tidak ada
    'values' => ['Tidak ada', 'Ada'],
  ],

  'separator',
  'mata_sklera' => [
    'blok' => 'multi-radio',
    'question' => 'Warna sklera pada mata?',
    'value_default' => $mcu['mata_sklera'] ?? 'Putih', // default putih
    'values' => ['Ikterik', 'Putih'],
    // 'labels' => ['Ikterik', 'Putih'],
  ],

  'separator',
  'hidung_sekret' => [
    'blok' => 'multi-radio',
    'question' => 'Sekret pada hidung?',
    'value_default' => $mcu['hidung_sekret'] ?? 'Normal', // default putih
    'values' => ['Bening', 'Normal', 'Purulent'],
  ],
  'hidung_polip' => [
    'blok' => 'multi-radio',
    'question' => 'Polip pada hidung?',
    'value_default' => $mcu['hidung_polip'] ?? 'Negatif', // default putih
    'values' => ['Negatif', 'Positif'],
  ],



  'separator',
  'mulut_faring' => [
    'blok' => 'multi-radio',
    'question' => 'Faring pada mulut?',
    'value_default' => $mcu['mulut_faring'] ?? 'Normal', // default putih
    'values' => ['Hiperemis', 'Normal'],
  ],
  'mulut_tonsil_kanan' => [
    'blok' => 'multi-radio',
    'question' => 'Tonsil kanan?',
    'value_default' => $mcu['mulut_tonsil_kanan'] ?? 'Normal', // default putih
    'values' => ['Tidak Normal', 'Normal'],
  ],
  'mulut_tonsil_kiri' => [
    'blok' => 'multi-radio',
    'question' => 'Tonsil kiri?',
    'value_default' => $mcu['mulut_tonsil_kiri'] ?? 'Normal', // default putih
    'values' => ['Tidak Normal', 'Normal'],
  ],
  'mulut_stomatitis' => [
    'blok' => 'multi-radio',
    'question' => 'Stomatitis?',
    'value_default' => $mcu['mulut_stomatitis'] ?? 'Negatif', // default putih
    'values' => ['Negatif', 'Positif'],
  ],




  'separator',
  'telinga_serumen_prop' => [
    'blok' => 'multi-radio',
    'question' => 'serumen_prop?',
    'value_default' => $mcu['telinga_serumen_prop'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'telinga_gendang_telinga' => [
    'blok' => 'multi-radio',
    'question' => 'gendang_telinga?',
    'value_default' => $mcu['telinga_gendang_telinga'] ?? 'Intak',
    'values' => ['Intak', 'Ruftuire'],
  ],

  'separator',
  'telinga_pembesaran_kgb' => [
    'blok' => 'multi-radio',
    'question' => 'pembesaran_kgb?',
    'value_default' => $mcu['telinga_pembesaran_kgb'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'telinga_kelenjar_tiroid' => [
    'blok' => 'multi-radio',
    'question' => 'kelenjar_tiroid?',
    'value_default' => $mcu['telinga_kelenjar_tiroid'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],



  'separator',
  'jantung_inpeksi_ictus_cording' => [
    'blok' => 'multi-radio',
    'question' => 'inpeksi_ictus_cording?',
    'value_default' => $mcu['jantung_inpeksi_ictus_cording'] ?? 'Terlihat',
    'values' => ['Tidak Terlihat', 'Terlihat'],
  ],
  'jantung_palpasi_ictus_cording' => [
    'blok' => 'multi-radio',
    'question' => 'palpasi_ictus_cording?',
    'value_default' => $mcu['jantung_palpasi_ictus_cording'] ?? 'Teraba',
    'values' => ['Tidak Teraba', 'Teraba'],
  ],
  'jantung_perkusi' => [
    'blok' => 'multi-radio',
    'question' => 'perkusi?',
    'value_default' => $mcu['jantung_perkusi'] ?? 'Teraba',
    'values' => ['Tidak Teraba', 'Teraba'],
  ],
  'jantung_aukultasi_lublub' => [
    'blok' => 'multi-radio',
    'question' => 'aukultasi_lublub?',
    'value_default' => $mcu['jantung_aukultasi_lublub'] ?? 'Normal',
    'values' => ['Tidak Normal', 'Normal'],
  ],
  'jantung_aukultasi_murmur' => [
    'blok' => 'multi-radio',
    'question' => 'aukultasi_murmur?',
    'value_default' => $mcu['jantung_aukultasi_murmur'] ?? 'Negatif',
    'values' => ['Positif (abnormal)', 'Negatif'],
  ],



  'separator',
  'paru_inpeksi' => [
    'blok' => 'multi-radio',
    'question' => 'paru_inpeksi?',
    'value_default' => $mcu['paru_inpeksi'] ?? 'Sistematis',
    'values' => ['Asimetris', 'Restraksi', 'Sistematis'],
  ],
  'paru_palpasi_vocal_fremitis' => [
    'blok' => 'multi-radio',
    'question' => 'paru_palpasi_vocal_fremitis?',
    'value_default' => $mcu['paru_palpasi_vocal_fremitis'] ?? 'Teraba',
    'values' => ['Tidak teraba', 'Teraba'],
  ],
  'paru_perkusi' => [
    'blok' => 'multi-radio',
    'question' => 'paru_perkusi?',
    'value_default' => $mcu['paru_perkusi'] ?? 'Sonor',
    'values' => ['Redup', 'Sonor'],
  ],
  'paru_aukultasi' => [
    'blok' => 'multi-radio',
    'question' => 'paru_aukultasi?',
    'value_default' => $mcu['paru_aukultasi'] ?? 'Vesikuler',
    'values' => ['Bronchovesikuler', 'Vesikuler'],
    'labels' => ['Bronchovesikuler (abnormal)', 'Vesikuler'],
  ],




  'separator',
  'suara_tamabahan_ronci' => [
    'blok' => 'multi-radio',
    'question' => 'suara_tamabahan_ronci?',
    'value_default' => $mcu['suara_tamabahan_ronci'] ?? 'Negatif',
    'values' => ['Positif', 'Negatif'],
    'labels' => ['Positif (abnormal)', 'Negatif'],
  ],
  'suara_tamabahan_wheezing' => [
    'blok' => 'multi-radio',
    'question' => 'suara_tamabahan_wheezing?',
    'value_default' => $mcu['suara_tamabahan_wheezing'] ?? 'Negatif',
    'values' => ['Positif', 'Negatif'],
    'labels' => ['Positif (abnormal)', 'Negatif'],
  ],



  'separator',
  'abdomen_inpeksi_abdomen' => [
    'blok' => 'multi-radio',
    'question' => 'abdomen_inpeksi_abdomen?',
    'value_default' => $mcu['abdomen_inpeksi_abdomen'] ?? 'Datar',
    'values' => ['Cembung', 'Datar'],
  ],
  'abdomen_aukultasi_bising_usus' => [
    'blok' => 'multi-radio',
    'question' => 'abdomen_aukultasi_bising_usus?',
    'value_default' => $mcu['abdomen_aukultasi_bising_usus'] ?? '3x per menit',
    'values' => ['Hipoperistaltik', '3x per menit', 'Hiperpristaltik'],
  ],
  'abdomen_palpasi_liver' => [
    'blok' => 'multi-radio',
    'question' => 'abdomen_palpasi_liver?',
    'value_default' => $mcu['abdomen_palpasi_liver'] ?? 'Tidak teraba',
    'values' => ['Tidak teraba', 'Teraba (ada pembesaran)'],
  ],
  'abdomen_palpasi_limpa' => [
    'blok' => 'multi-radio',
    'question' => 'abdomen_palpasi_limpa?',
    'value_default' => $mcu['abdomen_palpasi_limpa'] ?? 'Tidak teraba',
    'values' => ['Tidak teraba', 'Teraba (ada pembesaran)'],
  ],
  'abdomen_perkusi_perut' => [
    'blok' => 'multi-radio',
    'question' => 'abdomen_perkusi_perut?',
    'value_default' => $mcu['abdomen_perkusi_perut'] ?? 'Timpani (normal)',
    'values' => ['Abnormal', 'Timpani (normal)'],
  ],
  'abdomen_perkusi_liver' => [
    'blok' => 'multi-radio',
    'question' => 'abdomen_perkusi_liver?',
    'value_default' => $mcu['abdomen_perkusi_liver'] ?? 'Pekak (normal)',
    'values' => ['Abnormal', 'Pekak (normal)'],
  ],



  'separator',
  'ginjal_nyeri_ketok' => [
    'blok' => 'multi-radio',
    'question' => 'ginjal_nyeri_ketok?',
    'value_default' => $mcu['ginjal_nyeri_ketok'] ?? 'Positif',
    'values' => ['Negatif', 'Positif'],
  ],



  'separator',
  'external_atas_inpeksi_pergerakan_tangan' => [
    'blok' => 'multi-radio',
    'question' => 'external_atas_inpeksi_pergerakan_tangan?',
    'value_default' => $mcu['external_atas_inpeksi_pergerakan_tangan'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],
  'external_atas_inpeksi_kekuatan_otot' => [
    'blok' => 'multi-radio',
    'question' => 'external_atas_inpeksi_kekuatan_otot?',
    'value_default' => $mcu['external_atas_inpeksi_kekuatan_otot'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],
  'external_atas_palpasi_nyeri_tekan' => [
    'blok' => 'multi-radio',
    'question' => 'external_atas_palpasi_nyeri_tekan?',
    'value_default' => $mcu['external_atas_palpasi_nyeri_tekan'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'external_atas_palpasi_benjolan' => [
    'blok' => 'multi-radio',
    'question' => 'external_atas_palpasi_benjolan?',
    'value_default' => $mcu['external_atas_palpasi_benjolan'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'external_atas_motorik_besar_bentuk_otot' => [
    'blok' => 'multi-radio',
    'question' => 'external_atas_motorik_besar_bentuk_otot?',
    'value_default' => $mcu['external_atas_motorik_besar_bentuk_otot'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],
  'external_atas_motorik_keseimbangan' => [
    'blok' => 'multi-radio',
    'question' => 'external_atas_motorik_keseimbangan?',
    'value_default' => $mcu['external_atas_motorik_keseimbangan'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],
  'external_atas_reflek_fisiologis' => [
    'blok' => 'multi-radio',
    'question' => 'external_atas_reflek_fisiologis?',
    'value_default' => $mcu['external_atas_reflek_fisiologis'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],
  'external_atas_sensorik' => [
    'blok' => 'multi-radio',
    'question' => 'external_atas_sensorik?',
    'value_default' => $mcu['external_atas_sensorik'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],



  'separator',
  'external_bawah_inpeksi_pergerakan_kaki' => [
    'blok' => 'multi-radio',
    'question' => 'external_bawah_inpeksi_pergerakan_kaki?',
    'value_default' => $mcu['external_bawah_inpeksi_pergerakan_kaki'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],
  'external_bawah_inpeksi_kekuatan_otot' => [
    'blok' => 'multi-radio',
    'question' => 'external_bawah_inpeksi_kekuatan_otot?',
    'value_default' => $mcu['external_bawah_inpeksi_kekuatan_otot'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],
  'external_bawah_palpasi_nyeri_tekan' => [
    'blok' => 'multi-radio',
    'question' => 'external_bawah_palpasi_nyeri_tekan?',
    'value_default' => $mcu['external_bawah_palpasi_nyeri_tekan'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'external_bawah_palpasi_benjolan_massa' => [
    'blok' => 'multi-radio',
    'question' => 'external_bawah_palpasi_benjolan_massa?',
    'value_default' => $mcu['external_bawah_palpasi_benjolan_massa'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'external_bawah_motorik_besar_bentuk_otot' => [
    'blok' => 'multi-radio',
    'question' => 'external_bawah_motorik_besar_bentuk_otot?',
    'value_default' => $mcu['external_bawah_motorik_besar_bentuk_otot'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],
  'external_bawah_motorik_keseimbangan' => [
    'blok' => 'multi-radio',
    'question' => 'external_bawah_motorik_keseimbangan?',
    'value_default' => $mcu['external_bawah_motorik_keseimbangan'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],
  'external_bawah_reflek_fisiologis' => [
    'blok' => 'multi-radio',
    'question' => 'external_bawah_reflek_fisiologis?',
    'value_default' => $mcu['external_bawah_reflek_fisiologis'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],
  'external_bawah_sensorik' => [
    'blok' => 'multi-radio',
    'question' => 'external_bawah_sensorik?',
    'value_default' => $mcu['external_bawah_sensorik'] ?? 'Normal',
    'values' => ['Abnormal', 'Normal'],
  ],




  'separator',
  'anus_genetalia_hemokokel' => [
    'blok' => 'multi-radio',
    'question' => 'anus_genetalia_hemokokel?',
    'value_default' => $mcu['anus_genetalia_hemokokel'] ?? 'Tidak ada',
    'values' => ['Tidak ada', 'Ada'],
  ],
  'anus_genetalia_hernia' => [
    'blok' => 'multi-radio',
    'question' => 'anus_genetalia_hernia?',
    'value_default' => $mcu['anus_genetalia_hernia'] ?? 'Tidak ada',
    'values' => ['Tidak ada', 'Ada'],
  ],
  'anus_genetalia_romberg' => [
    'blok' => 'multi-radio',
    'question' => 'anus_genetalia_romberg?',
    'value_default' => $mcu['anus_genetalia_romberg'] ?? 'Tidak ada',
    'values' => ['Tidak ada', 'Ada'],
  ],




  'separator',
  'kulit_warna' => [
    'blok' => 'multi-radio',
    'question' => 'kulit_warna?',
    'value_default' => $mcu['kulit_warna'] ?? 'Cerah kecoklatan',
    'values' => ['Cerah kecoklatan', 'Putih', 'Hitam kecoklatan'],
  ],
  'kulit_lesi' => [
    'blok' => 'multi-radio',
    'question' => 'kulit_lesi?',
    'value_default' => $mcu['kulit_lesi'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'kulit_kelembapan' => [
    'blok' => 'multi-radio',
    'question' => 'kulit_kelembapan?',
    'value_default' => $mcu['kulit_kelembapan'] ?? 'Lembap (tidak basah)',
    'values' => ['Kulit Basah', 'Lembap (tidak basah)', 'Kulit Kering'],
  ],
  'kulit_suhu' => [
    'blok' => 'multi-radio',
    'question' => 'kulit_suhu?',
    'value_default' => $mcu['kulit_suhu'] ?? '36-37 derajat',
    'values' => ['< 36 derajat', '36-37 derajat', '> 37 derajat'],
  ],
  'kulit_tugor' => [
    'blok' => 'multi-radio',
    'question' => 'kulit_tugor?',
    'value_default' => $mcu['kulit_tugor'] ?? '< 2 detik',
    'values' => ['< 2 detik', '> 2 detik'],
  ],



  'separator',
  'rambut_warna' => [
    'blok' => 'multi-radio',
    'question' => 'rambut_warna?',
    'value_default' => $mcu['rambut_warna'] ?? 'Hitam',
    'values' => ['Hitam', 'Pirang', 'Putih', 'Coklat', 'Hitam kecoklatan'],
  ],
  'rambut_tiniea_capitis' => [
    'blok' => 'multi-radio',
    'question' => 'rambut_tiniea_capitis?',
    'value_default' => $mcu['rambut_tiniea_capitis'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'rambut_tiniea_corporis' => [
    'blok' => 'multi-radio',
    'question' => 'rambut_tiniea_corporis?',
    'value_default' => $mcu['rambut_tiniea_corporis'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],



  'separator',
  'kuku_warna_dasar' => [
    'blok' => 'multi-radio',
    'question' => 'kuku_warna_dasar?',
    'value_default' => $mcu['kuku_warna_dasar'] ?? 'Merah muda cerah',
    'values' => ['Merah muda cerah', 'kebiruan', 'Putih polos', 'Merah pucat'],
  ],
  'kuku_capilla_refill_tine' => [
    'blok' => 'multi-radio',
    'question' => 'kuku_capilla_refill_tine?',
    'value_default' => $mcu['kuku_capilla_refill_tine'] ?? '< 2 detik',
    'values' => ['< 2 detik', '> 2 detik'],
  ],
  'separator'
];





// $arr['kuku']['warna dasar'] = [['merah muda cerah', 'kebiruan', 'putih polos', 'merah pucat'], 'merah muda cerah'];
// $arr['kuku']['capilla refill tine (crt)'] = [['<2 detik', '>2 detik'], '<2 detik'];

// $count = count($arr);
