<?php
set_title('Tambah Pasien');
$bm = '<b class=red>*</b>';

# ============================================================
# PROCESSORS 
# ============================================================
if (isset($_POST['btn_tambah_pasien'])) {
  unset($_POST['btn_tambah_pasien']);
  $koloms = '__';
  $isis = '__';


  foreach ($_POST as $key => $value) {
    if (!$value) continue;
    $koloms .= ",$key";
    $isis .= ",'$value'";
  }
  $koloms = str_replace('__,', '', $koloms);
  $isis = str_replace('__,', '', $isis);

  $s = "INSERT INTO tb_pasien ($koloms,id_klinik) VALUES ($isis,$id_klinik) ";
  // echo $s;
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
<div class="wadah gradasi-hijau tengah">
  <label for="nama" class="abu mb2">Nama Pasien</label>
  <input type="text" id="nama" name="nama" placeholder="Enter Nama Pasien..." class="form-control tengah f30 mb4">
  <label for="whatsapp" class="abu mb2">Whatsapp</label>
  <input type="text" id="whatsapp" name="whatsapp" placeholder="Enter whatsapp..." class="form-control tengah f30 mb4 consolas" autocomplete="off">
  <div id="whatsapp_formatted" class="mb4 consolas spacer f30">whatsapp_formatted</div>
  <label for="no_ktp" class="abu mb2">Nomor KTP</label>
  <input type="text" id="no_ktp" name="no_ktp" placeholder="Enter 16 Digit KTP Pasien..." class="form-control tengah f30 mb4 consolas spacer" autocomplete="off">
  <div id="no_ktp_info"></div>
</div>


<script>
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
    $('#input_kec').keyup(function() {
      $('#blok_alamat').slideUp();
      $('#btn_tambah_pasien').slideUp();
      let val = $(this).val();
      if (val.length < 3) {
        $('#list_kec').html('');
      } else {
        let id_kab = $('#id_kab').text();
        let link_ajax = `ajax/get_list_kec.php?keyword=${val}&id_kab=${id_kab}`;
        $.ajax({
          url: link_ajax,
          success: function(a) {
            $('#list_kec').slideDown(a);
            $('#list_kec').html(a);

          }
        })

      }

    });

    $('#input_kab').keyup(function() {
      $('#blok_kec').slideUp();
      $('#blok_alamat').slideUp();
      $('#btn_tambah_pasien').slideUp();
      $('#input_kec').val('');
      let val = $(this).val();
      if (val.length < 3) {
        $('#list_kab').html('');
      } else {
        let link_ajax = `ajax/get_list_kab.php?keyword=${val}`;
        $.ajax({
          url: link_ajax,
          success: function(a) {
            $('#list_kab').slideDown(a);
            $('#list_kab').html(a);

          }
        })

      }

    });
    $('#input_kab').keyup();


    $('.input_tanggal_lahir').change(function() {
      $('.input_tanggal_lahir').addClass('gradasi-merah');
      $("#btn_tambah_pasien").prop('disabled', true);
      let str =
        $('#input_thn').val() + '-' +
        $('#input_bln').val() + '-' +
        $('#input_tgl').val();

      $('#tanggal_lahir').val(str);
      let d = new Date(str);

      if (Object.prototype.toString.call(d) === "[object Date]") {
        // it is a date
        if (isNaN(d)) { // d.getTime() or d.valueOf() will also work
          // date object is not valid
          console.log('ZZZ');
        } else {
          // date object is valid
          let day = d.getDate();
          let month = d.getMonth();
          console.log('OK', day, month);
          if (day == parseInt($('#input_tgl').val())) {
            // ====================================================
            // DATE FORMAT OK
            // ====================================================

            // usia
            let t1 = Date.parse(new Date());
            let t0 = Date.parse(d);
            let usia = parseInt((t1 - t0) / (1000 * 60 * 60 * 24 * 365));
            $('#usia').text(usia);
            if (usia > 0) {
              $('.input_tanggal_lahir').removeClass('gradasi-merah');
              $("#btn_tambah_pasien").prop('disabled', false);

            }
          }
        }
      } else {
        // not a date object
        console.log('NOT date');
      }
    });

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

    $('#no_ktp').keyup(function() {
      let val = $(this).val();
      let kode_prov = val.substring(0, 2);
      let kode_kab = val.substring(0, 4);
      let kode_kec = val.substring(0, 6);

      let tgl_lahir = val.substring(6, 8);
      let bln_lahir = val.substring(8, 10);
      let thn_lahir = val.substring(10, 12);

      let no_urut = val.substring(12, 16);

      $('#no_ktp_info').text('');

      if (kode_kec.length == '6') {
        let link_ajax =
          $.ajax({

          })
      }





    });

  })
</script>