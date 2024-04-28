<style>
  #tb_edit_visi td,
  #edit_about td {
    background: none;
  }
</style>
<?php
$tentang_title = $klinik['tentang_title'] ?? 'Tentang Kami';
$tentang_desc = $klinik['tentang_desc'] ?? '';
$tentang_video = $klinik['tentang_video'] ?? '';
$tentang_video_bg = $klinik['tentang_video_bg'] ?? '';

$visi_title = $klinik['visi_title'] ?? 'Visi';
$visi_icon = $klinik['visi_icon'] ?? 'star';
$visi_href = $klinik['visi_href'] ?? '';

$misi_title = $klinik['misi_title'] ?? 'Misi';
$misi_icon = $klinik['misi_icon'] ?? 'calendar-star';
$misi_href = $klinik['misi_href'] ?? '';

$sejarah_title = $klinik['sejarah_title'] ?? 'Sejarah';
$sejarah_icon = $klinik['sejarah_icon'] ?? 'history';
$sejarah_href = $klinik['sejarah_href'] ?? '';

$goals_title = $klinik['goals_title'] ?? 'Goals';
$goals_icon = $klinik['goals_icon'] ?? 'medal';
$goals_href = $klinik['goals_href'] ?? '';


$href = $tentang_video;

$arr = ['visi', 'misi', 'sejarah', 'goals'];
$boxs = '';
foreach ($arr as $item) {
  $icon = $klinik[$item . '_icon'];
  $title = $klinik[$item . '_title'];
  $href = $klinik[$item . '_href'] ?? '';


  if ($item == 'visi') {
    $desc = $klinik[$item] ?? 'Visi Kami';
    $desc = "<p class='description'>$desc</p>";
  } else {
    $li = '';
    $pgf = '';
    for ($i = 1; $i <= 10; $i++) {
      $desc = $klinik["$item-$i"] ?? '';
      if (!$desc) continue;
      if ($item == 'sejarah') {
        if (!$parameter) {
          break; // hanya pgf pertama saja tampil di home
          $dot_lanjut = "... <a href='$href'>lanjut</a>";
        } else {
          $dot_lanjut = '';
        }
        $pgf .= "<p class='description'>$desc$dot_lanjut</p>";
      } else {
        $li .= "<li>$desc</li>";
      }
    }

    $desc = $li ? "<ol class='description'>$li</ol>" : $pgf;
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

  $input_misies = '';
  $input_sejarah = '';
  $input_goals = '';
  for ($i = 1; $i <= 10; $i++) {
    $misi = $klinik["misi-$i"] ?? '';
    $sejarah = $klinik["sejarah-$i"] ?? '';
    $goals = $klinik["goals-$i"] ?? '';

    $input_misies .= "
      <textarea class='form-control mb1' name='misi-$i' placeholder='Misi #$i...'>$misi</textarea>
    ";
    $input_sejarah .= "
      <textarea class='form-control mb1' name='sejarah-$i' placeholder='Paragraf Sejarah #$i...'>$sejarah</textarea>
    ";
    $input_goals .= "
      <textarea class='form-control mb1' name='goals-$i' placeholder='Goals #$i...'>$goals</textarea>
    ";
  }



  $edit_section = "
    <div class=container>
    $edit_section
    <div id=edit_about class='wadah mt2 gradasi-kuning'>
      <form method=post class=wadah>
        <div class=sub_form>Form Edit Text</div>
        <input class='form-control mb1' name=tentang_title value='$tentang_title' placeholder='Header tentang kami...'>
        <textarea class='form-control mb1' name=tentang_desc placeholder='Deskripsi tentang kami...' rows=5>$tentang_desc</textarea>

        <div class=wadah>
          <div class='f14 abu'>Icon</div>
          <table class='table tengah'>
            <tr class=' abu f14'>
              <td><span class=abu>#</span></td>
              <td><span class=abu>Visi</span></td>
              <td><span class=abu>Misi</span></td>
              <td><span class=abu>Sejarah</span></td>
              <td><span class=abu>Goals</span></td>
            </tr>
            <tr>
              <td class='kiri'>
                <span class='f14 abu'>captions</span>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=visi_title value='$visi_title' placeholder='visi icon...'>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=misi_title value='$misi_title' placeholder='misi icon...'>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=sejarah_title value='$sejarah_title' placeholder='sejarah icon...'>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=goals_title value='$goals_title' placeholder='goals icon...'>
              </td>
            </tr>
            <tr>
              <td class='kiri'>
                <span class='f14 abu'>icons</span>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=visi_icon value='$visi_icon' placeholder='visi icon...'>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=misi_icon value='$misi_icon' placeholder='misi icon...'>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=sejarah_icon value='$sejarah_icon' placeholder='sejarah icon...'>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=goals_icon value='$goals_icon' placeholder='goals icon...'>
              </td>
            </tr>
            <tr>
              <td class='kiri'>
                <span class='f14 abu'>href</span>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=visi_href value='$visi_href' placeholder='link visi menuju...'>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=misi_href value='$misi_href' placeholder='link misi menuju...'>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=sejarah_href value='$sejarah_href' placeholder='link sejarah menuju...'>
              </td>
              <td>
                <input class='form-control mb1 tengah' name=goals_href value='$goals_href' placeholder='link goals menuju...'>
              </td>
            </tr>
          </table>
        </div>
        
        <div class='wadah gradasi-hijau'>
          <table class='table' id=tb_edit_visi>
            <tr>
              <td valign=top align=right class='pr2 pt1 abu '>Visi</td>
              <td>
                <textarea class='form-control' name=visi placeholder='Visi kami...'>$klinik[visi]</textarea>
              </td>
            </tr>
            <tr>
              <td valign=top align=right class='pr2 pt1 abu '>Misi</td>
              <td>$input_misies</td>
            </tr>
            <tr>
              <td valign=top align=right class='pr2 pt1 abu '>Sejarah</td>
              <td>$input_sejarah</td>
            </tr>
            <tr>
              <td valign=top align=right class='pr2 pt1 abu '>Goals</td>
              <td>$input_goals</td>
            </tr>
          </table>
        </div>
        <button class='btn btn-success btn-sm ' name=btn_save_settings value=about>Save Settings</button>
      </form>
      

      <div class=wadah>
        <div class='abu f14 mb2 mt2'>Video Tentang Kami</div>

        <form method=post class='wadah mt2 gradasi-hijau'>
          <input required class='form-control mb1' name=tentang_video value='$tentang_video' placeholder='Link video...'>
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
          <h3>$tentang_title</h3>
          <p>$tentang_desc</p>
          $boxs
        </div>
      </div>
      $edit_section
    </div>
  </section>
";
