<?php
$judul = 'Upload Image Paket';
$sub_judul = "<a href='?manage_paket'>$img_prev</a>";
set_title($judul);
set_h2($judul, $sub_judul);
only(['admin', 'marketing']);

$id_paket = $_GET['id_paket'] ?? die(erid('id_paket'));

if (isset($_POST['btn_upload'])) {
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';

  include 'include/resize_img.php';

  $id_paket = $_POST['btn_upload'];
  $date = date('ymdHis');


  $path = 'assets/img/paket';
  $new_image = "$id_paket-$date.jpg";
  $tmp_image = $_FILES['image']['tmp_name'];

  echolog('move_uploaded_file');
  if (move_uploaded_file($tmp_image, "$path/$new_image")) {

    // resize image
    echolog('resize image');
    resize_img("$path/$new_image");

    // deleting image_old
    if (unlink("$path/$_POST[image_old]")) {
      // update database
      echolog('update data paket');
      $s = "UPDATE tb_paket SET image='$new_image' WHERE id=$id_paket";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    } else {
      echo div_alert('danger', 'Gagal menghapus image lama');
    }
  } else {
    echo div_alert('danger', 'Gagal move_uploaded_file');
  }

  echo div_alert('success', 'Upload sukses');
  jsurl('', 2000);
  exit;
}


$s = "SELECT 
a.id,
a.nama,
a.singkatan,
a.deskripsi,
a.image


FROM tb_paket a WHERE id='$id_paket'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'date_created'
      ) continue;

      $kolom = key2kolom($key);

      # ============================================================
      # IMAGE UPLOAD FORM
      # ============================================================
      $src = "assets/img/paket/$value";
      if ($key == 'image') {
        $value = "
        <form method=post enctype='multipart/form-data'>
          <input type=hidden name=image_old value='$d[image]'>

          <img src='$src' class='img-thumbnail' style='max-width:300px' >
          <div class='flexy mt2 mb4'>
            <div>
              <input type=file name=image accept=.jpg required class='form-control'>
            </div>
            <div>
              <button class='btn btn-primary' name=btn_upload value=$d[id]>Upload</button>
            </div>

          </div>
        </form>
        ";
      }

      $tr .= "
        <tr>
          <td>$kolom</td>
          <td>$value</td>
        </tr>
      ";
    }
  }
}

$tb = $tr ? "
  <table class=table>
    $tr
  </table>
" : div_alert('danger', "Data paket tidak ditemukan.");
echo "$tb";
