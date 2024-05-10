<?php
// $jadwal =  date('d-m-Y', strtotime('+13 day', strtotime($d['tanggal_order'])));
$jadwal = '10-05-2024'; // zzz
$jadwal_sesi = 'Sesi 3 (siang)'; // zzz
$jadwal_pukul = '13:20 - 15:00 WIB'; // zzz
$lokasi = 'Onsite di Perusahaan'; // zzz

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
    </div>
  </div>
";
