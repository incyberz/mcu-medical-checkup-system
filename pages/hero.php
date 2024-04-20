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
if ($username) {
  $welcome_msg = "Welcome $nama_user!";
  $welcome_msg2 = "Anda sedang login sebagai <b class='darkblue miring'>$role</b> dan dapat mengakses fitur tambahan sesuai dengan hak akses Anda";
  if ($role == 'admin') {
    $welcome_msg2 .= edit_section('hero', 'Hero (Landing Page)', $img_edit);
  }
}
?>
<section id="hero" class="d-flex align-items-center">
  <div class="container">
    <h1 class="upper"><?= $welcome_msg ?></h1>
    <h2 style='max-width: 400px'><?= $welcome_msg2 ?></h2>
    <a href="<?= $button_href ?>" class="btn-get-started scrollto"><?= $button_msg ?></a>
  </div>
</section>