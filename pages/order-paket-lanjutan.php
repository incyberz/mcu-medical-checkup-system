<style>
  .sub-judul {
    background: linear-gradient(to right, rgba(200, 255, 200, 1), #aaffaa00);
    padding: 8px 12px;
    border-radius: 5px;
    color: #77f;
    font-size: 20px;
  }
</style>
<?php
# ============================================================
# PROCESSORS
# ============================================================
$pesan_insert = '';
if (isset($_POST['btn_batalkan_order'])) {

  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';

  // echo div_alert('success', "Pastikan Anda membuka Whatsapp-Web atau sudah terinstall Aplikasi Whatsapp. Pesan Order akan diteruskan melalui whatsapp ke Marketing PT.MMC.");
  // jsurl($href, 2000);
  exit;
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
  <h2 class='sub-judul mt4 btn_aksi' id=data_order_awal__toggle>Data Order Awal</h2>
  <div class=wadah id=data_order_awal>
    $tb
    <div class='mt4 mb2 f14 darkred kanan'><span class='btn btn-danger btn-sm btn_aksi' id=form_batal__toggle>Batalkan Order</span></div>
    <form method=post class='hideita wadah gradasi-kuning' id=form_batal>
      <div class='mt1 f14 darkred'>Untuk memberikan kejelasan dan kepastian proses, Anda boleh membatalkan Order Anda, dan kami tetap sangat mengharapkan feedback dari Anda agar performa pelayanan kami tetap menjadi yang terbaik.</div>
      <div class='mt4 mb1'>Alasan Pembatalan:</div>
      <select class='form-control mb1' name=pilihan_alasan>
        <option value=1>Perihal harga tidak cocok</option>
        <option value=2>Pemeriksaan kurang lengkap</option>
        <option value=3>Rendahnya respon Pelayanan</option>
        <option value=4>Alasan lainnya...</option>
      </select>
      <textarea name=alasan_lainnya class='form-control' placeholder='Alasan lainnya...'></textarea>
      <button class='btn btn-danger mt2' name=btn_batalkan_order>Batalkan</button>
    </form>
  </div>
";
?>

<section>
  <h2 class='sub-judul mt4'>Download Proposal MCU</h2>
  <div class='wadah '>
    <p class="mt1">Silahkan Anda baca Proposal Penawaran dari kami. Untuk biaya dapat Anda negosiasikan kembali ke <a target="_blank" href="<?= $href_wa ?>&text=Yth. Marketing MMC%0a%0aSetelah melihat Proposal Penawaran, saya ingin bertanya perihal..."><?= $img_wa ?> Tim Marketing kami</a>. Untuk mendapatkan harga yang terbaik sesuai ekspektasi Anda, alangkah baiknya Saudara/i menyiapkan Purchase Order dari lembaga perusahaan Anda untuk kepastian komitmen transaksi Pembiayaan Medical Checkup.</p>
    <a href="#" class="btn btn-primary">Download Proposal Penawaran</a>
  </div>
</section>

<section>
  <h2 class='sub-judul mt4'>Kelengkapan Data</h2>
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
</section>

<section>
  <h2 class='sub-judul mt4'>Upload Data Peserta</h2>
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

</section>

<section>
  <h2 class='sub-judul mt4'>List Peserta MCU</h2>
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

</section>

<section>
  <h2 class='sub-judul mt4'>Progres Medical Checkup</h2>
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
    <button class="btn btn-primary w-100">Monitoring Progress Medical Checkup</button>
  </div>

</section>

<section>
  <h2 class='sub-judul mt4'>Download Hasil MCU</h2>
  <p>Jika semua pemeriksaan dan analisa dokter untuk seluruh karyawan Anda sudah selesai, Anda dapat mengunduh Hasil MCU.</p>
  <div class='wadah'>
    <div class="alert alert-info">Petugas belum melakukan publish hasil MCU.
      <hr>Mohon bersabar! Kami sedang menunggu semua hasil analisa dokter dan memastikan semua data bebas kesalahan.
    </div>
    <button class="btn btn-secondary w-100" disabled>Download Hasil Medical Checkup</button>
  </div>

</section>