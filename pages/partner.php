<?php
require_once 'include/resize_img.php';

















# ===============================================================
# PROCESSORS
# ===============================================================

if (isset($_POST['btn_add_partner'])) {
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  echo '<pre>';
  var_dump($_FILES);
  echo '</pre>';
  $new_image = "partner-$_POST[new_nomor]-$detik.jpg";
  $new_image_thumb = "partner-$_POST[new_nomor]-$detik-thumb.jpg";
  $s = "INSERT INTO tb_partner (
    nomor,
    image
  ) VALUES (
    $_POST[new_nomor],
    '$new_image'
  )";
  echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $img_asal = $_FILES['image']['tmp_name'];
  $img_baru = "$lokasi_partner/$new_image";
  $img_thumb = "$lokasi_partner/$new_image_thumb";
  move_uploaded_file($img_asal, $img_baru);

  echo resize_img($img_baru);
  echo resize_img($img_baru, $img_thumb, 250, 250);
  echo div_alert('success', 'Upload image and creating thumbnail success');
  jsurl('', 2000);
  exit;
} elseif (isset($_POST['btn_delete_partner'])) {
  $id_partner = $_POST['btn_delete_partner'];
  $image = $_POST['image'];
  $s = "DELETE FROM tb_partner WHERE id=$id_partner";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  // echo $s;
  $img = "$lokasi_partner/$image";
  if (unlink($img)) {
    echo div_alert('success', 'Deleting image success.');
    $img_thumb = str_replace('.', '-thumb.', $img);
    if (file_exists($img_thumb)) {
      unlink($img_thumb);
      echo div_alert('success', 'Deleting thumbnail success.');
    }
  }

  jsurl('', 1000);
  // echo "unlink(\"$lokasi_partner/$image\")";
  exit;
}

































# ===============================================================
# NORMAL FLOW
# ===============================================================
$divs = '';
$s = "SELECT *,id as id_partner FROM tb_partner ORDER BY nomor";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $id_partner = $d['id_partner'];
  $thumb = str_replace('.', '-thumb.', $d['image']);
  $lokasi_image = "$lokasi_partner/$d[image]";
  $lokasi_thumb = "$lokasi_partner/$thumb";
  if (!file_exists($lokasi_image)) { // jika image master hilang
    $s2 = "DELETE FROM tb_partner WHERE id=$id_partner";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    if (file_exists($lokasi_thumb)) {
      unlink($lokasi_thumb); // delete thumb jika master hilang
    }
    continue;
  }


  // gantikan thumb dg master jika tak ada
  $lokasi_thumb = file_exists($lokasi_thumb) ? $lokasi_thumb : $lokasi_image;


  $divs .= "
    <div class='col-lg-2 col-md-3'>
      <div class='partner-item'>
        <a href='$lokasi_image' class='galelry-lightbox'>
          <img src='$lokasi_thumb' alt='partner-$d[nama]' class='img-fluid'>
        </a>
      </div>
    </div>

  ";
}























# ===============================================================
# EDIT SECTION
# ===============================================================
$edit_section = $role == 'admin' ? edit_section('partner', 'partner') : '';
if ($edit_section) {

  $s = "SELECT a.*,
  a.id as id_partner,
  (SELECT MAX(nomor) FROM tb_partner) max_nomor 
  FROM tb_partner a order by a.nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $tr = '';
  $th = '';
  $i = 0;
  $max_nomor = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_partner = $d['id_partner'];
    $max_nomor = $d['max_nomor'];
    $td = '';
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'date_created'
        || $key == 'id_partner'
        || $key == 'max_nomor'
      ) continue;
      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th class='tengah'>$kolom</th>";
      }
      $triple_id = $key . "__partner__" . $id_partner;
      $editable = 'editable';
      $tengah = '';
      if ($key == 'image') {
        $editable = 'tengah';
        $src = "$lokasi_partner/$value";
        if (file_exists($src)) {
          $value = "
            <a href='$src' target=_blank>
              <img src='$lokasi_partner/$d[image]' class='img-thumbnail' style='max-width:300px;max-height:300px;'>
            </a> 
            <form method=post style='display:inline'>
              <input type=hidden name=image value=$d[image]>
              <button class='btn-transparan' name=btn_delete_partner value=$id_partner onclick='return confirm(\"Hapus gambar ini?\")'>$img_delete</button>
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

  $tb_partner = "
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
              <button class='btn btn-success ' name='btn_add_partner'>Add Partner</button>
            </div>
          </form>
        </td>
      </tr>
    </table>
  ";


  $edit_section .= "
  <div class='hideita wadah gradasi-kuning mt2' id=edit_partner>
    <h3>Header Partner</h3>
    <form method=post>
      <input required class='form-control mb2' name='partner_header' value='$partner_header' placeholder='Team Section Header...'>
      <input required class='form-control mb2' name='partner_desc' value='$partner_desc' placeholder='Team Description...'>
      <button class='btn btn-success btn-sm' name=btn_save_settings value=tim>Save Settings</button>
    </form>
    <hr>
    <h3>Edit Image Partner</h3>
    $tb_partner
  </div>
  ";
}

































?>
<section id="partner" class="partner p0">
  <div class="container">

    <div class="section-title">
      <h2><?= $partner_header ?></h2>
      <p><?= $partner_desc ?></p>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row g-0">
      <?= $divs ?>
    </div>
    <?= $edit_section ?>
  </div>
</section>