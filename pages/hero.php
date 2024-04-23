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
echo "<style>#hero{background: url('assets/img/$bg_hero') top center;}</style>";

if ($username) {
  $welcome_login .= "Welcome <b class=darkblue>$nama_user</b>! Anda sedang login sebagai <b class='darkblue miring'>$role</b> dan dapat mengakses fitur tambahan sesuai dengan hak akses Anda";
  if ($role == 'admin') {
    $edit_section .= edit_section('hero', 'Hero (Landing Page)', $img_edit);
    $edit_section .= "
      <div id=edit_hero class='hideit wadah mt2 gradasi-kuning'>
        <form method=post class=wadah>
          <div class=sub_form>Form Edit Hero Text</div>
          <input class='form-control mb1' name=header_welcome value='$header_welcome' placeholder='Header Welcome...'>
          <input class='form-control mb1' name=welcome_msg2 value='$welcome_msg2' placeholder='Header Welcome...'>
          <div class='flexy mb1'>
            <div class='pt2 pl2'>Button caption:</div>
            <div>
              <input class='form-control' name=button_caption value='$button_caption' placeholder='Button Caption...'>
            </div>
            <div class='pt2 pl2'>link menuju:</div>
            <div>
              <input class='form-control' name=button_href value='$button_href' placeholder='Target link...'>
            </div>
            <div class=pt1>
              <button class='btn btn-success btn-sm on-dev' name=btn_save_settings value='hero'>Save Settings</button>
            </div>
          </div>
        </form>
        <form method=post enctype='multipart/form-data'>
          <div class='flexy mb1 wadah'>
            <div class='pt2 pl2'>Background untuk hero: <b class='consolas darkblue'>$bg_hero</b></div>
            <div class='pt2 pl2'>Ganti dengan:</div>
            <div>
              <input type=file class='form-control' name=bg_hero value='$bg_hero' accept='.jpg'>
            </div>
            <div class=pt1>
              <button class='btn btn-success btn-sm on-dev' name=btn_save_settings value='hero'>Upload</button>
            </div>
          </div>
        </form>
      </div>
    ";
    $edit_section = "<div class='wadah bg-white mt4'><p>$welcome_login</p>$edit_section</div>";
  }
}

echo "
<section id='hero' class='d-flex align-items-center'>
  <div class='container'>
    <h1 class='upper'>$header_welcome</h1>
    <h2 style='max-width: 400px'>$welcome_msg2</h2>
    <a href='$button_href' class='btn-get-started scrollto'>$button_caption</a>
    $edit_section
  </div>
</section>
";
?>