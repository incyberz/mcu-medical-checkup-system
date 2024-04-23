<?php

for ($i = 1; $i <= 10; $i++) {
  $kelebihan[$i] = "Kelebihan $i... ";
  $kelebihan_desc[$i] = "Deskripsi Kelebihan $i...";
  $icon[$i] = '';
}


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

$trs = '';
for ($i = 1; $i <= 10; $i++) $trs .= "
  <tr>
    <td width=25% valign=top class=pr2>
      <input class='form-control' name='kelebihan[]' value='$kelebihan[$i]' placeholder='kelebihan...'>
      <div class=flexy>
        <div class='pl2 pt2 f12 abu' >icon</div>
        <div>
          <input class='form-control' name='icon[]' value='$icon[$i]' placeholder='icon...'>
        </div>
      </div>
    </td>
    <td>
      <textarea class='form-control mb2' name='kelebihan_desc[]' placeholder='kelebihan_desc...' rows=3>$kelebihan_desc[$i]</textarea>
    </td>
  </tr>
";

if ($username and $role == 'admin') {
  $edit_section .= "
    <div id=edit_why-us class='hideit wadah mt2 gradasi-kuning'>
      <form method=post class=wadah>
        <div class=sub_form>Form Edit Text</div>
        <input class='form-control mb1' name=mengapa_kami value='$mengapa_kami' placeholder='mengapa_kami...'>
        <textarea class='form-control mb1' name=mengapa_kami_desc placeholder='mengapa_kami_desc...' rows=5>$mengapa_kami_desc</textarea>
        <div class='wadah gradasi-hijau'>
          <table width=100%>
            $trs
          </table>
        </div>
        <button class='btn btn-success btn-sm on-dev' name=btn_save_settings value='why-us'>Save Settings</button>
      </form>
    </div>
  ";
}


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
