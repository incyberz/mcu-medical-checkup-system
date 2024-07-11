<?php
$id_pemeriksaan = $_GET['id_pemeriksaan'] ?? die(erid('id_pemeriksaan'));
$nama_pemeriksaan = $_GET['nama_pemeriksaan'] ?? die(erid('nama_pemeriksaan'));
set_h2('Detail Pemeriksaan', "Manage Detail Pemeriksaan <b class='proper darkblue'>$nama_pemeriksaan</b>");
only(['admin', 'marketing']);














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
    label,
    satuan
  ) VALUES (
    '$id_pemeriksaan',
    'input',
    'NEW DETAIL PEMERIKSAAN',
    'satuan'
  )";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Add detail sukses. Silahkan pilih detail tersebut untuk editing selanjutnya.");
  jsurl('', 1000);
}














# ============================================================
# MAIN SELECT PEMERIKSAAN
# ============================================================
$s = "SELECT 
a.id as id_detail,
a.label,
a.*,
b.nama as nama_pemeriksaan 
FROM tb_pemeriksaan_detail a 
JOIN tb_pemeriksaan b ON a.id_pemeriksaan=b.id 
WHERE b.id=$id_pemeriksaan
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$tr = '';
$th = '';
if (!mysqli_num_rows($q)) {
  echo div_alert('danger', 'Pemeriksaan ini belum mempunyai detail pemeriksaan');
} else {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_detail = $d['id_detail'];
    $td = '';
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'id_detail'
        || $key == 'id_pemeriksaan'
        || $key == 'nama_pemeriksaan'
      ) continue;
      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th>$kolom</th>";
      }

      $null_class = $value ? '' : 'f12 abu miring';
      $value = $value ? $value : 'NULL';

      # ============================================================
      # INPUT EDITING
      # ============================================================
      $id = "input_editing__$key" . "__$id_detail";
      $belum_punya = div_alert('danger', "Input Editing untuk kolom [$key] belum ditentukan");
      $input_editing = $belum_punya;

      if (
        $key == 'label'
        || $key == 'placeholder'
        || $key == 'value'
        || $key == 'class'
        || $key == 'satuan'
      ) {
        $input_editing = "<input class='form-control input_editing' id=$id value='$value'>";
      } elseif (
        $key == 'min'
        || $key == 'max'
        || $key == 'minlength'
        || $key == 'maxlength'
        || $key == 'minrange'
        || $key == 'maxrange'
      ) {
        $input_editing = "<input type=number class='form-control input_editing' id=$id value='$value'>";
      } elseif ($key == 'blok') {
        $arr = ['input', 'input-range',  'select', 'radio-toolbar'];
        $opt = '';
        foreach ($arr as $v) {
          $selected = $v == $value ? 'selected' : '';
          $opt .= "<option $selected >$v</option>";
        }
        $input_editing = "
          <select class='form-control input_editing' id=$id>$opt</select>
        ";
      } elseif ($key == 'type') {
        $arr = ['text', 'number', 'date', 'time', 'checkbox', 'radio'];
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
      }




      $dual_id =   "$key-$id_detail";
      $toggle_id = $dual_id . '__toggle';
      $btn = $input_editing == $belum_punya ? '' : "<button class='btn btn-primary btn-sm w-100 mt2 btn_save' id=btn_save__$dual_id>Save</button>";
      $td .= "
        <td>
          <div class='btn_aksi $null_class' id=$toggle_id>$value</div>
          <div class='wadah gradasi-kuning editing hideit' id=$dual_id style='min-width:200px'>
            $input_editing
            $btn
          </div>
        </td>
      ";
    }
    $tr .= "
      <tr>
        <td><button class='btn-transparan btn_delete' id=btn_delete__$dual_id>$img_delete</button></td>
        $td
      </tr>
    ";
  }
}

echo "
<style>.btn_aksi:hover{color:blue; letter-spacing: .3px; font-weight:bold}</style>
<div class='gradasi-toska' style='overflow-x: scroll'>
  <table class=table>
    <thead><th>&nbsp;</th>$th</thead>
    $tr
    <tr>
      <td colspan=100%>
        <form method=post>
          <button class='btn-transparan green' name=btn_tambah_detail>$img_add Tambah Detail</button>
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
      let rid = tid.split('-');
      let aksi = rid[0];
      let field = rid[1];
      let id_detail = rid[2];

      let link_ajax = "ajax/ajax_update_pemeriksaan_detail.php";
      console.log(aksi, field, id_detail);

      if (aksi == 'btn_save') {
        new_value = $('#input_editing__' + field + '__' + id_detail).val().trim();
        if (value == new_value) {
          $('#editing__value__' + field + '__' + id_detail).slideUp();
          return;
        }
        link_ajax = `ajax/ajax_update.php?tb=pemeriksaan&aksi=update&field=${field}&id=${id_detail}&value=${new_value}`;

      } else if (aksi == 'btn_delete') {
        console.log('deleting');
        link_ajax = `ajax/ajax_update.php?tb=pemeriksaan&aksi=delete&id=${id_detail}`;
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
    })
  })
</script>