<style>
  #tb_edit_visi td {
    background: none;
  }
</style>
<?php
// for edit

$href = $tentang['video'];

$arr = ['visi', 'misi', 'sejarah', 'goals'];
$boxs = '';
foreach ($arr as $item) {
  $icon = $tentang[$item . '_icon'];
  $title = $tentang[$item . '_title'];
  $href = $tentang[$item . '_href'];
  $desc = $tentang[$item] ?? '';

  // echo '<pre>';
  // var_dump($desc);
  // echo '</pre>';

  if (is_array($desc)) { // jika desc berupa array
    $li = '';
    $pgf = '';
    foreach ($desc as $item_desc) {
      if ($item == 'sejarah' || $item == 'goals') {
        $item_desc = strlen($item_desc) < 100 ? $item_desc : substr($item_desc, 0, 100);
        $pgf .= "<p class='description'>$item_desc... <a href='$href'>lanjut</a></p>";
        break; // hanya pgf pertama saja tampil di home
      } else {
        $li .= "<li>$item_desc</li>";
      }
    }
    $desc = $li ? "<ol class='description'>$li</ol>" : $pgf;
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


$edit_section = $role == 'admin' ? edit_section('about', 'about (tentang kami)') : '';
if ($username and $role == 'admin') {
  $edit_section = "
    <div class=container>
    $edit_section
    <div id=edit_about class='wadah mt2 gradasi-kuning'>
      <form method=post class=wadah>
        <div class=sub_form>Form Edit Text</div>
        <input class='form-control mb1' name=tentang_title value='$tentang[title]' placeholder='Header tentang kami...'>
        <textarea class='form-control mb1' name=tentang_desc placeholder='Deskripsi tentang kami...' rows=5>$tentang[desc]</textarea>
        
        <div class='wadah gradasi-hijau'>
          <table class='table' id=tb_edit_visi>
            <tr>
              <td valign=top align=right class='pr2 pt1 abu '>Visi</td>
              <td>
                <textarea class='form-control' name=visi placeholder='visi...'></textarea>
              </td>
            </tr>
            <tr>
              <td valign=top align=right class='pr2 pt1 abu '>Misi</td>
              <td>
                <textarea class='form-control mb1' name=misi[] placeholder='misi...'></textarea>
                <textarea class='form-control mb1' name=misi[] placeholder='misi...'></textarea>
                <textarea class='form-control mb1' name=misi[] placeholder='misi...'></textarea>
                <textarea class='form-control mb1' name=misi[] placeholder='misi...'></textarea>
                <textarea class='form-control mb1' name=misi[] placeholder='misi...'></textarea>
                <textarea class='form-control mb1' name=misi[] placeholder='misi...'></textarea>
                <textarea class='form-control mb1' name=misi[] placeholder='misi...'></textarea>
                <textarea class='form-control mb1' name=misi[] placeholder='misi...'></textarea>
                <textarea class='form-control mb1' name=misi[] placeholder='misi...'></textarea>
                <textarea class='form-control mb1' name=misi[] placeholder='misi...'></textarea>
              </td>
            </tr>
            <tr>
              <td valign=top align=right class='pr2 pt1 abu '>Sejarah</td>
              <td>
                <textarea class='form-control mb1' name=sejarah[] placeholder='Paragraf sejarah...'></textarea>
                <textarea class='form-control mb1' name=sejarah[] placeholder='Paragraf sejarah...'></textarea>
                <textarea class='form-control mb1' name=sejarah[] placeholder='Paragraf sejarah...'></textarea>
                <textarea class='form-control mb1' name=sejarah[] placeholder='Paragraf sejarah...'></textarea>
                <textarea class='form-control mb1' name=sejarah[] placeholder='Paragraf sejarah...'></textarea>
                <textarea class='form-control mb1' name=sejarah[] placeholder='Paragraf sejarah...'></textarea>
                <textarea class='form-control mb1' name=sejarah[] placeholder='Paragraf sejarah...'></textarea>
                <textarea class='form-control mb1' name=sejarah[] placeholder='Paragraf sejarah...'></textarea>
                <textarea class='form-control mb1' name=sejarah[] placeholder='Paragraf sejarah...'></textarea>
                <textarea class='form-control mb1' name=sejarah[] placeholder='Paragraf sejarah...'></textarea>
              </td>
            </tr>
            <tr>
              <td valign=top align=right class='pr2 pt1 abu '>Goals</td>
              <td>
                <textarea class='form-control mb1' name=goals[] placeholder='goals...'></textarea>
                <textarea class='form-control mb1' name=goals[] placeholder='goals...'></textarea>
                <textarea class='form-control mb1' name=goals[] placeholder='goals...'></textarea>
                <textarea class='form-control mb1' name=goals[] placeholder='goals...'></textarea>
                <textarea class='form-control mb1' name=goals[] placeholder='goals...'></textarea>
                <textarea class='form-control mb1' name=goals[] placeholder='goals...'></textarea>
                <textarea class='form-control mb1' name=goals[] placeholder='goals...'></textarea>
                <textarea class='form-control mb1' name=goals[] placeholder='goals...'></textarea>
                <textarea class='form-control mb1' name=goals[] placeholder='goals...'></textarea>
                <textarea class='form-control mb1' name=goals[] placeholder='goals...'></textarea>
              </td>
            </tr>
          </table>
        </div>
        <button class='btn btn-success btn-sm ' name=btn_save_settings value=about>Save Settings</button>
      </form>
      

      <div class=wadah>
        <div class='abu f14 mb2 mt2'>Video Tentang Kami</div>

        <form method=post class='wadah mt2 gradasi-hijau'>
          <input required class='form-control mb1' name=tentang_video value='$tentang[video]' placeholder='Link video...'>
          <button class='btn btn-success btn-sm mt2' name=btn_save_settings value=about>Update Link</button>
        </form>

        <form method=post enctype='multipart/form-data' class='wadah mt4 gradasi-hijau'>
          <div class=flexy>
            <div>Background video</div>
            <img src='$lokasi_img/about2.jpg' class='img-thumbnail'>
            <div><input required type=file class='form-control' name=tentang_video_bg ></div>
            <div><button class='btn btn-success' name=btn_save_settings value=about>Replace</button></div>
          </div>
        </form>
        <div class='abu miring f12'>Untuk background sebaiknya foto kantor atau foto bersama tim.</div>

      </div>




    </div>
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
      $edit_section
    </div>
  </section>
";
