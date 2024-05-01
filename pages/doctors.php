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
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  echo '<pre>';
  var_dump($_FILES);
  echo '</pre>';
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
  $divs = '';
  $th = '';
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;

    $nama = $d['nama'];
    $link_twitter = strlen($d['twitter']) <= 1 ? no_medsos('twitter', $nama) : link_medsos('twitter');
    $link_facebook = strlen($d['facebook']) <= 1 ? no_medsos('facebook', $nama) : link_medsos('facebook');
    $link_instagram = strlen($d['instagram']) <= 1 ? no_medsos('instagram', $nama) : link_medsos('instagram');
    $link_linkedin = strlen($d['linkedin']) <= 1 ? no_medsos('linkedin', $nama) : link_medsos('linkedin');

    $divs .= "
      <div class='col-lg-6'>
        <div class='member d-flex align-items-start'>
          <div class='piczzz'><img src='assets/img/dokter-dan-tim/$d[image]' class='img-tim' alt='$d[image]'></div>
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
        <div class='mb4'><img src='$lokasi_team/$d[image]' class='img-tim'></div>
        <form method=post enctype=multipart/form-data class='wadah p2 mb4'>
          <input type=file name=image required>
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

  $edit_section = $role == 'admin' ? edit_section('doctors', 'dokter dan tim') : '';
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
    $tb_upload
  </div>
  ";
}


echo "
  <section id='doctors' class='doctors'>
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
