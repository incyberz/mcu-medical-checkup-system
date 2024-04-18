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
            <p>Tambun Business Park Blok C12, Jl. Raya Pantura, Tambun Selatan</p>
          </div>

          <div class="email">
            <i class="bi bi-envelope"></i>
            <h4>Email:</h4>
            <p>mmcpjk3@gmail.com</p>
          </div>

          <div class="phone">
            <i class="bi bi-phone"></i>
            <h4>Telepon:</h4>
            <p>021-8909 5776</p>
          </div>

        </div>

      </div>

      <div class="col-lg-8 mt-5 mt-lg-0">

        <form action="forms/contact.php" method="post" role="form" class="php-email-form">
          <div class="row">
            <div class="col-md-6 form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder="Nama Anda" required>
            </div>
            <div class="col-md-6 form-group mt-3 mt-md-0">
              <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            </div>
          </div>
          <div class="form-group mt-3">
            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
          </div>
          <div class="form-group mt-3">
            <textarea class="form-control" name="message" rows="5" placeholder="Pesan" required></textarea>
          </div>
          <div class="my-3">
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your message has been sent. Thank you!</div>
          </div>
          <div class="text-center"><button type="submit">Kirim Pesan</button></div>
        </form>

      </div>

    </div>

  </div>
</section><!-- End Contact Section -->