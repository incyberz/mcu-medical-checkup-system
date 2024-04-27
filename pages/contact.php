<style>
  .img-zoom {
    transition: .5s;
    opacity: 60%;
  }

  .img-zoom:hover {
    transform: scale(1.1);
    opacity: 100%;
  }
</style>

<?php
$edit_section = $role == 'admin' ? edit_section('contact', 'kontak kami') : '';


?>
<section id="contact" class="contact">
  <div class="container">

    <div class="section-title">
      <h2>Kontak</h2>
      <p>Untuk perihal fee marketing, basic price, jenis pemeriksaan, atau tentang proses MCU yang kurang jelas, dapat Anda tanyakan kepada Tim Marketing kami. Untuk alamat kantor kami adalah sebagai berikut:</p>
    </div>
  </div>

  <div>

    <div class="text-center" style="max-width: 400px; margin:auto">
      <!-- <iframe style="border:0; width: 100%; height: 350px;" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621" frameborder="0" allowfullscreen></iframe> -->

      <a target="_blank" href="https://maps.app.goo.gl/Yss5W1xgCk6NQL9E9">
        <img src="assets/img/lokasi-kantor.png" alt="lokasi-kantor" class="img-fluid img-zoom">

      </a>
    </div>
  </div>

  <div class="container">
    <div class="row mt-5">

      <div class="col-lg-4">
        <div class="info">
          <div class="address">
            <i class="bi bi-geo-alt"></i>
            <h4>Lokasi:</h4>
            <p><?= $alamat ?></p>
          </div>

          <div class="email">
            <i class="bi bi-envelope"></i>
            <h4>Email:</h4>
            <p><?= $email ?></p>
          </div>

          <div class="phone">
            <i class="bi bi-phone"></i>
            <h4>Telepon:</h4>
            <p><?= $phone ?></p>
          </div>

        </div>

      </div>

      <div class="col-lg-8 mt-5 mt-lg-0">

        <div class="php-email-form">
          <div class="form-group mt-3">
            <input type="text" minlength="3" maxlength="30" name="nama_anda" class="form-control input-kontak" id="nama_anda" placeholder="Nama Anda..." required>
            <div class="f12 abu mt1 mb2 hideit" id=nama_anda_info>3 s.d 30 huruf</div>
          </div>
          <div class="form-group mt-3">
            <input type="text" minlength="5" maxlength="50" class="form-control input-kontak" name="subject_pesan" id="subject_pesan" placeholder="Subject pesan..." required>
            <div class="f12 abu mt1 mb2 hideit" id=subject_pesan_info>Subject pesan antara 5 s.d 50 karakter</div>
          </div>
          <div class="form-group mt-3">
            <textarea minlength="50" maxlength="500" class="form-control input-kontak mb2" name="isi_pesan" id="isi_pesan" rows="5" placeholder="Pesan atau pertanyaan yang ingin Anda sampaikan..." required></textarea>
            <div class="f12 abu mt1 mb2 hideit" id=isi_pesan_info>Isi pesan antara 50 s.d 500 karakter</div>
          </div>
          <div class="text-center">
            <button type="submit" id=btn_kirim_pesan2><i class="bi bi-whatsapp"></i> Kirim ke Tim Marketing kami</button>
            <button type="submit" id=btn_kirim_pesan class="hideit"><i class="bi bi-whatsapp"></i> Kirim ke Tim Marketing kami</button>
            <div class="f12 abu mt1 mb2" id=btn_kirim_info>Silahkan isi dahulu semua field!</div>
          </div>
        </div>

        <div class="bordered p2 hideit" id=blok_processing>
          <div class="consolas abu">Processing message...</div>
          <hr>
          <div class="darkblue">Pesan Anda akan kami teruskan via Whatsapp ke Tim Marketing. Jika pesan berhasil terkirim maka Anda akan menerima pesan balasan dalam waktu dekat.</div>
          <hr>
          <div class="hijau">Terimakasih telah menghubungi kami !</div>
        </div>

      </div>

    </div>
    <?= $edit_section ?>

  </div>
</section>

<script>
  function toTitleCase(str) {
    return str.replace(
      /\w\S*/g,
      function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      }
    );
  }

  $(function() {
    $('.input-kontak').keyup(function() {
      let nama_anda = $('#nama_anda').val();
      let subject_pesan = $('#subject_pesan').val();
      let isi_pesan = $('#isi_pesan').val();

      if (nama_anda) {
        if (nama_anda.length >= 3 && nama_anda.length <= 30) {
          $('#nama_anda_info').fadeOut();
        } else {
          $('#nama_anda_info').show();
          return;
        }
        $('#nama_anda').val(toTitleCase(nama_anda.replace(/[^a-zA-Z ]/g, '').replace('  ', ' ')));
      }

      if (subject_pesan) {
        if (subject_pesan.length >= 5 && subject_pesan.length <= 50) {
          $('#subject_pesan_info').fadeOut();
        } else {
          $('#subject_pesan_info').show();
          return;
        }
        $('#subject_pesan').val(toTitleCase(subject_pesan.replace(/[^a-zA-Z0-9 ]/g, '').replace('  ', ' ')));
      }

      if (isi_pesan) {
        if (isi_pesan.length >= 50 && isi_pesan.length <= 500) {
          $('#isi_pesan_info').fadeOut();
        } else {
          $('#isi_pesan_info').text(`Anda baru mengetik ${isi_pesan.length} karakter, minimal 50 karakter`);
          $('#isi_pesan_info').show();
          return;
        }
        // $('#isi_pesan').val(isi_pesan.replace('  ', ' ').replace('<', '< '));
      }

      if (nama_anda && subject_pesan && isi_pesan) {

        $('#btn_kirim_pesan2').hide()
        $('#btn_kirim_pesan').fadeIn()
        $('#btn_kirim_info').fadeOut()
        console.log('OK');
      } else {
        $('#btn_kirim_info').text('Silahkan isi dahulu semua field !')
        $('#btn_kirim_info').fadeIn()
        $('#btn_kirim_pesan').hide();
        $('#btn_kirim_pesan2').fadeIn();
      }

    });
    $('.input-kontak').keyup();

    $('#btn_kirim_pesan').click(function() {
      $('.php-email-form').slideUp();
      $('#blok_processing').slideDown();

      let no_wa = $('#no_wa_marketing').text();
      if (!no_wa) {
        alert('nomor wa marketing undefined');
        return;
      }

      let tgl = new Date();

      let text_wa = `MESSAGE FROM CONTACT PAGE%0a%0aFrom: ${$('#nama_anda').val()}%0aSubject: ${$('#subject_pesan').val()}%0aPesan: ${$('#isi_pesan').val()}%0a%0a[MMC Information System, ${tgl}]`;

      setTimeout(() => {
        location.replace(`https://api.whatsapp.com/send?phone=${no_wa}&text=${text_wa}`)
      }, 5000);
    });
  })
</script>