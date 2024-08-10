<?php
if (isset($_POST['id_perusahaan'])) jsurl("?import&p=$_POST[p]&id_perusahaan=$_POST[id_perusahaan]");
only(['admin', 'marketing']);
$p = $_GET['p'] ?? '';
$id_perusahaan = $_GET['id_perusahaan'] ?? '';
if (!$p || !$id_perusahaan) {
  $arr = ['hematologi', 'urine'];
  $opt = '';
  foreach ($arr as $k => $v) {
    $V = ucwords($v);
    // echo "<div><a class='btn btn-primary mb2' href='?import&p=hematologi'>Import $V</a></div>";
    $opt .= "<option value=$v>Import $V</option>";
  }

  $opt2 = '';
  $s = "SELECT * FROM tb_perusahaan ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    // $id=$d['id'];
    $opt2 .= "<option value=$d[id]>$d[nama]</option>";
  }


  echo "
    <form method=post class='wadah gradasi-hijau'>
      <div class=flexy>
        <div>
          <select class='form-control' name=p>
            $opt
          </select>
        </div>
        <div class='pt2 f14 abu'>untuk</div>
        <div>
          <select class='form-control' name=id_perusahaan>
            $opt2
          </select>
        </div>
        <div>
          <button class='btn btn-primary'>Next Import</button>
        </div>
      </div>
    </form>
  ";
  exit;
}

# ============================================================
# DATA PERUSAHAAN
# ============================================================
$s = "SELECT * FROM tb_perusahaan WHERE id=$id_perusahaan";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data perusahaan tidak ditemukan'));
$perusahaan = mysqli_fetch_assoc($q);

# ============================================================
# HEADER AND STYLES
# ============================================================
$P = ucwords($p);
set_h2("Import $P", "
  <a href='?import'>$img_prev</a> 
  Import <b class=darkblue>$P</b> untuk <b class=darkblue>$perusahaan[nama]</b> 
  <b class='hideita bg-red' id=id_perusahaan>$id_perusahaan</b>
  
  <style>
    .item_pasien:hover{color:blue;font-weight:bold; background: #faf}
  </style>
");

if (isset($_POST['btn_import'])) {
  echo '<pre>';
  var_dump($_FILES);
  echo '</pre>';
}

if ($p == 'hematologi') {
  $id_pemeriksaan = 3;
  $arr_id_det = [
    'HGB' => 95,
    'HCT' => 96,
    'WBC' => 97,
    'PLT' => 98,
    'RBC' => 99,
    'MCV' => 100,
    'MCH' => 101,
    'MCHC' => 102,
    'Lym_p' => 103,
    'Mid_p' => 104,
    'GR_p' => 105,
  ];

  echo "
    <b class='hideita bg-red' id=id_pemeriksaan>$id_pemeriksaan</b>
    <form class='wadah gradasi-hijau' method=post enctype='multipart/form-data'>
      <div class='mb1 f10 abu'>File CSV Test Result dari Alat $P</div>
      <input required accept='.csv' type=file name=csv_file class='form-control mb2' placeholder='File CSV...'>
      <button class='btn btn-primary' name=btn_import>Import</button>
    </form>
  ";

  $s = "SELECT a.* 
  FROM tb_import_hematologi a ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr = '';
  if (mysqli_num_rows($q)) {
    $i = 0;
    $th = '';
    while ($d = mysqli_fetch_assoc($q)) {
      $id = $d['Sample_ID'];
      $i++;
      $td = '';
      $arr_hasil = '';
      $arr_tanggal_by = "$id_pemeriksaan=$now,$id_user||";
      foreach ($d as $k => $v) {
        if (
          $k == 'id'
          || $k == 'Time'
        ) continue;

        if (key_exists($k, $arr_id_det)) {
          $kolom = "<span class=blue>$k<br>$arr_id_det[$k]</span>";
          $arr_hasil .= "$arr_id_det[$k]=$v||";
          $v = "<span class=blue>$v</span>";
        } else {
          $kolom = "<span class='f8 abu'>$k</span>";
          if ($k != 'Name') {
            $v = "<span class='f8 abu miring'>$v</span>";
          }
        }

        if ($k == 'Sample_ID') {
          $v = "<span class='f8 abu'>$v<div>" . date('d-m-y', strtotime($d['Time'])) . '</div></span>';
        }

        if ($k == 'Name') {
          $toggle_id = "form$id" . '__toggle';
          $v = "
            $v
            <div style='min-width: 300px'>
              <div><button class='btn_aksi mt1' id=$toggle_id>Import ke pasien:</button></div>
              <div class='wadah gradasi-kuning mt2 hideit' id=form$id>
                <input class='form-control mb2 nama_pasien' id=nama_pasien__$id placeholder='enter nama pasien...'>
                <div id=hasil_ajax__$id>hasil_ajax</div>
                <button class='btn btn-primary btn-sm w-100 hideit btn_import' id=btn_import__$id>Import</button>
                <div class='hideita target_id_pasien bg-red tengah mt1' id=target_id_pasien__$id>???</div>
              </div>
            </div>
          ";
        }

        if ($i == 1) $th .= "<th>$kolom</th>";
        $td .= "<td>$v</td>";
      }
      if ($i == 1) $th .= "<th>arr_hasil</th><th>arr_tanggal_by</th>";

      $tr .= "
        <tr>
          $td
          <td id=arr_hasil__$id>$arr_hasil</td>
          <td id=arr_tanggal_by__$id>$arr_tanggal_by</td>
        </tr>
      ";
    }
  }

  $tb = $tr ? "
    <table class='table f12'>
      <thead>$th</thead>
      $tr
    </table>
  " : div_alert('danger', "Data import_hematologi tidak ditemukan.");
  echo "$tb";
} else {
  die(div_alert('danger', "Belum ada handler untuk page [ $p ]"));
}
























?>
<script>
  $(document).on("click", ".item_pasien", function() {
    let tid = $(this).prop('id');
    let rid = tid.split('__');
    let aksi = rid[0];
    let id_import = rid[1];
    let val = $(this).text();
    let rval = val.split(' - ');
    let nama_pasien = rval[0];
    let id_pasien = rval[1];
    console.log(aksi, id_import, nama_pasien, id_pasien);
    if (parseInt(id_pasien)) {
      $('#target_id_pasien__' + id_import).text(id_pasien);
      $('#nama_pasien__' + id_import).val(nama_pasien);
      $('#hasil_ajax__' + id_import).html('');
      $('#hasil_ajax__' + id_import).hide();
      $('#btn_import__' + id_import).show();
    }
  });

  $(function() {
    let id_perusahaan = $('#id_perusahaan').text();
    let id_pemeriksaan = $('#id_pemeriksaan').text();
    $(".nama_pasien").keyup(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_import = rid[1];
      console.log(aksi, id_import);


      // rollback tampilan
      $('#btn_import__' + id_import).hide();
      $('#target_id_pasien__' + id_import).text('');
      $('#hasil_ajax__' + id_import).show('');

      let val = $(this).val();
      let intval = parseInt(val);
      if (val.length < 3 && !intval) {
        $('#hasil_ajax__' + id_import).html('ZZZ');
      } else {
        let link_ajax = `ajax/get_pasien_import.php?id_perusahaan=${id_perusahaan}&id_pemeriksaan=${id_pemeriksaan}&id_import=${id_import}&keyword=${val}`;
        $.ajax({
          url: link_ajax,
          success: function(a) {
            $('#hasil_ajax__' + id_import).html(a);

          }
        })

      }
    });


    $(".btn_import").click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      let arr_hasil = $('#arr_hasil__' + id).text();
      let arr_tanggal_by = $('#arr_tanggal_by__' + id).text();
      console.log(aksi, id, arr_hasil, arr_tanggal_by);

      link_ajax = `ajax/ajax_update_import.php?id_pasien=${id_pasien}&arr_hasil=${arr_hasil}&arr_tanggal_by=${arr_tanggal_by}`;
      $.ajax({
        url: link_ajax,
        success: function(a) {
          alert(a)
        }
      })
    });

  })
</script>