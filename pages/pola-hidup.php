<?php
echo "<h2>POLA HIDUP</h2>";
function ilustrasi($nama, $w = 180, $h = 'auto')
{
  $me = "img/ilustrasi/$nama.jpeg";
  $na = "img/img_na.jpg";
  $width =  $w . 'px';
  $height = $h == 'auto' ? '' : $h . 'px';
  if (file_exists($me)) {
    return "<img class='img-thumbnail' src='$me' width=$width height=$height />";
  } else {
    return "<img class='img-thumbnail' src='$na' width=$width height=$height />
    <div class='tengah f12 miring'>ilustrasi $nama n/a</div>
    ";
  }
}

$img_tidur = ilustrasi('tidur');
$img_merokok = ilustrasi('merokok');
$img_stress = ilustrasi('stress');
$img_olahraga = ilustrasi('olahraga');
$img_tato = ilustrasi('tato');
$img_tindik = ilustrasi('tindik');
$img_obat = ilustrasi('obat');
$img_minum = ilustrasi('minum');

?>
<div class="wadah gradasi-hijau">
  <div class="tengah mb4">
    <?= $img_tidur ?>
  </div>
  <select class="form-control mb2 tengah" name=lama_waktu_tidur>
    <option value="3">Saya tidur 3 jam per hari</option>
    <option value="4">Saya tidur 4 jam per hari</option>
    <option value="5">Saya tidur 5 jam per hari</option>
    <option value="6">Saya tidur 6 jam per hari</option>
    <option value="7">Saya tidur 7 jam per hari (normal)</option>
    <option value="8" selected>Saya tidur 8 jam per hari (normal)</option>
    <option value="9">Saya tidur 9 jam per hari (normal)</option>
    <option value="10">Saya tidur 10 jam per hari</option>
    <option value="11">Saya tidur 11 jam per hari</option>
    <option value="12">Saya tidur 12 jam per hari</option>
  </select>
</div>

<div class="wadah gradasi-hijau">
  <div class="tengah mb4">
    <?= $img_merokok ?>
  </div>
  <select class="form-control tengah mb2" name=saya_perokok id=saya_perokok>
    <option value="0">Saya Tidak Pernah Merokok</option>
    <option value="1">Saya Perokok Pasif</option>
    <option value="2">Dahulu Saya Pernah Merokok</option>
    <option value="3">Saya Perokok Aktif</option>
  </select>

  <select class="form-control tengah mb2 hideit" name=jumlah_rokok id=jumlah_rokok>
    <option value="1">1 s.d 4 batang per hari</option>
    <option value="5">5 s.d 9 batang per hari</option>
    <option value="10">>10 batang per hari</option>
  </select>

  <script>
    $(function() {
      $('#saya_perokok').change(function() {
        if ($(this).val() == 3) {
          $('#jumlah_rokok').fadeIn();
        } else {
          $('#jumlah_rokok').fadeOut();
        }
      });
      $('#saya_perokok').change();
    })
  </script>
</div>

<div class="wadah gradasi-hijau">
  <div class="tengah mb4">
    <?= $img_stress ?>
  </div>
  <select class="form-control tengah mb2" name=level_stress>
    <option value="1">Beban Kerja tidak membuat saya stress</option>
    <option value="2" selected>Beban Kerja terkadang membuat saya stress</option>
    <option value="3">Beban Kerja sering membuat saya stress</option>
  </select>
</div>

<div class="wadah gradasi-hijau">
  <div class="tengah mb4">
    <?= $img_olahraga ?>
  </div>

  <select class="form-control tengah mb2" name=level_olahraga>
    <option value="1">Saya kurang berolahraga</option>
    <option value="2" selected>Terkadang saya berolahraga (1 - 2 kali/mgg)</option>
    <option value="3">Saya rutin berolahraga hampir setiap hari</option>
  </select>
</div>

<div class="wadah gradasi-hijau">
  <div class="tengah mb4">
    <?= $img_tato ?>
  </div>

  <select class="form-control tengah mb2" name=punya_tato id=punya_tato>
    <option value="0">Saya tidak punya tato</option>
    <option value="1">Saya punya tato di bagian</option>
  </select>
  <input type="text" class="hideit form-control tengah mb2" name=tato_di_bagian id=tato_di_bagian placeholder="tato pada bagian...">
  <script>
    $(function() {
      $('#punya_tato').change(function() {
        if ($(this).val() == 1) {
          $('#tato_di_bagian').fadeIn();
        } else {
          $('#tato_di_bagian').val('-');
          $('#tato_di_bagian').fadeOut();
        }
      });
      $('#punya_tato').change();
    })
  </script>
</div>

<div class="wadah gradasi-hijau">
  <div class="tengah mb4">
    <?= $img_tindik ?>
  </div>

  <select class="form-control tengah mb2" name=punya_tindik id=punya_tindik>
    <option value="0">Saya tidak punya tindik</option>
    <option value="1">Saya punya tindik di bagian</option>
  </select>
  <input type="text" class="hideit form-control tengah mb2" name=tindik_di_bagian id=tindik_di_bagian placeholder="tindik pada bagian...">
  <script>
    $(function() {
      $('#punya_tindik').change(function() {
        if ($(this).val() == 1) {
          $('#tindik_di_bagian').fadeIn();
        } else {
          $('#tindik_di_bagian').val('-');
          $('#tindik_di_bagian').fadeOut();
        }
      });
      $('#punya_tindik').change();
    })
  </script>
</div>

<div class="wadah gradasi-hijau">
  <div class="tengah mb4">
    <?= $img_obat ?>
  </div>

  <select class="form-control tengah mb2" name=saya_pakai_obat id=saya_pakai_obat>
    <option value="0">Saya Tidak Pernah Pakai Obat Terlarang</option>
    <option value="1">Saya Pernah Pakai Obat pada tahun</option>
    <option value="2">Saya Kadang-kadang Pakai Obat</option>
    <option value="3">Saya Sering Pakai Obat</option>
  </select>
  <input type="number" min=1990 max=2024 class="hideit form-control tengah mb2" name=tahun_pakai_obat id=tahun_pakai_obat placeholder="Tahun pakai obat...">
  <script>
    $(function() {
      $('#saya_pakai_obat').change(function() {
        if ($(this).val() == 1) {
          $('#tahun_pakai_obat').fadeIn();
        } else {
          $('#tahun_pakai_obat').fadeOut();
        }
      });
      $('#saya_pakai_obat').change();
    })
  </script>
</div>

<div class="wadah gradasi-hijau">
  <div class="tengah mb4">
    <?= $img_minum ?>
  </div>

  <select class="form-control tengah mb2" name=saya_minum id=saya_minum>
    <option value="0">Saya Tidak Pernah Minum-minuman Keras</option>
    <option value="1">Saya Pernah Minum* pada tahun</option>
    <option value="2">Saya Kadang-kadang Minum*</option>
    <option value="3">Saya Sering Minum*</option>
  </select>
  <input type="text" class="hideit form-control tengah mb2" name=tahun_minum id=tahun_minum placeholder="Tahun minum...">
  <input type="text" class="hideit form-control tengah mb2" name=jumlah_gelas id=jumlah_gelas placeholder="Jumlah gelas per hari...">

  <script>
    $(function() {
      $('#saya_minum').change(function() {
        if ($(this).val() == 1) {
          $('#tahun_minum').fadeIn();
          $('#jumlah_gelas').fadeIn();
        } else {
          $('#tahun_minum').fadeOut();
          $('#jumlah_gelas').fadeOut();
        }
      });
      $('#saya_minum').change();
    })
  </script>
</div>