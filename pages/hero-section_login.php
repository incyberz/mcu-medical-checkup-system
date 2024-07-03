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
  include 'welcome_login.php';
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