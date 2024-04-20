<?php
admin_only();

// if (isset($_POST['btn_order_paket'])) {
//   $order_no = $_POST['order_no'] ?? die(erid('order_no'));

//   $s = "SELECT 1 FROM tb_order WHERE order_no='$order_no'";
//   $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
// }

$judul = 'Manage Page';
set_title($judul);
$divs = '';
$p = $_GET['p'] ?? die(erid('p'));
echo "
<div class='section-title'>
  <h2>$judul</h2>
  <p>$back | Silahkan Anda melakukan $judul</p>
</div>
";

echo div_alert('danger', "$back | Maaf, page ini masih dalam tahap pengembangan. Terimakasih sudah mencoba!");
