<?php
$judul = 'Manage Sticker';
$id_paket = $_GET['id_paket'] ?? die(div_alert('danger', 'Index id_paket belum terdefinisi.'));
$nama_paket = $_GET['nama_paket'] ?? die(div_alert('danger', 'Index nama_paket belum terdefinisi.'));
$sub_judul = "<a href='?manage-paket'>Back</a> | Manage Sticker untuk <b class='biru'>$nama_paket</b>";
set_title($judul);
set_h2($judul, $sub_judul);
only(['admin', 'marketing']);
$img_sticker = "<img src='$lokasi_icon/sticker.png' height=25px class='zoom pointer' />";












# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_paket'])) {
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  $s = "SELECT 1 FROM tb_paket WHERE id_program=$_POST[id_program]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $nomor = mysqli_num_rows($q) + 1;


  $s = "INSERT INTO tb_paket (
    id_program,
    no,
    nama,
    deskripsi
  ) VALUES (
    $_POST[id_program],
    $nomor,
    '$_POST[new_paket]',
    'deskripsi paket baru...'
  )";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Add Paket sukses. Silahkan pilih paket tersebut untuk editing selanjutnya.");
  jsurl('', 3000);
} elseif (isset($_POST['btn_delete_paket'])) {
  $s = "DELETE FROM tb_paket WHERE id = $_POST[btn_delete_paket]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Delete Paket sukses.");
  jsurl('', 3000);
}



























$s = "SELECT 
*,
(SELECT COUNT(1) FROM tb_paket_sticker WHERE kode = CONCAT('$id_paket-',a.id)) ada 
FROM tb_sticker a 
WHERE a.id_klinik=$id_klinik
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  echo div_alert('danger', 'Belum ada data sticker pada klinik ini.');
} else {
  $i = 0;
  $tr = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_sticker = $d['id'];
    $id_check = "check__$id_sticker";
    $checked = $d['ada'] ? 'checked' : '';
    $tebal_biru = $d['ada'] ? 'tebal_biru' : '';
    $tr .= "
      <tr>
        <td>$i</td>
        <td class=hideit>$nama_paket</td>
        <td>
          <div class='form-check form-switch'>
            <input class='form-check-input check-sticker checkbox-parent' type='checkbox' id='$id_check' name='checkbox-$id_check' $checked>
            <label class='form-check-label proper pointer $tebal_biru' for='$id_check' id='label-$id_check'>
              $d[nama]
            </label>
          </div>
        </td>
      </tr>
    ";
  }
  echo "
    <style>.tebal_biru{color:blue;font-weight:bold}</style>
    <span class=hideit id=id_paket>$id_paket</span>
    <table class='table table-hover table-striped'>
      $tr
    </table>
    <a class='btn btn-primary' href='?print-label&id_pasien=random&id_paket=$id_paket&nama_paket=$nama_paket'><i class='bx bx-printer'></i> Test Print</a>
  ";
}
?>
<script>
  $(function() {
    $('.check-sticker').click(function() {
      let tid = $(this).prop('id');
      let checked = $(this).prop('checked');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_sticker = rid[1];
      let id_paket = $('#id_paket').text();

      console.log(aksi, id_sticker, id_paket, checked);

      let kode = `${id_paket}-${id_sticker}`;
      if (checked) {
        aksi = 'insert';
      } else {
        aksi = 'delete';
      }
      let link_ajax = `ajax/crud.php?tb=tb_paket_sticker&aksi=${aksi}&id=kode&kolom=kode&value=${kode}`;

      $.ajax({
        url: link_ajax,
        success: function(a) {
          console.log('reply from AJAX: ', a);
          if (a.trim() == 'sukses') {
            if (checked) {
              $('#label-' + tid).addClass('tebal_biru');
            } else {
              $('#label-' + tid).removeClass('tebal_biru');

            }
          } else {
            alert(a);
          }
        }
      })


    })
  })
</script>