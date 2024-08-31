<?php
$s = "SELECT * FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=$id_pemeriksaan";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $arr_input = [];
  while ($d = mysqli_fetch_assoc($q)) {
    $arr_input[$d['id']] = $d;
  }


  # ============================================================
  # PENENTUAN BLOK INPUT
  # ============================================================
  # $arr_input['blok'] : - radio-toolbar
  #                      - input-range
  #                      - select
  # ============================================================
  $blok_inputs = '';
  include 'pemeriksaan-blok_input_handler.php';

  $tanggal_show = date('d-F-Y H:i');

  $form_pemeriksaan = "
    <form method='post' class='form-pemeriksaan wadah bg-white' id=blok_form>

      <!-- =========================================================== -->
      <!-- BLOK INPUTS -->
      <!-- =========================================================== -->
      $blok_inputs

      <div class='flexy mb2 flex-center'>
        <input type=checkbox required id=cek>
        <label for=cek>Saya menyatakan bahwa data diatas sudah benar.</label>
      </div>
      <button class='btn btn-primary w-100' name=btn_submit_data_pasien value='$id_pasien'>Submit Data</button>
      <input type=hidden name=last_pemeriksaan value='$nama_pemeriksaan by $nama_user'>
      <input type=hidden name=id_pemeriksaan value='$id_pemeriksaan'>
    </form>
  ";
} else { // end ada detail
  $form_pemeriksaan =  div_alert('danger tengah', "Detail Pemeriksaan belum ada | <a href='?manage_pemeriksaan_detail&id_pemeriksaan=$id_pemeriksaan&nama_pemeriksaan=$nama_pemeriksaan'>Manage</a>");
}
