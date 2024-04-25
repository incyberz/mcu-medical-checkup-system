<?php
$jenis = $_GET['jenis'] ?? 'mcu';
$s = "SELECT * FROM tb_jenis_program WHERE jenis='$jenis'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  echo div_alert('danger', "Maaf, data jenis program tidak ditemukan.");
} else {
  $d = mysqli_fetch_assoc($q);
  $nama_jenis_program = $d['nama'];
  $deskripsi_jenis_program = $d['deskripsi'];
}

set_title("$nama_jenis_program - $nama_sistem");
echo "
  <div class='section-title'>
    <h2>$nama_jenis_program</h2>
    <p>$back | $deskripsi_jenis_program</p>
  </div>
";
?>

<section id="produk" class="produk p0">
  <?php
  if ($jenis == 'klinik-pratama') {
    echo "
    <div class='wadah gradasi-toska'>
      <h3>Informasi Klinik Pratama Mutiara</h3>
      <p>Untuk informasi lebih lanjut tentang Klinik Pratama kami, Anda dapat mengunjungi <a href='http://kliniktambun24jam.blogspot.com/2016/05/klinik-tammbun-24-jam-mutiara-1.html' target=_blank>website blog kami</a></p>

      <h4>Informasi Singkat Klinik</h4>
      <table class=table>
        <tr>
          <td>
            
          </td>
        </tr>
      </table> 
    </div>
    <pre>
      Klinik Mutiara 1
Alamat                  :Jl.Kalimaya IV blok L2 no 1,Perum Metland Tambun (depan lapangan Tenis)
Telp                      :021 2948 7893

KLINIK MUTIARA 1
Klinik Mutiara 1
Alamat                  :Jl.Kalimaya IV blok L2 no 1,Perum Metland Tambun (depan lapangan Tenis)
Telp                      :021 2948 7893
CS                       : 0813 6768 9414   

Melayani peserta BPJS KESEHATAN dengan
No FASKES            : 0132 U 011

Dengan Fasilitas  :

    Dokter Umum dengan jadwal sbb :

    Poli Umum                  :Prakrek 24 Jam
    UGD (Emergensi)       : 24 Jam  

    Klinik Bersalin           :Praktek Bidan 24 Jam (Persalinan Normal)
    Poli Gigi                    : Selasa, Kamis, Sabtu (Pukul 08.00 Pagi dan 16.00  Sore)

Kami juga melayani (Non BPJS):

    Pelayanan Pengobatan
    Home care
    Khitanan (Sirkumsisi)
    Imunisasi
    Pemeriksaan Ibu Hamil
    Persalinan
    Klinik Rawat Inap
    Operasi Kecil
    Keur Dokter

    Medical Check Up karyawan / Calon Karyawan
    Penyelenggaraan Inhouse Klinik di perusahaan
    Penyelenggaraan Klinik Sekolah 
    Penyelenggaraan POSKESTREN (Pos KesehatanPesantren)


    </pre>
    ";
  } else {
    // echo div_alert('danger m3', "Maaf belum ada handler untuk jenis program $jenis. Anda boleh melaporkan ke kami via Whatsapp di paling atas. Terimakasih.");
    $s = "SELECT *,a.id as id_program FROM tb_program a WHERE a.jenis='$jenis' AND a.id_klinik=$id_klinik";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (!mysqli_num_rows($q)) {
      echo div_alert('danger m3', "Maaf, belum ada data untuk klasifikasi program <b class='darkblue consolas'>$jenis</b>.");
    } else {
      while ($program = mysqli_fetch_assoc($q)) {
        $id_program = $program['id_program'];
        $href = $program['href'] ?? "?program-detail&id_program=$id_program";
        echo "
          <div class=row>
            <div class='col-lg-6 col-md-6 d-flex align-items-stretch'>
              <div class='icon-box'>
                <h4>
                  <a href='$href'>
                    <img class='img-mcu' src='assets/img/$program[image]' alt='mcu-corporate'>
                    <div>$program[nama]</div>
                  </a>
                </h4>
                <p><span class='shout'>$program[shout]</span></p>
                <div style='min-height:80px'><p>$program[deskripsi]</p></div>
                <a class='btn btn-success w-100 mt3' href='$href'>$program[caption]</a>
              </div>
            </div>
          </div>
        ";
      }
    }
  }

  ?>
</section>