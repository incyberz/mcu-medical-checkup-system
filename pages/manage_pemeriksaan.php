<?php
$judul = 'Manage Pemeriksaan';
$arr_for = [
  'detail' => ['nama', 'biaya', 'count_pemeriksaan_detail',],
  'deskripsi' => ['nama', 'singkatan', 'deskripsi',],
  'jenis' => ['nama', 'jenis',],
  // 'sticker' => ['nama', 'cetak_sticker'],
  'visibility' => ['nama', 'status', 'show_to_paket'],
  'sampel' => ['nama', 'sampel'],
  // 'biaya' => ['nama', 'biaya'],
  // 'durasi' => ['nama', 'durasi'],
  'image' => ['nama', 'image'],
];

$navs = '';
$for = $_GET['for'] ?? 'detail';
foreach ($arr_for as $key => $arr) {
  $nav_active = $key == $for ? 'nav_active' : '';
  $navs .= "<div class='proper darkblue pointer $nav_active p2 pt1 pb1 br5 navigasi' id=navigasi__$key>$key</div>";
}

$sub_judul = "
  <style>
    .nav_active{background: linear-gradient(#efe,#cfc); font-weight:bold} 
    .pointer{transition:.2s} 
    .pointer:hover{font-weight:bold; letter-spacing: 1px}
  </style>
  <div class='flexy flex-center' id=nav_for>$navs</div>
";
set_h2($judul, $sub_judul);
only(['admin', 'marketing', 'dokter', 'dokter-pj']);

if (!array_key_exists($for, $arr_for)) die("index [$for] invalid");
$fields = '';
$arr_field = $arr_for[$for];

# ============================================================
# INCLUDES
# ============================================================
$arr_jenis_pemeriksaan = [];
include 'include/arr_jenis_pemeriksaan.php';
include 'include/arr_sampel.php';











# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_pemeriksaan'])) {
  $jenis = $_POST['jenis'] ?? die('index [jenis] belum terdefinisi.');
  $nama = $_POST['nama'] ?? die('index [nama] belum terdefinisi.');
  $singkatan = substr($nama, 0, 10);

  $s = "INSERT INTO tb_pemeriksaan (
    id_klinik,
    jenis,
    nama,
    singkatan
  ) VALUES (
    '$id_klinik',
    '$jenis',
    '$nama',
    '$singkatan'
  )";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Add Pemeriksaan sukses. Silahkan pilih pemeriksaan tersebut untuk editing selanjutnya.");
  jsurl('', 3000);
}














# ============================================================
# MAIN SELECT PEMERIKSAAN
# ============================================================
$s = "SELECT 
b.nama as jenis_pemeriksaan, 
a.id as id_pemeriksaan,
a.nama, -- kolom pertama
a.singkatan, -- kolom kedua
a.*, -- kolom berikutnya
(SELECT count(1) FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=a.id) count_pemeriksaan_detail,
(SELECT count(1) FROM tb_paket_detail WHERE id_pemeriksaan=a.id) count_paket_detail


FROM tb_pemeriksaan a 
JOIN tb_jenis_pemeriksaan b ON a.jenis=b.jenis 
WHERE a.id_klinik=$id_klinik 
ORDER BY b.nomor, a.nomor  
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$count_pemeriksaan = mysqli_num_rows($q);

$tr = '';
if (!mysqli_num_rows($q)) {
  $tr = div_alert('danger', 'Belum ada program pada klinik ini.');
} else {
  $tr = '';
  $th = '';
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_pemeriksaan = $d['id_pemeriksaan'];
    $jenis = $d['jenis'];
    $jenis_pemeriksaan = $d['jenis_pemeriksaan'];
    $nama_pemeriksaan = $d['nama'];
    $status = $d['status'];

    // count_pemeriksaan_detail info
    $detail_info = "$img_detail";

    # ============================================================
    # PAKET IMAGE UPLOAD
    # ============================================================
    $image_info = '';
    $href = "?upload_image_pemeriksaan&id_pemeriksaan=$id_pemeriksaan";
    if ($d['image']) { // ada data image di DB
      $src = "$lokasi_pemeriksaan/$d[image]";
      if (!file_exists($src)) {
        $image_info = '<span class=red>paket image hilang</span>';
      } else {
        $image_info = $img_image;
      }
    } else {
      $image_info = img_icon('upload_gray');
    }
    $link_upload_image = "<a href='$href' onclick='return confirm(\"Upload Image?\")'>$image_info</a>";



    $td = "<td>$i<div class='f10 abu miring'>id.$id_pemeriksaan</div></td>";
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'id_pemeriksaan'
        || $key == 'id_klinik'
        || $key == 'date_created'
      ) continue;

      $hideit = in_array($key, $arr_field) ? '' : 'hideit';

      // echo "<hr>KEY OF d : $key";
      // key nama tetap tampil
      if ($key != 'nama') {
        $class = '';
        foreach ($arr_for as $key2 => $arr) {
          if (in_array($key, $arr)) {
            $class = "blok blok__$key2";
            // echo "<br>IN ARRAY key:$key key2:$key2 ";
            break;
          }
        }
      } else {
        $class = '';
      }

      /// zzz test
      if ($key == 'count_paket_detail') {
        $hideit = '';
      }


      # ============================================================
      # CREATE HEADER TABLE
      # ============================================================
      if ($i == 1) {
        $kolom = ucwords(str_replace('_', ' ', $key));
        $kolom = $key == 'count_paket_detail' ? "<span onclick='alert(`Count Paket Detail\n\nJumlah assign pemeriksaan ini ke Paket MCU`)'>$img_detail</span>" : $kolom;
        $width = ($key == 'count_paket_detail') ? 'width=30px' : '';
        $th .= "<th class='$hideit $class' $width>$kolom</th>";
      }
      $style_zero = $value ? '' : 'f12 abu miring';

      if ($key == 'nama') {
        // $value =  "<a href='?manage-single-paket&id_pemeriksaan=$id_pemeriksaan'>$value</a>";
      } elseif ($key == 'count_pemeriksaan_detail') {
        # ============================================================
        # LIST PEMERIKSAAN DETAIL
        # ============================================================
        $count_pemeriksaan_detail = $d['count_pemeriksaan_detail'];
        if ($count_pemeriksaan_detail) {
        }
        $value .= " | <a href='?manage_pemeriksaan_detail&id_pemeriksaan=$id_pemeriksaan&nama_pemeriksaan=$nama_pemeriksaan'>Manage Detail</a>";
      } elseif ($key == 'image') {
        $value = $link_upload_image;
      } elseif ($key == 'status') {
        # ============================================================
        # STATUS PAKET
        # ============================================================
        $label = $value ? 'active' : 'disabled';
        $id_check = "status__$id_pemeriksaan";
        $checked = $value ? 'checked' : '';
        $tebal_biru = $value ? 'tebal_biru' : '';

        $value = "
          <div class='form-check form-switch'>
            <input class='form-check-input check_status' type='checkbox' id='$id_check' $checked>
            <label class='form-check-label proper pointer $tebal_biru f12' for='$id_check' id='label-$id_check'>
              $label
            </label>
          </div>

        ";
      } elseif ($key == 'cetak_sticker') {
        # ============================================================
        # IS CETAK STICKER
        # ============================================================
        // $label = $value ? 'ON' : 'off';
        // $id_check = "cetak_sticker__$id_pemeriksaan";
        // $checked = $value ? 'checked' : '';
        // $tebal_biru = $value ? 'tebal_biru' : '';

        // $value = "
        //   <div class='form-check form-switch'>
        //     <input class='form-check-input check_sticker' type='checkbox' id='$id_check' $checked>
        //     <label class='form-check-label proper pointer $tebal_biru f12' for='$id_check' id='label-$id_check'>
        //       $label
        //     </label>
        //   </div>

        // ";
      } elseif ($key == 'show_to_paket') {
        # ============================================================
        # IS SHOW TO PAKET
        # ============================================================
        $label = $value ? 'ON' : 'off';
        $id_check = "show_to_paket__$id_pemeriksaan";
        $checked = $value ? 'checked' : '';
        $tebal_biru = $value ? 'tebal_biru' : '';

        $value = "
          <div class='form-check form-switch'>
            <input class='form-check-input check_show_to_paket' type='checkbox' id='$id_check' $checked>
            <label class='form-check-label proper pointer $tebal_biru f12' for='$id_check' id='label-$id_check'>
              $label
            </label>
          </div>

        ";
      }

      $style_non_aktif = $status ? '' : 'f12 abu miring';
      $value_id = "value__$key" . "__$id_pemeriksaan";

      $input_for_editing = '';
      if ($key == 'nama' || $key == 'singkatan' || $key == 'biaya') {
        $input_for_editing = "<input class='form-control' value='$value' id=input__$value_id>";
      } elseif ($key == 'deskripsi') {
        $input_for_editing = "<textarea rows=10 class='form-control' id=input__$value_id>$value</textarea>";
      } elseif ($key == 'jenis' || $key == 'sampel') {
        $opt = '';
        $arr = $key == 'jenis' ? $arr_jenis_pemeriksaan : $arr_sampel;
        foreach ($arr as $k => $v) {
          if (!$v) continue;
          $selected = $value == $k ? 'selected' : '';
          $opt .= "<option value='$k' $selected>$v</option>";
        }
        $input_for_editing = "<select class='form-control' id=input__$value_id><option>$opt</option></select>";
      }

      $value = $value ? $value : 'NULL';
      $class_value = ($key == 'count_paket_detail') ? '' : 'value';

      $td .= "
        <td class='$style_non_aktif $hideit $class'>
          <div class='$style_zero $class_value' id=$value_id>$value</div>
          <div class='hideit editing wadah mt1 gradasi-kuning' id=editing__$value_id>
            $input_for_editing
            <button class='btn btn-primary btn-sm mt2 btn_save' id=btn_save__$value_id>Save</button>
          </div>
        </td>
      ";
    }

    if ($d['count_paket_detail']) {
      $aksi_delete = "<span onclick='alert(`Tidak bisa hapus pemeriksaan ini karena sudah pernah dipakai pada Paket MCU.`)'>$img_delete_disabled</span>";
    } else {
      $aksi_delete = "
        <button class='btn-transparan btn_save' id=btn_delete__$value_id onclick='return confirm(\"Delete Pemeriksaan ini?\")'>$img_delete</button>
      ";
    }

    $tr .= "
      <tr class=tr id=tr__$value_id>
        <td><span class='f10 miring abu'>$jenis</span></td>
        $td
        <td>
          $aksi_delete
        </td>
      </tr>
    ";
  }
}

include 'include/select_jenis_pemeriksaan.php';

$count_next = $count_pemeriksaan + 1;
$tr_tambah = "
  <tr>
    <td>&nbsp;</td>
    <td>$count_next</td>
    <td colspan=100%>
      <div class=flexy>
        <div><span class=btn_aksi id=form_tambah_pemeriksaan__toggle>$img_add</span></div>
        <form method=post class='flexy form-inline' id=form_tambah_pemeriksaan>
          <div>
            <input required minlength=3 maxlength=100 class='form-control' name=nama placeholder='Nama Pemeriksaan...'>
          </div>
          <div>
            $select_jenis_pemeriksaan
          </div>
          <div>
            <button class='btn btn-success' name=btn_add_pemeriksaan>Add Pemeriksaan</button>
          </div>
        </form>
      </div>
    </td>
  </tr>
";


echo "
  <table class=table>
    <thead>
      <th width=100px>Jenis</th>
      <th width=50px>No</th>
      $th
      <th width=30px class=tengah onclick='alert(`Anda boleh menghapus Paket jika belum ada Detail Paket dan belum ada yang melakukan Order pada paket tersebut.`)'>$img_delete_disabled</th>
    </thead>
    $tr
    $tr_tambah
  </table>
";


















































?>
<script>
  $(function() {
    $('.check_status').click(function() {
      let tid = $(this).prop('id');
      let checked = $(this).prop('checked');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_pemeriksaan = rid[1];


      let status = checked ? '1' : "0";
      // if (checked) {
      //   aksi = 'insert';
      // } else {
      //   aksi = 'delete';
      // }
      // return;
      let link_ajax = `ajax/crud.php?tb=paket&aksi=update&id=${id_pemeriksaan}&kolom=status&value=${status}`;
      console.log(aksi, id_pemeriksaan, checked, link_ajax);

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
      });
    });

    $('.navigasi').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);
      $('.blok').hide();
      $('.blok__' + id).show();
      $('.navigasi').removeClass('nav_active');
      $(this).addClass('nav_active');

    });

    $('.value').dblclick(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let field = rid[1];
      let id = rid[2];
      console.log(aksi, field, id);
      $('.editing').hide();
      $('#editing__' + tid).toggle();

    });

    $('.btn_save').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let field = rid[2];
      let id_pemeriksaan = rid[3];
      let value = $('#value__' + field + '__' + id_pemeriksaan).text().trim();

      let link_ajax;
      let new_value = '';
      if (aksi == 'btn_save') {
        new_value = $('#input__value__' + field + '__' + id_pemeriksaan).val().trim();
        if (value == new_value) {
          $('#editing__value__' + field + '__' + id_pemeriksaan).slideUp();
          return;
        }
        link_ajax = `ajax/ajax_update.php?tb=pemeriksaan&aksi=update&field=${field}&id=${id_pemeriksaan}&value=${new_value}`;

      } else if (aksi == 'btn_delete') {
        console.log('deleting');
        link_ajax = `ajax/ajax_update.php?tb=pemeriksaan&aksi=delete&id=${id_pemeriksaan}`;
      } else {
        alert(`undefined aksi [${aksi}]`);
        return;
      }
      $.ajax({
        url: link_ajax,
        success: function(a) {
          if (a.trim() == 'sukses') {
            if (aksi == 'btn_save') {
              // update value
              $('#value__' + field + '__' + id_pemeriksaan).text(new_value);

              // slide up
              $('#editing__value__' + field + '__' + id_pemeriksaan).slideUp();

            } else if (aksi == 'btn_delete') {
              console.log('slide up tr');
              $('#tr__value__' + field + '__' + id_pemeriksaan).slideUp();

            }

          } else {
            alert(a);
          }
        }
      })


    });
  })
</script>