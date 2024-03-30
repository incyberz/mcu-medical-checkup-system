<style>
  .ukuran_pupil {
    cursor: pointer;
  }

  .ukuran_pupil:hover {
    background: linear-gradient(#cfc, #afa)
  }

  .ukuran_pupil_selected {
    border: solid 3px blue
  }
</style>
<?php
echo "<h2>PEMERIKSAAN MATA</h2>";

$pakai_kacamata = radio_tf('Apakah Anda memakai kacamata?', 'kacamata', '', 'Pakai', 0);

$visus_mata_kanan =  input('visus_mata_kanan', '', 'Visus Mata Kanan', 1, 'number', 1, 20);
$mata_kanan =  radio_tf('Reflek Cahaya', 'feklek_cahaya_kanan', '', '', '', 'Positif', 'Negatif');
$mata_kanan .=  radio_tf('Konjungtiva', 'konjungtiva_kanan', '', '', '', 'Anemis', 'An Anemis');

$bm_kanan = radio('bola_mata_kanan', 'bmk1', 1, 'Normal');
$bm_kanan .= radio('bola_mata_kanan', 'bmk2', 2, 'Strabismus');
$bm_kanan .= radio('bola_mata_kanan', 'bmk3', 3, 'Lainnya');

$visus_mata_kiri =  input('visus_mata_kiri', '', 'Visus Mata Kiri', 1, 'number', 1, 20);
$mata_kiri =  radio_tf('Reflek Cahaya', 'feklek_cahaya_kiri', '', '', '', 'Positif', 'Negatif');
$mata_kiri .=  radio_tf('Konjungtiva', 'konjungtiva_kiri', '', '', '', 'Anemis', 'An Anemis');

$bm_kiri = radio('bola_mata_kiri', 'bmk1', 1, 'Normal');
$bm_kiri .= radio('bola_mata_kiri', 'bmk2', 2, 'Strabismus');
$bm_kiri .= radio('bola_mata_kiri', 'bmk3', 3, 'Lainnya');

$pupil_kanan = '';
for ($i = 1; $i <= 8; $i++) {
  $size = (3 + $i) . 'mm';
  $pupil_kanan .= "<div class='bordered br5 p2 tengah ukuran_pupil pupil_kanan' id=pupil_kanan__$i>$i <div style='width:$size;height:$size;background:black; border-radius:50%'></div></div>";
}
$pupil_kanan = "<div class='flexy mb4'>$pupil_kanan</div>";
$input = input('ukuran_pupil_kanan', '', 'Pupil Kanan (mm)', 1, 'number', 1, 8);

$pupil_kanan .= "
  <div class='row mb4'>
    <div class=col-10>
      $input
    </div>
    <div class='col-2 pt2'>
      mm
    </div>
  </div>
";
$pupil_kanan .= radio_tf('Pupil Kanan', 'bentuk_pupil_kanan', '', '', 0, 'Isokor', 'An Isokor');

$pupil_kiri = '';
for ($i = 1; $i <= 8; $i++) {
  $size = (3 + $i) . 'mm';
  $pupil_kiri .= "<div class='bordered br5 p2 tengah ukuran_pupil pupil_kiri' id=pupil_kiri__$i>$i <div style='width:$size;height:$size;background:black; border-radius:50%'></div></div>";
}
$pupil_kiri = "<div class='flexy mb4'>$pupil_kiri</div>";
$input = input('ukuran_pupil_kiri', '', 'Pupil Kiri (mm)', 1, 'number', 1, 8);

$pupil_kiri .= "
  <div class='row mb4'>
    <div class=col-10>
      $input
    </div>
    <div class='col-2 pt2'>
      mm
    </div>
  </div>
";
$pupil_kiri .= radio_tf('Pupil Kiri', 'bentuk_pupil_kiri', '', '', 0, 'Isokor', 'An Isokor');

echo "
  <div class=wadah>
    $pakai_kacamata
  </div>
  <div class=wadah>
    <div class=mb2>Visus Mata Kanan</div>
    <div class='row mb4'>
      <div class=col-10>
        $visus_mata_kanan
      </div>
      <div class='col-2 consolas tebal pt2'>
        /20
      </div>
    </div>
    $mata_kanan
  </div>
  <div class=wadah>
    Bola Mata Kanan
    $bm_kanan
  </div>
  <div class=wadah>
    <div class=mb2>Visus Mata Kiri</div>
    <div class='row mb4'>
      <div class=col-10>
        $visus_mata_kiri
      </div>
      <div class='col-2 consolas tebal pt2'>
        /20
      </div>
    </div>
    $mata_kiri
  </div>
  <div class=wadah>
    Bola Mata Kiri
    $bm_kiri
  </div>

  <div class=wadah>
    <div class=mb2>Pupil Kanan</div>
    $pupil_kanan
  </div>

  <div class=wadah>
    <div class=mb2>Pupil Kiri</div>
    $pupil_kiri
  </div>




";




?>

<script>
  $(function() {
    $('.ukuran_pupil').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);
      $('#ukuran_' + aksi).val(id);
      $('.' + aksi).removeClass('ukuran_pupil_selected');
      $(this).addClass('ukuran_pupil_selected');
    })
  })
</script>