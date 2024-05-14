<?php
session_start();
$_SESSION['mmc_role'] = 'nakes';
$_SESSION['mmc_username'] = 'nakes1';
?>
<script>
  location.replace('index.php?cari-pasien')
</script>