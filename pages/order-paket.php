<style>
  .item-corporate {
    background: #ddffffaa;
  }
</style>
<?php
// admin_only();


$pesan_insert = '';
if (isset($_POST['btn_verifikasi'])) {


  $pesan_by_system = str_replace('<br>', '%0a', $_POST['pesan_by_system']);
  $pesan_tambahan = $_POST['pesan_tambahan'] ? "%0a%0a%0a_Pesan tambahan:_%0a $_POST[pesan_tambahan]" : '';
  $text_wa = "$pesan_by_system $pesan_tambahan";
  $href = "https://api.whatsapp.com/send?phone=$no_wa&text=$text_wa";

  $href = str_replace(array("\r", "\n"), '', $href);
  $href = str_replace("\n", '%0a', $href);

  echo div_alert('success', "Pastikan Anda membuka Whatsapp-Web atau sudah terinstall Aplikasi Whatsapp. Pesan Order akan diteruskan melalui whatsapp ke Marketing PT.MMC.");
  jsurl($href, 2000);
  exit;
}

if (isset($_POST['btn_order_paket'])) {
  $order_no = $_POST['order_no'] ?? die(erid('order_no'));
  $pendaftar = $_POST['pendaftar'] ?? die(erid('pendaftar'));
  $jabatan = $_POST['jabatan'] ?? die(erid('jabatan'));
  $perusahaan = $_POST['perusahaan'] ?? die(erid('perusahaan'));
  $jumlah_peserta = $_POST['jumlah_peserta'] ?? die(erid('jumlah_peserta'));
  $id_paket = $_POST['btn_order_paket'] ?? die(erid('btn_order_paket'));

  $s = "SELECT 1 FROM tb_order WHERE order_no='$order_no'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    // sudah ada data
    $pesan_insert = div_alert('success', 'Order Anda sudah tersimpan di database.');
  } else {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $s = "INSERT INTO tb_order (
      order_no,
      pendaftar,
      jabatan,
      perusahaan,
      jumlah_peserta,
      id_paket,
      ip_address
    ) VALUES (
      '$order_no',
      '$pendaftar',
      '$jabatan',
      '$perusahaan',
      '$jumlah_peserta',
      '$id_paket',
      '$ip_address'
    )";

    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $pesan_insert = div_alert('success', 'Order Anda berhasil tersimpan.');
  }




  // exit;
}

$judul = 'Order Paket';
set_title($judul);
$divs = '';
$id_paket = $_GET['id_paket'] ?? die(erid('id_paket'));

// get paket properti
$s = "SELECT 
a.id_program,
a.no as no_paket,
a.nama as nama_paket,
a.deskripsi,
a.biaya,
a.info_biaya 

FROM tb_paket a WHERE id=$id_paket";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) die('Data Paket tidak ditemukan.');
$paket = mysqli_fetch_assoc($q);

$id_program = $paket['id_program'];

$Corporate = $id_program == 1 ? 'Corporate' : 'Mandiri';

$s = "SELECT 
a.id as id_paket,
a.nama as nama_paket,
a.deskripsi,
a.info_biaya,
a.biaya,
a.customizable,
(
  SELECT COUNT(1) FROM tb_paket_detail 
  WHERE id_paket=a.id) count_pemeriksaan

FROM tb_paket a 
JOIN tb_program b ON a.id_program = b.id
WHERE a.id=$id_paket ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$count_valid_paket = 0;
while ($paket = mysqli_fetch_assoc($q)) {
  if ($paket['count_pemeriksaan'] == 0) continue;
  $count_valid_paket++;
  $id_paket = $paket['id_paket'];
  $nama_paket = $paket['nama_paket'];
  $customizable = $paket['customizable'];

  $where_id = $customizable ? "b.id_klinik=$id_klinik" : " a.id_paket=$paket[id_paket]";

  if ($customizable) {
    $s2 = "SELECT 
    a.id as id_pemeriksaan,
    a.nama as nama_pemeriksaan,
    a.deskripsi 

    FROM tb_pemeriksaan a 
    WHERE a.id_klinik=$id_klinik ";
    $lihat_detail = 'Lihat Pilihan Pemeriksaan';

    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    $pilihan = '';
    while ($detail = mysqli_fetch_assoc($q2)) {
      $id_pemeriksaan = $detail['id_pemeriksaan'];
      $deskripsi = $detail['deskripsi'];
      $pilihan .= "
        <tr>
          <td>
            <input type=checkbox name=pemeriksaan__$id_pemeriksaan id=pemeriksaan__$id_pemeriksaan>
          </td>
          <td>
            <label for=pemeriksaan__$id_pemeriksaan class=pointer>
              $detail[nama_pemeriksaan] 
              <div class='f12 abu miring'>$deskripsi</div>
            </label>
          </td>
        </tr>
      ";
    }
    $details = "
      <form method=post>
        <table class='table table-bordered kiri'>
          $pilihan
        </table>
        <button class='btn btn-primary w-100' onclick='onDev()'>Submit Custom Pemeriksaan</button>
      </form>
    ";
    $form_order = '';
  } else {

    // non custom
    $lihat_detail = 'Lihat Detail Pemeriksaan';
    $order_no = date('y') . "0$id_klinik-0$id_program-" . strtotime('now');
    $form_order = "
      <form method=post class='mt4 wadah gradasi-hijau'>
        <div class='flexy flex-between'>
          <div class='sub_form'>Form Order Paket MCU</div>
          <div class='consolas darkblue flexy'>
            <div>Order No:</div> 
            <div>
              <input type=hidden name=order_no value=$order_no >
              <input name=order_no2 value=$order_no class='form-control form-control-sm' disabled>
            </div> 
            
          </div>

        </div>

        <div class='darkabu mb1'>Nama Anda</div>
        <input required minlength=3 maxlength=30 class='form-control' name=pendaftar id=pendaftar>
        <div class='mb4 mt1 f12 abu miring'>Mohon Anda masukan nama lengkap Anda sesuai KTP agar proses verifikasi berjalan lancar</div>

        <select class='form-control mb2' name=jabatan id=jabatan>
          <option value='0'>--Pilih Posisi--</option>
          <option value='Pimpinan Perusahaan'>Saya Pimpinan Perusahaan</option>
          <option value='Divisi HRD'>Saya Divisi HRD</option>
          <option value='Divisi Marketing'>Saya Divisi Marketing</option>
          <option value='Divisi Lainnya'>Saya Divisi Lainnya</option>
        </select>

        <input required minlength=3 maxlength=50 class='form-control mb4' name=perusahaan id=perusahaan placeholder='Nama Perusahaan...'>

        <div class='darkabu mb1'>Perkiraan Jumlah Peserta MCU</div>
        <input required type=number min=10 max=100000 class='form-control mb1' name=jumlah_peserta id=jumlah_peserta>
        <div class='mb2 f12 abu miring'>Silahkan masukan estimasi jumlah peserta MCU yang akan Anda daftarkan! Jumlah peserta sangat mempengaruhi terhadap negosiasi biaya paket.</div>

        <div class=mt2><button disabled class='btn btn-primary w-100' name=btn_order_paket id=btn_order_paket value=$id_paket>Order Paket</button></div>

      </form>
    ";

    $s2 = "SELECT 
    b.nama as nama_pemeriksaan

    FROM tb_paket_detail a 
    JOIN tb_pemeriksaan b ON a.id_pemeriksaan =b.id 
    WHERE a.id_paket=$paket[id_paket] ORDER BY no";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    $details = '';
    while ($detail = mysqli_fetch_assoc($q2)) {
      $details .= "<li>$detail[nama_pemeriksaan]</li>";
    }
    if ($details) $details = "<ol class='f14 darkabu m0 pl3'>$details</ol>";
  }




  $id_toggle = 'detail' . $id_paket . '__toggle';
  $biaya_show = $paket['biaya'] ? number_format($paket['biaya'], 0) : '';
  $shout = $id_program == 1 ?  $paket['info_biaya'] : 'Rp' . $biaya_show;
  $shout = $shout == 'Rp' ? 'Custom Biaya' : $shout;

  if ($pesan_insert) {
    $tgl = date('M d, Y, H:i:s');

    $arr = explode('?', $_SERVER['HTTP_REFERER']);
    $link = "$arr[0]?verifikasi-order&order_no=$order_no";
    $link = urlencode($link);

    $pesan_by_system = "*ORDER-NO : $order_no* <br>============================<br>Kepada Yth. Tim Marketing Mutiara Medical Center,<br><br>Saya *$pendaftar* selaku *$jabatan di $perusahaan* mengajukan Order Paket MCU *$Corporate $nama_paket* dengan estimasi jumlah peserta sebanyak *$jumlah_peserta karyawan*.<br><br>Mohon segera di-follow-up untuk Surat Penawaran-nya. Terimakasih. [Mutiara MCU System, $tgl]<br><br>$link";
    $divs = "
      $pesan_insert
      <div class='wadah p2 item-corporate' style='max-width:600px; margin:auto'>
        <form method=post class=''>
          <div class='f14 darkabu mb1 consolas'>Pesan By System</div>
          <div class='bordered f12 abu p1 mb4'>$pesan_by_system</div>
          <input type=hidden name=pesan_by_system value='$pesan_by_system' />

          <div class='f14 darkabu mb1'>Pesan tambahan dari Anda (opsional)</div>
          <textarea class='form-control mb2' rows=5 name=pesan_tambahan id=pesan_tambahan></textarea>

          <button class='btn btn-primary w-100' name=btn_verifikasi>Lanjutkan Verifikasi by Whatsapp</button>
          <div class='f12 mt1 darkabu'>Pesan Order Anda akan kami teruskan ke Bagian Marketing PT. MMC via Whatsapp agar segera di follow-up dan segera membuat Surat Penawaran untuk Anda.</div>

        </form>
      </div>
    ";
  } else {

    $src_hi = "assets/img/paket/$id_paket-hi.jpg";
    $src = "assets/img/paket/$id_paket.jpg";
    if (file_exists($src_hi)) {
      $paket =  "<a href='$src_hi' target=_blank><img src='$src_hi' class='img-fluid br5'></a>";
    } elseif (file_exists($src)) {
      $paket =  "<a href='$src' target=_blank><img src='$src' class='img-fluid br5'></a>";
    } else {
      $paket = "        
      <h3 >$paket[nama_paket]</h3>
      <div class='f14  mt1 mb2'>$paket[deskripsi]</div>
    ";
    }

    $divs = "
      <div class='wadah p2 item-corporate' style='max-width:600px; margin:auto'>
        $paket
        <div class='f18 consolas darkblue mt1 mb1'>$shout</div>
        <span class='btn_aksi pointer f12' id=$id_toggle> $img_detail $lihat_detail</span>
        <div id=detail$id_paket class='hideit wadah gradasi-kuning mt1 '>$details</div>
        $form_order
      </div>
    ";
  }
}

$alert = '';
if (!$count_valid_paket) $alert = div_alert('danger', "Maaf, belum ada Paket yang cocok untuk Program ini. Anda boleh menghubungi kami untuk informasi lebih lanjut dengan cara klik Nomor Whatsapp di paling atas.");

echo "
<div class='section-title'>
  <h2>$judul</h2>
  <p>$back | Silahkan Anda melanjutkan proses order MCU $Corporate!</p>
</div>

<section id='produk' class='produk p0'>
  $divs
  $alert
</section>
";


?>
<script>
  $(function() {
    $('#jabatan').change(function() {
      let val = $(this).val();
      console.log(val);
      if (val == '0') {
        $(this).addClass('gradasi-merah');
        $('#btn_order_paket').prop('disabled', 1);

      } else {
        $('#btn_order_paket').prop('disabled', 0);
        $(this).removeClass('gradasi-merah');

      }
    })
  })
</script>