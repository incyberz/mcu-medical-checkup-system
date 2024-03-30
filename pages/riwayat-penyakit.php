<h2>KUESIONER RIWAYAT PENYAKIT</h2>

<?php
echo radio_tf('Apakah Anda pernah mengalami Serangan Jantung?', 'jantung', 'pernah');
echo radio_tf('Apakah Anda mempunyai Tekanan Darah Tinggi (hipertensi)?', 'hipertensi',);
echo radio_tf('Apakah Anda penderita Kencing Manis?', 'kencing_manis',);
echo radio_tf('Apakah Anda pernah terkena Stroke?', 'stroke', 'pernah');
echo radio_tf('Apakah Anda punya Riwayat Kanker?', 'kanker',);
echo radio_tf('Apakah Anda punya Kolesterol Tinggi?', 'kolesterol',);
echo radio_tf('Apakah Anda punya alergi terhadap makanan?', 'alergi_makanan',);
echo radio_tf('Apakah Anda punya alergi terhadap obat-obatan?', 'alergi_obat',);
echo radio_tf('Apakah Anda punya gejala TBC?', 'gejala_tbc',);
echo radio_tf('Apakah Anda punya gejala Asthma?', 'gejala_asma',);
