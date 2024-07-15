<?php
# ============================================================
# CARI PASIEN
# ============================================================
$aksi = $_GET['aksi'] ?? 'mcu';
$MCU = strtoupper($aksi);
$Medical_Checkup = $aksi == 'mcu' ? 'Medical Checkup' : 'Laboratorium';
$not_aksi = $aksi == 'mcu' ? 'lab' : 'mcu';
$Not_Medical = $aksi == 'mcu' ? 'Laboratorium' : 'Medical Checkup';

set_h2('Pencarian Pasien', "<span class='abu f14 miring'>Pencarian Pasien untuk</span> 
  <div>
    <span class='hideit bg-red' id=aksi>$aksi</span>
    <b class=darkblue>Pemeriksaan $Medical_Checkup</b> | 
    <a href='?cari_pasien&aksi=$not_aksi' class='miring f12'>
    $Not_Medical
    </a>
  </div>
");
only(['admin', 'nakes', 'marketing']);


















# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_paket'])) {
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  echo div_alert('success', "Delete Paket sukses.");
  jsurl('', 3000);
}

$list_order_no = '';
$s = "SELECT * 
FROM tb_order 
WHERE status >= 3 -- Registered Pasien 
AND status < 100 -- Belum selesai
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  // $id=$d['id'];
  $list_order_no .= "<option value='$d[order_no]'>$d[order_no] - $d[perusahaan]</option>";
}

require_once 'include/mcu_functions.php';
require_once 'include/radio_toolbar_functions.php';
require_once 'include/radio_jenis_pasien.php';
$ilustrasi = ilustrasi($aksi);


?>
<div class="tengah mb4" id=img_ilustrasi><?= $ilustrasi ?></div>
<div class="wadah gradasi-hijau tengah" style="max-width: 500px; margin:auto;">
  <div><?= $radio_jenis_pasien ?></div>
  <span id="jenis" class="hideit bg-red">cor</span>
  <div class="hideit">
    <!-- khusus untuk pasien perusahaan -->
    <div class="mb1 f12 abu">Active Order saat ini:</div>
    <select name="order_no" id="order_no" class="form-control mb4 tengah"><?= $list_order_no ?></select>
  </div>
  <input class="form-control mb2 tengah f24" name="keyword" id="keyword" placeholder="keyword..." autocomplete="off">

  <div id="list_pasien" class="biru"></div>
  <div id="list_pasien_text_awal" class='hideit '>Silahkan masukan No. MCU atau nama pasien pada input keyword diatas minimal 3 huruf.</div>

</div>
<script>
  $(function() {
    let order_no = $('#order_no').val();
    let jenis = $('#jenis').text();
    let text_awal = $('#list_pasien_text_awal').text();
    $('#list_pasien').text(text_awal);


    $('#order_no').change(function() {
      order_no = $(this).val();
    });

    $('#keyword').keyup(function() {
      let keyword = $(this).val().trim();
      if (keyword.length < 3) {
        $('#list_pasien').html(text_awal);
        $('#img_ilustrasi').slideDown();
        return;
      }
      let link_ajax = 'ajax/ajax_cari_pasien.php?keyword=' + keyword + '&order_no=' + order_no + '&jenis=' + jenis;
      console.log(link_ajax);

      $.ajax({
        url: link_ajax,
        success: function(a) {
          $('#list_pasien').html(a);
          $('#img_ilustrasi').slideUp();
        }
      })
    });

    $('.jenis_pasien').click(function() {
      let tid = $(this).prop('for');
      let rid = tid.split('__');
      jenis = rid[1];
      $('#jenis').text(jenis);
      $('#keyword').keyup();
    });
  })
</script>