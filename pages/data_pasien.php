<?php
$s = "SELECT
a.date_created as tanggal_daftar,
a.id as id_pasien,
a.nama,
a.jenis ,
a.order_no,
a.id_paket_custom,
b.nama as jenis_pasien,
( 
  SELECT p.nama FROM tb_status_pasien p WHERE p.status=a.status
  ) status_pasien,
( 
  SELECT p.nama FROM tb_paket p 
  JOIN tb_order q ON p.id=q.id_paket 
  JOIN tb_pasien r ON q.order_no=r.order_no 
  WHERE r.id=a.id 
  ) nama_paket,
( 
  SELECT status_bayar FROM tb_paket_custom p
  WHERE p.id=a.id_paket_custom 
  ) status_bayar,
( 
  SELECT tanggal_bayar FROM tb_paket_custom p
  WHERE p.id=a.id_paket_custom 
  ) tanggal_bayar,
( 
  SELECT COUNT(1) FROM tb_paket_custom_detail p 
  JOIN tb_paket_custom q ON p.id_paket_custom=q.id 
  WHERE q.id=a.id_paket_custom 
  ) count_detail_paket_custom,
( 
  SELECT last_pemeriksaan FROM tb_hasil_pemeriksaan p 
  WHERE p.id_pasien=a.id 
  ) last_pemeriksaan,
( 
  SELECT status FROM tb_hasil_pemeriksaan p 
  WHERE p.id_pasien=a.id 
  ) status_pemeriksaan

FROM tb_pasien a 
JOIN tb_jenis_pasien b ON a.jenis=b.jenis 
WHERE a.date_created > '$tanggal_awal' 
ORDER BY date_created DESC
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $th = '<th>No</th>';
  // $jenis['COR'] = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_pasien = $d['id_pasien'];
    $JENIS = strtoupper($d['jenis']);
    $jenis[$JENIS]++;

    $td = "<td>$i</td>";
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'id_pasien'
        || $key == 'id_paket_custom'
        || $key == 'jenis_pasien'
        || $key == 'order_no'
        || $key == 'jenis'
        || $key == 'count_detail_paket_custom'
        || $key == 'tanggal_bayar'
        || $key == 'last_pemeriksaan'
      ) continue;
      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th>$kolom</th>";
      }

      if ($key == 'nama') {
        $value = "$value<div class='mt1 f14 miring abu'>$d[jenis_pasien]</div>";
      } elseif ($key == 'status_bayar') {
        $value = $JENIS == 'COR' ? "Corporate $img_check" : 'baru didaftarkan';
        if ($d['id_paket_custom']) {
          $value = 'sudah punya paket';
          if ($d['count_detail_paket_custom']) {
            $value = 'sudah memilih detail paket';
            if ($d['tanggal_bayar']) {
              if ($JENIS == 'BPJ') {
                $value = 'BPJS';
              } else {
                $value = 'LUNAS';
              }
              $tgl = hari_tanggal($d['tanggal_bayar'], 0, 0, 1, 1, '-');
              $value = "<div class=f12>$tgl</div>$value $img_check";
            }
          }
        }
        $value = "<i class='f14 abu'>$value</i>";
      } elseif ($key == 'status_pasien') {
        $value = $value ? "<span class=f14>$value</span>" : "<span class='f12 abu miring'>pasien baru</span>";
        $value .= "<div class='mt1 f10 abu'>MCU$thn-$id_pasien</div>";
      } elseif ($key == 'tanggal_daftar') {
        $value = '<span class=f14>' . hari_tanggal($value, 0, 1, 1, 0, '-') . '</span>';
      } elseif ($key == 'nama_paket') {
        if ($JENIS == 'COR') {
          $value = "$value<div class='f12 abu miring'><a href='?manage_order&order_no=$d[order_no]'>$d[order_no]</a></div>";
        } else {
          if (!$value and !$d['id_paket_custom']) {
            $value = '<span class="btn btn-primary btn-sm">Buat Baru</span>';
          } else {
            $id_custom = $d['id_paket_custom'];
            if ($id_custom < 10) {
              $id_custom = "00$id_custom";
            } elseif ($id_custom < 100) {
              $id_custom = "0$id_custom";
            }
            $value = "Paket-$id_custom";
          }
          $count = $d['count_detail_paket_custom'] ? "<span class='green'>$d[count_detail_paket_custom] pemeriksaan</span>" : '<span class=red>belum ada item pemeriksaan</span>';
          $value = "<a href='?manage_paket_custom&id_pasien=$id_pasien' >$value<div class='f12 mt1'>$count</div></a>";
        }
      } elseif ($key == 'status_pemeriksaan') {
        $loading = "<img src='assets/img/gifs/loading.gif' height=25px>";
        if (!$value) {
          $value = '<span class="f12 miring darkred">belum pemeriksaan</span>';
        } elseif ($value == 1) {
          $value = "<span class='f12 miring green'>awal pemeriksaan $loading</span>";
        } elseif ($value == 2) {
          $value = "
            <span class='f12 miring green'>
              sedang pemeriksaan 
              <span class=btn_aksi id=last_pemeriksaan__toggle>$loading</span>
            </span>
            <div class='hideit wadah gradasi-kuning mt2 mb2' id=last_pemeriksaan>
              <div class='abu miring'>Last Pemeriksaan:</div>
                $d[last_pemeriksaan]
              </div>
            </div>
          ";
        } else {
          $value = div_alert('danger', 'UNKNOWN STATUS');
        }
        $value .= "<div class=><a href='?tampil_pasien&id_pasien=1454&jenis=bpj'>$img_next</a></div>";
      }

      $td .= "<td>$value</td>";
    }
    $tr .= "
      <tr>
        $td
        <td>
          $img_edit 
          $img_delete 
        </td>
      </tr>
    ";
  }
}

$data_pasien = $tr ? "
  <table class=table>
    <thead>$th<th>Aksi</th></thead>
    $tr
  </table>
" : div_alert('danger', "Data pasien tidak ditemukan.");
