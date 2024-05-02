<?php
function no_medsos($medsos, $nama)
{
  return "<div onclick='alert(\"Maaf, saudara/i $nama belum memasukan data $medsos-nya.\")'><i class='ri-$medsos-fill'></i></div>";
}
function link_medsos($medsos)
{
  return "<a href='$medsos'><i class='ri-$medsos-fill'></i></a>";
}






















if (isset($_POST['btn_upload_image_tim'])) {
  $id_tim = $_POST['btn_upload_image_tim'];

  $nama = strtolower($_POST['nama']);
  $nama = preg_replace('/[.,\'"` ]/', '-', $nama);
  $nama = preg_replace('/--/', '-', $nama);


  $nama_file = $_FILES['image']['name'];
  $ekstensi = strtolower(substr($nama_file, -strpos(strrev($nama_file), '.')));
  $nama_baru = "tim-$id_klinik-$id_tim-$nama-$detik.$ekstensi";
  $nama_baru = preg_replace('/--/', '-', $nama_baru);
  $nama_baru_thumb = "tim-$id_klinik-$id_tim-$nama-$detik-thumb.$ekstensi";
  $nama_baru_thumb = preg_replace('/--/', '-', $nama_baru_thumb);
  echo "<br>Processing image... $nama_baru...";

  $tujuan = "$lokasi_tim/$nama_baru";
  $tujuan_thumb = "$lokasi_tim/$nama_baru_thumb";
  $tmpName = $_FILES['image']['tmp_name'];

  $result = move_uploaded_file($tmpName, $tujuan);


  $orig_image = imagecreatefromjpeg($tujuan);
  $image_info = getimagesize($tujuan);
  $width_orig  = $image_info[0];
  $height_orig = $image_info[1];

  $min_width = 50;
  $min_height = 50;

  $max_width_thumb = 250; // for thumbnail
  $max_height_thumb = 250;

  $max_width = 1000; // for high res
  $max_height = 1000;


  if ($width_orig < $min_width || $height_orig < $min_height) {
    jsurl("Resolusi image terlalu kecil. Silahkan pilih gambar dengan min-size $min_width x $min_height pixel !", 3000);
  } else if ($width_orig > $max_width || $height_orig > $max_height) {
    if ($width_orig > $height_orig) {
      $width = $max_width;
      $height = round($height_orig * $max_width / $width_orig, 0);
    } else {
      $height = $max_height;
      $width = round($width_orig * $max_height / $height_orig, 0);
    }
    echo "<br>Current : $width_orig x $height_orig px";
    echo "<br>Resize to : $width x $height px";

    $destination_image = imagecreatetruecolor($width, $height);
    imagecopyresampled($destination_image, $orig_image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
    // This will just copy the new image over the original at the same filePath.
    imagejpeg($destination_image, $tujuan, 80);

    // creating thumbnail
    if ($width_orig > $height_orig) {
      $width = $max_width_thumb;
      $height = round($height_orig * $max_width_thumb / $width_orig, 0);
    } else {
      $height = $max_height_thumb;
      $width = round($width_orig * $max_height_thumb / $height_orig, 0);
    }
    echo "<br>Current : $width_orig x $height_orig px";
    echo "<br>Thumbnail : $width x $height px";

    $destination_image = imagecreatetruecolor($width, $height);
    imagecopyresampled($destination_image, $orig_image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
    // This will just copy the new image over the original at the same filePath.
    imagejpeg($destination_image, $tujuan_thumb, 80);
  } else {
    echo '<br>No need to be resized.';
  }

  //deleting old image
  if ($_POST['old_image']) {
    $old_image = "$lokasi_tim/$_POST[old_image]";
    if (file_exists($old_image)) {
      unlink($old_image);
      echo '<br>deleting old image success';
    } else {
      echo '<br>old image is missing';
    }

    $thumb = str_replace('.', '-thumb.', $old_image);
    if (file_exists($thumb)) {
      unlink($thumb);
      echo '<br>deleting thumbnail success';
    } else {
      echo '<br>thumbnail is missing';
    }
  }

  // save to DB
  $s = "UPDATE tb_tim SET image='$nama_baru' WHERE id=$id_tim";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo '<br>updating database success';


  echo div_alert('success mt3', "Upload Image sukses.");
  jsurl('', 2000);
  exit;
} elseif (isset($_POST['btn_delete_tim'])) {
  $s = "DELETE FROM tb_tim WHERE id=$_POST[btn_delete_tim]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', 'Anggota tim berhasil dihapus.');
  jsurl();
} elseif (isset($_POST['btn_add_tim'])) {
  $s = "INSERT INTO tb_tim (
    id_klinik,
    nama,
    jabatan,
    nomor
  ) VALUES (
    '$id_klinik',
    '$_POST[nama]',
    '$_POST[jabatan]',
    '$_POST[nomor]'
  )";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', 'Tambah Anggota tim berhasil.');
  jsurl();
}






























$divs = '';
if ($role == 'admin') {

  require_once 'include/editable_js_for_td.php';

  $s = "SELECT 
  a.*,
  a.id as id_tim

  FROM tb_tim a ORDER BY a.nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $jumlah_anggota = mysqli_num_rows($q);
  $tr = '';
  $tr_upload = '';
  $th = '';
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;

    $nama = $d['nama'];
    $link_twitter = strlen($d['twitter']) <= 1 ? no_medsos('twitter', $nama) : link_medsos('twitter');
    $link_facebook = strlen($d['facebook']) <= 1 ? no_medsos('facebook', $nama) : link_medsos('facebook');
    $link_instagram = strlen($d['instagram']) <= 1 ? no_medsos('instagram', $nama) : link_medsos('instagram');
    $link_linkedin = strlen($d['linkedin']) <= 1 ? no_medsos('linkedin', $nama) : link_medsos('linkedin');

    $src = "$lokasi_tim/$d[image]";
    $thumb = str_replace('.', '-thumb.', $src);
    $thumb = file_exists($thumb) ? $thumb : $src;

    $divs .= "
      <div class='col-lg-6'>
        <div class='member d-flex align-items-start'>
          <div class='piczzz'><img src='$thumb' class='img-tim' alt='$d[image]'></div>
          <div class='member-info'>
            <h4>$d[nama]</h4>
            <span>$d[jabatan]</span>
            <p>$d[shout]</p>
            <div class='social'>
              $link_twitter
              $link_facebook
              $link_instagram
              $link_linkedin
            </div>
          </div>
        </div>
      </div>
    ";


    $td = '';
    foreach ($d as $key => $value) {
      $id_tim = $d['id_tim'];
      if (
        $key == 'id'
        || $key == 'id_klinik'
        || $key == 'date_created'
        || $key == 'image'
        || $key == 'id_tim'
      ) continue;

      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th>$kolom</th>";
      }
      $name = $key . '[]';
      $triple_id = $key . "__tim__$id_tim";
      $this_td = "<td class='editable' id=$triple_id>$value</td>";

      // $td .= "<td>$input</td>";
      $td .= $this_td;
    }
    $td_hapus = "
      <td>
        <form method=post class='form-inline m0'>
          <button class='btn-transparan' name=btn_delete_tim value='$id_tim' onclick='return confirm(\"Hapus anggota ini?\")'>$img_delete</button>
        </form>
      </td>
    ";
    $tr .= "<tr>$td$td_hapus</tr>";
    $tr_upload .= "
      <div class=p3>
        <div class='mt4 mb2'>$d[nomor]. $d[nama]</div>
        <div class='mb4'><img src='$lokasi_tim/$d[image]' class='img-tim'></div>
        <form method=post enctype=multipart/form-data class='wadah p2 mb4'>
          <input type=file name=image required accept=.jpg>
          <input type=hidden name=nama value='$d[nama]'>
          <input type=hidden name=old_image value='$d[image]'>
          <button class='btn btn-success btn-sm' name=btn_upload_image_tim value=$id_tim>Replace</button>
        </form>
      </div>
    ";
  }


  $no_urut_baru = $jumlah_anggota + 1;
  $tb_tim = "
    <table class=table>
      <thead>
        $th
        <th>Aksi</th>
      </thead>
      $tr
      <tr>
        <td colspan=100%>
          <form method=post>
            <div class='flexy'>
              <div class='pt2 f12 abu'>
                #$no_urut_baru
                <input type=hidden name=nomor value='$no_urut_baru'>
              </div>
              <div>
                <input class='form-control' required minlength=3 maxlength=30 name=nama placeholder='Nama Anggota baru...'>
              </div>
              <div>
                <input class='form-control' required minlength=3 maxlength=30 name=jabatan placeholder='Jabatan...'>
              </div>
              <div>
                <button class='btn btn-success' name=btn_add_tim>Add Tim</button>
              </div>
            </div>
          </form>
        </td>
      </tr>
    </table>
  ";

  $tb_upload = "
    <div class=wadah>
      $tr_upload
    </div>
  ";

  $edit_section = $role == 'admin' ? edit_section('tim', 'dokter dan tim') : '';
  $edit_section .= "
  <div class='wadah gradasi-kuning hideita mt2' id=edit_doctors>
    <form method=post>
      <input required class='form-control mb2' name='team_header' value='$team_header' placeholder='Team Section Header...'>
      <input required class='form-control mb2' name='team_desc' value='$team_desc' placeholder='Team Description...'>
      <button class='btn btn-success btn-sm' name=btn_save_settings value=tim>Save Settings</button>
    </form>
    <hr>
    <h3>The List of Team</h3>
    <p>Untuk editing silahkan klik <i class=darkblue>cell</i> pada tabel lalu ubah value-nya.</p>
    $tb_tim
    <hr>
    <h3>Team Avatars</h3>
    <p class=biru>Gunakanlah foto wajah (only-face) dengan ukuran lebar dan tinggi yang hampir sama! Jangan lupa, tersenyum :)</p>
    $tb_upload
  </div>
  ";
}


echo "
  <section id='tim' class='tim p0'>
    <div class='container'>

      <div class='section-title'>
        <h2>$team_header</h2>
        <p>$team_desc</p>
      </div>

      <div class='row'>
        $divs
      </div>
      $edit_section

    </div>
  </section>
";
