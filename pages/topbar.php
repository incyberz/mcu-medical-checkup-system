<?php


$links = '';
foreach ($social_links as $key => $link) {
  $links .= "<a href='$link' class='$key'><i class='bi bi-$key'></i></a>";
}

echo "
  <div id='topbar' class='d-flex align-items-center fixed-top'>
    <div class='container d-flex justify-content-between'>
      <div class='contact-info d-flex align-items-center'>
        <a href='mailto:$email'><i class='bi bi-envelope'></i> <span class='desktop-only'>$email</span></a>
        <i class='bi bi-phone desktop-only'></i> <span class='desktop-only'>$phone</span>
        <i class='bi bi-whatsapp'></i> $link_wa
      </div>
      <div class='d-none d-lg-flex social-links align-items-center'>
        $links
      </div>
    </div>
  </div>
";
