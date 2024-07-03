<?php
$carousel = $_GET['carousel'] ?? '';
$judul = $carousel ? 'Upload Carousel Image' : 'Upload Image Paket';
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


  if ($carousel) {
    $path =  $lokasi_carousel;
    $new_image = "carousel-$id_paket-$date.jpg";
    $tmp_image = $_FILES['carousel']['tmp_name'];
  } else {
    $path =  $lokasi_paket;
    $new_image = "$id_paket-$date.jpg";
    $tmp_image = $_FILES['image']['tmp_name'];
  }

  $Image = $carousel ? 'Carousel' : 'Image';
  echolog("move_uploaded_file $Image");
  if (move_uploaded_file($tmp_image, "$path/$new_image")) {

    // resize image
    echolog("resize $Image");
    resize_img("$path/$new_image", '', 1280, 1000, 200, 200);

    // deleting image_old
    if ($_POST['image_old']) {
      if (unlink("$path/$_POST[image_old]")) {
        echolog("hapus $Image lama berhasil");
      } else {
        echo div_alert('danger', "Gagal menghapus $Image lama");
      }
    } else {
      echo div_alert('info', "$Image pertama kali diupload untuk paket ini");
    }
    // update database
    echolog("update data paket kolom $Image");
    $kolom = $carousel ? 'carousel_image' : 'image';
    $s = "UPDATE tb_paket SET $kolom='$new_image' WHERE id=$id_paket";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  } else {
    echo div_alert('danger', "Gagal move_uploaded_file $Image");
  }

  echo div_alert('success', "<a href='?manage_paket'>$img_prev Back to Manage Paket</a> | Upload $Image sukses");
  jsurl('', 5000);
  exit;
}


$s = "SELECT 
a.id,
a.nama,
a.singkatan,
a.deskripsi,
a.image,
a.carousel_image as carousel


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
      if ($key == 'image' and !$carousel) {
        $src = "assets/img/paket/$value";
        $belum_ada = '<span class="red miring">belum ada image</span>';
        $img = ($value and file_exists($src)) ? "<img src='$src' class='img-thumbnail' style='max-width:300px' >" : $belum_ada;

        $value = "
        <form method=post enctype='multipart/form-data'>
          <input type=hidden name=image_old value='$d[image]'>

          $img
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
      } elseif ($key == 'image' and $carousel) {
        $value = "<a href='?upload_image_paket&id_paket=$id_paket'>" . img_icon('upload_gray') . ' Upload Image</a>';
      } elseif ($key == 'carousel' and !$carousel) {
        $value = "<a href='?upload_image_paket&id_paket=$id_paket&carousel=1'>" . img_icon('upload_gray') . ' Upload Carousel</a>';
      } elseif ($key == 'carousel' and $carousel) {
        $src = "$lokasi_carousel/$value";
        $belum_ada = '<span class="red miring">belum ada carousel image</span>';
        $img = ($value and file_exists($src)) ? "<img src='$src' class='img-thumbnail' style='max-width:300px' >" : $belum_ada;

        $value = "
        <form method=post enctype='multipart/form-data'>
          <input type=hidden name=image_old value='$d[carousel]'>

          $img
          <div class='flexy mt2 mb4'>
            <div>
              <input type=file name=carousel accept=.jpg required class='form-control'>
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
