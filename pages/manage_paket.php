<?php
$judul = 'Manage Paket';
$sub_judul = '';
set_title($judul);
set_h2($judul, $sub_judul);
only(['admin', 'marketing']);












# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_paket'])) {
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  $s = "SELECT 1 FROM tb_paket WHERE id_program=$_POST[id_program]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $nomor = mysqli_num_rows($q) + 1;


  $s = "INSERT INTO tb_paket (
    id_program,
    no,
    nama,
    deskripsi
  ) VALUES (
    $_POST[id_program],
    $nomor,
    '$_POST[new_paket]',
    'deskripsi paket baru...'
  )";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Add Paket sukses. Silahkan pilih paket tersebut untuk editing selanjutnya.");
  jsurl('', 3000);
} elseif (isset($_POST['btn_delete_paket'])) {
  $s = "DELETE FROM tb_paket WHERE id = $_POST[btn_delete_paket]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Delete Paket sukses.");
  jsurl('', 3000);
}















$s = "SELECT 
a.id as id_paket,
a.nama as nama_paket,
a.status,
a.image,
a.carousel_image as carousel,
b.nama as nama_program,
(
  SELECT COUNT(1) FROM tb_paket_detail
  WHERE id_paket=a.id) count_paket_detail,
(
  SELECT COUNT(1) FROM tb_order
  WHERE id_paket=a.id) count_order,
(
  SELECT COUNT(1) FROM tb_paket_sticker
  WHERE kode LIKE CONCAT(a.id,'-%')) count_sticker
FROM tb_paket a 
JOIN tb_program b ON a.id_program=b.id
JOIN tb_jenis_program c ON b.jenis=c.jenis
WHERE b.id_klinik=$id_klinik";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$count_paket = mysqli_num_rows($q);

$tr = '';
if (!mysqli_num_rows($q)) {
  $tr = div_alert('danger', 'Belum ada program pada klinik ini.');
} else {
  $tr = '';
  $th = '';
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_paket = $d['id_paket'];
    $nama_paket = $d['nama_paket'];
    $count_paket_detail = $d['count_paket_detail'];
    $count_order = $d['count_order'];
    $status = $d['status'];

    // detail info
    $detail_info = "$img_detail";

    # ============================================================
    # PAKET IMAGE UPLOAD
    # ============================================================
    $image_info = '';
    $href = "?upload_image_paket&id_paket=$id_paket";
    if ($d['image']) { // ada data image di DB
      $src = "$lokasi_paket/$d[image]";
      if (!file_exists($src)) {
        $image_info = '<span class=red>paket image hilang</span>';
      } else {
        $image_info = $img_image;
      }
    } else {
      $image_info = img_icon('upload_gray');
    }
    $link_upload_image = "<a href='$href' onclick='return confirm(\"Upload Image?\")'>$image_info</a>";

    # ============================================================
    # CAROUSEL IMAGE UPLOAD
    # ============================================================
    $carousel_info = '';
    $href = "?upload_image_paket&id_paket=$id_paket&carousel=1";
    if ($d['carousel']) { // ada data carousel di DB
      $src = "$lokasi_carousel/$d[carousel]";
      if (!file_exists($src)) {
        $carousel_info = '<span class=red>carousel image hilang</span>';
      } else {
        $carousel_info = $img_image;
      }
    } else {
      $carousel_info = img_icon('upload_gray');
    }
    $link_upload_carousel = "<a href='$href' onclick='return confirm(\"Upload Carousel Image?\")'>$carousel_info</a>";


    $td = "<td>$i</td>";
    foreach ($d as $key => $value) {
      if ($key == 'id_paket') continue;
      if ($i == 1) {
        $kolom = ucwords(str_replace('_', ' ', $key));
        $th .= "<th>$kolom</th>";
      }
      $style_zero = $value ? '' : 'f12 abu miring';

      $icon_detail = '';
      if ($key == 'nama_paket') {
        $icon_detail = $detail_info;
        $value = "<a href='?manage-single-paket&id_paket=$id_paket'>$value</a>";
      } elseif ($key == 'image') {
        $value = $link_upload_image;
      } elseif ($key == 'carousel') {
        $value = $link_upload_carousel;
      } elseif ($key == 'status') {
        # ============================================================
        # STATUS PAKET
        # ============================================================
        $label = $value ? 'active' : 'disabled';
        $id_check = "check__$id_paket";
        $checked = $value ? 'checked' : '';
        $tebal_biru = $value ? 'tebal_biru' : '';

        $value = "
          <div class='form-check form-switch'>
            <input class='form-check-input check-sticker checkbox-parent' type='checkbox' id='$id_check' name='checkbox-$id_check' $checked>
            <label class='form-check-label proper pointer $tebal_biru f12' for='$id_check' id='label-$id_check'>
              $label
            </label>
          </div>

        ";
      } elseif ($key == 'count_sticker') {
        $label = $d['count_sticker'] ? 'label_green' : 'label_gray';
        $img_sticker = "<img src='$lokasi_icon/$label.png' height=20px class='zoom pointer' style='display:inline-block;margin-left: 10px' />";
        $value .= " <a href='?assign_sticker&id_paket=$id_paket&nama_paket=$nama_paket'>$img_sticker</a>";
      }

      $style_non_aktif = $status ? '' : 'f12 abu miring';
      $td .= "
        <td class='$style_non_aktif'>
          <div class='$style_zero'>
            $value
            $icon_detail 
          </div>
        </td>
      ";
    }

    if ($count_paket_detail || $count_order) {
      $aksi_delete = "
        <span onclick='alert(\"Tidak bisa hapus paket ini karena terdapat Paket Detail atau sudah pernah di-order. Hapus dahulu semua Paket Detail-nya dan seluruh Transaksi Order-nya.\")'>
          $img_delete_disabled
        </span> 
      ";
    } else {
      $aksi_delete = "
        <form method=post class='m0' style='display:inline-block'> 
          <button value=$id_paket name=btn_delete_paket class='btn-transparan' onclick='return confirm(\"Delete Paket ini?\")'>$img_delete</button>
        </form>
      ";
    }

    $tr .= "
      <tr>
        $td
        <td>
          <span class=on-dev>$img_edit</span>
          $aksi_delete
        </td>
      </tr>
    ";
  }
}

include 'include/select_program.php';

$count_next = $count_paket + 1;
$tr_tambah = "
  <tr>
    <td>$count_next</td>
    <td colspan=100%>
      <div class=flexy>
        <div><span class=btn_aksi id=form_tambah_paket__toggle>$img_add</span></div>
        <form method=post class='flexy form-inline' id=form_tambah_paket>
          <div>
            <input required minlength=3 maxlength=100 class='form-control' name=new_paket placeholder='Nama Paket baru...'>
          </div>
          <div>
            $select_program
          </div>
          <div>
            <button class='btn btn-success' name=btn_add_paket>Add Paket</button>
          </div>
        </form>
      </div>
    </td>
  </tr>
";


echo "
  <table class=table>
    <thead>
      <th>No</th>
      $th
      <th>Aksi</th>
    </thead>
    $tr
    $tr_tambah
  </table>
";


















































?>
<script>
  $(function() {
    $('.check-sticker').click(function() {
      let tid = $(this).prop('id');
      let checked = $(this).prop('checked');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_paket = rid[1];


      let status = checked ? '1' : "0";
      // if (checked) {
      //   aksi = 'insert';
      // } else {
      //   aksi = 'delete';
      // }
      // return;
      let link_ajax = `ajax/crud.php?tb=paket&aksi=update&id=${id_paket}&kolom=status&value=${status}`;
      console.log(aksi, id_paket, checked, link_ajax);

      $.ajax({
        url: link_ajax,
        success: function(a) {
          console.log('reply from AJAX: ', a);
          if (a.trim() == 'sukses') {
            if (checked) {
              $('#label-' + tid).addClass('tebal_biru');
              $('#label-' + tid).text('Active');
            } else {
              $('#label-' + tid).removeClass('tebal_biru');
              $('#label-' + tid).text('disabled');

            }
          } else {
            alert(a);
          }
        }
      })


    })
  })
</script>