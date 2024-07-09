<?php
$judul = 'Assign Pemeriksaan';
$custom = $_GET['custom'] ?? '';
$id_paket = $_GET['id_paket'] ?? die(div_alert('danger', 'Index [id_paket] belum terdefinisi.'));
$nama_paket = $_GET['nama_paket'] ?? die(div_alert('danger', 'Index [nama_paket] belum terdefinisi.'));
$href =  '?manage_paket';
$arr_id_pemeriksaan = [];
if ($custom) {
  $id_pasien = $_GET['id_pasien'] ?? die(div_alert('danger', 'Index [id_pasien] belum terdefinisi.'));
  $href =  "?manage_paket_custom&id_pasien=$id_pasien";
  $s = "SELECT id_pemeriksaan FROM tb_paket_custom_detail a 
  JOIN tb_paket_custom b ON a.id_paket_custom=b.id 
  JOIN tb_pasien c ON b.id=c.id_paket_custom 
  WHERE c.id=$id_pasien 
  AND a.id_paket_custom=$id_paket
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    array_push($arr_id_pemeriksaan, $d['id_pemeriksaan']);
  }
}
$sub_judul = "
  Assign Pemeriksaan untuk <b class='biru'>$nama_paket</b>
  <span class='hideit bg-red' id=custom>$custom</span>
  <div class=mt2><a href='$href'>$img_prev</a></div> 
";
set_title($judul);
set_h2($judul, $sub_judul);
only(['admin', 'marketing']);
$img_sticker = "<img src='$lokasi_icon/sticker.png' height=25px class='zoom pointer' />";












# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_paket'])) {
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
a.id as id_pemeriksaan,
a.*,
b.nama as jenis_pemeriksaan,
a.nama as nama_pemeriksaan,
a.biaya

FROM tb_pemeriksaan a 
JOIN tb_jenis_pemeriksaan b ON a.jenis=b.jenis 
WHERE a.id_klinik=$id_klinik 
ORDER BY b.nama, a.nama 
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  echo div_alert('danger', 'Belum ada data sticker pada klinik ini.');
} else {
  $i = 0;
  $tr = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_pemeriksaan = $d['id_pemeriksaan'];
    $id_check = "check__$id_pemeriksaan";
    $ada = in_array($id_pemeriksaan, $arr_id_pemeriksaan);
    $checked = $ada ? 'checked' : '';
    $tebal_biru = $ada ? 'tebal_biru' : '';
    $tr .= "
      <tr>
        <td>$i</td>
        <td class=hideit>$nama_paket</td>
        <td>
          $d[jenis_pemeriksaan]
        </td>
        <td>
          <div class='form-check form-switch'>
            <input class='form-check-input check_pemeriksaan checkbox-parent' type='checkbox' id='$id_check' name='checkbox-$id_check' $checked>
            <label class='form-check-label proper pointer $tebal_biru' for='$id_check' id='label-$id_check'>
              $d[nama]
            </label>
          </div>
        </td>
        <td>
          $d[biaya]
        </td>
      </tr>
    ";
  }
  echo "
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
    $('.check_pemeriksaan').click(function() {
      let tid = $(this).prop('id');
      let checked = $(this).prop('checked');
      let rid = tid.split('__');
      let id_pemeriksaan = rid[1];
      let id_paket = $('#id_paket').text();
      let id_user = $('#id_user').text();

      if (!id_user || !id_paket || !id_pemeriksaan) {
        alert(`Data kurang lengkap.\n\nid_user: ${id_user}\nid_paket: ${id_paket}\nid_pemeriksaan: ${id_pemeriksaan}\n`)
        return;
      }


      let kode = `${id_paket}-${id_pemeriksaan}`;
      let aksi = '';
      let custom = parseInt($('#custom').text());
      let tb = custom ? 'paket_custom_detail' : 'paket_detail';
      let id_paket_field = custom ? 'id_paket_custom' : 'id_paket';
      let link_ajax;
      if (checked) {
        aksi = 'insert_update';
        let koloms = `kode,${id_paket_field},id_pemeriksaan,assign_by`;
        let isis = `'${kode}',${id_paket},${id_pemeriksaan},${id_user}`;
        let pairs = `${id_paket_field}=${id_paket},id_pemeriksaan=${id_pemeriksaan},assign_date=CURRENT_TIMESTAMP,assign_by=${id_user}`;
        link_ajax = `ajax/crud.php?tb=${tb}&aksi=${aksi}&koloms=${koloms}&isis=${isis}&pairs=${pairs}`;
      } else {
        aksi = 'delete';
        link_ajax = `ajax/crud.php?tb=${tb}&aksi=${aksi}&value_id=${kode}&kolom_id=kode`;
      }
      console.log(link_ajax);

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
            $('#' + tid).prop('checked', false);
          }
        }
      })


    })
  })
</script>