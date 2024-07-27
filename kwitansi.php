<style>
  * {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
    box-sizing: border-box;
  }

  .body {
    width: 21cm;
    height: 9.85cm !important;
    padding: 0.3cm;
    /* background: #efe; */
    border-bottom: dashed 1px #ccc;
  }

  .kwitansi {
    height: 100%;
    /* background: pink; */
    overflow: hidden;
    display: grid;
    grid-template-columns: 22% 78%;
  }

  .header,
  .logo,
  .alamat,
  .konten {
    border: solid 1px darkblue;
    font-size: 14px;
  }

  .header {
    height: 17%;
    /* background: pink; */
    display: flex;
    align-items: center;
    text-align: center;
  }

  .header div {
    font-size: 30px;
    width: 100%;
    letter-spacing: 10px;
  }

  .konten {
    height: 83%;
    padding: 15px;
    position: relative;
  }

  .footer {
    position: absolute;
    bottom: 0;
    /* background: red; */
    width: 100%;
    left: 0;
    text-align: center;
    padding: 7px;
    font-size: 10px;
    letter-spacing: .4px;
    border-top: solid 1px gray;
  }

  .logo,
  .alamat {
    display: flex;
    /* background: pink; */
    height: 50%;
    text-align: center;
    align-items: center;
    color: #009241;
    font-weight: bold;
  }

  .alamat {
    height: 50%;
    /* background: yellow; */
    /* font-weight: normal; */
    padding: 10px;
    font-size: 12px;
  }

  .alamat_jalan {
    margin-top: 10px;
  }

  .nama {
    background: yellow;
  }

  .nomor {
    background: pink;
  }

  .judul {
    text-align: center;
    font-size: 30px;
  }

  .mmc_logo {
    width: 40%;
    display: inline-block;
    margin-bottom: 10px;
  }

  .mutiara {
    letter-spacing: 1px;
    font-size: 15px;
  }

  .row {
    display: grid;
    grid-template-columns: 25% 2% 59%;
    margin-bottom: 6px;
  }

  .list_pemeriksaan {
    padding: 5px 0 0 15px;
    font-size: 14px;
  }

  .telah_terima {
    color: #444;
    font-style: italic;
  }

  .nominal {
    color: darkblue;
    font-size: 35px;
  }

  .qr {
    position: absolute;
    bottom: 40px;
    right: 15px;
    height: 80px;
    width: 80px;
  }

  .qr_img {
    width: 100%;
  }

  .terbilang {
    font-style: italic;
    font-family: 'Times New Roman', Times, serif;
    text-transform: capitalize;
  }
</style>

<?php
include 'conn.php';
include 'include/insho_functions.php';
include 'include/terbilang.php';
$id_paket_custom = $_POST['id_paket_custom'] ?? '';
$id_pasien_corporate_mandiri = $_POST['id_pasien_corporate_mandiri'] ?? '';
if (!$id_paket_custom and !$id_pasien_corporate_mandiri) {
  echo '<h1 style=color:red>Page ini tidak bisa diakses secara langsung</h1>';
  jsurl('index.php', 5000);
}

# ============================================================
# MAIN SELECT
# ============================================================
$s = "SELECT 
a.nominal_bayar,
b.id as id_pasien,
b.nama as nama_pasien,
c.nama as nama_kasir,
a.tanggal_bayar,
# ============================================================
# DENGAN MCU ATAU LAB SAJA
# ============================================================
(
  SELECT COUNT(1) FROM tb_paket_custom p 
  JOIN tb_paket_custom_detail q ON p.id=q.id_paket_custom 
  JOIN tb_pemeriksaan r ON r.id=q.id_pemeriksaan 
  WHERE r.jenis = 'MCU'
  ) is_mcu



FROM tb_paket_custom a 
JOIN tb_pasien b ON a.id=b.id_paket_custom 
JOIN tb_user c ON a.kasir=c.id 
WHERE a.id=$id_paket_custom";

if ($id_pasien_corporate_mandiri) {
  $s = "SELECT 
  a.nominal_bayar,
  b.id as id_pasien,
  b.nama as nama_pasien,
  c.nama as nama_kasir,
  a.tanggal_bayar,
  # ============================================================
  # DENGAN MCU ATAU LAB SAJA
  # ============================================================
  (
    SELECT COUNT(1) FROM tb_paket_custom p 
    JOIN tb_paket_custom_detail q ON p.id=q.id_paket_custom 
    JOIN tb_pemeriksaan r ON r.id=q.id_pemeriksaan 
    WHERE r.jenis = 'MCU'
    ) is_mcu



  FROM tb_pembayaran a 
  JOIN tb_pasien b ON a.id_pasien=b.id 
  JOIN tb_user c ON a.kasir=c.id 
  WHERE a.id_pasien=$id_pasien_corporate_mandiri
  ";
}
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

if (mysqli_num_rows($q) == 0) die(div_alert('danger', 'Data tidak ditemukan'));
$d = mysqli_fetch_assoc($q);
$kasir = $d['nama_kasir'];
$id_pasien = $d['id_pasien'];
$nama_pasien = ucwords(strtolower($d['nama_pasien']));
$nama_kasir = ucwords(strtolower($d['nama_kasir']));
$nominal_show = number_format($d['nominal_bayar']);
$terbilang = terbilang($d['nominal_bayar']) . ' rupiah';
$tanggal_show = hari_tanggal($d['tanggal_bayar'], 1, 0, 0);
$mcu_dan = $d['is_mcu'] ? 'Medical Checkup dan' : '';

$nomor = $d['is_mcu'] ? 'MCU' : 'LAB';
$nomor .= "-$id_pasien";

?>

<div class=body>
  <div class="kwitansi">
    <div>
      <div class="logo">
        <div>
          <img src="assets/img/mmc_logo.png" alt="kwitansi" class="mmc_logo">
          <div class="mutiara">MUTIARA</div>
          <div>MEDICAL CENTER</div>
        </div>
      </div>
      <div class="alamat">
        <div>
          <div>TAMBUN BUSINESS PARK BLOK C NO. 12</div>
          <div class="alamat_jalan">Jl. Raya Teuku Umar No.256, Tambun, Kabupaten Bekasi, Jawa Barat 17510</div>
        </div>
      </div>
    </div>
    <div>
      <div class="header">
        <div>KWITANSI</div>
      </div>
      <div class="konten">
        <div class="row">
          <div class="telah_terima">Telah terima dari</div>
          <div>:</div>
          <div><?= $nama_pasien ?></div>
        </div>
        <div class="row">
          <div class="telah_terima">Uang sejumlah</div>
          <div>:</div>
          <div class="nominal">Rp <?= $nominal_show ?>,-</div>
        </div>
        <div class="row">
          <div class="telah_terima">Terbilang</div>
          <div>:</div>
          <div class="terbilang"><?= $terbilang ?></div>
        </div>
        <div class="row">
          <div class="telah_terima">Untuk Pembayaran</div>
          <div>:</div>
          <div>
            Biaya <?= $mcu_dan ?> Pemeriksaan Laboratorium
          </div>
        </div>
        <div class="row">
          <div class="telah_terima">Nomor</div>
          <div>:</div>
          <div><?= $nomor ?></div>
        </div>
        <div class="row">
          <div class="telah_terima">Tanggal Pemeriksaan</div>
          <div>:</div>
          <div><?= $tanggal_show ?></div>
        </div>

        <div class="qr">
          <img src="assets/img/qr_mmc.jpg" alt="MMC" class="qr_img">
        </div>

        <div class="footer">
          Printed by <?= $nama_kasir ?> at Mutiara Medical System, <?= date('D, M d, Y, H:i:s') ?>, http://mmc-clinic.com
        </div>

      </div>

    </div>

  </div>
</div>