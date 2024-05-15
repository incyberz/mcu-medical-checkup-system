<?php
$judul = 'Pencarian Pasien';
// $id_paket = $_GET['id_paket'] ?? die(div_alert('danger', 'Index id_paket belum terdefinisi.'));
// $nama_paket = $_GET['nama_paket'] ?? die(div_alert('danger', 'Index nama_paket belum terdefinisi.'));
// $sub_judul = "<a href='?manage-paket'>Back</a> | Manage Sticker untuk <b class='biru'>$nama_paket</b>";
$sub_judul = '';
set_title($judul);
set_h2($judul, $sub_judul);
only('users');
$id_pasien = $_GET['id_pasien'] ?? '';












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



# ============================================================
# LIHAT PASIEN JIKA ID PASIEN ADA
# ============================================================
if ($id_pasien) {
  # ============================================================
  # START TAMPIL PASIEN
  # ============================================================
  include 'tampil-pasien.php';
} else {

  # ============================================================
  # START FORM PENCARIAN
  # ============================================================
  # ============================================================
  # GET LIST ACTIVE ORDER
  # ============================================================
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




?>
  <div class="wadah gradasi-hijau tengah" style="max-width: 500px; margin:auto;">
    <div class="mb1 f12 abu">Pencarian pada:</div>
    <select name="order_no" id="order_no" class="form-control mb4 tengah"><?= $list_order_no ?></select>
    <input class="form-control mb2 tengah f24" name="keyword" id="keyword" placeholder="keyword..." autocomplete="off">

    <div id="list_pasien"></div>
    <div id="list_pasien_text_awal" class=hideit>Silahkan masukan No. MCU atau nama pasien pada input keyword diatas.</div>

  </div>
  <script>
    $(function() {
      let order_no = $('#order_no').val();
      let text_awal = $('#list_pasien_text_awal').text();
      $('#list_pasien').text(text_awal);


      $('#order_no').change(function() {
        order_no = $(this).val();
      });

      $('#keyword').keyup(function() {
        let keyword = $(this).val().trim();
        if (keyword.length < 2) {
          $('#list_pasien').html(text_awal);
          return;
        }
        let link_ajax = 'ajax/ajax_cari_pasien.php?keyword=' + keyword + '&order_no=' + order_no;

        $.ajax({
          url: link_ajax,
          success: function(a) {
            $('#list_pasien').html(a);
            // if (a.trim() == 'sukses') {
            // } else {
            //   $('#list_pasien').html('');
            //   alert(a)
            // }

          }
        })
      })
    })
  </script>

<?php } ?>

<!-- <ul>
  <li>TB/BB/LP</li>
  Tensi/BW/Res
  Visus Mata
  Pemfis Dr
  Darah (DL)
  Darah (KM)
  Rontgen
  EKG

  spiro
  audio 

  <button class="btn btn-primary" type="button" disabled>
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    Loading...
  </button>

</ul> -->