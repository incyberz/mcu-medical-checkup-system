<?php
$kesimpulan = "Belum ada analogi kesimpulan untuk pemeriksaan $pemeriksaan.";

if ($pemeriksaan == 'tb_bb') {
  $imt = round($mcu['berat_badan'] / (($mcu['tinggi_badan'] / 100) * ($mcu['tinggi_badan'] / 100)), 2);

  if ($imt >= 27) {
    $obesitas1 = $obesitas;
  } elseif (
    $imt >= 25
  ) {
    $obesitas1 = 'gemuk';
  } elseif ($imt >= 18.5) {
    $obesitas1 = 'normal';
  } elseif ($imt >= 17) {
    $obesitas1 = 'kurus';
  } else {
    $obesitas1 = 'sangat kurus';
  }

  $blp = $gender == 'l' ? 90 : 80;
  $obesitas2 = $mcu['lingkar_perut'] >= $blp ? $obesitas : 'dalam batas normal';
  $kesimpulan = "
    <h4 class='darkblue miring f18'>Index Masa Tubuh</h4>
    <ul>
      <li>Index Masa Tubuh (IMT) = $imt</li>
      <li>Kesimpulan Berat Tubuh : $obesitas1</li>
    </ul>

    <h4 class='darkblue miring f18'>Lingkar Perut</h4>
    <ul>
      <li>Batas lingkar perut untuk $gender_show yaitu $blp</li>
      <li>Kesimpulan Lingkar Perut : $obesitas2</li>
    </ul>

  ";
}



$hasil_form = "
<div id=blok_hasil>
    <h2>HASIL PEMERIKSAAN</h2>
    <div>
      <b class=blue>Telah Diperiksa</b> oleh <span class='darkblue'>$pemeriksa</span> pada tanggal <span class=consolas>$tanggal_show</span> | <span class='abu f12'>$eta</span>
    </div>

    <div class='flex-center'>
      <div style=''>
        <table class='table table-hover'>
          $hasil_form
        </table>
      </div>
    </div>

    <div class='wadah gradasi-toska kiri'>
      <h3 class=mb4>Kesimpulan Pemeriksaan $arr_pemeriksaan[$pemeriksaan]</h3>
      $kesimpulan
    </div>
  </div>

  
  <button class='btn btn-secondary btn-sm mb2' id=btn_ubah_nilai>Ubah Nilai</button>
";

echo "
<script>
  $(function() {
    $('#btn_ubah_nilai').click(function() {
      $('#blok_hasil').slideToggle();
      $('#blok_form').slideToggle();
      if ($(this).text() == 'Ubah Nilai') {
        $(this).text('Lihat Hasil');
      } else {
        $(this).text('Ubah Nilai');
      }
    })
  })
</script>
";
