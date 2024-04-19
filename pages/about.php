<?php
$href = $tentang['video']['src'];

$arr = ['visi', 'misi', 'sejarah', 'goals'];
$boxs = '';
foreach ($arr as $item) {
  $icon = $tentang[$item]['icon'];
  $title = $tentang[$item]['title'];
  $href = $tentang[$item]['href'];
  $desc = $tentang[$item]['desc'] ?? '';
  $list = $tentang[$item]['list'] ?? [];
  if (!$desc) {
    // tidak ada deskripsi artinya berupa list
    if ($list) {
      $li = '';
      $pgf = '';
      foreach ($list as $item_desc) {
        if ($item == 'sejarah' || $item == 'goals') {
          $item_desc = strlen($item_desc) < 100 ? $item_desc : substr($item_desc, 0, 100);
          $pgf .= "<p class='description'>$item_desc... <a href='$href'>lanjut</a></p>";
          break; // hanya pgf pertama saja tampil di home
        } else {
          $li .= "<li>$item_desc</li>";
        }
      }
      $desc = $li ? "<ol class='description'>$li</ol>" : $pgf;
    }
  } else {
    $desc = "<p class='description'>$desc</p>";
  }

  $boxs .= "
    <div class='icon-box'>
      <div class='icon'><i class='bx bx-$icon'></i></div>
      <h4 class='title'><a href='$href'>$title</a></h4>
      $desc
    </div>
  ";
}


echo "
  <section id='about' class='about'>
    <div class='container-fluid'>
      <div class='row'>
        <div class='col-xl-5 col-lg-6 video-box d-flex justify-content-center align-items-stretch position-relative'>
          <a href='$href' class='glightbox play-btn mb-4'></a>
        </div>
        <div class='col-xl-7 col-lg-6 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5'>
          <h3>$tentang[title]</h3>
          <p>$tentang[desc]</p>
          $boxs
        </div>
      </div>
    </div>
  </section>
";
