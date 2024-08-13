<?php
if (isset($_POST['id_perusahaan'])) jsurl("?import&p=$_POST[p]&id_perusahaan=$_POST[id_perusahaan]");
only(['admin', 'marketing']);
set_title('Import ...');
$p = $_GET['p'] ?? '';
$tb = strtolower(str_replace('-', '_', $p));
$id_perusahaan = $_GET['id_perusahaan'] ?? '';
$arr_id_pemeriksaan_by_p = [
  'Hematologi' => 3,
  'Urine' => 20,
  'SGOT' => 33,
  'SGPT' => 34,
  'Cholesterol' => 35,
  'Kreatinin' => 42,
  'Asam-urat' => 43,
  'Glukosa-puasa' => 44,
  'Glukosa-sewaktu' => 46,
];
$arr_id_pemeriksaan_by_p = [
  'Hematologi' => 3,
  'Urine' => 20,
  'Kimia-Darah' => 0,
];
if (!$p || !$id_perusahaan) {
  $opt = '';
  foreach ($arr_id_pemeriksaan_by_p as $k => $v) {
    $opt .= "<option value=$k>Import $k</option>";
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
# ID PEMERIKSAAN BY PAGE NAME (P)
# ============================================================
$blok_yang_belum = '';
if ($p == 'Kimia-Darah') {
  echo 'ZZZ';
} else {
  $id_pemeriksaan = $arr_id_pemeriksaan_by_p[$p] ?? die(div_alert('danger', "id_pemeriksaan undefined. No handler for page [ $p ]"));

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
  <b class='hideita bg-red' id=id_pemeriksaan>$id_pemeriksaan</b>
  <div class='wadah f10 kiri mt2'>
    <div class='mb1 bold darkred'>Yang belum:</div>
    <div class='row'>$yang_belum</div>
  </div>";
}




# ============================================================
# HEADER AND STYLES
# ============================================================
$P = ucwords($p);
set_h2("Import $P", "
  <a href='?import'>$img_prev</a> 
  Import <b class=darkblue>$P</b> untuk <b class=darkblue>$perusahaan[nama]</b> 
  <b class='hideita bg-red' id=id_perusahaan>$id_perusahaan</b>
  <b class='hideita bg-red' id=p>$p</b>
  <b class='hideita bg-red' id=tb>$tb</b>

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
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $s = "DESCRIBE tb_import_$tb";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $arr_field = [];
  while ($d = mysqli_fetch_assoc($q)) {
    array_push($arr_field, $d['Field']);
  }


  $src = "tmp_$p.csv";
  if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $src)) {
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
        if ($arr[1] == 'Sample_ID') {
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
          echo $s;
          $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
        }
      } // end if $arr
    } // foreach $arr_csv
  } // end if move_uploaded_file
  // jsurl(); //zzz debug
  exit;
}






















echo "
  
  <form class='wadah gradasi-hijau' method=post enctype='multipart/form-data'>
    <div class='mb1 f10 abu'>File CSV Test Result dari Alat $P</div>
    <input required accept='.csv' type=file name=csv_file class='form-control mb2' placeholder='File CSV...'>
    <button class='btn btn-primary' name=btn_import>Import</button>
  </form>
";

if ($p == 'hematologi') {
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
              <div>
                <button class='btn btn-sm btn-success btn_aksi mt1' id=$toggle_id>Import ke pasien:</button> 
                <button class='btn btn-sm btn-danger mt1 btn_delete_import' id=btn_delete_import__$id>Delete</button> 
              </div>
              <div class='wadah gradasi-kuning mt2 hideita' id=form$id>
                <input class='form-control mb2 nama_pasien' id=nama_pasien__$id placeholder='enter nama pasien...'>
                <div id=hasil_ajax__$id>hasil_ajax</div>
                <button class='btn btn-primary btn-sm w-100 hideita btn_import' id=btn_import__$id>Import</button>
                <div class='hideita target_id_pasien bg-red tengah mt1' id=target_id_pasien__$id>???</div>
              </div>
            </div>
          ";
        }

        if ($i == 1) $th .= "<th>$kolom</th>";
        $td .= "<td>$v</td>";
      }
      if ($i == 1) $th .= "<th class='hideita'>arr_hasil</th><th class='hideita'>arr_tanggal_by</th>";

      $tr .= "
        <tr id=tr__$id>
          $td
          <td class='hideita' id=arr_hasil__$id>$arr_hasil</td>
          <td class='hideita' id=arr_tanggal_by__$id>$arr_tanggal_by</td>
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
} elseif ($p == 'Kimia-Darah') {

  $arr_id_det = [
    'GD_SEWAKTU' => 138,
    'GD_PUASA' => 152,
    'CHOLESTEROL' => 136,
    'SGOT' => 149,
    'SGPT' => 150,
    'ASAM_URAT' => 137,
    'CREATININ' => 151,
  ];

  $arr_id_pem = [
    'GD_SEWAKTU' => 46,
    'GD_PUASA' => 44,
    'CHOLESTEROL' => 35,
    'SGOT' => 33,
    'SGPT' => 34,
    'ASAM_URAT' => 43,
    'CREATININ' => 42,
  ];

  $arr_id_pem_by_id_det = [
    138 => 46,
    152 => 44,
    136 => 35,
    149 => 33,
    150 => 34,
    137 => 43,
    151 => 42,
  ];


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
      $arr_tanggal_by = '';
      // foreach ($arr_id_pem as $id_pemeriksaan) {
      //   $arr_tanggal_by .= "$id_pemeriksaan=$now,$id_user||";
      // }
      foreach ($d as $k => $v) {
        // echo "<br>$v";

        if (!($k == 'Name' || $k == 'Time')) $v = floatval($v);
        if (
          $k == 'id'
          || $k == 'Time'
        ) continue;

        if (key_exists($k, $arr_id_det)) {
          $kolom = "<span class=blue>$k<br>$arr_id_det[$k]</span>";
          if ($v) { // jika 0 maka tidak diperiksa
            $arr_hasil .=  "$arr_id_det[$k]=$v||";
            $id_pemeriksaan = $arr_id_pem_by_id_det[$arr_id_det[$k]];
            $arr_tanggal_by .= "$id_pemeriksaan=$now,$id_user||";
          }

          $v = floatval($v) ? "<span class=blue>$v</span>" : "<i class=red>$v</i>";
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
              <div>
                <button class='btn btn-sm btn-success btn_aksi mt1' id=$toggle_id>Import ke pasien:</button> 
                <button class='btn btn-sm btn-danger mt1 btn_delete_import' id=btn_delete_import__$id>Delete</button> 
              </div>
              <div class='wadah gradasi-kuning mt2 hideita' id=form$id>
                <input class='form-control mb2 nama_pasien' id=nama_pasien__$id placeholder='enter nama pasien...'>
                <div id=hasil_ajax__$id>hasil_ajax</div>
                <button class='btn btn-primary btn-sm w-100 hideita btn_import' id=btn_import__$id>Import</button>
                <div class='hideita target_id_pasien bg-red tengah mt1' id=target_id_pasien__$id>???</div>
              </div>
            </div>
          ";
        }

        if ($i == 1) $th .= "<th>$kolom</th>";
        // $v = floatval($v);
        // $v_show = $v ? $v : "<i class=red>$v</i>";
        $td .= "<td>$v</td>";
      }
      if ($i == 1) $th .= "<th class='hideita'>arr_hasil</th><th class='hideita'>arr_tanggal_by</th>";

      $tr .= "
        <tr id=tr__$id>
          $td
          <td class='hideita' id=arr_hasil__$id>$arr_hasil</td>
          <td class='hideita' id=arr_tanggal_by__$id>$arr_tanggal_by</td>
        </tr>
      ";
    }
  }

  $tb = $tr ? "
    <table class='table f12 table-striped table-hover'>
      <thead>$th</thead>
      $tr
    </table>
  " : div_alert('danger', "Data import_$tb tidak ditemukan.");
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
    let p = $('#p').text();
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