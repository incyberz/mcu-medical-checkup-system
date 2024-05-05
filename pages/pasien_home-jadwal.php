<?php
// $jadwal =  date('d-m-Y', strtotime('+13 day', strtotime($d['tanggal_order'])));
$jadwal = '10-05-2024'; // zzz
$jadwal_sesi = 'Sesi 3 (siang)'; // zzz
$jadwal_pukul = '13:20 - 15:00 WIB'; // zzz
$lokasi = 'Onsite di Perusahaan'; // zzz

$notif_kuesioner = "<div class='tengah f12 blue mt1 tebal' id=btn_update_biodata_info>Silahkan isi dahulu kuesioner agar Anda mendapatkan Sticker-Medis untuk prasyarat pemeriksaan</div>";


$type_btn_jadwal = 'primary';
$blok_jadwal = $status < 2 ? '' : "
  <div class='card mb4 gradasi-hijau'>
    <div class='card-body'>
      <h3>Jadwal Medical Checkup Anda</h3>
      <table class=table>
        <tr><td class='kolom tengah' colspan=100%>Nomor MCU / Antrian</td></tr>
        <tr>
          <td class=' tengah' colspan=100%>
            <span class='green f50'>$id_pasien_show</span>
          </td>
        </tr>
        <tr><td class=kolom>Jadwal</td><td>$jadwal</td></tr>
        <tr><td class=kolom>Sesi</td><td>$jadwal_sesi</td></tr>
        <tr><td class=kolom>Pukul</td><td>$jadwal_pukul</td></tr>
        <tr><td class=kolom>Lokasi</td><td>$lokasi</td></tr>
      </table>
      <a class='btn btn-$type_btn_jadwal w-100' href='?isi-kuesioner&id_program=$id_program&id_pasien=$id_pasien'>Isi Kuesioner Online</a>
      $notif_kuesioner
    </div>
  </div>
";
