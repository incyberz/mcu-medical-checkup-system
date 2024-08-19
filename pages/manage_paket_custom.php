<?php
// ?assign_pemeriksaan&id_paket=11&nama_paket=Paket MCU Karyawan (Basic)
$id_pasien = $_GET['id_pasien'] ?? die(erid('id_pasien'));
$s = "SELECT a.*,b.nama as jenis_pasien FROM tb_pasien a JOIN tb_jenis_pasien b ON a.jenis=b.jenis WHERE a.id=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data Pasien tidak ditemukan.'));
$pasien = mysqli_fetch_assoc($q);

// validasi data pasien
$id_paket_custom = $pasien['id_paket_custom'];
$id_harga_perusahaan = $pasien['id_harga_perusahaan'];
$order_no = $pasien['order_no'];
$harga_perusahaan = [];

if ($order_no) {
  $err = '';
  $jenis_pasien = $pasien['jenis_pasien'];
} else {
  $err = 'Tidak ada nomor order';
  if ($id_paket_custom) {
    $err = '';
    $jenis_pasien = $pasien['jenis_pasien'];
  } else {
    $err = 'Belum membuat Paket Custom';
    $jenis_pasien = 'Corporate Individu';
    if ($id_harga_perusahaan) {
      $err = '';

      // 
      $s = "SELECT a.nama as nama_perusahaan,b.* 
      FROM tb_perusahaan a 
      JOIN tb_harga_perusahaan b ON a.id=b.id_perusahaan 
      JOIN tb_pasien c ON b.id=c.id_harga_perusahaan 
      WHERE b.id = $id_harga_perusahaan";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      $harga_perusahaan = mysqli_fetch_assoc($q);
    } else {
      $err = 'Belum memilih harga perusahaan';
    }
  }
}

// if ($err) die(div_alert('danger', $err));
if ($err) div_alert('danger', $err);

// extract data pasien
$JENIS = strtoupper($pasien['jenis']);
$gender_icon = $pasien['gender'] ? img_icon('gender-' . $pasien['gender']) : '';

$judul = $id_paket_custom ? 'Paket Custom' : 'Paket Corporate';
$judul = $JENIS == 'COR' ? $judul : 'Paket Individu';
$link_back = "<a href='?pendaftaran'>$img_prev</a>";
$perusahaan_show = !$harga_perusahaan ? '' : "<div class='darkblue f20'>Karyawan $harga_perusahaan[nama_perusahaan]</div>";
set_h2("Manage $judul", "
$judul untuk $gender_icon <b class=darkblue>$pasien[nama]</b> 
pasien <b class=darkblue>$jenis_pasien</b>
$perusahaan_show
<div class=mt2>$link_back</div>
");
















# ============================================================
# KHUSUS CORPORATE DATANG KE KLINIK
# ============================================================
if ($JENIS == 'COR') {

  if (!$id_harga_perusahaan) {

    # ============================================================
    # PROCESSORS
    # ============================================================
    if (isset($_POST['btn_pilih_paket_corporate'])) {
      $s = "UPDATE tb_pasien SET id_harga_perusahaan=$_POST[id_harga_perusahaan] WHERE id=$id_pasien";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      jsurl();
    }


    echo '<div class="biru tebal mb2">Silahkan Pilih Paket Corporate:</div>';

    $s = "SELECT * FROM tb_paket 
    WHERE id_program=1 -- corporate only
    AND status = 1 -- active only
    ";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

    // $default_harga[11] = 95000;
    $tr = '';
    $i = 0;
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;

      // ambil harga khusus perusahaan
      $s2 = "SELECT a.id,
      a.harga,
      b.nama as nama_perusahaan 
      FROM tb_harga_perusahaan a 
      JOIN tb_perusahaan b ON a.id_perusahaan=b.id 
      WHERE a.id_paket=$d[id]";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));

      $opt = '';
      while ($d2 = mysqli_fetch_assoc($q2)) {
        $harga_show = 'Rp ' . number_format($d2['harga']) . ',-';
        $opt = "<option value=$d2[id]>$d2[nama_perusahaan] - $harga_show</option>";
      }
      $belum_ada_harga = div_alert('danger mt2', "Belum ada harga khusus untuk Paket ini.");
      $select_harga = !$opt ? $belum_ada_harga : "<select class='form-control' name='id_harga_perusahaan'>$opt</select>";

      $btn_pilih = $opt ? "<button class='btn btn-primary' name=btn_pilih_paket_corporate>Pilih</button>" : "
        <div class=pt2><a href='?manage_harga_perusahaan&id_harga_perusahaan=$d[id]'>Manage</a></div>
      ";





      $harga = $default_harga[$d['id']] ?? '';
      $tr .= "
        <tr>
          <td>$i</td>
          <td>
            <form method=post class=mb4>
              <div class='f20 darkblue'>$d[nama]</div>
              <div class=flexy>
                <div class='f14 pt2 abu miring'>Harga Paket Rp</div>
                <div>$select_harga</div>
                <div>
                  $btn_pilih
                </div>
              </div>
            </form>
          </td>
        </tr>
      ";
    }
    echo "
      <div class='wadah gradasi-hijau'>
        <table class='table td_trans'>
          $tr
        </table>
      </div>
    ";
    exit;
  }

  // exit;
}
























# ============================================================
# PROCESSORS PEMBAYARAN
# ============================================================
if (isset($_POST['btn_bayar'])) {
  if ($_POST['btn_bayar'] == -1) {
    // Undo Pembayaran | Admin Only
    $pairs = "
      status_bayar=NULL, 
      sum_biaya=NULL, 
      nominal_bayar=NULL, 
      tanggal_bayar=NULL, 
      kasir=NULL
    ";
  } else {
    $pairs = "
      status_bayar=$_POST[btn_bayar], 
      sum_biaya=$_POST[sum_biaya], 
      nominal_bayar=$_POST[nominal_bayar], 
      tanggal_bayar=CURRENT_TIMESTAMP, 
      kasir=$id_user
    ";
  }

  if ($id_harga_perusahaan) {
    $s = "INSERT INTO tb_pembayaran (
      id_pasien, 
      status_bayar, 
      sum_biaya, 
      nominal_bayar, 
      tanggal_bayar, 
      kasir
    ) VALUES (
      $id_pasien, 
      $_POST[btn_bayar], 
      $_POST[sum_biaya], 
      $_POST[nominal_bayar], 
      CURRENT_TIMESTAMP, 
      $id_user
    ) ON DUPLICATE KEY UPDATE 
      $pairs 
    ";

    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo div_alert('success', 'Update Pembayaran sukses');
    // pasien harus login add username


    // echo '<br>ADD USERNAME';

    $username_baru = strtolower(str_replace(' ', '', $pasien['nama']));
    $s = "SELECT 1 FROM tb_pendaftar WHERE username like '$username_baru%'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $count = mysqli_num_rows($q);
    if ($count) {
      $count++;
      $username_baru .= $count;
    }

    // echo "<br>$username_baru";





    $s = "UPDATE tb_pasien SET username='$username_baru' WHERE id=$id_pasien";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo div_alert('success', 'Update Username pasien sukses');
    // exit;
  } else {
    $s = "UPDATE tb_paket_custom SET $pairs WHERE id=$_POST[id_paket_custom]";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo div_alert('success', 'Update Pembayaran sukses');
    $s = "UPDATE tb_pasien SET status=7 WHERE id=$id_pasien";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo div_alert('success', 'Update Status pasien sukses');
  }
  jsurl('', 1000);
}





























$items = div_alert('danger', 'Belum ada item pemeriksaan');
$sum_biaya = 0;
$sum_biaya_show = 'Rp 0,-';
$jumlah_pemeriksaan = 0;
if (!$pasien['id_paket_custom'] and !$id_harga_perusahaan) {
  // auto-create id_paket_custom
  $s = "SELECT MAX(id)+1 as new_id FROM tb_paket_custom";
  echolog('select max id');
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $new_id = $d['new_id'] ?? 1;

  $s = "INSERT INTO tb_paket_custom (id) VALUES ($new_id)";
  echolog('inserting new id');
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $s = "UPDATE tb_pasien SET id_paket_custom=$new_id WHERE id=$id_pasien";
  echolog('updating paket custom');
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl('', 2000);
} else {
  $items = '';

  if ($id_harga_perusahaan) {

    $s = "SELECT 
    b.nama as nama_pemeriksaan,
    b.biaya,
    d.harga  
    FROM tb_paket_detail a 
    JOIN tb_pemeriksaan b ON a.id_pemeriksaan=b.id 
    JOIN tb_paket c ON a.id_paket=c.id 
    JOIN tb_harga_perusahaan d ON d.id_paket=c.id 
    WHERE d.id=$id_harga_perusahaan
    ";
  } else {
    $s = "SELECT 
    b.nama as nama_pemeriksaan,
    b.biaya,  
    b.biaya as harga 
    FROM tb_paket_custom_detail a 
    JOIN tb_pemeriksaan b ON a.id_pemeriksaan=b.id 
    WHERE a.id_paket_custom=$pasien[id_paket_custom]";
  }

  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $i = 0;
  $jumlah_pemeriksaan = mysqli_num_rows($q);
  $harga_perusahaan = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $harga_perusahaan = $d['harga'];
    $biaya_show = $id_harga_perusahaan ? '<span class="f12 abu miring">(harga paket)</span>' : number_format($d['biaya']) . '.-';
    $sum_biaya += $d['biaya'];
    $items .= "
      <tr>
        <td>$i</td>
        <td>$d[nama_pemeriksaan]</td>
        <td class='kanan'><span class=' darkblue'>$biaya_show</span></td>
      </tr>
    ";
  }

  $sum_biaya =  $id_harga_perusahaan ? $harga_perusahaan : $sum_biaya;
  $sum_biaya_show =  'Rp ' . number_format($sum_biaya) . ',-';


  $items = "
    <table class='table table-hover'>
      $items
      <tr>
        <td>&nbsp;</td>
        <td>TOTAL BIAYA</td>
        <td class='kanan'><span class='darkblue'>$sum_biaya_show</span></td>
      </tr>
    </table>
  ";
}

// echo $items;

$assign_pemeriksaan = $id_harga_perusahaan ? '' : "<a href='?assign_pemeriksaan&id_paket=$id_paket_custom&id_pasien=$id_pasien&nama_paket=Paket Custom $id_paket_custom&custom=1' >Assign Pemeriksaan</a>";

$id_paket_custom = $pasien['id_paket_custom'];
$tr_item = "
  <tr>
    <td>Item Pemeriksaan</td>
    <td>
      $items
      $assign_pemeriksaan
    </td>
  </tr>
";



if ($pasien['id_paket_custom']) {
  $s = "SELECT a.*,
  (SELECT nama FROM tb_user WHERE id=a.kasir) nama_kasir, 
  (SELECT status_bayar FROM tb_pembayaran WHERE id_pasien=$id_pasien) status_bayar_corporate_mandiri 
  FROM tb_paket_custom a 
  WHERE id='$pasien[id_paket_custom]'
  ";
} elseif ($pasien['id_harga_perusahaan']) {

  $s = "SELECT a.*,
  (SELECT nama FROM tb_user WHERE id=a.kasir) nama_kasir, 
  (SELECT status_bayar FROM tb_pembayaran WHERE id_pasien=$id_pasien) status_bayar_corporate_mandiri 
  FROM tb_pembayaran a 
  WHERE id_pasien='$id_pasien' 
  ";
} else {
  die(div_alert('danger', "[id_paket_custom] dan [id_harga_perusahaan] masih null"));
}
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_paket = $id_paket_custom;
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'id_pasien'
        || $key == 'date_created'
        || $key == 'tanggal_bayar'
        || $key == 'kasir'
        || $key == 'nominal_bayar'
        || $key == 'nama_kasir'
      ) continue;
      $kolom = key2kolom($key);

      if ($key == 'sum_biaya') {
        $kolom = 'Total Bayar';
        if ($d['nominal_bayar']) {
          // summary terdahulu telah ada
          $kolom = 'Nominal Bayar';
          $nominal = 'Rp ' . number_format($d['nominal_bayar']) . ',-';
          $tanggal_bayar_show = hari_tanggal($d['tanggal_bayar']);
          $value = "
            <div class='blue f30'>$nominal</div>
            <div class='f12 abu miring mt1'>Terbayar pada $tanggal_bayar_show</div>
            <div class='f12 abu miring mt1'>Kasir: $d[nama_kasir]</div>
          ";
        } else {
          $opsi_diskon = $JENIS == 'IDV' ? 'Opsi Diskon' : '';
          $value = "
            <div class='flexy flex-between'>
              <div class='blue f30'>$sum_biaya_show</div>
              <div class='f12 pointer pt4 abu' id=opsi_diskon>$opsi_diskon</div>
            </div>
          ";
        }
      } elseif ($key == 'biaya_manual') {
        $value = !$sum_biaya ? '-' : "<input type=number step=1000 class='form-control' value='$value' />";
      } elseif ($key == 'persen_diskon') {
        $value = !$sum_biaya ? '-' : "<input type=number step=0.1 class='form-control' value='$value' />";
      } elseif ($key == 'info_biaya') {
        $value = !$sum_biaya ? '-' : "<textarea class='form-control' rows=6>$value</textarea>";
      } elseif ($key == 'status_bayar') {

        $status_bayar = div_alert('danger', 'Belum terbayar');
        $form_action = '';
        $form_target = '';
        $form_cetak_sticker = '';
        if ($value === '0' || $value === 0 || $value || $d['status_bayar_corporate_mandiri']) {
          if ($d['status_bayar_corporate_mandiri']) {
            $status_bayar = div_alert('success tengah', 'Terbayar LUNAS (Mandiri Corporate)');
          } elseif ($value) {
            $status_bayar = div_alert('success tengah', 'Terbayar LUNAS');
          } else {
            $status_bayar = div_alert('info tengah', 'Terbayar dengan BPJS');
          }

          if ($role == 'admin') {
            $form_undo = "
              <div class=kanan><button class='btn btn-danger btn-sm' name=btn_bayar value='-1' onclick='return confirm(`Yakin UNDO Pembayaran?`)'>Undo Pembayaran</button></div>
            ";
          } else {
            $form_action = 'kwitansi.php';
            $form_target = '_blank';
            $form_undo = "<div><span class='btn btn-danger btn-sm' onclick='alert(`Hanya Role Admin yang dapat UNDO Pembayaran.\n\nSilahkan relogin jika Anda Admin.`)'>Undo Pembayaran</span></div>";
          }
          $form_komponen = "
          <div class='flexy flex-between'>
            <div><button class='btn btn-primary' name=btn_cetak_kwitansi>CETAK KWITANSI</button></div>
            $form_undo
          </div>
          ";

          $id_paket_show = $id_paket < 100 ? "00$id_paket" : $id_paket;
          $nama_paket = "Paket-$id_paket_show";

          $id_pasien_corporate_mandiri = $id_harga_perusahaan ? $id_pasien : '';

          $form_cetak_sticker = "
            <div class='wadah gradasi-kuning'>
              <h4 class='darkabu mb2'>Cetak Sticker Medis</h4>
              <form method=post target=_blank action='?cetak_sticker'>
                <input type=hidden name=id_paket_custom class='form-control mb2' value='$id_paket_custom' readonly>
                <input type=hidden name=id_pasien class='form-control mb2' value='$id_pasien' readonly>
                <input type=hidden name=nama_paket class='form-control mb2' value='$nama_paket' readonly>
                <input type=hidden name=id_pasien_corporate_mandiri class='form-control mb2' value='$id_pasien_corporate_mandiri' readonly>
                <button class='btn btn-primary' name=btn_cetak_kwitansi>CETAK STICKER</button>
              </form>
            </div>
          ";
        } else {
          // belum bayar
          if ($jumlah_pemeriksaan) {
            if ($JENIS == 'BPJ') {
              $cara_bayar = "
                <button class='btn btn-warning w-100' onclick='return confirm(`Set Dengan BPJS?`)' name=btn_bayar value=0>Bayar Dengan BPJS</button>
              ";
            } elseif ($JENIS == 'IDV' || ($JENIS == 'COR' and $id_harga_perusahaan)) {
              $cara_bayar = "
                <span class='btn btn-primary w-100 btn_aksi' id=blok_bayar_cash__toggle>Pembayaran Cash</span>
              ";
            } else {
              die(div_alert('danger', 'Jenis pasien invalid.'));
            }
          } else {
            $cara_bayar = '<span class="f12 abu miring">belum ada item pemeriksaan</span>';
          }

          $form_komponen = "
            <input type=hidden name=sum_biaya value='$sum_biaya' >
            <div class=row>
              <div class=col-6>
                $cara_bayar
              </div>
            </div>
            <div class='wadah gradasi-kuning mt2 hideit' id=blok_bayar_cash>
              <div>Nominal Bayar</div>
              <input type=number step=1000 class='form-control mt1 mb3' name=nominal_bayar value='$sum_biaya'>
              <button class='btn btn-primary w-100' onclick='return confirm(`Set Sudah Terbayar?`)' name=btn_bayar value=1>Set Sudah Terbayar</button>
            </div>
          ";
        }

        $input_hidden = $id_harga_perusahaan
          ? "<input type=hidden name=id_pasien_corporate_mandiri value='$id_pasien' >"
          : "<input type=hidden name=id_paket_custom value='$id_paket_custom' >";

        $value = "
          $status_bayar
          <form method=post action='$form_action' target='$form_target' class='mb2'>
            $input_hidden
            $form_komponen
          </form>
          $form_cetak_sticker
        ";
      }

      $hideit = '';
      $class = '';
      if ($key == 'biaya_manual' || $key == 'persen_diskon' || $key == 'info_biaya') {
        $hideit = 'hideit';
        $class = 'opsi_diskon';
      }
      $tr .= "
        <tr class='$hideit $class'>
          <td>$kolom</td>
          <td>$value</td>
        </tr>
      ";
    }
  }
} else {

  if ($pasien['id_harga_perusahaan']) {
    // auto insert tb_pembayaran IF NOT EXIST
    $s = "INSERT INTO tb_pembayaran (id_pasien) VALUES ($id_pasien)";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    jsurl();
  }
}


$tb = $tr ? "
  <table class='table td_trans'>
    $tr_item
    $tr
  </table>
" : div_alert('danger', "Data paket_custom tidak ditemukan.");
echo "
  <div class='wadah gradasi-hijau'>
    $tb
  </div>
";
?>
<script>
  $(function() {
    $('#opsi_diskon').click(function() {
      $('.opsi_diskon').toggle()
    })
  })
</script>