<?php
session_start();
$_SESSION['mmc_role'] = 'pasien';
$_SESSION['mmc_username'] = 'mcu-2';
?>
<script>
  location.replace('index.php?isi-kuesioner&id_program=1&id_pasien=2&start=1')
</script>