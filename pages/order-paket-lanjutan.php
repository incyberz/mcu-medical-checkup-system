<?php


# ============================================================
# PROCESSORS
# ============================================================
$pesan_insert = '';
if (isset($_POST['btn_verifikasi'])) {


  $pesan_by_system = str_replace('<br>', '%0a', $_POST['pesan_by_system']);
  $pesan_tambahan = $_POST['pesan_tambahan'] ? "%0a%0a%0a_Pesan tambahan:_%0a $_POST[pesan_tambahan]" : '';
  $text_wa = "$pesan_by_system $pesan_tambahan";
  $href = "https://api.whatsapp.com/send?phone=$whatsapp&text=$text_wa";

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
}
















# ============================================================
# NORMAL FLOW 
# ============================================================
$judul = 'Order Paket Lanjutan';
$sub_judul = "<a href='?pendaftar_home' >$img_home</a> | Silahkan lengkapi data untuk melanjutkan Proses Order";
set_title($judul);
set_h2($judul, $sub_judul);
only(['pendaftar']);
$order_no = $_GET['order_no'] ?? die(erid('order_no'));

$s = "SELECT 
a.order_no,
a.tanggal_order,
d.nama as paket,
e.nama as program,
a.jumlah_peserta,
a.tanggal_verifikasi,
b.nama as verifikator,
c.nama as status_order  
FROM tb_order a 
JOIN tb_user b ON a.diverifikasi_oleh=b.id 
JOIN tb_status_order c ON a.status=c.status 
JOIN tb_paket d ON a.id_paket=d.id 
JOIN tb_program e ON d.id_program=e.id 
WHERE order_no='$order_no'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    foreach ($d as $key => $value) {
      if ($key == 'id') continue;
      if ($key == 'tanggal_order' || $key == 'tanggal_verifikasi') {
        $value = date('d-F-Y H:i', strtotime($value));
      }


      $kolom = key2kolom($key);
      $tr .= "
        <tr>
          <td><span class='abu miring'>$kolom</span></td>
          <td>$value</td>
        </tr>
      ";
    }
  }
}

$tb = $tr ? "
  <table class=table>
    $tr
  </table>
" : div_alert('danger', "Data order tidak ditemukan.");
echo "
  <h3 class='btn btn-secondary btn-sm mt4 btn_aksi' id=data_order_awal__toggle>Data Order Awal</h3>
  <div class=wadah id=data_order_awal>
    $tb
    <div class='mt4 mb2 f14 darkred kanan'><button class='btn btn-danger btn-sm'>Batalkan Order</button></div>
    <div class='wadah gradasi-kuning'>
      <div class='mt1 f14 darkred'>Untuk memberikan kejelasan dan kepastian proses, Anda boleh membatalkan Order Anda, dan kami tetap sangat mengharapkan feedback dari Anda agar performa pelayanan kami tetap menjadi yang terbaik.</div>
      <div class='mt4 mb1'>Alasan Pembatalan:</div>
      <select class='form-control mb1' name=pilihan_alasan>
        <option value=1>Perihal harga tidak cocok</option>
        <option value=2>Pemeriksaan kurang lengkap</option>
        <option value=3>Rendahnya respon Pelayanan</option>
        <option value=4>Alasan lainnya...</option>
      </select>
      <textarea name=alasan_lainnya class='form-control' placeholder='Alasan lainnya...'></textarea>
      <button class='btn btn-danger on-dev mt2'>Batalkan</button>
    </div>
  </div>
";
?>
<h3 class='btn btn-secondary btn-sm mt4'>Proposal MCU</h3>
<div class='wadah '>
  <a href="#" class="btn btn-primary">Download Proposal Penawaran</a>
  <p class="mt1">Silahkan Anda baca Proposal Penawaran dari kami. Untuk biaya dapat Anda negosiasikan kembali ke <a target="_blank" href="<?= $href_wa ?>&text=Yth. Marketing MMC%0a%0aSetelah melihat Proposal Penawaran, saya ingin bertanya perihal..."><?= $img_wa ?> Tim Marketing kami</a>.</p>
</div>

<h3 class='btn btn-secondary btn-sm mt4'>Kelengkapan Data</h3>
<p>Silahkan Anda lengkapi data untuk melanjutkan proses order.</p>
<div class='wadah '>
  <input type="text" class="form-control" name=email placeholder="Email">
  <div class="f12 abu mb2 mt1">Silahkan Anda isi email jika ingin Hasil Medical Checkup kami kirimkan via email</div>

  <div class="f12 abu mb1 mt4">Pelaksanaan MCU</div>
  <select class="form-control mb4" name="is_onsite" id="is_onsite">
    <option value="1">Onsite di Perusahaan Anda</option>
    <option value="2">Karyawan datang ke Klinik</option>
  </select>



  <div class="wadah">
    <div class="f12 abu mb2 mt1">Alamat Lengkap Perusahaan</div>
    <textarea class="form-control mb1" name=alamat placeholder="Alamat lengkap..." rows="4"></textarea>
    <input type="text" class="form-control mb1" name=kecamatan placeholder="Kecamatan...">
    <input type="text" class="form-control mb1" name=kabupaten placeholder="Kabupaten...">
    <input type="text" class="form-control mb1" name=provinsi placeholder="Provinsi..." value="Jawa Barat">
  </div>

</div>

<h3 class='btn btn-secondary btn-sm mt4'>Upload Data Peserta</h3>
<p>Silahkan Anda upload data karyawan untuk Peserta MCU dalam bentuk File Excel (untuk posisi field bebas, kami akan menyesuaikannya). Silahkan unduh <a href="assets/xls/template-peserta-mcu.xlsx" target="_blank">File Template Excel</a> jika Anda membutuhkannya.</p>
<div class='wadah '>
  <div class="flexy">
    <div>
      <input type="file" name="file_excel" id="file_excel" accept=".xls,.xlsx">
    </div>
    <div>
      <button class="btn btn-success">Upload</button>
    </div>
  </div>
</div>

<h3 class='btn btn-secondary btn-sm mt4'>List Peserta MCU</h3>
<p>Berikut adalah List Peserta MCU yang sudah kami daftarkan. Silahkan di cek ulang jika ada kekeliruan.</p>
<div class='wadah'>
  <table class="table">
    <tr>
      <td>Total Peserta</td>
      <td>600</td>
    </tr>
    <tr>
      <td>Peserta Melengkapi Biodatanya</td>
      <td>600 of 600 (100%)</td>
    </tr>
    <tr>
      <td>Peserta Menjawab Kuesioner Online</td>
      <td>413 of 600 (84%)</td>
    </tr>
  </table>
</div>

<h3 class='btn btn-secondary btn-sm mt4'>Progres Medical Checkup</h3>
<p>Anda dapat ikut monitoring sejauh mana proses pemeriksaan Medical Checkup untuk karyawan Anda.</p>
<div class='wadah'>
  <table class="table">
    <tr>
      <td>Total Peserta</td>
      <td>600</td>
    </tr>
    <tr>
      <td>Pendaftaran</td>
      <td>600 of 600 (100%)</td>
    </tr>
    <tr>
      <td>Pemeriksaan</td>
      <td>413 of 600 (84%)</td>
    </tr>
    <tr>
      <td>Analisa Dokter</td>
      <td>167 of 600 (27%)</td>
    </tr>
    <tr>
      <td>Publish Hasil</td>
      <td>0 of 600 (<span class="f12 miring">Petugas belum Publish Hasil MCU karyawan Anda</span> )</td>
    </tr>
  </table>
</div>

<h3 class='btn btn-secondary btn-sm mt4'>Download Hasil MCU</h3>
<p>Jika semua pemeriksaan dan analisa dokter untuk seluruh karyawan Anda sudah selesai, Anda dapat mengunduh Hasil MCU.</p>
<div class='wadah'>
  <div class="alert alert-info">Petugas belum melakukan publish hasil MCU.
    <hr>Mohon bersabar! Kami sedang menunggu semua hasil analisa dokter dan memastikan semua data bebas kesalahan.
  </div>
</div>