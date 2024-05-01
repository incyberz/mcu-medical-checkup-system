<?php
$judul = 'Pendaftar Home';
$sub_judul = "Selamat datang $nama_user di MMC Information System";
set_title($judul);
set_h2($judul, $sub_judul);
only(['pendaftar']);

$s = "SELECT * FROM tb_order WHERE username_pendaftar='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
