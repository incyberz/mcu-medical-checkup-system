<?php
# ============================================================
# LOBBY PASIEN 
# ============================================================
include 'lobby_pasien.php';
echo "<div id=lobby_pasien>$lobby_pasien</div>";

# ============================================================
# CARI PASIEN
# ============================================================
only(['admin', 'nakes', 'marketing', 'dokter', 'dokter-pj']);

# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_paket'])) {
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





















# ============================================================
# INCLUDES
# ============================================================
require_once 'include/mcu_functions.php';
require_once 'include/radio_toolbar_functions.php';
require_once 'include/radio_jenis_pasien.php';
$ilustrasi = ilustrasi('mcu');










# ============================================================
# FINAL ECHO | FORM PENCARIAN
# ============================================================
echo "
  <div class='tengah hideit' id=form_pencarian>
    <span class='abu f18 '>Pencarian Pasien untuk</span> 
    <div class='bold darkblue f18'>Pemeriksaan Medical Checkup dan Laboratorium</div>

    <div class='tengah mb4 mt2' id=img_ilustrasi>$ilustrasi</div>

    <div class='wadah gradasi-hijau tengah' style='max-width: 500px; margin:auto;'>
      <div>$radio_jenis_pasien</div>
      <span id='jenis' class='hideit bg-red'>cor</span>
      <input class='form-control mb2 tengah f24' name='keyword' id='keyword' placeholder='keyword...' autocomplete='off'>

      <div id='list_pasien' class='biru'></div>
      <div id='list_pasien_text_awal' class='hideit '>Silahkan masukan No. MCU atau nama pasien pada input keyword diatas minimal 3 huruf.</div>
    </div>
  </div>
";



# ============================================================
# NAVIGASI
# ============================================================

?>
<div class="tengah mt2">
  <button class="btn btn-sm btn-secondary" id=btn_nav>Pencarian Pasien</button>
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

    $('#btn_nav').click(function() {
      let cap = $(this).text();
      if (cap == 'Pencarian Pasien') {
        $(this).text('Lobby Pasien');
      } else {
        $(this).text('Pencarian Pasien');
      }
      $('#form_pencarian').slideToggle();
      $('#lobby_pasien').slideToggle();
    });
  })
</script>