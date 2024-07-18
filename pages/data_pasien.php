<?php
# ============================================================
# INCLUDES 
# ============================================================
include 'include/arr_status_pasien.php';
$arr_status_pasien[0] = 'Belum Pembayaran';
$count_status = [];
foreach ($arr_status_pasien as $key => $value) {
  // inisialisasi jumlah status pasien
  $count_status[$key] = 0;
}




# ============================================================
# MAIN SELECT DATA PASIEN
# ============================================================
$s = "SELECT
a.date_created as tanggal_daftar,
a.id as id_pasien,
a.nama,
a.jenis ,
a.order_no,
a.id_paket_custom,
a.status,
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
    $count_status[$d['status'] ?? 0]++;

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
        || $key == 'status'
      ) continue;
      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th>$kolom</th>";
      }

      if ($key == 'nama') {
        $value = "
          $value
          <div class='mt1 f14 miring abu'>
            $d[jenis_pasien]
            <a style='display:inline-block;margin-left:10px' href='?tampil_pasien&id_pasien=$d[id_pasien]&jenis=$d[jenis]&mode=edit_pasien' onclick='return confirm(`Edit pasien ini?`)'>$img_edit</a>
            <a href='?hapus_pasien&id_pasien=$d[id_pasien] onclick='return confirm(`Hapus pasien ini?`)'>$img_delete</a>
          </div>
        ";
      } elseif ($key == 'status_bayar') {
        $value = $JENIS == 'COR' ? "Corporate $img_check" : 'baru didaftarkan';
        if ($d['id_paket_custom']) {
          $value = 'sudah punya paket';
          if ($d['count_detail_paket_custom']) {
            $value = 'sudah memilih detail paket';
            if ($d['tanggal_bayar']) {
              if ($JENIS == 'BPJ') {
                $value = '<span class="coklat bold">BPJS</span>';
              } else {
                $value = '<span class="green bold">LUNAS</span>';
              }
              $tgl = hari_tanggal($d['tanggal_bayar'], 0, 0, 1, 1, '-');
              $value = "<div class=f12>$tgl</div>$value $img_check";
            }
          }
        }
        $value = "<i class='f12 abu'>$value</i>";
      } elseif ($key == 'status_pasien') {
        if (!$value) {
          $value = "<span class='f12 red bold'>pasien baru</span> <a href='?manage_paket_custom&id_pasien=$id_pasien'>$img_next</a>";
        } elseif ($d['status'] == 10) {
          $value =  "<span class='f12 green'>$d[status] ~ $value $img_check</span>";
        } else {
          $value = "<span class=f12>$d[status] ~ $value</span>";
        }
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
        if ($d['status'] == 10) {
          $value = "<span class='f12 green'> selesai $img_check</span>";
        } elseif (!$value) {
          $value = '<span class="f12 miring red bold">belum-pem.</span>';
        } elseif ($value == 1) {
          $value = "<span class='f12 miring green'>awal pemeriksaan $loading</span>";
        } elseif ($value == 2) {
          $toggle_id = "last_pemeriksaan_$id_pasien" . "__toggle";
          $value = "
            <span class='f12 miring green'>
              sedang-pem. 
              <span class=btn_aksi id=$toggle_id>$loading</span>
            </span>
            <div class='hideit wadah gradasi-kuning mt2 mb2' id=last_pemeriksaan_$id_pasien>
              <div class='abu miring'>Last Pemeriksaan:</div>
                $d[last_pemeriksaan]
              </div>
            </div>
          ";
        } else {
          $value = div_alert('danger', 'UNKNOWN STATUS');
        }

        # ============================================================
        # FINAL UI STATUS PEMERIKSAAN
        # ============================================================
        if ($d['status']) {
          $value .= "
            <div class=>
              <a href='?tampil_pasien&id_pasien=$d[id_pasien]&jenis=$d[jenis]'>$img_next</a>
            </div>
          ";
        } else {
          // ABAIKAN JIKA PASIEN BELUM BAYAR
          $value = '-';
        }
      }

      $td .= "<td>$value</td>";
    }
    $tr .= "
      <tr>
        $td
      </tr>
    ";
  }
}


$selisih = strtotime('now') - strtotime($last_update_header);
// echo "selisih update header : $selisih seconds ZZZ";

# ============================================================
# ASUMSI BAHWA PENDAFTARAN PASIEN BARU MELEBIHI 30 DETIK
# ============================================================
if ($selisih > 30) {
  $count_pasien_null = $count_status[0];
  $count_pasien_ready = $count_status[7] + $count_status[8]; // yang ready dan sdh cetak sticker
  $count_pasien_sedang =  $count_status[9];

  $s = "INSERT INTO tb_header (
    id_klinik,
    count_pasien_null,
    count_pasien_ready,
    count_pasien_sedang,
    last_update
  ) VALUES (
    $id_klinik,
    $count_pasien_null,
    $count_pasien_ready,
    $count_pasien_sedang,
    CURRENT_TIMESTAMP 
  ) ON DUPLICATE KEY UPDATE 
    id_klinik = $id_klinik,
    count_pasien_null = $count_pasien_null,
    count_pasien_ready = $count_pasien_ready,
    count_pasien_sedang = $count_pasien_sedang,
    last_update = CURRENT_TIMESTAMP
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', 'Auto UPDATE Header Count sukses.');
  jsurl();
}


$data_pasien = $tr ? "
  <table class=table>
    <thead>$th</thead>
    $tr
  </table>
" : div_alert('danger', "Data pasien tidak ditemukan.");
