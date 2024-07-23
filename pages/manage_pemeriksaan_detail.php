<?php
$id_detail = $_GET['id_detail'] ?? '';
if ($id_detail) {
  if (!$id_detail) die(kosong('id_detail'));
  $s = "SELECT b.nama,b.id 
  FROM tb_pemeriksaan_detail a 
  JOIN tb_pemeriksaan b ON a.id_pemeriksaan=b.id
  WHERE a.id=$id_detail";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $id_pemeriksaan = $d['id'];
  $nama_pemeriksaan = $d['nama'];

  $img_up = img_icon('up');
  $link_up = "<a href='?manage_pemeriksaan_detail&id_pemeriksaan=$id_pemeriksaan' >$img_up</a>";
} else {
  $link_up = '';
  $id_pemeriksaan = $_GET['id_pemeriksaan'] ?? die(erid('id_pemeriksaan'));
  $nama_pemeriksaan = $_GET['nama_pemeriksaan'] ?? '';
  if (!$id_pemeriksaan) die(kosong('id_pemeriksaan'));
  if (!$nama_pemeriksaan) {
    $s = "SELECT nama FROM tb_pemeriksaan WHERE id=$id_pemeriksaan";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $d = mysqli_fetch_assoc($q);
    $nama_pemeriksaan = $d['nama'];
  }
}

$mode = $_GET['mode'] ?? '';
$DPem = !$mode ? 'Manage Detail Pemeriksaan' : '<div class="f30 blue">Batasan Hasil Pemeriksaan</div>';

set_h2('Detail Pemeriksaan', "
  $DPem <b class='proper darkblue'>$nama_pemeriksaan</b>
  <div class=mt2><a href='?manage_pemeriksaan'>$img_prev</a> $link_up</div>
");
$roles = ['admin', 'marketing', 'nakes', 'dokter'];
$str_roles = join(', ', $roles);
only(
  $roles,
  'Yang berhak update pertanyaan adalah: [$str_roles]
  <hr>
  <span class=biru>Silahkan Logout kemudian re-Login sebagai role diatas untuk editing Detail Pemeriksaan</span>
  <hr>
  <a href=# onclick=window.close()>Close Tab</a> | <a href=?logout>Logout</a>
  ',
  0
);











# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_tambah_detail'])) {
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  $s = "INSERT INTO tb_pemeriksaan_detail (
    id_pemeriksaan,
    blok,
    label
  ) VALUES (
    '$id_pemeriksaan',
    'input',
    '$_POST[label]'
  )";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Add detail sukses. Silahkan pilih detail tersebut untuk editing selanjutnya.");
  jsurl('');
}














# ============================================================
# MAIN SELECT PEMERIKSAAN
# ============================================================
$sql_id_detail = $id_detail ? "a.id = $id_detail" : 1;
$s = "SELECT 
a.id as id_detail,
a.label, -- ordering UI
a.blok, -- ordering UI
a.option_values, -- ordering UI
a.option_default, -- ordering UI

a.min, -- ordering UI
a.max, -- ordering UI
a.minrange, -- ordering UI
a.maxrange, -- ordering UI

a.normal_value,
a.normal_lo_l,
a.normal_hi_l,
a.normal_lo_p,
a.normal_hi_p,
a.step,

a.*,
b.nama as nama_pemeriksaan 
FROM tb_pemeriksaan_detail a 
JOIN tb_pemeriksaan b ON a.id_pemeriksaan=b.id 
WHERE b.id=$id_pemeriksaan 
AND $sql_id_detail 
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$tr = '';
$th = '';
if (!mysqli_num_rows($q)) {
  echo div_alert('danger', 'Pemeriksaan ini belum mempunyai detail pemeriksaan. <hr><span class=blue>Silahkan Tambah Detail kemudian Anda sesuaikan (perbarui)!</span>');
} else {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_detail = $d['id_detail'];
    $td = '';
    $th = '';
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'id_detail'
        || $key == 'id_pemeriksaan'
        || $key == 'nama_pemeriksaan'
      ) continue;

      # ============================================================
      # EXCEPTION HIDEIT
      # ============================================================
      $hideit = 'hideit';
      $needed = [];
      if ($mode == 'batasan') {
        $needed = [
          'normal_value',
          'normal_lo_l',
          'normal_hi_l',
          'normal_lo_p',
          'normal_hi_p',
        ];
      } elseif ($d['blok'] == 'radio' || $d['blok'] == 'radio-toolbar' || $d['blok'] == 'multi-radio' || $d['blok'] == 'select') {
        // fields yang dibutuhkan
        $needed = [
          'option_values',
          'option_default',
          'option_labels',
          'class',
          'option_class',
          'satuan',
        ];
      } elseif ($d['blok'] == 'input-range') {
        $needed = [
          'class',
          'min',
          'max',
          'minrange',
          'maxrange',

          // 'normal_value',
          // 'normal_lo_l',
          // 'normal_hi_l',
          // 'normal_lo_p',
          // 'normal_hi_p',
          'step',
          'satuan',

        ];
      } elseif ($d['blok'] == 'array-gigi') {
        $needed = ['class', 'label'];
      }


      if (in_array($key, $needed)) $hideit = '';

      # ============================================================
      # HIDEIT TD CLASS
      # ============================================================
      $td_class = "$hideit td" . "__$id_detail " . 'td' . "__$id_detail" . "__$key";
      // hanya label dan blok yang selalu muncul
      if ($key == 'label' || $key == 'blok') $td_class = '';

      # ============================================================
      # HEADER TABEL
      # ============================================================
      $kolom = key2kolom($key);
      $widths = [
        'label' => '20%',
        'blok' => '10%',
        // 'name' => '15%',
        // 'class' => '10%',
        // 'option_labels' => '15%',
        // 'option_values' => '10%',
        // 'option_default' => '10%',
      ];
      $width = '';
      if (array_key_exists($key, $widths)) $width = $widths[$key];
      $th .= "<th class='$td_class' width=$width>$kolom</th>";

      $null_class = $value ? '' : 'f12 abu miring';
      $value = $value ? $value : 'NULL';

      # ============================================================
      # INPUT EDITING
      # ============================================================
      $id = "input_editing__$key" . "__$id_detail";
      $belum_punya = div_alert('danger', "Input Editing untuk kolom [$key] belum ditentukan");
      $input_editing = $belum_punya;

      $input_editing = "<input class='form-control input_editing' id=$id value='$value'>";
      if (
        $key == 'min'
        || $key == 'max'
        || $key == 'minlength'
        || $key == 'maxlength'
        || $key == 'minrange'
        || $key == 'maxrange'
      ) {
        $value = floatval($value);
        $input_editing = "<input type=number class='form-control input_editing' id=$id value='$value'>";
      } elseif ($key == 'blok') {
        $arr = ['input', 'input-range',  'select', 'radio-toolbar', 'array-gigi'];
        $opt = '';
        foreach ($arr as $v) {
          $selected = $v == $value ? 'selected' : '';
          $opt .= "<option $selected >$v</option>";
        }
        $input_editing = "
          <select class='form-control input_editing' id=$id>$opt</select>
        ";
      } elseif ($key == 'type' || $key == 'step') {
        $arr = $key == 'type'
          ? ['text', 'number', 'date', 'time', 'checkbox', 'radio']
          : [1, 0.1, 0.01];
        $opt = '';
        foreach ($arr as $v) {
          $selected = $v == $value ? 'selected' : '';
          $opt .= "<option $selected >$v</option>";
        }
        $input_editing = "
          <select class='form-control input_editing' id=$id>$opt</select>
        ";
      } elseif ($key == 'required') {
        $arr = ['true', 'false'];
        $opt = '';
        foreach ($arr as $v) {
          $selected = $v == $value ? 'selected' : '';
          $opt .= "<option $selected >$v</option>";
        }
        $input_editing = "
          <select class='form-control input_editing' id=$id>$opt</select>
        ";
      } elseif ($key == 'option_labels' || $key == 'option_values') {
        $pesan = $key == 'option_labels' ? 'pisahkan dengan koma, misal: Setuju, Netral, Tidak Setuju' : 'tanpa spasi, pisahkan dg koma, misal: 1,0,-1';
        $input_editing = "
          <textarea class='form-control input_editing' id=$id>$value</textarea>
          <div class='f12 tengah mt1 biru'>$pesan</div>
        ";
      }




      $dual_id =   "$key-$id_detail";
      $toggle_id = $dual_id . '__toggle';
      $btn = $input_editing == $belum_punya ? '' : "<button class='btn btn-primary btn-sm w-100 mt2 btn_save' id=btn_save__$dual_id>Save</button>";
      $td .= "
        <td id=td__$dual_id class='$td_class'>
          <div class='btn_aksi $null_class' id=$toggle_id>$value</div>
          <div class='wadah gradasi-kuning editing hideit' id=$dual_id style='min-width:200px'>
            $input_editing
            $btn
          </div>
        </td>
      ";
    }
    $tr .= "
      <tr id=tr__$id_detail>
        <td>
          <button class='btn-transparan btn_save' id=btn_delete__$dual_id  >$img_delete</button>
          <div class='f10 abu mt1'>id.$id_detail</div>
        </td>
        <td>
          <table class='table table-bordered' id=sub_table>
            <thead>
              $th
            </thead>
            <tr>
              $td
            </tr>
          </table>
        </td>
      </tr>
    ";
  }
}

echo "
<style>
  .btn_aksi:hover{color:blue; letter-spacing: .3px; font-weight:bold}
  #sub_table th{font-size: 10px; background: #005; color: white}
</style>
<div class='gradasi-toska' style='overflow-x: scroll'>
  <table class=table>
    $tr
    <tr>
      <td colspan=100%>
        <form method=post>
          <div class=flexy>
            <div><input class='form-control' required minlength=3 maxlength=50 name=label placeholder='Detail baru...'></div>
            <div>
              <button class='btn-transparan green' name=btn_tambah_detail >$img_add Tambah Detail</button>
            </div>
          </div>
        </form>
      </td>
    </tr>
  </table>
</div>
";































?>
<script>
  $(function() {
    $('.btn_save').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let rid2 = rid[1].split('-');
      let field = rid2[0];
      let id_detail = rid2[1];
      let dual_id = '#' + field + '-' + id_detail;
      let toggle_id = dual_id + '__toggle';
      let value = $(toggle_id).text().trim();

      let link_ajax;

      console.log(aksi, field, id_detail, value);

      if (aksi == 'btn_save') {
        new_value = $('#input_editing__' + field + '__' + id_detail).val().trim();
        if (value == new_value) {
          console.log('SAMA', value, new_value);
          $(dual_id).slideUp();
          return;
        }
        link_ajax = `ajax/ajax_update.php?tb=pemeriksaan_detail&aksi=update&field=${field}&id=${id_detail}&value=${new_value}`;

      } else if (aksi == 'btn_delete') {
        let y = confirm('Yakin DELETE detail pemeriksaan ini?');
        if (!y) return;
        console.log('deleting');
        link_ajax = `ajax/ajax_update.php?tb=pemeriksaan_detail&aksi=delete&id=${id_detail}`;
      } else {
        alert(`undefined aksi [${aksi}]`);
        return;
      }

      console.log(link_ajax);

      $.ajax({
        url: link_ajax,
        success: function(a) {
          if (a.trim() == 'sukses') {
            console.log('sukses');
            if (aksi == 'btn_save') {
              // update value
              $(toggle_id).text(new_value);

              // slide up
              $(dual_id).slideUp();

              // if change blok then refresh
              if (field == 'blok') location.reload();

            } else if (aksi == 'btn_delete') {
              $('#tr__' + id_detail).slideUp();

            }

          } else {
            alert(a);
          }
        }
      })
    })
  })
</script>