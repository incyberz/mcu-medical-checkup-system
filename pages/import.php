<?php
if (isset($_POST['id_perusahaan'])) jsurl("?import&id_pemeriksaan=$_POST[id_pemeriksaan]&id_perusahaan=$_POST[id_perusahaan]");
only(['admin', 'marketing']);
set_title('Import ...');
$id_pemeriksaan = $_GET['id_pemeriksaan'] ?? '';
$id_perusahaan = $_GET['id_perusahaan'] ?? '';
$arr_pemeriksaan = [];
include 'include/arr_id_pemeriksaan.php';
include 'import-pilih_pemeriksaan.php';
$tb = $arr_pemeriksaan[$id_pemeriksaan]['singkatan'];
$nama_pemeriksaan = $arr_pemeriksaan[$id_pemeriksaan]['nama'];

# ============================================================
# DATA PERUSAHAAN
# ============================================================
$s = "SELECT * FROM tb_perusahaan WHERE id=$id_perusahaan";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data perusahaan tidak ditemukan'));
$perusahaan = mysqli_fetch_assoc($q);


# ============================================================
# ID PEMERIKSAAN BY PAGE NAME (P)
# ============================================================
$yang_belum = '';
$arr = ['tb_order b ON a.order_no=b.order_no', 'tb_harga_perusahaan b ON a.id_harga_perusahaan=b.id '];
$i = 0;
foreach ($arr as $v) {
  $s = "SELECT a.nama,a.id,c.arr_tanggal_by 
  FROM tb_pasien a 
  JOIN $v 
  JOIN tb_hasil_pemeriksaan c ON a.id=c.id_pasien 
  WHERE b.id_perusahaan = $id_perusahaan  
  ORDER BY a.nama
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    if (!strpos("salt||$d[arr_tanggal_by]", "||$id_pemeriksaan=")) {
      $i++;
      $nama = ucwords(strtolower(substr($d['nama'], 0, 20)));
      $yang_belum .= "<div class='col-xl-2 col-lg-3 col-md-4 col-sm-6'>$i. $nama</div>";
    }
  }
}

$blok_yang_belum = "
<div class='wadah f10 kiri mt2'>
  <div class='mb1 bold darkred'>Yang belum:</div>
  <div class='row'>$yang_belum</div>
</div>";




# ============================================================
# HEADER AND STYLES
# ============================================================
set_h2("Import $nama_pemeriksaan", "
  <a href='?import'>$img_prev</a> 
  Import <b class=darkblue>$nama_pemeriksaan</b> untuk <b class=darkblue>$perusahaan[nama]</b> 
  <i class=hideit>id_perusahaan:<b class='bg-red' id=id_perusahaan>$id_perusahaan</b></i>
  <i class=hideit>id_pemeriksaan:<b class='bg-red' id=id_pemeriksaan>$id_pemeriksaan</b></i>
  <i class=hideit>tb:<b class='bg-red' id=tb>$tb</b></i>

  $blok_yang_belum
  
  <style>
    .item_pasien:hover{color:blue;font-weight:bold; background: #faf}
  </style>
");


































# ============================================================
# PROCESSORS
# ============================================================
if (isset($_POST['btn_import'])) {


  $s = "TRUNCATE tb_import_$tb";
  echolog($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $s = "DESCRIBE tb_import_$tb";
  echolog($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $arr_field = [];
  while ($d = mysqli_fetch_assoc($q)) {
    array_push($arr_field, $d['Field']);
  }


  $src = "tmp_$id_pemeriksaan.csv";
  if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $src)) {
    echolog("move_uploaded_file $src");
    $arr_csv = baca_csv($src);

    $arr_id_det = [
      'HGB' => 'HGB',
      'HCT' => 'HCT',
      'WBC' => 'WBC',
      'PLT' => 'PLT',
      'RBC' => 'RBC',
      'MCV' => 'MCV',
      'MCH' => 'MCH',
      'MCHC' => 'MCHC',
      'Lym%' => 'Lym_p',
      'Mid%' => 'Mid_p',
      'GR%' => 'GR_p',
    ];

    $begin = 0;
    $values = '';
    $arr_index_csv = [];
    foreach ($arr_csv as $row => $arr) {
      if ($arr) {
        if ($arr[1] == 'Sample_ID' || $arr[1] == 'Sample ID') {
          $begin = 1;

          foreach ($arr as $key => $value) {
            $value = str_replace(' ', '_', $value);
            $value = str_replace('#', '', $value);
            $value = str_replace('%', '_p', $value);
            $arr_index_csv[$value] = $key;
          }

          continue; // begin inserting process
        }

        if ($begin) {
          $values = '';
          foreach ($arr_field as $field) {
            $koma = strlen($values) ? ',' : '';
            $val = $arr[$arr_index_csv[$field]];
            $values .= "$koma'$val'";
          }

          $s = "INSERT INTO tb_import_$tb VALUES ($values) ";
          echolog($s);
          $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
        }
      } // end if $arr
    } // foreach $arr_csv
    if (!$begin) {
      die(div_alert('danger', "Index [begin] is false, nothing to INSERT. Check your CSV header name!"));
    }
  } // end if move_uploaded_file
  jsurl();
  exit;
}






















echo "
  
  <form class='wadah gradasi-hijau' method=post enctype='multipart/form-data'>
    <div class='mb1 f10 abu'>File CSV Test Result dari Alat $nama_pemeriksaan</div>
    <input required accept='.csv' type=file name=csv_file class='form-control mb2' placeholder='File CSV...'>
    <button class='btn btn-primary' name=btn_import>Import</button>
  </form>
";

if ($id_pemeriksaan == $id_pemeriksaan_kd || $id_pemeriksaan == $id_pemeriksaan_dl) {
  if ($id_pemeriksaan == $id_pemeriksaan_dl) {
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
  } elseif ($id_pemeriksaan == $id_pemeriksaan_kd) {
    $arr_id_det = [
      'GD_SEWAKTU' => 172,
      'GD_PUASA' => 170,
      'GD_PP' => 171,
      'CHOLESTEROL' => 166,
      'LDL' => 167,
      'HDL' => 168,
      'TRIG' => 169,
      'SGOT' => 161,
      'SGPT' => 162,
      'ASAM_URAT' => 165,
      'CREATININ' => 164,
      'UREUM' => 163,
    ];
  }


  $s = "SELECT a.* 
  FROM tb_import_$tb a ";
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
        if (!($k == 'Name' || $k == 'Time')) $v = floatval($v);

        if (
          $k == 'id'
          || $k == 'Time'
        ) continue;

        if (key_exists($k, $arr_id_det)) {
          $kolom = "<span class=blue>$k<br>$arr_id_det[$k]</span>";
          $arr_hasil .= $v ? "$arr_id_det[$k]=$v||" : '';
          $v = $v ? "<span class=blue>$v</span>" : "<span class='abu miring f10'>$v</span>";
        } else {
          $kolom = "<span class='f8 abu'>$k</span>";
          if ($k != 'Name') {
            $v = "<span class='f8 abu miring'>$v</span>";
          }
        }

        if ($k == 'Sample_ID' || $k == 'Sample ID') {
          $v = "<span class='f8 abu'>$v<div>" . date('d-m-y', strtotime($d['Time'])) . '</div></span>';
        }

        if ($k == 'Name') {
          $v = "
            $v
            <div style='min-width: 300px'>
              <div>
                <button class='btn btn-sm btn-danger mt1 btn_delete_import' id=btn_delete_import__$id>Delete</button> 
              </div>
              <div class='wadah gradasi-kuning mt2'>
                <input class='form-control mb2 nama_pasien' id=nama_pasien__$id placeholder='enter nama pasien...'>
                <div id=hasil_ajax__$id></div>
                <button class='btn btn-primary btn-sm w-100 hideit btn_import' id=btn_import__$id>Import</button>
                <div class='hideit target_id_pasien bg-red tengah mt1' id=target_id_pasien__$id>???</div>
              </div>
            </div>
          ";
        }

        if ($i == 1) $th .= "<th>$kolom</th>";
        $td .= "<td>$v</td>";
      }
      if ($i == 1) $th .= "<th class='hideit'>arr_hasil</th><th class='hideit'>arr_tanggal_by</th>";

      $tr .= "
        <tr id=tr__$id>
          $td
          <td class='hideit' id=arr_hasil__$id>$arr_hasil</td>
          <td class='hideit' id=arr_tanggal_by__$id>$arr_tanggal_by</td>
        </tr>
      ";
    }
  }

  echo $tr ? "
    <table class='table f12 table-striped table-hover'>
      <thead>$th</thead>
      $tr
    </table>
  " : div_alert('danger', "Data import_$tb tidak ditemukan.");
} else {
  die(div_alert('danger', "Belum ada handler untuk ID_PEMERIKSAAN [ $id_pemeriksaan ]"));
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
    let tb = $('#tb').text();

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
        $('#hasil_ajax__' + id_import).html('ketik minimal 3 huruf...');
      } else {
        let link_ajax = `ajax/get_pasien_import.php?id_perusahaan=${id_perusahaan}&id_pemeriksaan=${id_pemeriksaan}&id_import=${id_import}&keyword=${val}`;
        console.log(link_ajax);

        $.ajax({
          url: link_ajax,
          success: function(a) {
            $('#hasil_ajax__' + id_import).html(a);

          }
        })

      }
    });


    $(".btn_delete_import").click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);
      let link_ajax = `ajax/ajax_delete_import.php?id_import=${id}&tb=${tb}`;
      $.ajax({
        url: link_ajax,
        success: function(a) {
          if (a.trim() == 'sukses') {
            $('#tr__' + id).slideUp();
          } else {
            alert(a)
          }
        }
      });

    });

    $(".btn_import").click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      let arr_hasil = $('#arr_hasil__' + id).text();
      let arr_tanggal_by = $('#arr_tanggal_by__' + id).text();
      console.log(aksi, id, arr_hasil, arr_tanggal_by);
      let id_pasien = $('#target_id_pasien__' + id).text();
      if (parseInt(id_pasien)) {
        let link_ajax = `ajax/ajax_update_import.php?id_pasien=${id_pasien}&arr_hasil=${arr_hasil}&arr_tanggal_by=${arr_tanggal_by}&id_import=${id}&tb=${tb}`;
        // console.log(link_ajax);
        // return;

        $.ajax({
          url: link_ajax,
          success: function(a) {
            if (a.trim() == 'sukses') {
              $('#tr__' + id).slideUp();
            } else {
              alert(a)
            }
          }
        });
      } else {
        console.log(`id_pasien [${id_pasien}] invalid.`);
      }

    });

  })
</script>