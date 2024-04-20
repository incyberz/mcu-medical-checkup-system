<?php
$divs = '';
foreach ($keunggulan as $key => $item) {
  $divs .= "
    <div class='col-xl-4 d-flex align-items-stretch mb-4'>
      <div class='icon-box mt-4 mt-xl-0'>
        <i class='bx bx-$item[icon]'></i>
        <h4>$key</h4>
        <p>$item[desc]</p>
      </div>
    </div>
  
  ";
}


$edit_section = $role == 'admin' ? edit_section('why-us', 'Why-us (Keunggulan)') : '';

echo "
<section id='why-us' class='why-us'>
  <div class='container'>

    <div class='row'>
      <div class='col-lg-4 d-flex align-items-stretch'>
        <div class='content'>
          <h3>$mengapa_kami</h3>
          <p>$mengapa_kami_desc</p>
          <!-- <div class='text-center'>
            <a href='#' class='more-btn'>Learn More <i class='bx bx-chevron-right'></i></a>
          </div> -->
        </div>
      </div>
      <div class='col-lg-8 d-flex align-items-stretch'>
        <div class='icon-boxes d-flex flex-column justify-content-center'>
          <div class='row'>$divs</div>
        </div>
      </div>
    </div>
    $edit_section
  </div>
</section>
";
