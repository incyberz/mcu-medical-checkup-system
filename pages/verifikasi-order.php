<style>
  .item-corporate {
    background: #ddffffaa;
  }
</style>
<?php
$order_no = $_GET['order_no'] ?? die(erid('order_no'));
$judul = 'Verifikasi Order';
set_title($judul);
set_h2($judul, "Untuk Order No : $order_no");
only(['admin', 'marketing']);

$long_date_show = date('F d, Y, H:i:s');




































# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_batalkan'])) {
  $order_no = $_POST['btn_batalkan'];
  $s = "UPDATE tb_order SET 
  status = -1, 
  tanggal_verifikasi = CURRENT_TIMESTAMP,
  diverifikasi_oleh = $id_user,
  alasan_batal = '$_POST[alasan_batal]'

  WHERE order_no='$order_no'";
  echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Order No. $order_no telah dibatalkan.");
  // jsurl('', 3000);

} elseif (isset($_POST['btn_proses'])) {

  $order_no = $_POST['btn_proses'];
  // unset($_POST['btn_proses']);
  $username_baru = $_POST['username_pendaftar'];
  $username_sama = $_POST['username_sama'] ?? false;
  $password = substr($_POST['no_wa_pendaftar'], -4);
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  // check if username exists
  $s = "SELECT 1 FROM tb_pendaftar WHERE username = '$_POST[username_pendaftar]'";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q) and !$username_sama) {
    echo div_alert('danger', "Username [ $_POST[username_pendaftar] ] sudah digunakan.");
    $tr = '';
    foreach ($_POST as $key => $isi) {
      $kolom = key2kolom($key);
      $hideit = $key == 'btn_proses' ? 'hideit' : '';
      $tr .= "
        <tr class='$hideit'>
          <td>$kolom</td>
          <td>:</td>
          <td>$isi<input type=hidden readonly name=$kolom class='form-control' value='$isi' /></td>
        </tr>
      ";
    }
    echo "
      <form method=post class='wadah gradasi-kuning'>
        <table class='table td_trans'>
          $tr
        </table>

        <button class='btn btn-primary' name=username_sama value='1'>Proses Order untuk Pendaftar yang sama </button>
        <a class='btn btn-danger' href='?verifikasi-order&order_no=$order_no'>Review</a>
      </form>
    ";
    exit;
  } else {

    // password is last 4 digit of no_wa_pendaftar

    $s = "INSERT INTO tb_pendaftar (
      nama,
      perusahaan,
      jabatan,
      no_wa,
      username,
      password
    ) VALUES (
      '$_POST[nama_pendaftar]',
      '$_POST[perusahaan_pendaftar]',
      '$_POST[jabatan_pendaftar]',
      '$_POST[no_wa_pendaftar]',
      '$_POST[username_pendaftar]',
      md5('$password')
    )";
    // echo "$s <hr>";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echo div_alert('success', "Pendaftar baru berhasil ditambahkan.");
  }


  $s = "UPDATE tb_order SET 
  status = 1, 
  tanggal_verifikasi = CURRENT_TIMESTAMP,
  diverifikasi_oleh = $id_user,
  no_wa = '$_POST[no_wa_pendaftar]', 
  pesan_tambahan = '$_POST[pesan_tambahan]', 
  username_pendaftar = '$_POST[username_pendaftar]' 

  WHERE order_no='$order_no'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Data Order No. $order_no telah diupdate.");

  // jsurl('', 3000);
  // processing whatsapp message
  $pesan_wa = pesan_wa('after_order', $_POST['nama_pendaftar'], $_POST['perusahaan_pendaftar'], $order_no, $_POST['username_pendaftar'], $password);
  $href = "https://api.whatsapp.com/send?phone=$_POST[no_wa_pendaftar]&text=" . html2wa($pesan_wa);

  echo "
    <div class='f14 abu mt4 mb1'>Preview Pesan WA untuk pendaftar:</div>
    <div class=wadah>
      $pesan_wa
      <div class='mt2'><a target=_blank class='btn btn-primary w-100' href='$href' onclick='location.replace(\"?verifikasi-order&order_no=$order_no\")'>Kirim Pesan</a></div>
    </div>
  ";
  exit;
}























# ===========================================================
# NORMAL FLOW
# ===========================================================
$s = "SELECT 
a.tanggal_order as tanggal_order,
a.pendaftar,
a.jabatan,
a.perusahaan,
a.jumlah_peserta,
a.status,
a.alasan_batal,
a.tanggal_verifikasi,
a.no_wa as no_wa_pendaftar,
a.username_pendaftar,
b.nama as nama_paket,
c.nama as program,

(
  SELECT nama FROM tb_user WHERE id=a.diverifikasi_oleh) diverifikasi_oleh,
(
  SELECT nama FROM tb_status_order WHERE status=a.status) status_order
FROM tb_order a 
JOIN tb_paket b ON a.id_paket=b.id 
JOIN tb_program c ON b.id_program=c.id 
WHERE a.order_no='$order_no'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  die('Data order tidak ditemukan.');
} else {
  $d = mysqli_fetch_assoc($q);
  $status = $d['status'];
  $nama_pendaftar = $d['pendaftar'];
  $perusahaan_pendaftar = $d['perusahaan'];
  $jabatan_pendaftar = $d['jabatan'];
  $tr = '';
  foreach ($d as $key => $value) {
    if (
      $key == 'status'
      || $key == 'alasan_batal'
      || $key == 'diverifikasi_oleh'
      || $key == 'tanggal_verifikasi'
    ) continue;
    if ($key == 'tanggal_order') {
      $value = date('d-M-y, H:i', strtotime($value)) . " ~ <span class='f12 miring abu'>" . eta(strtotime($value) - strtotime('now')) . '</span>';
    } elseif ($key == 'status_order') {
      if ($status == -1) {
        $tgl = date('d-M-y', strtotime($d['tanggal_verifikasi']));
        $value = "
          <div class='tebal miring red'>$value</div>
          <div class='f12 miring abu'>Dibatalkan oleh: $d[diverifikasi_oleh] at $tgl</div>
          <div class='f12 miring abu'>Alasan: $d[alasan_batal]</div>
        ";
      } elseif ($status > 0) {
        $tgl = date('d-M-y', strtotime($d['tanggal_verifikasi']));
        $value = "
          <div class='tebal miring green'>$value</div>
          <div class='f12 miring abu'>Diterima dan diproses oleh: $d[diverifikasi_oleh] at $tgl</div>
        ";
      } else {
        $value = '<span class="tebal miring red">Belum diverifikasi</span>';
      }
    }
    $kolom = key2kolom($key);
    $tr .= "
      <tr>
        <td>$kolom</td>
        <td>:</td>
        <td>$value</td>
      </tr>
    ";
  }
  echo "<table class='table'>$tr</table>";
}

$proses_or_batal = '';
$form_batalkan_order = '';
$form_proses_order = '';
if ($status) {
  // if ($status == -1) {
  //   // $proses_or_batal = "DIBATALKAN";
  // } elseif ($status == 1) {
  //   $proses_or_batal = "BARU DIPROSES";
  // } else {
  //   $proses_or_batal = "PROSES LANJUT";
  // }
} else {
  // status is null BELUM DIPROSES
  $proses_or_batal = "
  <div class='mb2'>
    <span class='btn btn-primary btn_aksi btn_handle_order' id=form_proses_order__toggle>Proses Order</span>
    <span class='btn btn-danger btn_aksi btn_handle_order' id=form_batalkan_order__toggle>Batalkan Order</span>
  </div>
  ";

  $form_batalkan_order = "
  <form class='hideit wadah' method='post' id=form_batalkan_order>
    <div class='flexy'>
      <div>
        <input required type='text' class='form-control' name=alasan_batal placeholder='Alasan batal...'>
      </div>
      <div>
        <button class='btn btn-danger' name=btn_batalkan value='$order_no'>Batalkan</button>
      </div>
    </div>
  </form>
  ";

  $username_baru = strtolower(str_replace(' ', '', $d['pendaftar']));
  $s = "SELECT 1 FROM tb_pendaftar WHERE username like '$username_baru%'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $count = mysqli_num_rows($q);
  if ($count) {
    $count++;
    $username_baru .= $count;
  }

  $opt_pendaftar = '<option value="">--Pendaftar Baru--</option>';
  $s = "SELECT * FROM tb_pendaftar ORDER BY nama";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    // $id=$d['id'];
    $opt_pendaftar .= "<option value=$d[username]>$d[nama] - $d[no_wa] - $d[perusahaan]</option>";
  }



  $form_proses_order = "
    <form class='hideita wadah gradasi-hijau' method='post' id=form_proses_order>
      <input type=hidden name=nama_pendaftar value='$nama_pendaftar'> 
      <input type=hidden name=perusahaan_pendaftar value='$perusahaan_pendaftar'> 
      <input type=hidden name=jabatan_pendaftar value='$jabatan_pendaftar'> 

      <select class='form-control mb2'>$opt_pendaftar</select>

      <div class='f14 darkabu mb1 mt4'>Username pendaftar baru:</div>
      <input required class='form-control' name=username_pendaftar id=username_pendaftar value='$username_baru' placeholder='Username untuk Pendaftar...'>
      <div class='f12 abu miring mb3 mt1'>Password default sama dengan username</div>

      <div class='f14 darkabu mb1 mt4'>Whatsapp Pendaftar:</div>
      <input required class='form-control mb1' name=no_wa_pendaftar id=no_wa_pendaftar placeholder='Whatsapp Pendaftar...'>
      <div class='f12 abu miring mb3 ml1'>Lihat pada pesan whatsapp yang diterima</div>

      <textarea class='form-control mb3' name=pesan_tambahan placeholder='Pesan tambahan untuk pendaftar ini jika ada...'></textarea>

      <button class='btn btn-primary' name=btn_proses value='$order_no'>Proses dan Kirim ke Whatsapp Pendaftar</button>
    </form>
  ";
}

echo "$proses_or_batal";
echo $form_batalkan_order;
echo $form_proses_order;

if ($status > 0) {
  $password = substr($d['no_wa_pendaftar'], -4);
  $s2 = "SELECT * FROM tb_pendaftar WHERE no_wa like '$d[no_wa_pendaftar]'";

  $pesan_wa = pesan_wa('after_order', $d['pendaftar'], $d['perusahaan'], $order_no, $d['username_pendaftar'], $password);
  $href = "https://api.whatsapp.com/send?phone=$d[no_wa_pendaftar]&text=" . html2wa($pesan_wa);

  echo "
    <button class='btn btn-success btn_aksi' id=kirim_ulang_pesan__toggle>Kirim Ulang Pesan WA</button>
    <div class='hideit wadah mt4' id=kirim_ulang_pesan>
      <div class='f14 abu mt4 mb1'>Preview Pesan WA untuk pendaftar:</div>
      <div class=wadah>
        $pesan_wa
        <div class='mt2'><a target=_blank class='btn btn-primary w-100' href='$href' onclick='location.replace(\"?verifikasi-order&order_no=$order_no\")'>Kirim Pesan</a></div>
      </div>
    </div>
  ";
}
?>































<script>
  $(function() {
    $('.btn_handle_order').click(function() {
      let id = $(this).prop('id');
      if (id == 'form_proses_order__toggle') {
        $('#form_batalkan_order').slideUp();
      } else {
        $('#form_proses_order').slideUp();
      }
    })
  })
</script>


<script>
  $(function() {
    $('#no_wa_pendaftar').keyup(function() {
      let val = $(this).val();

      if (val.length > 2) {
        if (val.substring(0, 1) == '0') {
          $(this).val('62' + val.substring(1, 100));
        }
      }

      $(this).val(
        $(this).val().replace(/[^0-9]/g, '')
      )
    });

    $('#username_pendaftar').keyup(function() {
      $(this).val(
        $(this).val()
        .trim()
        .toLowerCase()
        .replace(/[!@#$%^&*()+\-=\[\]{}.,;:'`"\\|<>\/?~ ]/gim, '')
      );

    });
  })
</script>