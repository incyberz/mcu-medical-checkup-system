<?php
$aksi = $_GET['aksi'] ?? 'add';
$from = $_GET['from'] ?? '';
$Save = $aksi == 'add' ? 'Save' : 'Update';
$link_back = $from ? "<a href='?" . urldecode($from) . "' > $img_prev kembali</a>" : "<a href='?paket_harga_perusahaan'>$img_prev List Paket Harga</a>";
set_h2('Add Harga Paket', $link_back);

if (isset($_POST['btn_add_harga_paket'])) {
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  if (!$_POST['id_paket']) {
    echo div_alert('danger', 'Anda belum memilih Paket Pemeriksaan');
  } elseif (!$_POST['id_perusahaan']) {
    echo div_alert('danger', 'Anda belum memilih Perusahaan');
  } else {
    $s = "SELECT 
    b.nama as nama_perusahaan, 
    c.nama as nama_paket,
    a.harga,
    a.id_paket 
    FROM tb_harga_perusahaan a 
    JOIN tb_perusahaan b ON a.id_perusahaan=b.id 
    JOIN tb_paket c ON a.id_paket=c.id 
    WHERE a.id_perusahaan=$_POST[id_perusahaan]
    ";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (mysqli_num_rows($q)) {
      $d = mysqli_fetch_assoc($q);
      if ($d['id_paket'] == $_POST['id_paket']) {
        echo div_alert('danger', "Perusahaan <b class=darkblue>$d[nama_perusahaan]</b> sudah mengambil paket tersebut dengan harga <b class=darkblue>Rp $d[harga]</b>");
      } else {
        echo div_alert('danger', "Perusahaan <b class=darkblue>$d[nama_perusahaan]</b> sudah mengambil <b class=darkblue>$d[nama_paket]</b> dengan harga <b class=darkblue>Rp $d[harga]</b>");
      }
    } else {
      $s = "INSERT INTO tb_harga_perusahaan (
        id_perusahaan,
        id_paket,
        harga
      ) VALUES (
        $_POST[id_perusahaan],
        $_POST[id_paket],
        $_POST[harga]
      )";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      jsurl();
    }
  }
}


# ============================================================
# SELECT PERUSAHAAN NON CARA-BAYAR NON BC
# ============================================================
$s = "SELECT a.*,
(
  SELECT concat(p.nama,' - Rp ',q.harga) FROM tb_paket p 
  JOIN tb_harga_perusahaan q ON p.id=q.id_paket
  WHERE q.id_perusahaan=a.id ) nama_paket

FROM tb_perusahaan a  
WHERE a.cara_bayar!='bc'
ORDER BY a.nama
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$opt_perusahaan = '';
$list_perusahaan_sudah = '';
$count_perusahaan_available = 0;
while ($d = mysqli_fetch_assoc($q)) {
  $nama_perusahaan = strtoupper($d['nama']);
  if ($d['nama_paket']) {
    $list_perusahaan_sudah .=  "<li>$nama_perusahaan - $d[nama_paket]</li>";
  } else {
    $count_perusahaan_available++;
    $opt_perusahaan .= "<option value=$d[id]>$nama_perusahaan</option>";
  }
}


# ============================================================
# SELECT PAKET
# ============================================================
$s = "SELECT * FROM tb_paket 
    WHERE id_program=1 -- corporate only
    AND status = 1 -- active only
    ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$opt_paket = '';
while ($d = mysqli_fetch_assoc($q)) {
  $opt_paket .= "<option value=$d[id]>$d[nama]</option>";
}

$komponen_form = $count_perusahaan_available
  ? "
  <div class=row>
      <div class='col-lg-6 col-xl-4'>
        <select name='id_perusahaan' id='id_perusahaan' class='form-control mb1'>
          <option value='0'>-- Pilih Perusahaan --</option>
          $opt_perusahaan
        </select>
        <div class='f14 mb4 kanan mr1'><a href='?add_perusahaan' class=''>+ Add Perusahaan</a></div>
      </div>
      <div class='col-lg-6 col-xl-4'>
        <select name='id_paket' id='id_paket' class='form-control mb1'>
          <option value='0'>-- Pilih Paket Pemeriksaan --</option>
          $opt_paket
        </select>
        <div class='f14 mb4 kanan mr1'><a href='?manage_paket' class=''>Manage Paket</a></div>
      </div>
      <div class='col-lg-12 col-xl-4'>
        <input required type=number min=10000 max=10000000 name='harga' id='harga' class='form-control mb4' placeholder='Harga Rp per pasien...'>
      </div>
    </div>
    
    <div class=tengah>
    <button class='btn btn-primary ' name=btn_add_harga_paket>Add Harga Paket</button>
  </div>
"
  : div_alert('danger', 'Belum ada perusahaan atau semua perusahaan sudah mempunyai Paket Harga<hr><a href=?add_perusahaan>Add Perusahaan</a>');


# ============================================================
# FORM ADD HARGA PAKET
# ============================================================
echo "
  <form method='post' class='wadah gradasi-hijau'>
    <div class='wadah gradasi-kuning'>
      <div class='mb2 mt2'>Perusahaan yang sudah mempunyai Paket Harga:</div>
      <ol>
        $list_perusahaan_sudah
      </ol>

    </div>
    $komponen_form
  </form>
";
