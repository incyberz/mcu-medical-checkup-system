<?php
$arr = [
  'berat_badan' => [
    'blok' => 'input-range',
    'label' => 'Berat Badan',
    'type' => 'number',
    'placeholder' => '...',
    'value' => $mcu['berat_badan'],
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 40,
    'max' => 120,
    'minlength' => 0,
    'maxlength' => 0,
    'range' => [40, 50, 60, 70, 80, 90],
    'satuan' => 'kg'
  ],
  'tinggi_badan' => [
    'blok' => 'input-range',
    'label' => 'Tinggi Badan',
    'type' => 'number',
    'placeholder' => '...',
    'value' => $mcu['tinggi_badan'],
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 120,
    'max' => 200,
    'minlength' => 0,
    'maxlength' => 0,
    'range' => [140, 150, 160, 170, 180],
    'satuan' => 'cm'
  ],
  'lingkar_perut' => [
    'blok' => 'input-range',
    'label' => 'Lingkar Perut',
    'type' => 'number',
    'placeholder' => '...',
    'value' => $mcu['lingkar_perut'],
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 60,
    'max' => 120,
    'minlength' => 0,
    'maxlength' => 0,
    'range' => [70, 80, 90, 100],
    'satuan' => 'cm'
  ],
];


$s = "CREATE TABLE tb_pemeriksaan_detail (
    id INT(11) NOT NULL AUTO_INCREMENT,
    id_pemeriksaan INT(11) NOT NULL,

    blok  varchar(11) NOT NULL,
    label  varchar(11) NOT NULL,
    type  varchar(11)  NULL,
    placeholder  varchar(11) NOT NULL,
    value  INT(11) NOT NULL,
    required  INT(11) NOT NULL,
    class  varchar(11) NOT NULL,
    min  INT(11) NOT NULL,
    max  INT(11) NOT NULL,
    minlength  INT(11) NOT NULL,
    maxlength  INT(11) NOT NULL,
    minrange  INT(11) NOT NULL,
    maxrange  INT(11) NOT NULL,
    satuan  INT(11) NOT NULL)
";
