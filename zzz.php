<?php
session_start();
$_SESSION['mmc_role'] = 'pendaftar';
$_SESSION['mmc_username'] = 'iinsholihin';
?>
<script>
  location.replace('index.php?pendaftar_home')
</script>