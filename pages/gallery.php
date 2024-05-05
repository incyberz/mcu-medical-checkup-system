<?php
include 'include/resize_img.php';

















# ===============================================================
# PROCESSORS
# ===============================================================

if (isset($_POST['btn_add_gallery'])) {
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  echo '<pre>';
  var_dump($_FILES);
  echo '</pre>';
  $new_image = "gallery-$_POST[new_nomor]-$detik.jpg";
  $new_image_thumb = "gallery-$_POST[new_nomor]-$detik-thumb.jpg";
  $s = "INSERT INTO tb_gallery (
    nomor,
    image
  ) VALUES (
    $_POST[new_nomor],
    '$new_image'
  )";
  echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $img_asal = $_FILES['image']['tmp_name'];
  $img_baru = "$lokasi_gallery/$new_image";
  $img_thumb = "$lokasi_gallery/$new_image_thumb";
  move_uploaded_file($img_asal, $img_baru);

  echo resize_img($img_baru);
  echo resize_img($img_baru, $img_thumb, 250, 250);
  echo div_alert('success', 'Upload image and creating thumbnail success');
  jsurl('', 2000);
  exit;
} elseif (isset($_POST['btn_delete_gallery'])) {
  $id_gallery = $_POST['btn_delete_gallery'];
  $image = $_POST['image'];
  $s = "DELETE FROM tb_gallery WHERE id=$id_gallery";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  // echo $s;
  $img = "$lokasi_gallery/$image";
  if (unlink($img)) {
    echo div_alert('success', 'Deleting image success.');
    $img_thumb = str_replace('.', '-thumb.', $img);
    if (file_exists($img_thumb)) {
      unlink($img_thumb);
      echo div_alert('success', 'Deleting thumbnail success.');
    }
  }

  jsurl('', 1000);
  // echo "unlink(\"$lokasi_gallery/$image\")";
  exit;
}

































# ===============================================================
# NORMAL FLOW
# ===============================================================
$divs = '';
$s = "SELECT *,id as id_gallery FROM tb_gallery ORDER BY nomor";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $id_gallery = $d['id_gallery'];
  $thumb = str_replace('.', '-thumb.', $d['image']);
  $lokasi_image = "$lokasi_gallery/$d[image]";
  $lokasi_thumb = "$lokasi_gallery/$thumb";
  if (!file_exists($lokasi_image)) { // jika image master hilang
    $s2 = "DELETE FROM tb_gallery WHERE id=$id_gallery";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    if (file_exists($lokasi_thumb)) {
      unlink($lokasi_thumb); // delete thumb jika master hilang
    }
    continue;
  }


  // gantikan thumb dg master jika tak ada
  $lokasi_thumb = file_exists($lokasi_thumb) ? $lokasi_thumb : $lokasi_image;


  $divs .= "
    <div class='col-lg-3 col-md-4'>
      <div class='gallery-item'>
        <a href='$lokasi_image' class='galelry-lightbox'>
          <img src='$lokasi_thumb' alt='gallery-$d[nama]' class='img-fluid'>
        </a>
      </div>
    </div>

  ";
}























# ===============================================================
# EDIT SECTION
# ===============================================================
$edit_section = $role == 'admin' ? edit_section('gallery', 'gallery') : '';
if ($edit_section) {

  $s = "SELECT a.*,
  a.id as id_gallery,
  (SELECT MAX(nomor) FROM tb_gallery) max_nomor 
  FROM tb_gallery a order by a.nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $tr = '';
  $th = '';
  $i = 0;
  $max_nomor = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_gallery = $d['id_gallery'];
    $max_nomor = $d['max_nomor'];
    $td = '';
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'date_created'
        || $key == 'id_gallery'
        || $key == 'max_nomor'
      ) continue;
      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th class='tengah'>$kolom</th>";
      }
      $triple_id = $key . "__gallery__" . $id_gallery;
      $editable = 'editable';
      $tengah = '';
      if ($key == 'image') {
        $editable = 'tengah';
        $src = "$lokasi_gallery/$value";
        if (file_exists($src)) {
          $value = "
            <a href='$src' target=_blank>
              <img src='$lokasi_gallery/$d[image]' class='img-thumbnail' style='max-width:300px;max-height:300px;'>
            </a> 
            <form method=post style='display:inline'>
              <input type=hidden name=image value=$d[image]>
              <button class='btn-transparan' name=btn_delete_gallery value=$id_gallery onclick='return confirm(\"Hapus gambar ini?\")'>$img_delete</button>
            </form>
          ";
        } else {
          $value = $img_warning;
        }
      } elseif ($key == 'nomor') {
        $tengah = 'tengah';
      }
      $td .= "<td class='$editable $tengah' id='$triple_id'>$value</td>";
    }
    $tr .= "
      <tr>
        $td
      </tr>
    ";
  }

  $new_nomor = $max_nomor + 1;

  $tb_gallery = "
    <table class=table>
      <thead>$th</thead>
      $tr
      <tr>
        <td colspan=100%>
          <form method=post class='flexy' enctype='multipart/form-data'>
            <div>
              <input type=hidden name=new_nomor value=$new_nomor>
              <input required class='form-control' type=file name=image accept=.jpg>
            </div>
            <div>
              <button class='btn btn-success ' name='btn_add_gallery'>Add Gallery</button>
            </div>
          </form>
        </td>
      </tr>
    </table>
  ";


  $edit_section .= "
  <div class='hideita wadah gradasi-kuning mt2' id=edit_gallery>
    <h3>Header Gallery</h3>
    <form method=post>
      <input required class='form-control mb2' name='gallery_header' value='$gallery_header' placeholder='Team Section Header...'>
      <input required class='form-control mb2' name='gallery_desc' value='$gallery_desc' placeholder='Team Description...'>
      <button class='btn btn-success btn-sm' name=btn_save_settings value=tim>Save Settings</button>
    </form>
    <hr>
    <h3>Edit Image Gallery</h3>
    $tb_gallery
  </div>
  ";
}

































?>
<section id="gallery" class="gallery p0">
  <div class="container">

    <div class="section-title">
      <h2><?= $gallery_header ?></h2>
      <p><?= $gallery_desc ?></p>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row g-0">
      <?= $divs ?>
    </div>
    <?= $edit_section ?>
  </div>
</section>