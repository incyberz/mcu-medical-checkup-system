<style>
  #hero {
    background: url("assets/img/hero-bg3.jpg") top center;
    background-repeat: no-repeat;
  }

  #hero h1,
  #hero h2 {
    text-shadow: 2px 1px 3px white;
  }
</style>
<?php
// bg hero
echo "<style>#hero{background: url('assets/img/$bg_hero') top center no-repeat;}</style>";
$welcome_login = '';
if ($username) {
  $welcome_login .= "Welcome <b class=darkblue>$nama_user</b>! Anda sedang login sebagai <b class='darkblue miring'>$role</b> dan dapat mengakses fitur tambahan sesuai dengan hak akses Anda";
  if ($role == 'admin') {
    // edit global variable
    $edit_section .= edit_section('global', 'Global Variable', $img_edit);
    $edit_section .= "
      <div id=edit_global class='hideita wadah mt2 gradasi-kuning'>
        <form method=post class=wadah>
          <div class='consolas abu f14 mb2'>Form Edit Global Variable</div>
          <input required class='form-control mb1' name=nama_sistem value='$nama_sistem' placeholder='Nama Sistem...'>
          <input required class='form-control mb1' name=singkatan_sistem value='$singkatan_sistem' placeholder='Singkatan Sistem...'>
          <input required class='form-control mb1' name=whatsapp value='$whatsapp' placeholder='Kontak Whatsapp...'>
          <input required class='form-control mb1' name=telepon value='$telepon' placeholder='Kontak Telepon...'>
          <input required type=email class='form-control mb1' name=email value='$email' placeholder='Kontak Email...'>
          <textarea required class='form-control mb1' name=alamat value='$alamat' placeholder='Alamat lengkap...' rows=4>$alamat</textarea>
          <div class='wadah mt2'>
            <div class='f14 abu mb1'>Sosial Media</div>
            <input class='form-control mb1' name=twitter value='$twitter' placeholder='Media Tweeter...'>
            <input class='form-control mb1' name=facebook value='$facebook' placeholder='Media Facebook...'>
            <input class='form-control mb1' name=instagram value='$instagram' placeholder='Media Instagram...'>
            <input class='form-control mb1' name=linkedin value='$linkedin' placeholder='Media LinkedIn...'>
          </div>
          <div class=pt1>
            <button class='btn btn-success btn-sm proper' name=btn_save_settings value='global'>Save global Settings</button>
          </div>
        </form>
        <form method=post enctype='multipart/form-data'>
          <div class='mb1 wadah'>
            <div class=mb4>$img_header_logo</div>
            <div class=flexy>
              <div class='pt2 pl2'>Ganti logo dengan:</div>
              <div>
                <input required type=file class='form-control' name=header_logo value='$header_logo' accept='.png'>
              </div>
              <div class=pt1>
                <button class='btn btn-success btn-sm ' name=btn_save_settings value='global'>Replace</button>
              </div>
            </div>
            <div class='miring abu f12 mt2 pl2'>Wajib PNG transparan dengan height 50px, klik-kanan pada logo diatas, lalu Save As, lalu edit gambar tersebut</div>
          </div>
        </form>
      </div>
    ";

    // edit hero
    $edit_section .= edit_section('hero', 'Hero (Landing Page)', $img_edit);
    $edit_section .= "
      <div id=edit_hero class='hideita wadah mt2 gradasi-kuning'>
        <form method=post class=wadah>
          <div class='consolas abu f14 mb2'>Form Edit Hero Text</div>
          <input class='form-control mb1' name=hero_header value='$hero_header' placeholder='Header Welcome...'>
          <input class='form-control mb1' name=hero_desc value='$hero_desc' placeholder='Header Welcome...'>
          <div class='flexy mb1'>
            <div class='pt2 pl2'>Button caption:</div>
            <div>
              <input class='form-control' name=hero_button value='$hero_button' placeholder='Button Caption...'>
            </div>
            <div class='pt2 pl2'>link menuju:</div>
            <div>
              <input class='form-control' name=hero_href value='$hero_href' placeholder='Target link...'>
            </div>
            <div class=pt1>
              <button class='btn btn-success btn-sm proper' name=btn_save_settings value='hero'>Save hero Settings</button>
            </div>
          </div>
        </form>
        <form method=post enctype='multipart/form-data'>
          <div class='mb1 wadah'>
          <img src='$lokasi_img/$bg_hero' class='img-thumbnail' />
            <div class='flexy mt2'>
              <div class='pt2 pl2'>Ganti bg-hero dengan:</div>
              <div>
                <input required type=file class='form-control' name=bg_hero value='$bg_hero' accept='.jpg'>
              </div>
              <div class=pt1>
                <button class='btn btn-success btn-sm' name=btn_save_settings value='hero'>Replace</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    ";
    $fitur_login = "<div class='wadah bg-white mt4'><p>$welcome_login</p>$edit_section</div>";
    // end fitur admin

  } elseif ($role == 'marketing') {
    $fitur_login = "
      <div class='wadah gradasi-kuning mt4'>
        <p>$welcome_login</p>
        <a class='btn btn-primary' href='?manage-paket'>Manage Paket</a>
        <a class='btn btn-primary' href='?manage-order'>Manage Order</a>
      </div>
    ";
  }
}

$section_login = !$username ? '' : "
  <section>
    <div class='container'>
      $fitur_login
    </div>
  </section>
";

echo "
<section id='hero' class='d-flex align-items-center'>
  <div class='container'>
    <h1 class='upper'>$hero_header</h1>
    <h2 style='max-width: 400px'>$hero_desc</h2>
    <a href='$hero_href' class='btn-get-started scrollto'>$hero_button</a>
  </div>
</section>
$section_login
";
?>