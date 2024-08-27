<?php
set_title('Tambah Pasien');
$bm = '<b class=red>*</b>';

# ============================================================
# PROCESSORS 
# ============================================================
if (isset($_POST['btn_submit_pasien_corporate'])) {

  $s = "INSERT INTO tb_pasien (
  id_klinik,
  jenis,
  nama,
  no_ktp,
  whatsapp,
  tanggal_lahir,
  gender,
  id_kec,
  usia,
  alamat
  ) VALUES (
  $id_klinik,
  'cor',
  '$_POST[nama]',
  '$_POST[no_ktp]',
  '$_POST[whatsapp]',
  '$_POST[tanggal_lahir]',
  '$_POST[gender]',
  '$_POST[id_kec]',
  '$_POST[usia]',
  '$_POST[alamat]'
  ) ";
  echolog('inserting new pasien');
  echo '<pre>';
  var_dump($s);
  echo '</pre>';
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $s = "SELECT MAX(id) as new_id FROM tb_pasien";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $new_id = $d['new_id'];

  $s = "UPDATE tb_pasien SET username = 'mcu$new_id' WHERE id=$new_id";
  echolog('updating username');
  echo '<pre>';
  var_dump($s);
  echo '</pre>';
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  jsurl('?pendaftaran');
  exit;
}

?>
<style>
  .spacer {
    letter-spacing: 5px;
  }
</style>
<form method=post class="wadah gradasi-hijau tengah">
  <label for="nama" class="abu mb2">Nama Pasien</label>
  <input required id="nama" name="nama" minlength="3" maxlength="30" placeholder="Enter Nama Pasien..." class="form-control tengah f30 mb4" autocomplete="off">
  <label for="whatsapp" class="abu mb2">Whatsapp</label>
  <input required minlength="10" maxlength="15" id="whatsapp" name="whatsapp" placeholder="Enter whatsapp..." class="form-control tengah f30 mb4 consolas" autocomplete="off">
  <div id="whatsapp_formatted" class="mb4 consolas spacer f40 hideit blue">...</div>
  <label for="no_ktp" class="abu mb2">Nomor KTP</label>
  <input minlength="16" maxlength="16" id="no_ktp" name="no_ktp" placeholder="Enter 16 Digit KTP Pasien..." class="form-control tengah f30 mb4 consolas spacer" autocomplete="off">
  <div id="no_ktp_error" class="red bold mb4 hideit">Nomor KTP belum valid, silahkan lihat pada KTP asli.</div>
  <div id="no_ktp_ok" class="green bold mb4"></div>
  <div id="no_ktp_tmp" class="hideit"></div>
  <button class="btn btn-primary w-100 hideit" name=btn_submit_pasien_corporate id=btn_submit_pasien_corporate>Submit Pasien Corporate</button>
  <span class="btn btn-secondary w-100" id=btn_blm_bisa_submit>Belum bisa submit..</span>
  <div id="no_ktp_debug" class=hideit>no_ktp_debug 3211133102870004</div>

  <div class="wadah gradasi-kuning hideit">
    <div>Autofill:</div>
    <input readonly name=tanggal_lahir id=tanggal_lahir placeholder="tanggal_lahir">
    <input readonly name=gender id=gender placeholder="gender">
    <input readonly name=id_kec id=id_kec placeholder="id_kec">
    <input readonly name=usia id=usia placeholder="usia">
    <input readonly name=alamat id=alamat placeholder="alamat">
  </div>
</form>


<script>
  function set_ui_awal() {
    $('#no_ktp_ok').hide();
    $('#btn_submit_pasien_corporate').hide();
    $('#btn_submit_pasien_corporate').prop('disabled', true);
    $('#btn_blm_bisa_submit').show();
    $('#no_ktp_error').hide();
    $('#no_ktp_tmp').text('');

  }

  function toTitleCase(str) {
    return str.replace(
      /\w\S*/g,
      function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      }
    );
  }

  $(document).on('click', '.item_kab', function() {
    let r = $(this).text().split(' - ');
    let nama_kab = r[0];
    let id_kab = r[1];
    $('#id_kab').text(id_kab);
    $('#input_kab').val(nama_kab);
    $('#blok_kec').slideDown();
    $('#list_kab').slideUp();

    let link_ajax = `ajax/get_list_kec.php?keyword=none&id_kab=${id_kab}`;
    $.ajax({
      url: link_ajax,
      success: function(a) {
        $('#list_kec').slideDown(a);
        $('#list_kec').html(a);

      }
    })

    // console.log(r);
  });


  $(document).on('click', '.item_kec', function() {
    let r = $(this).text().split(' - ');
    let nama_kec = r[0];
    let id_kec = r[1];
    $('#id_kec').val(id_kec);
    $('#input_kec').val(nama_kec);
    $('#blok_alamat').slideDown();
    $('#list_kec').slideUp();
    $('#btn_tambah_pasien').slideDown();

    // console.log(r);
  })


  $(function() {

    $('#whatsapp').focus(function() {
      $('#whatsapp_formatted').show();
    })
    $('#whatsapp').focusout(function() {
      $('#whatsapp_formatted').hide();
    })
    $('#whatsapp').keyup(function() {
      let val = $(this).val();

      if (val.length > 2) {
        if (val.substring(0, 1) == '0') {
          $(this).val('62' + val.substring(1, 100));
        }
      }
      if (val.length > 5) {
        if (val.substring(0, 2) != '62') {
          $(this).val('');
        }
      }

      $(this).val(
        $(this).val().replace(/[^0-9]/g, '')
      )

      $('#whatsapp_formatted').text(
        val.substring(0, 4) + '-' +
        val.substring(4, 7) + '-' +
        val.substring(7, 10) + '-' +
        val.substring(10, 14)
      );
    })
    $('#nama').keyup(function() {
      $(this).val(toTitleCase($(this).val()));
    })

    let valid_kec = false;
    let valid_ttl = false;
    let valid_no_urut = false;
    $('#no_ktp').focusout(function() {
      let val = $(this).val();
      if (!val.length) {
        set_ui_awal();
      };
    })

    $('#no_ktp').keyup(function() {
      let val = $(this).val();
      let val_tmp = $('#no_ktp_tmp').text();
      if (!val.length || val == val_tmp) return;
      set_ui_awal();
      $('#no_ktp_error').show();

      let kd_prv = val.substring(0, 2);
      let kd_kab = val.substring(0, 4);
      let kd_kec = val.substring(0, 6);

      let tg_lhr = parseInt(val.substring(6, 8));
      let bl_lhr = parseInt(val.substring(8, 10));
      let th_lhr = parseInt(val.substring(10, 12));

      let no_urut = val.substring(12, 16);

      let kd_gender = null;
      if (tg_lhr > 40 && tg_lhr <= 71) {
        kd_gender = 'p';
        tg_lhr = tg_lhr - 40;
      } else if (tg_lhr > 0 && tg_lhr <= 31) {
        kd_gender = 'l';
      } else {
        if (tg_lhr.length == 2) {
          console.log('Invalid kode tanggal: ' + tg_lhr);
          return;
        }
      }

      if (no_urut.length == 4 && !parseInt(no_urut)) {
        console.log('Invalid nomor urut: ' + no_urut);
        return;
      } else {
        if (no_urut.length == 4) {
          valid_no_urut = true;
        }
      }

      let tahun = th_lhr > 30 ? 1900 + th_lhr : 2000 + th_lhr;
      let str_ttl = tahun + '-' + bl_lhr + '-' + tg_lhr;
      let d_ttl = new Date(str_ttl);
      let tgl_from_d_ttl = d_ttl.getDate();
      // if (isNaN(tgl_from_d_ttl)) {
      //   console.log('Invalid tanggan from object tanggal | ', tgl_from_d_ttl);
      //   return;
      // }
      if (val.length >= 12) {
        if (tgl_from_d_ttl == tg_lhr) {
          valid_ttl = true;
        } else {

          console.log('Invalid tanggan from object tanggal | ', tgl_from_d_ttl, tg_lhr);
          return;

        }
      }


      let tanggal_lahir = $('#tanggal_lahir').val();
      let usia = $('#usia').val();
      let gender = $('#gender').val();
      let id_kec = $('#id_kec').val();
      let alamat = $('#alamat').val();

      $('#no_ktp_debug').html(`
        <br>kd_prv: ${kd_prv}
        <br>kd_kab: ${kd_kab}
        <br>kd_kec: ${kd_kec}
        <br>tg_lhr: ${tg_lhr}
        <br>bl_lhr: ${bl_lhr}
        <br>th_lhr: ${th_lhr}
        <br>no_urut: ${no_urut}
        <br>kd_gender: ${kd_gender}
        <br>tahun: ${tahun}
        <br>str_ttl: ${str_ttl}
        <br>d_ttl: ${d_ttl}
        <br>tgl_from_d_ttl: ${tgl_from_d_ttl}
        <br>`);

      if (kd_kec.length < 6) {
        $('#id_kec').val('');
      } else {
        // if (val.length == 6 || val.length == 12 || val.length == 16) {
        if (val.length == 16) {
          let link_ajax = "ajax/get_kecamatan.php?&id_kec=" + kd_kec;
          $.ajax({
            url: link_ajax,
            success: function(a) {
              let h = a.split("__");
              // console.log(h[0], h[1]);
              let alamat = 'Kecamatan ' + h[1];

              if (h[0].trim() == 'sukses') {
                $('#alamat').val(alamat);
                valid_kec = true;
              } else {
                $('#alamat').val('');
              }

              if (valid_kec && valid_ttl && valid_no_urut) {
                $('#no_ktp_error').hide();

                // hitung usia
                let today = new Date();
                let usia = Math.floor((today - d_ttl) / (1000 * 60 * 60 * 24 * 365));
                $('#usia').val(usia);
                console.log(d_ttl, usia);


                // gender
                $('#gender').val(kd_gender);
                $('#id_kec').val(kd_kec);
                $('#tanggal_lahir').val(str_ttl);


                let gender_show = kd_gender == 'l' ? 'Laki-laki' : 'Perempuan';
                $('#no_ktp_ok').show();
                $('#btn_submit_pasien_corporate').show();
                $('#btn_submit_pasien_corporate').prop('disabled', false);
                $('#btn_blm_bisa_submit').hide();
                $('#no_ktp_ok').html(`Gender: ${gender_show}, usia ${usia} tahun, TL: ${str_ttl}, alamat: ${alamat}`);
                $('#no_ktp_tmp').text(val);

              } else {
                console.log(valid_kec, valid_ttl, no_urut);

              }

            }
          })

        }
      }






    });

  })
</script>